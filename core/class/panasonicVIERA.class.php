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
require_once dirname(__FILE__) . '/../php/panasonicVIERAIptables.inc.php';


class panasonicVIERA extends eqLogic {

    // name of ip address configuration key
    const KEY_ADDRESS = 'address';
    // name of uuid configuration key
    const KEY_UUID = 'uuid';
    // name of model configuration key
    const KEY_MODEL = 'model';
    // name of the features configuration key
    const KEY_FEATURES = 'features';
    /**
     * this configuration key if a boolean that indicates if the mac
     * address has been fetched by discovery or filled manually
     */
    const KEY_MAC_DISCOVERED = 'macaddress_discovered';


    const KEY_WAKEUP = 'wakeup';
    const KEY_WAKEUPCMD = 'wakeupcmd';

    const KEY_VOLUMESTEP = 'volume_step';

    const KEY_THEME = 'theme';


    const COMMANDS_GROUPS = [
        'basic' => 'Basiques',
        'numeric' => 'Numeriques',
        'record' => 'Enregistrement',
        'multimedia' => 'Multimedia',
        'colors' => 'Couleurs',
        'others' => 'Autres'
    ];

    // The command template for WakeOnLan command
    const TEMPLATE_CMD_WAKEUP = [
        'name' => 'Wake UP',
        'logicalId' => 'wakeup',
        'type' => 'action',
        'subType' => 'other',
        'configuration' => [
            'description' => 'Wakeup the TV',
            'group' => 'basic',
            'wakeup_type' => 'none',
            'action' => 'wakeup',
            'command' => 'none',
            'autocreated' => true
        ],
    ];

    // The mapping of erros messages with errors codes
    const PANASONIC_VIERA_LIB_ERRORS = [
        408 => 'La TV est indisponible',
        405 => 'Cette commande semble ne pas être supportée par la TV'
    ];

    /*     * *************************Attributs****************************** */
    /**
     * @var this is the official commands index
     */
    protected static $_command_index = [];


    /*     * ***********************Methode static*************************** */
    /**
     * Function that compare two command object
     *
     * @param $cmd_a
     * @param $cmd_b
     * @return [int]
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
     *
     * @var array
     */
    public static function getCommandGroups() {
        return self::COMMANDS_GROUPS;
    }

    /**
     * Return the dependancy info about this plugin
     *
     * @return [array] an array with the following keys
     *    log : the name of the log file
     *    progress_file : the path to the file which indicates the progres status
     *    state : the status of dependancies
     */
    public static function dependancy_info() {
        $return = array();
        $return['log'] = 'panasonicVIERA_dependancy';
        $return['progress_file'] = '/tmp/dependancy_panasonicVIERA_in_progress';
        $return['state'] = 'ok';
        $lib_version = self::getLibraryVersion('local');
        $online_lib_version = self::getLibraryVersion('online');
        if (is_null($lib_version)) {
            $return['state'] = 'nok';
        } else if (!is_null($online_lib_version) && version_compare($online_lib_version, $lib_version, '>')) {
            $return['state'] = 'nok';
        }
        log::add('panasonicVIERA', 'debug', "dependency check, local : $lib_version, remote : $online_lib_version");
        return $return;
    }

    /**
     * Run the installation of dependancies
     *
     */
    public static function dependancy_install() {
        log::remove('panasonicVIERA_dependancy');
        $cmd = 'sudo /bin/bash ' . dirname(__FILE__) . '/../../resources/install.sh';
        $cmd .= ' >> ' . log::getPathToLog('panasonicVIERA_dependancy') . ' 2>&1 &';
        exec($cmd);
    }

