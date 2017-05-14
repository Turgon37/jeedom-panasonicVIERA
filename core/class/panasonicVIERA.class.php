<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';


class panasonicVIERA extends eqLogic {

    const KEY_ADDRESS = 'address';

    /*     * *************************Attributs****************************** */
    /**
     * @var this is the official commands index
     */
    protected static $_command_index = [];


    /*     * ***********************Methode static*************************** */
    /**
     * Function that compare two command object
     *
     */
    public static function sortListCmd($cmd_a, $cmd_b) {
        if ($cmd_a['name'] == $cmd_b['name']) {
            return 0;
        }
        return ($cmd_a['name'] < $cmd_b['name']) ? -1 : 1;
    }

    /**
     * Return the list of official commands
     *
     * @return array
     */
    public static function getCommandsIndex() {
        if (empty(self::$_command_index)) {
            self::$_command_index = include(__DIR__ . '/../config/commands.config.php');
            usort(self::$_command_index, array('panasonicVIERA', 'sortListCmd'));
        }
        return self::$_command_index;
    }

    /**
     * Retourne les groupes de l'objet
     * @var array
     */
    public static function getCommandGroups() {
        return [
            'basic' => 'has_basic',
            'numeric' => 'has_numeric',
            'record' => 'has_record',
            'multimedia' => 'has_multimedia',
            'colors' => 'has_colors',
            'others' => 'has_others'
        ];
    }

    /**
     * Return the timeout value for standard TV commands
     * @return [int] the timeout in seconds
     */
    public static function getCommandTimeout() {
        return config::byKey('command_timeout', 'panasonicVIERA', 2);
    }

    /**
     * Return the timeout value for discovery commands
     * @return [int] the discovery timeout in seconds
     */
    public static function getDiscoveryTimeout() {
        return config::byKey('discovery_timeout', 'panasonicVIERA', 3);
    }

    /**
     * Execute a 3rd party command not written in PHP
     *
     * @param [string] the name of the command file in 3rdparty/ directory
     * @param [array] the list of command arguments
     * @param [string] OPTIONNAL a verbose name to include in errors statments.
     * @return mixed : the output of the command (stdout of the command)
     */
    public static function execute3rdParty($command, $args = [], $name = null) {
        $base_path = realpath(__DIR__ . '/../../3rdparty');
        $cmdline = sprintf("%s/%s %s", $base_path, $command, implode(' ', $args));
        // by default the log 'name' will be the command name
        if ($name === null) {
            $name = $command;
        }

        $output = null;

        log::add('panasonicVIERA', 'debug', 'execute3rdParty : '. $cmdline);
        $shell_output = shell_exec(escapeshellcmd($cmdline));
        $decoded = json_decode($shell_output, JSON_OBJECT_AS_ARRAY|JSON_NUMERIC_CHECK);
        if (is_null($decoded)) {
            throw new Exception(__("The command", __FILE__) . " $command " . __('has not returned a valid JSON output.', __FILE__));
        }

        # transcript logs messages from python script to jeedom
        if (isset($decoded['log'])) {
            foreach ($decoded['log'] as $record) {
                log::add('panasonicVIERA', $record['level'], $record['message']);
            }
        }
        # handle return code and error message
        if (isset($decoded['status']) && intval($decoded['status']) != 0) {
            $message = __("The command", __FILE__) . " $name " . __('has failed.', __FILE__);
            if (isset($decoded['error'])) {
                $message = $message . "<br />" . __($decoded['error'], __FILE__);
            }
            throw new Exception($message);
        }

        # handle standard output
        if (isset($decoded['output'])) {
            $output = $decoded['output'];
        }

        return $output;
    }

