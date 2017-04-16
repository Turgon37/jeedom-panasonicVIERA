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

require_once __DIR__ . '/../php/constants.inc.php';


class panasonicTV2 extends eqLogic {

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
            usort(self::$_command_index, array('panasonicTV2', 'sortListCmd'));
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
        return config::byKey('command_timeout', 'panasonicTV2', 2);
    }

    /**
     * Return the timeout value for discovery commands
     * @return [int] the discovery timeout in seconds
     */
    public static function getDiscoveryTimeout() {
        return config::byKey('discovery_timeout', 'panasonicTV2', 3);
    }

    /**
     * Execute a 3rd party command not written in PHP
     *
     * @param [string] the name of the command file in 3rdparty/ directory
     * @param [array] the list of command arguments
     * @param [string] OPTIONNAL a verbose name to include in errors statments.
     * @return mixed : the output of the command
     */
    public static function execute3rdParty($command, $args = [], $name = null) {
        $base_path = realpath(__DIR__ . '/../../3rdparty');
        $cmdline = sprintf("%s/%s %s", $base_path, $command, implode(' ', $args));
        if ($name === null) {
            $name = $command;
        }
        $output = null;

        log::add(PANASONIC_TV2_LOG_KEY, 'debug', 'execute3rdParty : '. $cmdline);
        $shell_output = shell_exec(escapeshellcmd($cmdline));
        $decoded = json_decode($shell_output, JSON_OBJECT_AS_ARRAY|JSON_NUMERIC_CHECK);
        if ($decoded !== null) {
            # transcript logs messages from python script to jeedom
            if (isset($decoded['log'])) {
                foreach ($decoded['log'] as $record) {
                    log::add(PANASONIC_TV2_LOG_KEY, $record['level'], $record['message']);
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
        } else {
            throw new Exception(__("The command", __FILE__) . " $command " . __('has not returned a valid JSON output.', __FILE__));
        }
        return $output;
    }

    /*     * *********************Méthodes d'instance************************* */

    /**
     * Ajoute une commande à l'objet
     *
     * @param cmd $cmd La commande a ajouter
     */
    public function addCommand($cmd) {
        if (cmd::byEqLogicIdCmdName($this->getId(), $cmd['name'])) {
            log::add(PANASONIC_TV2_LOG_KEY, 'debug', '=> addCommand('. $cmd['name'].') command already exist');
            return;
        }

        if ($cmd) {
            $panasonicTV2Cmd = new panasonicTV2Cmd();
            $panasonicTV2Cmd->setName($cmd['name']);
            $panasonicTV2Cmd->setEqLogic_id($this->id);
            $panasonicTV2Cmd->setLogicalId($cmd['configuration']['command']);
            $panasonicTV2Cmd->setConfiguration('action', $cmd['configuration']['action']);
            $panasonicTV2Cmd->setConfiguration('command', $cmd['configuration']['command']);
            $panasonicTV2Cmd->setConfiguration('group', $cmd['group']);
            $panasonicTV2Cmd->setType($cmd['type']);
            $panasonicTV2Cmd->setSubType($cmd['subType']);
            if ($cmd['icon'] != '')
                $panasonicTV2Cmd->setDisplay('icon', '<i class=" '.$cmd['icon'].'"></i>');
            log::add(PANASONIC_TV2_LOG_KEY, 'debug', '=> addCommand('. $cmd['name'].') add command');
            $panasonicTV2Cmd->save();
        }
    }

    /**
     * Supprime la commande $name de l'objet
     *
     * @param String $name Le nom de la commande
     */
    public function removeCommand($cmd) {
        if (($panasonicTV2Cmd = cmd::byEqLogicIdCmdName($this->getId(), $cmd['name']))) {
            log::add(PANASONIC_TV2_LOG_KEY, 'debug', '=> removeCommand('. $cmd['name'].') remove command');
            $panasonicTV2Cmd->remove();
        }
    }

    /**
     * Ajoute un groupe de commandes
     *
     * @param String $groupName Le nom du groupe de commandes
     */
    public function addCommands($group_name) {
        log::add(PANASONIC_TV2_LOG_KEY, 'debug', '=> addCommands('.$group_name.')');

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
        log::add(PANASONIC_TV2_LOG_KEY, 'debug', '=> removeCommands('.$group_name.')');
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
            log::add(PANASONIC_TV2_LOG_KEY, 'debug', '=> preSave empty id');
            return;
        }

        log::add(PANASONIC_TV2_LOG_KEY, 'debug', 'official index contains : ' . count(self::getCommandsIndex()). " commands");

        foreach(self::getCommandGroups() as $name => $key) {
            if ($this->getConfiguration($key) == 1) {
                log::add(PANASONIC_TV2_LOG_KEY, 'debug', "add $name commands");
                $this->addCommands($name);
            } else {
                log::add(PANASONIC_TV2_LOG_KEY, 'debug', "remove $name commands");
                $this->removeCommands($name);
            }
        }
    }

    public function postSave() {

    }

    public function preUpdate() {
        $addr = $this->getConfiguration(self::KEY_ADDRESS);
        if ($addr == '') {
            log::add(PANASONIC_TV2_LOG_KEY, 'debug', '=> preUpdate: ip address empty');
            throw new Exception(__('The IP address must not be empty. Check you network gateway to find it.', __FILE__));
        }

        if (!filter_var($addr, FILTER_VALIDATE_IP)) {
            log::add(PANASONIC_TV2_LOG_KEY, 'debug', '=> preUpdate: ip address checking failure');
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
}

class panasonicTV2Cmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */
    public function execute($_options = array()) {
        $panasonicTV = $this->getEqLogic();
        $tvip = $panasonicTV->getConfiguration(panasonicTV2::KEY_ADDRESS);

        switch($this->type) {
            case 'action':
                log::add(PANASONIC_TV2_LOG_KEY, 'debug', 'Action command');
                $action = $this->getConfiguration('action');
                $command = $this->getConfiguration('command');
                panasonicTV2::execute3rdParty("panasonic_viera_adapter.py", [$action, $tvip, $command], $this->getName());

                break;
            case 'info':
                log::add(PANASONIC_TV2_LOG_KEY, 'debug', 'Info command');
                $action = $this->getConfiguration('action');
                $command = $this->getConfiguration('command');
                return panasonicTV2::execute3rdParty("panasonic_viera_adapter.py", [$action, $tvip, $command], $this->getName());
            default:
                throw new Exception(__('Unknown command type : ', __FILE__) . $this->type);
        }
    }

    /*     * **********************Getteur Setteur*************************** */
}

?>