    /**
     * Return the specific health informations
     *
     * @return [array] an array with the following keys
     *
     */
    public static function health() {
        $healths = [];

        /* LIBRARY HEALTH */
        $health_lib = [
            'advice' => '',
            'result' => '',
            'state' => true,
            'test' => __('Version de la bibliothèque Python', __FILE__),
        ];
        $lib_local = self::getLibraryVersion('local');
        $lib_online = self::getLibraryVersion('online');
        if (version_compare($lib_online, $lib_local, '>')) {
            $health_lib['state'] = false;
            $health_lib['advice'] = __('Vous devriez relancer l\'installation des dépendances', __FILE__);
            $health_lib['result'] = 'NOK';
        } else {
            $health_lib['result'] = 'OK';
        }
        $health_lib['result'] .= sprintf(' (Local = %s, Online = %s)', $lib_local, $lib_online);

        $healths[] = $health_lib;
        return $healths;
    }

    /**
     * Return the version of the local panasonic viera library
     *
     * @param [string] the instance of version to check
     * @return string|null
     */
    public static function getLibraryVersion($instance = 'local') {
        // check lock key to prevent multiple run of the dscovery at the same time
        $version = cache::byKey('panasonicVIERA__library_version_' . $instance)->getValue(null);
        # try to fetch the asked version from cmdline
        if ($version === null) {
            log::add('panasonicVIERA', 'debug', 'fetch library version from 3rdparty');
            try {
                $lib_version = panasonicVIERA::execute3rdParty("panasonic_viera_adapter.py", ['version', "--$instance"]);
            } catch (Exception $e) {
                log::add('panasonicVIERA', 'debug', 'catch Exception from 3rdparty');
                $lib_version = null;
            }
            if (!is_null($lib_version)) {
                cache::set('panasonicVIERA__library_version', $lib_version, 60*60*24);
                $version = $lib_version;
            }
        }
        return $version;
    }

    /**
     * Return the timeout value for standard TV commands
     * @return [int] the timeout in seconds
     */
    public static function getConfigCommandTimeout() {
        return config::byKey('command_timeout', 'panasonicVIERA', 2);
    }