    public static function discoverNetwork() {
        // check lock key to prevent multiple run of the dscovery at the same time
        if (cache::byKey('panasonicVIERA__discover_lock')->getValue(false) == true) {
            throw new Exception(__('Discovery is already in progress', __FILE__));
        }
        cache::set('panasonicVIERA__discover_lock', true, 30);

        $result = [
            'updated' => 0,
            'created' => 0
        ];
        log::add('panasonicVIERA', 'debug', 'run discovery command');
        $discovered = self::execute3rdParty("panasonic_viera_adapter.py", ['find'], 'discover');
        if (count($discovered)) {
            log::add('panasonicVIERA', 'debug', 'found ' . count($discovered) . ' TV(s) on the network');
            foreach ($discovered as $tv) {
                if (!isset($tv['address']) || is_null($tv['address']) || empty($tv['address'])) {
                    throw new Exception(__('Missing address key in discovery answer from 3rd party', __FILE__));
                }

                $address = $tv['address'];
                $uuid = self::extractUUIDFromDiscoverResponse($tv['discovery']);
                $eq = null;

                // try to find an existing cmd by the uuid
                if (!is_null($uuid) and !empty($uuid)) {
                    $eq = self::byLogicalId($uuid, 'panasonicVIERA');
                    log::add('panasonicVIERA', 'debug', sprintf("search existing equipment by uuid '%s'", $uuid));
                    if (is_object($eq)) {
                        log::add('panasonicVIERA', 'debug', sprintf("found existing equipment %d by uuid '%s'", $eq->getId(), $uuid));
                    }
                }
                // if the search by uuid did not work use the ip address instead
                if (!is_object($eq)) {
                    log::add('panasonicVIERA', 'debug', sprintf("search existing equipment by address '%s'", $address));
                    $search = self::byTypeAndSearhConfiguration('panasonicVIERA', sprintf('"%s":"%s"', self::KEY_ADDRESS, $address));
                    if (count($search)) {
                        $eq = $search[0];
                    }
                    if (is_object($eq)) {
                        log::add('panasonicVIERA', 'debug', sprintf("found existing equipment %d by address '%s'", $eq->getId(), $address));
                    }
                }

                // if no equipment exist with address and UUID, create one
                if (!is_object($eq)) {
                    log::add('panasonicVIERA', 'debug', "create new TV equipment with address '" . $address);
                    $result['created'] += 1;
                    $eq = new panasonicVIERA();
                    $eq->setEqType_name('panasonicVIERA');
                    $eq->setName($address);
                } else {
                    $result['updated'] += 1;
                }

                // set eq' settings
                $eq->setIpAddress($address);
                if (!is_null($uuid) and !empty($uuid)) {
                    $eq->setLogicalId($uuid);
                }
                $eq->save();
            }
        }
        cache::getCache()->delete('panasonicVIERA__discover_lock');
        return $result;
    }

    /**
     * Extract the TV UUID from the given discovery answer
     *
     * @param array the discovery array which contains some items like HTTP headers
     * @return string|null the uuid's string or null if parse fail
     */
    protected static function extractUUIDFromDiscoverResponse($response) {
        log::add('panasonicVIERA', 'debug', 'extractUUID: try to extract uuid from ' . print_r($response,true));
        if (!is_array($response)) {
            return null;
        }

        // use USN header if defined
        if (isset($response['USN'])) {
            $usn = strtolower($response['USN']);
            log::add('panasonicVIERA', 'debug', sprintf("extractUUID: use USN: '%s'", $usn));
            $matches = [];
            $r = preg_match('/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}/', $usn, $matches);
            if ($r === 1 && count($matches)) {
                log::add('panasonicVIERA', 'debug', sprintf("extractUUID: found UUID : '%s'", $matches[0]));
                return $matches[0];
            }
        }
        return null;
    }


    /*     * *********************Méthodes d'instance************************* */

    /**
     * Ajoute une commande à l'objet
     *
     * @param cmd $cmd La commande a ajouter
     */
    public function addCommand($cmd) {
        if (cmd::byEqLogicIdCmdName($this->getId(), $cmd['name'])) {
            log::add('panasonicVIERA', 'debug', '=> addCommand('. $cmd['name'].') command already exist');
            return;
        }

        if ($cmd) {
            $panasonicVIERACmd = new panasonicVIERACmd();
            $panasonicVIERACmd->setName($cmd['name']);
            $panasonicVIERACmd->setEqLogic_id($this->id);
            $panasonicVIERACmd->setLogicalId($cmd['configuration']['command']);
            $panasonicVIERACmd->setConfiguration('action', $cmd['configuration']['action']);
            $panasonicVIERACmd->setConfiguration('command', $cmd['configuration']['command']);
            $panasonicVIERACmd->setConfiguration('group', $cmd['group']);
            $panasonicVIERACmd->setType($cmd['type']);
            $panasonicVIERACmd->setSubType($cmd['subType']);
            if ($cmd['icon'] != '')
                $panasonicVIERACmd->setDisplay('icon', '<i class=" '.$cmd['icon'].'"></i>');
            log::add('panasonicVIERA', 'debug', '=> addCommand('. $cmd['name'].') add command');
            $panasonicVIERACmd->save();
        }
    }

