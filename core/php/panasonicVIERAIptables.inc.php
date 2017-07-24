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

require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class panasonicVIERAIptables
{
    /**
     * Return the metadata array of Iptables settings
     *
     * @return [array]
     *  Each item of the first array is a Iptables parameter
     *  For each, theses configs keys are available
     *     - default : the default value when empty
     *     - type : the type of content in 'string', 'integer'
     *     - required : a boolean that indicates if parameter is mandatory or not
     *     - visible : if false, this parameter will not be available on the configuration screen
     *     - cmdline : the specific first part of cmdline to apply, the second part will be the value
     *                exemple for the 'comment' setting below the final cmdline will be
     *                  --match comment --comment JEEDOM_PANASONICVIERA
     */
    public static function getIptablesSettings() {
        return [
            'table' => [
                'default' => 'filter',
                'type' => 'string',
                'required' => true,
                'description' => 'Nom de la table Iptables',
                'note' => ''
            ],
            'chain' => [
                'default' => 'INPUT',
                'type' => 'string',
                'required' => true,
                'description' => 'Nom de la chaine Iptables',
                'note' => 'Il s\'agit du nom de la chaîne dans laquelle la règle va être ajoutée puis supprimée.'
            ],
            'protocol' => [
                'default' => 'udp',
                'type' => 'string',
                'required' => true,
                'description' => 'Protocole réseau',
                'note' => 'Le protocol s\'applique aux paquets utilisés pour la découverte des TVs'
            ],
            'dport' => [
                'default' => '60000',
                'type' => 'integer',
                'required' => true,
                'description' => 'Port de destination Iptables',
                'note' => 'Ce port correspond au port d\'écoute de Jeedom sur lequel les réponses de TVs vont arriver.'
            ],
            'jump' => [
                'default' => 'ACCEPT',
                'type' => 'string',
                'required' => true,
                'description' => 'Nom de l\'action Iptables (jump)',
                'note' => 'L\'action définit le comportement d\'un paquet pour lequel la règle s\'applique.'
            ],
            'comment' => [
                'default' => 'JEEDOM_PANASONICVIERA',
                'type' => 'string',
                'required' => false,
                'visible' => false,
                'cmdline' => '--match comment --comment',
                'description' => 'Commentaire de la règle',
                'note' => 'Il s\'agit du nom de la chaîne dans laquelle la règle va être ajoutée puis supprimée.'
            ]
        ];
    }

    /**
     * Return the iptables settings to use to create specific discovery rule
     * @return [string] the iptables specifications
     */
    public static function getConfigDiscoveryIptablesSettings($key = 'chain') {
        $metadata = self::getIptablesSettings();
        if (!is_string($key) || !array_key_exists($key, $metadata)) {
            throw new Exception("The key '$key' is not available as iptables setting");
        }
        $default = isset($metadata[$key]['default']) ? $metadata[$key]['default'] : null;
        $type = $metadata[$key]['type'];
        $value = config::byKey('discovery_iptables_settings_' . $key, 'panasonicVIERA', $default);

        switch ($type) {
            case 'string':
                if (   !is_null($value) &&
                    ( !is_string($value) || !preg_match('/^[a-zA-Z_]+$/', $value))    ) {
                    log::add('panasonicVIERA', 'error', sprintf("%s %s %s",
                        __('La valeur du paramètre Iptables', __FILE__),
                        " '$key' => '$value' ",
                        __('est incorrecte.', __FILE__)
                    ));
                    return null;
                }
                break;
            case 'integer':
                $value = intval($value);
                if (   !is_null($value) &&
                    (!is_integer($value) || !preg_match('/^[1-9][0-9]*$/', $value))     ) {
                    log::add('panasonicVIERA', 'error', sprintf("%s %s %s",
                        __('La valeur du paramètre Iptables', __FILE__),
                        " '$key' => '$value' ",
                        __('est incorrecte.', __FILE__)
                    ));
                    return null;
                }
                break;
            default:
                throw new Exception("The key '$key' have a bad type");
        }
        return $value;
    }

    /**
     * Execute Iptables command
     *
     * @param [string] iptables action in 'insert', 'delete'
     * @throw Exception in case of failure
     *
     */
    public static function executeIptables($action = 'insert') {
        $args = [];

        # init all iptables settings
        foreach (self::getIptablesSettings() as $name => $metadata) {
            $value = self::getConfigDiscoveryIptablesSettings($name);
            if (isset($metadata['required']) &&
                $metadata['required'] &&
                (empty($value) || is_null($value))  ) {
                throw new Exception(__("Impossible d'appliquer la règle Iptables car le paramètre", __FILE__) . " '$name' " . __('est vide alors qu\'il est requis.', __FILE__));
            }
            if (isset($metadata['cmdline'])) {
                $cmdl = $metadata['cmdline'];
            } else {
                $cmdl = $name;
            }

            // add iptables action after table setting
            if ($name == 'chain') {
                switch ($action) {
                    case 'insert':
                    case 'delete':
                        $args[$action] = $value;
                        break;
                    default:
                        throw new Exception("The given iptables action is not available '$action'.");
                }
            } else {
                $args[$cmdl] = $value;
            }
        }

        # prepare the command line
        $cmdlines = [];
        foreach ($args as $key => $value) {
            if (!is_null($value)) {
                if ($key[0] != '-') {
                    $key = "--$key";
                }
                array_push($cmdlines, sprintf("%s %s", $key, $value));
            }
        }
        $cmdline = sprintf("sudo iptables %s", implode(' ', $cmdlines));

        # execute the command line
        log::add('panasonicVIERA', 'debug', 'executeIptables : '. $cmdline);
        $outputs = [];
        $last_line = exec(escapeshellcmd($cmdline), $outputs, $ret);
        if (!empty($last_line)) {
            log::add('panasonicVIERA', 'error', 'Iptables execution : '. $last_line);
        }
        if ($ret != 0) {
            if (count($outputs)) {
                foreach ($outputs as $row) {
                    if (empty($row)) {
                        continue;
                    }
                    log::add('panasonicVIERA', 'error', 'Iptables execution : '. $row);
                }
            }
            throw new Exception(__("La création de la règle de parefeu temporaire a echouée. Verifier les logs.", __FILE__));
        }
    }

}

?>
