<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option] any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

return [
    [
        'name' => 'Apps',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_APPS-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Button myapps',
        'group' => 'basic',
        'icon' => 'fa fa-windows',
    ],
    [
        'name' => 'Aspect',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_ASPECT-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Aspect button',
        'group' => 'basic',
    ],
    [
        'name' => 'Blue',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_BLUE-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Blue button',
        'group' => 'colors',
    ],
    [
        'name' => 'Cancel',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_CANCEL-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Button exit',
        'group' => 'basic',
    ],
    # CC = 'NRC_CC-ONOFF'
    [
        'name' => 'Channel+',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_CH_UP-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Channel up',
        'group' => 'basic',
        'icon' => 'fa fa-plus',
    ],
    [
        'name' => 'Channel-',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_CH_DOWN-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Channel down',
        'group' => 'basic',
        'icon' => 'fa fa-minus',
    ],
    # CHAT_MODE = 'NRC_CHAT_MODE-ONOFF'
    # DIGA_CONTROL = 'NRC_DIGA_CTL-ONOFF'
    [
        'name' => 'Display Mode',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_DISP_MODE-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Aspect-Ratio button',
        'group' => 'basic',
        'icon' => 'fa fa-arrows-alt',
    ],
    [
        'name' => 'Down',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_DOWN-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Down arrow',
        'group' => 'basic',
        'icon' => 'fa fa-arrow-down',
    ],
    [
        'name' => 'OK',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_ENTER-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Button OK',
        'group' => 'basic',
    ],
    [
        'name' => 'EPG',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_EPG-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'EPG button',
        'group' => 'basic',
        'icon' => 'fa fa-calendar-o',
    ],
    # EZ_SYNC = 'NRC_EZ_SYNC-ONOFF'
    [
        'name' => 'Favorites',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_FAVORITE-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Favorites channels button',
        'group' => 'others',
        'icon' => 'fa fa-star-o',
    ],
    [
        'name' => 'Fast forward',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_FF-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Fast forward button',
        'group' => 'record',
        'icon' => 'fa fa-forward',
    ],
    # GAME = 'NRC_GAME-ONOFF'
    [
        'name' => 'Green',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_GREEN-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Green button',
        'group' => 'colors',
    ],
    [
        'name' => 'Guide',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_GUIDE-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Help center button',
        'group' => 'basic',
        'icon' => 'fa fa-question'
    ],
    #HOLD = 'NRC_HOLD-ONOFF'
    [
        'name' => 'Home',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_HOME-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Home button',
        'group' => 'basic',
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Index',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_INDEX-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Index button',
        'group' => 'basic',
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Info',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_INFO-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Info button',
        'group' => 'basic',
        'icon' => 'fa fa-info-circle',
    ],
    [
        'name' => 'Input',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_CHG_INPUT-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'AV/Input button',
        'group' => 'basic',
        'icon' => 'techno-tv6',
    ],
    [
        'name' => 'Internet',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_INTERNET-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Internet button',
        'group' => 'multimedia',
    ],
    [
        'name' => 'Last view',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_R_TUNE-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Last view button',
        'group' => 'numeric',
        'icon' => 'fa fa-refresh',
    ],
    [
        'name' => 'Left',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_LEFT-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Left arrow',
        'group' => 'basic',
        'icon' => 'fa fa-arrow-left',
    ],
    [
        'name' => 'Link',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_VIERA_LINK-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Link button',
        'group' => 'multimedia',
        'icon' => 'fa fa-comment',
    ],
    [
        'name' => 'Menu',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_MENU-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Menu button',
        'group' => 'basic',
        'icon' => 'fa fa-wrench',
    ],
    # MPX = 'NRC_MPX-ONOFF'
    [
        'name' => 'Mute',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_MUTE-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Mute toggle',
        'group' => 'basic',
        'icon' => 'fa fa-volume-off',
    ],
    # NETWORK = 'NRC_CHG_NETWORK-ONOFF'
    [
        'name' => '0',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_D0-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Button 0',
        'group' => 'numeric',
    ],
    [
        'name' => '1',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_D1-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Button 1',
        'group' => 'numeric',
    ],
    [
        'name' => '2',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_D2-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Button 2',
        'group' => 'numeric',
    ],
    [
        'name' => '3',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_D3-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Button 3',
        'group' => 'numeric',
    ],
    [
        'name' => '4',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_D4-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Button 4',
        'group' => 'numeric',
    ],
    [
        'name' => '5',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_D5-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Button 5',
        'group' => 'numeric',
    ],
    [
        'name' => '6',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_D6-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Button 6',
        'group' => 'numeric',
    ],
    [
        'name' => '7',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_D7-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Button 7',
        'group' => 'numeric',
    ],
    [
        'name' => '8',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_D8-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Button 8',
        'group' => 'numeric',
    ],
    [
        'name' => '9',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_D9-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Button 9',
        'group' => 'numeric',
    ],
    #NET_BS = 'NRC_NET_BS-ONOFF'
    #NET_CS = 'NRC_NET_CS-ONOFF'
    #NET_TD = 'NRC_NET_TD-ONOFF'
    [
        'name' => 'Off timer',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_OFFTIMER-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Off timer button',
        'group' => 'others',
    ],
    [
        'name' => 'Option',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_SUBMENU-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Option menu button',
        'group' => 'others',
    ],
    [
        'name' => 'Pause',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_PAUSE-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Pause button',
        'group' => 'record',
        'icon' => 'fa fa-pause',
    ],
    # PICTAI = 'NRC_PICTAI-ONOFF'
    [
        'name' => 'Play',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_PLAY-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Play button',
        'group' => 'record',
        'icon' => 'fa fa-play',
    ],
    # P_NR = 'NRC_P_NR-ONOFF'
    [
        'name' => 'Power off',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_POWER-ONOFF'
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Power off the TV',
        'group' => 'basic',
        'icon' => 'fa fa-power-off',
    ],
    # PROGRAM = 'NRC_PROG-ONOFF'
    [
        'name' => 'Record',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_REC-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Record button',
        'group' => 'record',
        'icon' => 'fa fa-circle',
    ],
    [
        'name' => 'Red',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_RED-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Red button',
        'group' => 'colors',
    ],
    [
        'name' => 'Back',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_RETURN-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Back button',
        'group' => 'basic',
        'icon' => 'fa fa-reply',
    ],
    [
        'name' => 'Rewind',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_REW-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Rewind button',
        'group' => 'record',
        'icon' => 'fa fa-backward',
    ],
    [
        'name' => 'Right',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_RIGHT-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Right arrow',
        'group' => 'basic',
        'icon' => 'fa fa-arrow-right',
    ],
    # R_SCREEN = 'NRC_R_SCREEN-ONOFF'
    # SAP = 'NRC_SAP-ONOFF'
    [
        'name' => 'SD Card',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_SD_CARD-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'SD Card button',
        'group' => 'multimedia',
    ],
    [
        'name' => 'Next',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_SKIP_NEXT-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Next button',
        'group' => 'record',
        'icon' => 'fa fa-step-forward',
    ],
    [
        'name' => 'Previous',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_SKIP_PREV-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Previous button',
        'group' => 'record',
        'icon' => 'fa fa-step-backward',
    ],
    # SPLIT = 'NRC_SPLIT-ONOFF'
    [
        'name' => 'Stop',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_STOP-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Stop button',
        'group' => 'record',
        'icon' => 'fa fa-stop',
    ],
    [
        'name' => 'Subtitles',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_STTL-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Subtitles button',
        'group' => 'others',
        'icon' => 'fa fa-table',
    ],
    [
        'name' => 'Surround',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_SURROUND-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Surround setting button',
        'group' => 'others',
    ],
    # SWAP = 'NRC_SWAP-ONOFF'
    [
        'name' => 'Teletext',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_TEXT-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Teletext button',
        'group' => 'others',
        'icon' => 'fa fa-building-o',
    ],
    [
        'name' => 'TV',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_TV-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'TV button',
        'group' => 'basic',
        'icon' => 'techno-tv6',
    ],
    # THIRTY_SECOND_SKIP = 'NRC_30S_SKIP-ONOFF'
    [
        'name' => '3D',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_3D-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => '3D button',
        'group' => 'others',
    ],
    [
        'name' => 'Up',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_UP-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Up arrow',
        'group' => 'basic',
        'icon' => 'fa fa-arrow-up',
    ],
    [
        'name' => 'Vol+',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_VOLUP-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Volume up',
        'group' => 'basic',
        'icon' => 'fa fa-volume-up',
    ],
    [
        'name' => 'Vol-',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_VOLDOWN-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Volume down',
        'group' => 'basic',
        'icon' => 'fa fa-volume-down',
    ],
    # VTOOLS = 'NRC_VTOOLS-ONOFF'
    [
        'name' => 'Yellow',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_YELLOW-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Yellow button',
        'group' => 'colors',
    ],
    [
        'name' => 'Recording list',
        'configuration' => [
            'action' => 'X_SendKey',
            'command' => 'NRC_RECLIST-ONOFF',
        ],
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Recording list button',
        'version' => '0.1',
        'required' => '',
        'group' => 'others',
        'icon' => 'fa fa-film',
    ]
];
?>