    /**
     * Supprime la commande $name de l'objet
     *
     * @param String $name Le nom de la commande
     */
    public function removeCommand($cmd) {
        if (($panasonicVIERACmd = cmd::byEqLogicIdCmdName($this->getId(), $cmd['name']))) {
            log::add('panasonicVIERA', 'debug', '=> removeCommand('. $cmd['name'].') remove command');
            $panasonicVIERACmd->remove();
        }
    }

    /**
     * Ajoute un groupe de commandes
     *
     * @param String $groupName Le nom du groupe de commandes
     */
    public function addCommands($group_name) {
        log::add('panasonicVIERA', 'debug', '=> addCommands('.$group_name.')');

        foreach (self::getCommandsIndex() as $cmd) {
            if ($cmd['group'] == $group_name)
                $this->addCommand($cmd);
        }
    }

    /**
     * Supprime un groupe de commandes
     *
     * @param String $groupName Le nom du groupe de commandes
     */
    public function removeCommands($group_name) {
        log::add('panasonicVIERA', 'debug', '=> removeCommands('.$group_name.')');
        foreach (self::getCommandsIndex() as $cmd) {
            if ($cmd['group'] == $group_name) {
                $this->removeCommand($cmd);
            }
        }
    }

    public function preInsert() {

    }

    public function postInsert() {

    }

    public function preSave() {
        if (!$this->getId()) {
            log::add('panasonicVIERA', 'debug', '=> preSave empty id');
            return;
        }

        log::add('panasonicVIERA', 'debug', 'official index contains : ' . count(self::getCommandsIndex()). " commands");

        foreach(self::getCommandGroups() as $name => $key) {
            if ($this->getConfiguration($key) == 1) {
                log::add('panasonicVIERA', 'debug', "add $name commands");
                $this->addCommands($name);
            } else {
                log::add('panasonicVIERA', 'debug', "remove $name commands");
                $this->removeCommands($name);
            }
        }
    }

    public function postSave() {

    }

    public function preUpdate() {
        $addr = $this->getConfiguration(self::KEY_ADDRESS);
        if ($addr == '') {
            log::add('panasonicVIERA', 'debug', '=> preUpdate: ip address empty');
            throw new Exception(__('The IP address must not be empty. Check you network gateway to find it.', __FILE__));
        }

        if (!filter_var($addr, FILTER_VALIDATE_IP)) {
            log::add('panasonicVIERA', 'debug', '=> preUpdate: ip address checking failure');
            throw new Exception(__('You entered a wrong IP address', __FILE__). " '$addr'.");
        }
    }

    public function postUpdate() {

    }

    public function preRemove() {

    }

    public function postRemove() {

    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*     * **********************Getteur Setteur*************************** */

    /**
     * Get the IP address of this eq
     *
     * @return string|null the IP address if available
     */
    public function getIpAddress() {
        return $this->getConfiguration(panasonicVIERA::KEY_ADDRESS);
    }

    /**
     * Set the new ip address for this command
     *
     * @param string the new IP address
     * @return this
     */
    public function setIpAddress($ip) {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            log::add('panasonicVIERA', 'debug', '=> setIpAddress: ip address checking failure');
            throw new Exception(__('You entered a wrong IP address', __FILE__). " '$ip'.");
        }
        $this->setConfiguration(panasonicVIERA::KEY_ADDRESS, $ip);
        return $this;
    }

}

class panasonicVIERACmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */
    public function execute($_options = array()) {
        $panasonicTV = $this->getEqLogic();
        $tvip = $panasonicTV->getIpAddress();

        switch($this->type) {
            case 'action':
                log::add('panasonicVIERA', 'debug', 'Action command');
                $action = $this->getConfiguration('action');
                $command = $this->getConfiguration('command');
                panasonicVIERA::execute3rdParty("panasonic_viera_adapter.py", [$action, $tvip, $command], $this->getName());

                break;
            case 'info':
                log::add('panasonicVIERA', 'debug', 'Info command');
                $action = $this->getConfiguration('action');
                $command = $this->getConfiguration('command');
                return panasonicVIERA::execute3rdParty("panasonic_viera_adapter.py", [$action, $tvip, $command], $this->getName());
            default:
                throw new Exception(__('Unknown command type : ', __FILE__) . $this->type);
        }
    }

    /*     * **********************Getteur Setteur*************************** */
}

?>
