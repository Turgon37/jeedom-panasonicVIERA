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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
?>
<form class="form-horizontal">
    <fieldset>
        <div class="form-group">
            <label class="col-lg-3 control-label">{{Timeout for TV's commands (let blank to use default)}}</label>
            <div class="col-lg-1">
                <input class="configKey form-control" data-l1key="command_timeout" placeholder="<?= panasonicTV2::getCommandTimeout() ?>"
                        type="number" min="0"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">{{Timeout for TV's discovery command (let blank to use default)}}</label>
            <div class="col-lg-1">
                <input class="configKey form-control" data-l1key="discovery_timeout" placeholder="<?= panasonicTV2::getDiscoveryTimeout() ?>"
                        type="number" min="0"/>
            </div>
        </div>
    </fieldset>
</form>
<br />
<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <a data-toggle="collapse" href="#collapse1">{{Make a donation}}</a>
        </div>
        <div id="collapse1" class="panel-collapse collapse">
            <div class="panel-body">
                {{This plugin is free available to allow everyone to use it easily. If you want, you can make a donation to the developer by using the following link}}
                <br />
                <br />
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="HC5NXE3C7Y7AW">
        <?php switch(translate::getLanguage()) :
            case 'fr_FR': ?>
                    <input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="{{Make a donation using Paypal}}">
        <?php   break; ?>
        <?php default: ?>
                    <input type="image" src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_donate_92x26.png" border="0" name="submit" alt="{{Make a donation using Paypal}}">
        <?php   break; ?>
        <?php endswitch; ?>
                    <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
                </form>
            </div>
        </div>
    </div>
</div>
