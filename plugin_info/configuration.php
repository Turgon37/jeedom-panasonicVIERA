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
<?php if (false) : ?>
<div class="panel panel-default">
    <div class="panel-heading">{{Make a donation}}</div>
    <div class="panel-body">
        {{This plugin is free available to allow everyone to use it easily. If you want, you can make a donation to the developer by using the following link}}
        <br />
        <br />
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
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
<?php endif; ?>