    /**
     * Return the broadcast ip address to use in magic packets
     * @return [string] the ip address
     */
    public static function getConfigBroadcastIp() {
        $ip = config::byKey('broadcast_ip', 'panasonicVIERA', '255.255.255.255');
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return null;
        }
        return $ip;
    }

    /**
     * Return the timeout value for discovery commands
     * @return [int] the discovery timeout in seconds
     */
    public static function getConfigDiscoveryTimeout() {
        return config::byKey('discovery_timeout', 'panasonicVIERA', 3);
    }

    /**
     * Return the timeout value for discovery commands
     * @return [int] the discovery timeout in seconds
     */
    public static function getConfigDiscoveryIptables() {
        $b = config::byKey('discovery_iptables', 'panasonicVIERA', false);
        return boolval($b);
    }

    /**
     * Execute a 3rd party command not written in PHP
     *
     * @param [string] the name of the command file in 3rdparty/ directory
     * @param [array] the list of command arguments
     * @param [string] OPTIONNAL a verbose name to include in errors statments.
     * @param [boolean] OPTIONNAL if true 3rd party command errors will be threw as exception.
     * @return mixed : the output of the command (stdout of the command)
     * @throw Exception in case of failure
     */
    public static function execute3rdParty($command, $args = [], $name = null, $throw_errors = true, $error_codes = []) {
        $base_path = realpath(__DIR__ . '/../../3rdparty');
        $extension = pathinfo($command, PATHINFO_EXTENSION);
        $runtime = 'bash';
        switch ($extension) {
            case 'py':
                $runtime = 'python';
                break;
        }

        $cmdline = sprintf("%s %s/%s %s", $runtime, $base_path, $command, implode(' ', $args));
        // by default the log 'name' will be the command name
        if ($name === null) {
            $name = $command;
        }

        $output = null;

        log::add('panasonicVIERA', 'debug', 'execute3rdParty : '. $cmdline);
        $shell_output = trim(shell_exec(escapeshellcmd($cmdline)));
        if ($shell_output == 'null') {
            log::add('panasonicVIERA', 'debug', "execute3rdParty : command $command has returned null");
            return null;
        }

        // decode json raw output
        $decoded = json_decode($shell_output, JSON_OBJECT_AS_ARRAY|JSON_NUMERIC_CHECK);
        if (is_null($decoded)) {
            log::add('panasonicVIERA', 'debug', "execute3rdParty : $command's output : $shell_output");
            throw new Exception(__("La commande", __FILE__) . " $command " . __('n\'a pas retournée de données JSON valides.', __FILE__));
        }

        # transcript logs messages from python script to jeedom
        if (isset($decoded['log'])) {
            foreach ($decoded['log'] as $record) {
                log::add('panasonicVIERA', $record['level'], $record['message']);
            }
        }
        # handle return code and error message
        if (isset($decoded['status']) && intval($decoded['status']) != 0) {
            $message = __("La commande", __FILE__) . " $name " . __('a echouée.', __FILE__);
            if ( isset($decoded['error_code']) ) {
                log::add('panasonicVIERA', 'debug', "execute3rdParty : command $command has returned error code : " . $decoded['error_code']);
            }
            if ( isset($decoded['error_code']) && isset($error_codes[$decoded['error_code']]) ) {
                $message .= "<br />" . __($error_codes[$decoded['error_code']], __FILE__);
            } elseif (isset($decoded['error'])) {
                $message .= "<br />" . __($decoded['error'], __FILE__);
            }
            if ($throw_errors) {
                throw new Exception($message);
            }
        }

        # handle standard output
        if (isset($decoded['output'])) {
            $output = $decoded['output'];
        }

        return $output;
    }

    /**
     * Entry point of TV network discovery function
     *
     * @return [array] an array which contains statistics about results
     *    Theses keys are available
     *       - updated the number of updated TVs
     *       - created the number of created TVs
     *       - total the total number of discovered TVs
     */
    public static function discoverNetwork() {
        // check lock key to prevent multiple run of the dscovery at the same time
        if (cache::byKey('panasonicVIERA__discover_lock')->getValue(false) == true) {
            log::add('panasonicVIERA', 'debug', 'discovering already in progress');
            throw new Exception(__('La découverte est déjà en cours. Si une erreur est survenue veuillez patientez quelques secondes.', __FILE__));
        }
        cache::set('panasonicVIERA__discover_lock', true, 30);

        $result = [
            'updated' => 0,
            'created' => 0,
            'total' => 0
        ];
        if (self::getConfigDiscoveryIptables()) {
            log::add('panasonicVIERA', 'debug', 'open firewall for discovery');
            panasonicVIERAIptables::executeIptables('insert');
        }
        log::add('panasonicVIERA', 'debug', 'run discovery command');
        $discovered = self::execute3rdParty("panasonic_viera_adapter.py", ['find'], 'discover');
        if (self::getConfigDiscoveryIptables()) {
            log::add('panasonicVIERA', 'debug', 'close firewall after discovery');
            panasonicVIERAIptables::executeIptables('delete');
        }
        if (count($discovered)) {
            log::add('panasonicVIERA', 'debug', 'found ' . count($discovered) . ' TV(s) on the network');
            foreach ($discovered as $tv) {
                if (!isset($tv['address']) || is_null($tv['address']) || empty($tv['address'])) {
                    throw new Exception(__('Missing address key in discovery answer from 3rd party', __FILE__));
                }

                $address = $tv['address'];
                $mac = null;
                if (isset($tv['mac'])) {
                    $mac = $tv['mac'];
                }
                $uuid = isset($tv['computed']['uuid']) ? $tv['computed']['uuid'] : null;
                $eq = null;

                // try to find an existing cmd by the mac address
                if (!is_null($mac) && !empty($mac)) {
                    $eq = self::byLogicalId($mac, 'panasonicVIERA');
                    log::add('panasonicVIERA', 'debug', sprintf("search existing equipment by mac address '%s'", $mac));
                    if (is_object($eq)) {
                        log::add('panasonicVIERA', 'debug', sprintf("found existing equipment %d by mac address '%s'", $eq->getId(), $mac));
                    }
                }

                // try to find an existing cmd by the uuid
                if (!is_object($eq) && !is_null($uuid) && !empty($uuid)) {
                    log::add('panasonicVIERA', 'debug', sprintf("search existing equipment by uuid '%s'", $uuid));
                    $search = self::byTypeAndSearhConfiguration('panasonicVIERA', sprintf('"%s":"%s"', self::KEY_UUID, $uuid));
                    if (count($search)) {
                        $eq = $search[0];
                    }
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
                    $name = isset($tv['computed']['name']) ? $tv['computed']['name'] : $address;;
                    log::add('panasonicVIERA', 'debug', sprintf('create new TV equipment with address \'%s\' and name : \'%s\'', $address, $name));
                    $eq = new panasonicVIERA();
                    $eq->setEqType_name('panasonicVIERA');
                    $eq->setName($name);
                    $result['created'] += 1;
                } else {
                    log::add('panasonicVIERA', 'debug', "update existing TV equipment");
                    $result['updated'] += 1;
                }
                $result['total'] = $result['updated'] + $result['created'];

                // update set eq settings
                $eq->setIpAddress($address);
                if (!is_null($mac) and !empty($mac)) {
                    $eq->setMacAddress($mac);
                    $eq->setConfiguration(self::KEY_MAC_DISCOVERED, true);
                } else {
                    $eq->setConfiguration(self::KEY_MAC_DISCOVERED, false);
                }
                // set uuid if available
                if ( !is_null($uuid) and !empty($uuid) ) {
                    $eq->setConfiguration(self::KEY_UUID, $uuid);
                }
                // set model if available
                if ( isset($tv['computed']['model_number']) ) {
                    $eq->setConfiguration(self::KEY_MODEL, $tv['computed']['model_number']);
                }

                if ( isset($tv['computed']) && is_array($tv['computed']) ) {
                    $eq->setConfiguration(self::KEY_FEATURES, $tv['computed']);
                }

                $eq->save();
            }
        }
        cache::getCache()->delete('panasonicVIERA__discover_lock');
        return $result;
    }

    /*     * *********************Méthodes d'instance************************* */

    /**
     * Ajoute une commande à l'objet
     *
     * @param cmd $cmd La commande a ajouter
     */
    protected function addCommand($command, $update = false) {
        if (!is_array($command)) {
            return;
        }

        $panasonicVIERACmd = cmd::byEqLogicIdCmdName($this->getId(), $command['name']);
        if ( is_object($panasonicVIERACmd) && !$update ) {
            log::add('panasonicVIERA', 'debug', '=> addCommand('. $command['name'].') command already exist');
            return;
        }
        if (!is_object($panasonicVIERACmd)) {
            log::add('panasonicVIERA', 'debug', '=> addCommand('. $command['name'].') add command');
            $panasonicVIERACmd = new panasonicVIERACmd();
            $panasonicVIERACmd->setEqLogic_id($this->getId());
        } else {
            log::add('panasonicVIERA', 'debug', '=> addCommand('. $command['name'].') update command');
        }

        $panasonicVIERACmd->setName($command['name']);
        $panasonicVIERACmd->setLogicalId(isset($command['logicalId']) ? $command['logicalId'] : $command['configuration']['command']);
        foreach ($command['configuration'] as $key => $value) {
            $panasonicVIERACmd->setConfiguration($key, $value);
        }
        $panasonicVIERACmd->setType($command['type']);
        $panasonicVIERACmd->setSubType($command['subType']);
        if (isset($command['icon']) && $command['icon'] != '') {
            $panasonicVIERACmd->setDisplay('icon', '<i class=" '.$command['icon'].'"></i>');
        }
        $panasonicVIERACmd->save();
    }

    /**
     * Supprime la commande $name de l'objet
     *
     * @param String $name Le nom de la commande
     */
    protected function removeCommand($cmd) {
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
    protected function addCommands($group_name) {
        log::add('panasonicVIERA', 'debug', '=> addCommands('.$group_name.')');

        foreach (self::getCommandsIndex() as $cmd) {
            # TODO remove filter on infos commands
            if ($cmd['configuration']['group'] == $group_name && $cmd['type'] == 'action' && $cmd['configuration']['action'] == 'sendkey') {
                $this->addCommand($cmd);
            }
        }
    }

    /**
     * Supprime un groupe de commandes
     *
     * @param String $groupName Le nom du groupe de commandes
     */
    protected function removeCommands($group_name) {
        log::add('panasonicVIERA', 'debug', '=> removeCommands('.$group_name.')');
        foreach (self::getCommandsIndex() as $cmd) {
            if ($cmd['configuration']['group'] == $group_name) {
                $this->removeCommand($cmd);
            }
        }
    }

    public function preInsert() {
        $this->setConfiguration(self::KEY_WAKEUP, 'none');
        $this->setConfiguration(self::KEY_VOLUMESTEP, 2);
        $this->setConfiguration(self::KEY_THEME, 'white');
    }

    public function postInsert() {

    }

    public function preUpdate() {
        $addr = $this->getConfiguration(self::KEY_ADDRESS);
        if ($addr == '') {
            log::add('panasonicVIERA', 'debug', '=> preUpdate: ip address empty');
            throw new Exception(__('L\'adresse IP ne peut etre vide. Vous pouvez la trouver dans les paramètres de votre TV ou de votre routeur (box).', __FILE__));
        }
        $this->setIpAddress($addr);

        $this->setMacAddress($this->getLogicalId());
    }

    public function postUpdate() {

    }

    public function preSave() {
        if (!$this->getId()) {
            log::add('panasonicVIERA', 'debug', '=> preSave empty id');
            return;
        }

        log::add('panasonicVIERA', 'debug', 'official index contains : ' . count(self::getCommandsIndex()). " commands");

        foreach(self::getCommandGroups() as $key => $name) {
            if ($this->getConfiguration($key) == 1) {
                log::add('panasonicVIERA', 'debug', "add $name commands");
                $this->addCommands($key);
            } else {
                log::add('panasonicVIERA', 'debug', "remove $name commands");
                $this->removeCommands($key);
            }
        }

        switch ($this->getConfiguration(self::KEY_WAKEUP, 'none')) {
            case 'wol':
                if ($this->getLogicalId() != '') {
                    log::add('panasonicVIERA', 'debug', '=> preSave: add wakeonlan command for valid mac address');
                    $cmd = self::TEMPLATE_CMD_WAKEUP;
                    $cmd['configuration']['wakeup_type'] = $this->getConfiguration(self::KEY_WAKEUP, 'none');
                    $cmd['configuration']['command'] = '';
                    $this->addCommand($cmd, true);
                }
                break;
            case 'cmd':
                $cmd = self::TEMPLATE_CMD_WAKEUP;
                $cmd['configuration']['wakeup_type'] = $this->getConfiguration(self::KEY_WAKEUP, 'none');
                $cmd['configuration']['command'] = $this->getConfiguration(self::KEY_WAKEUPCMD);
                $this->addCommand($cmd, true);
                break;
            case 'none':
                $this->removeCommand(self::TEMPLATE_CMD_WAKEUP);
                break;
            default:
                log::add('panasonicVIERA', 'error', "Bad value for ". self::KEY_WAKEUP . " configuration.");
                break;
        }

    }

    public function postSave() {

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
     * @throw Exception if ip address is not valid
     */
    public function setIpAddress($ip) {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            log::add('panasonicVIERA', 'debug', '=> setIpAddress: ip address checking failure');
            throw new Exception(__('Vous avez saisit une mauvaise adresse IP', __FILE__). " '$ip'.");
        }
        $this->setConfiguration(panasonicVIERA::KEY_ADDRESS, $ip);
        return $this;
    }

    /**
     * Set the new mac address for this command
     *
     * @param string the new mac address
     * @return this
     * @throw Exception if mac address is not valid
     */
    public function setMacAddress($mac) {
        // if mac is not empty validate it
        if ( $mac != '' ) {
            if (!filter_var($mac, FILTER_VALIDATE_MAC)) {
                log::add('panasonicVIERA', 'debug', '=> setMacAddress: mac address checking failure');
                throw new Exception(__('Vous avez saisit une mauvaise adresse MAC', __FILE__). " '$mac'.");
            }
            $this->setLogicalId($mac);
        } else {
            //log::add('panasonicVIERA', 'debug', '=> setMacAddress: remove wakeup command because of empty mac address');
            //$this->removeCommand(self::TEMPLATE_CMD_WAKEUP);
        }

        return $this;
    }

    /**
     * Return some devices informations
     *
     */
    public function getDeviceInformations() {
        $infos = self::execute3rdParty("panasonic_viera_adapter.py",
                [ 'informations', $this->getIpAddress() ],
                __FUNCTION__,
                true,
                self::PANASONIC_VIERA_LIB_ERRORS);
        if (is_null($infos)) {
            throw new Exception(__('La commande a retournée une valeur nulle, veuillez vérifier les dépendances et les log', __FILE__));
        } elseif (!is_array($infos)) {
            throw new Exception(__('La commande a retournée une mauvaise valeur, veuillez vérifier les dépendances et les log', __FILE__));
        }
        return $infos;
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
                $action = $this->getConfiguration('action');
                $command = $this->getConfiguration('command');
                if (empty($action) || is_null($action)) {
                    throw new Exception('Tried to execute a command with an empty action');
                }
                if (empty($command) || is_null($command)) {
                    throw new Exception('Tried to execute a command with an empty command');
                }
                log::add('panasonicVIERA', 'debug', sprintf('execute: Action command : %s', $action));
                switch ($action) {
                    case 'wakeup':
                        $wakeuptype = $this->getConfiguration('wakeup_type');
                        log::add('panasonicVIERA', 'debug', sprintf('execute: WakeUp command type : %s', $wakeuptype));
                        switch ($wakeuptype) {
                            case 'wol':
                            case 'cmd':
                                log::add('panasonicVIERA', 'debug', sprintf('execute: WakeUp command type : %s', $wakeuptype));
                                $wakeup_cmd = cmd::byId(str_replace('#', '', $command));
                                if (is_object($wakeup_cmd)) {
                                    log::add('panasonicVIERA', 'info', __('Execute la commande ', __FILE__) . $wakeup_cmd->getName());
                                    $wakeup_cmd->execCmd();
                                } else {
                                    log::add('panasonicVIERA', 'error', __('Impossible d\'executer la commande ' . $command, __FILE__));
                                }
                                break;
                        }
                        break;
                    default:
                        $result = panasonicVIERA::execute3rdParty("panasonic_viera_adapter.py",
                                [$action, $tvip, $command],
                                $this->getName(),
                                true,
                                self::PANASONIC_VIERA_LIB_ERRORS);
                        if (is_null($result)) {
                            throw new Exception(__('La commande a retournée une valeur nulle, veuillez vérifier les dépendances et les log', __FILE__));
                        }
                        break;
                }

                break;
            case 'info':
                log::add('panasonicVIERA', 'debug', 'Info command');
                $action = $this->getConfiguration('action');
                $command = $this->getConfiguration('command');
                return panasonicVIERA::execute3rdParty("panasonic_viera_adapter.py", [$action, $tvip, $command], $this->getName());
            default:
                throw new Exception(sprintf('Tried to execute an unknown command type : %s', $this->type));
        }
    }

    /*     * **********************Getteur Setteur*************************** */
}

?>
