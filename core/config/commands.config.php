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
            'action' => 'sendkey',
            'command' => 'NRC_APPS-ONOFF',
            'description' => 'Button myapps',
            'group' => 'basic'
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-windows'
    ],
    [
        'name' => 'Aspect',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_ASPECT-ONOFF',
            'description' => 'Aspect button',
            'group' => 'basic'
        ],
        'type' => 'action',
        'subType' => 'other'
    ],
    [
        'name' => 'Blue',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_BLUE-ONOFF',
            'description' => 'Blue button',
            'group' => 'colors'
        ],
        'type' => 'action',
        'subType' => 'other'
    ],
    [
        'name' => 'Cancel',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_CANCEL-ONOFF',
            'description' => 'Button exit',
            'group' => 'basic'
        ],
        'type' => 'action',
        'subType' => 'other'
    ],
    # CC = 'NRC_CC-ONOFF'
    [
        'name' => 'Channel+',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_CH_UP-ONOFF',
            'description' => 'Channel up',
            'group' => 'basic'
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-plus'
    ],
    [
        'name' => 'Channel-',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_CH_DOWN-ONOFF',
            'description' => 'Channel down',
            'group' => 'basic'
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-minus'
    ],
    # CHAT_MODE = 'NRC_CHAT_MODE-ONOFF'
    # DIGA_CONTROL = 'NRC_DIGA_CTL-ONOFF'
    [
        'name' => 'Display Mode',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_DISP_MODE-ONOFF',
            'description' => 'Aspect-Ratio button',
            'group' => 'basic'
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-arrows-alt'
    ],
    [
        'name' => 'Down',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_DOWN-ONOFF',
            'description' => 'Down arrow',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-arrow-down',
    ],
    [
        'name' => 'OK',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_ENTER-ONOFF',
            'description' => 'Button OK',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => 'EPG',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_EPG-ONOFF',
            'description' => 'EPG button',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-calendar-o',
    ],
    # EZ_SYNC = 'NRC_EZ_SYNC-ONOFF'
    [
        'name' => 'Favorites',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_FAVORITE-ONOFF',
            'group' => 'others',
            'description' => 'Favorites channels button',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-star-o',
    ],
    [
        'name' => 'Fast forward',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_FF-ONOFF',
            'description' => 'Fast forward button',
            'group' => 'record',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-forward',
    ],
    # GAME = 'NRC_GAME-ONOFF'
    [
        'name' => 'Green',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_GREEN-ONOFF',
            'description' => 'Green button',
            'group' => 'colors',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => 'Guide',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_GUIDE-ONOFF',
            'description' => 'Help center button',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-question'
    ],
    #HOLD = 'NRC_HOLD-ONOFF'
    [
        'name' => 'Home',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_HOME-ONOFF',
            'description' => 'Home button',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Index',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_INDEX-ONOFF',
            'description' => 'Index button',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Info',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_INFO-ONOFF',
            'description' => 'Info button',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-info-circle',
    ],
    [
        'name' => 'Input',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_CHG_INPUT-ONOFF',
            'description' => 'AV/Input button',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'techno-tv6',
    ],
    [
        'name' => 'Internet',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_INTERNET-ONOFF',
            'description' => 'Internet button',
            'group' => 'multimedia',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => 'Last view',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_R_TUNE-ONOFF',
            'description' => 'Last view button',
            'group' => 'numeric',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-refresh',
    ],
    [
        'name' => 'Left',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_LEFT-ONOFF',
            'description' => 'Left arrow',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-arrow-left',
    ],
    [
        'name' => 'Link',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_VIERA_LINK-ONOFF',
            'description' => 'Link button',
            'group' => 'multimedia',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-comment',
    ],
    [
        'name' => 'Menu',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_MENU-ONOFF',
            'description' => 'Menu button',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-wrench',
    ],
    # MPX = 'NRC_MPX-ONOFF'
    [
        'name' => 'Mute',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_MUTE-ONOFF',
            'description' => 'Mute toggle',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-volume-off',
    ],
    # NETWORK = 'NRC_CHG_NETWORK-ONOFF'
    [
        'name' => '0',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_D0-ONOFF',
            'description' => 'Button 0',
            'group' => 'numeric',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => '1',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_D1-ONOFF',
            'description' => 'Button 1',
            'group' => 'numeric',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => '2',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_D2-ONOFF',
            'description' => 'Button 2',
            'group' => 'numeric',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => '3',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_D3-ONOFF',
            'description' => 'Button 3',
            'group' => 'numeric',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => '4',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_D4-ONOFF',
            'description' => 'Button 4',
            'group' => 'numeric',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => '5',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_D5-ONOFF',
            'description' => 'Button 5',
            'group' => 'numeric',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => '6',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_D6-ONOFF',
            'description' => 'Button 6',
            'group' => 'numeric',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => '7',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_D7-ONOFF',
            'description' => 'Button 7',
            'group' => 'numeric',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => '8',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_D8-ONOFF',
            'description' => 'Button 8',
            'group' => 'numeric',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => '9',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_D9-ONOFF',
            'description' => 'Button 9',
            'group' => 'numeric',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    #NET_BS = 'NRC_NET_BS-ONOFF'
    #NET_CS = 'NRC_NET_CS-ONOFF'
    #NET_TD = 'NRC_NET_TD-ONOFF'
    [
        'name' => 'Off timer',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_OFFTIMER-ONOFF',
            'description' => 'Off timer button',
            'group' => 'others',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => 'Option',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_SUBMENU-ONOFF',
            'description' => 'Option menu button',
            'group' => 'others',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => 'Pause',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_PAUSE-ONOFF',
            'description' => 'Pause button',
            'group' => 'record'
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-pause',
    ],
    # PICTAI = 'NRC_PICTAI-ONOFF'
    [
        'name' => 'Play',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_PLAY-ONOFF',
            'description' => 'Play button',
            'group' => 'record'
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-play',
    ],
    # P_NR = 'NRC_P_NR-ONOFF'
    [
        'name' => 'Power off',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_POWER-ONOFF',
            'description' => 'Power off the TV',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-power-off',
    ],
    # PROGRAM = 'NRC_PROG-ONOFF'
    [
        'name' => 'Record',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_REC-ONOFF',
            'description' => 'Record button',
            'group' => 'record',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-circle',
    ],
    [
        'name' => 'Red',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_RED-ONOFF',
            'description' => 'Red button',
            'group' => 'colors',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => 'Back',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_RETURN-ONOFF',
            'description' => 'Back button',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-reply',
    ],
    [
        'name' => 'Rewind',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_REW-ONOFF',
            'description' => 'Rewind button',
            'group' => 'record',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-backward',
    ],
    [
        'name' => 'Right',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_RIGHT-ONOFF',
            'description' => 'Right arrow',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-arrow-right',
    ],
    # R_SCREEN = 'NRC_R_SCREEN-ONOFF'
    # SAP = 'NRC_SAP-ONOFF'
    [
        'name' => 'SD Card',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_SD_CARD-ONOFF',
            'description' => 'SD Card button',
            'group' => 'multimedia',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => 'Next',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_SKIP_NEXT-ONOFF',
            'description' => 'Next button',
            'group' => 'record',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-step-forward',
    ],
    [
        'name' => 'Previous',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_SKIP_PREV-ONOFF',
            'description' => 'Previous button',
            'group' => 'record',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-step-backward',
    ],
    # SPLIT = 'NRC_SPLIT-ONOFF'
    [
        'name' => 'Stop',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_STOP-ONOFF',
            'description' => 'Stop button',
            'group' => 'record',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-stop',
    ],
    [
        'name' => 'Subtitles',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_STTL-ONOFF',
            'description' => 'Subtitles button',
            'group' => 'others',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-table',
    ],
    [
        'name' => 'Surround',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_SURROUND-ONOFF',
            'description' => 'Surround setting button',
            'group' => 'others',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    # SWAP = 'NRC_SWAP-ONOFF'
    [
        'name' => 'Teletext',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_TEXT-ONOFF',
            'description' => 'Teletext button',
            'group' => 'others',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-building-o',
    ],
    [
        'name' => 'TV',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_TV-ONOFF',
            'description' => 'TV button',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'techno-tv6',
    ],
    # THIRTY_SECOND_SKIP = 'NRC_30S_SKIP-ONOFF'
    [
        'name' => '3D',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_3D-ONOFF',
            'description' => '3D button',
            'group' => 'others',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => 'Up',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_UP-ONOFF',
            'description' => 'Up arrow',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-arrow-up',
    ],
    [
        'name' => 'Vol+',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_VOLUP-ONOFF',
            'description' => 'Volume up',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-volume-up',
    ],
    [
        'name' => 'Vol-',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_VOLDOWN-ONOFF',
            'description' => 'Volume down',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-volume-down',
    ],
    # VTOOLS = 'NRC_VTOOLS-ONOFF'
    [
        'name' => 'Yellow',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_YELLOW-ONOFF',
            'description' => 'Yellow button',
            'group' => 'colors',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => 'Recording list',
        'configuration' => [
            'action' => 'sendkey',
            'command' => 'NRC_RECLIST-ONOFF',
            'description' => 'Recording list button',
            'group' => 'others',
        ],
        'type' => 'action',
        'subType' => 'other',
        'icon' => 'fa fa-film',
    ],
    [
        'name' => 'Set Volume',
        'configuration' => [
            'action' => 'set',
            'command' => 'SetVolume',
            'description' => 'Set the current TV volume',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => 'Get Volume',
        'configuration' => [
            'action' => 'render',
            'command' => 'GetVolume',
            'description' => 'Get the current TV volume',
            'group' => 'basic',
        ],
        'type' => 'info',
        'subType' => 'numeric',
    ],
    [
        'name' => 'Set Mute',
        'configuration' => [
            'action' => 'set',
            'command' => 'SetMute',
            'description' => 'Set the current mute status',
            'group' => 'basic',
        ],
        'type' => 'action',
        'subType' => 'other',
    ],
    [
        'name' => 'Get Mute',
        'configuration' => [
            'action' => 'render',
            'command' => 'GetMute',
            'group' => 'basic',
            'description' => 'Get the current TV volume',
        ],
        'type' => 'info',
        'subType' => 'binary',
    ]
];
?>
