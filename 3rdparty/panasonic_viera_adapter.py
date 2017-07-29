#!/usr/bin/env python
# -*- coding: utf-8 -*-

# This file is part of Jeedom.
#
# Jeedom is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# Jeedom is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
#

import argparse
import json
import logging
import io
import sys

try:
    import panasonic_viera
except ImportError:
    print(json.dumps(None))
    sys.exit(0)


class ArrayHandler(logging.Handler):
    """Simple array handler that put all log messages to a list
    """
    def __init__(self, dest):
        """Init a new array Handler
        """
        logging.Handler.__init__(self)
        self.dest = dest

    def emit(self, record):
        self.dest.append(dict(level=record.levelname, message=record.getMessage()))

# Init
logs = []
hdlr = ArrayHandler(logs)
panasonic_viera.getLogger().setLevel(logging.DEBUG)
panasonic_viera.getLogger().addHandler(hdlr)

result = dict({'status': 0})

# CREATE PARSER
parser = argparse.ArgumentParser(description="Adapter for panasonic-viera for jeedom")
parser.add_argument("--timeout", help="The timeout for network operations with the TV")
subparsers = parser.add_subparsers()

# VERSION
parser_version = subparsers.add_parser('version', help='Show the version and Exit')
parser_version.set_defaults(action='version', instance='local')
parser_version.add_argument("--local", action='store_const', dest='instance', const='local', help="Specify the local or online version")
parser_version.add_argument("--online", action='store_const', dest='instance', const='online', help="Specify the local or online version")

# FIND
parser_find = subparsers.add_parser('find', help='Find available TVs on the LAN')
parser_find.set_defaults(action='find')

# SENDKEY
parser_sendkey = subparsers.add_parser('sendkey', help='Simple sendkey action')
parser_sendkey.set_defaults(action='sendkey')
parser_sendkey.add_argument("host", help="The hostname of the TV")
parser_sendkey.add_argument("command", help="The command's code")

# RENDER (GET)
parser_render = subparsers.add_parser('render', help='Execute a render command')
parser_render.set_defaults(action='render')
parser_render.add_argument("host", help="The hostname of the TV")
parser_render.add_argument("command", help="The name of the information to render")

# SET
parser_set = subparsers.add_parser('set', help='Set a value')
parser_set.set_defaults(action='set')
parser_set.add_argument("host", help="The hostname of the TV")
parser_set.add_argument("command", help="The name of the information to set")
parser_set.add_argument("value", help="The new value to set")

# INFORMATIONS
parser_informations = subparsers.add_parser('informations', help='Retrieve device\'s informations')
parser_informations.set_defaults(action='informations')
parser_informations.add_argument("host", help="The hostname of the TV")

args = parser.parse_args()
if not hasattr(args, 'action'):
    parser.print_help()
    sys.exit(1)

Version = None
try:
    from packaging.version import Version
except ImportError:
    try:
        from distutils.version import StrictVersion as Version
    except ImportError:
        pass
if not Version:
    logs.append(dict(level='WARNING', message='La bibliothèque packaging.version n\'est pas disponible. Certaines fonctionalités risque de ne pas être disponibles'))

# MAIN
rc = panasonic_viera.RemoteControl(args.host if hasattr(args, 'host') else None)
if hasattr(args, 'timeout') and args.timeout is not None and hasattr(rc, 'setTimeout'):
    rc.setTimeout(args.timeout)

try:
    if args.action == 'sendkey':
        rc.sendKey(args.command)
        result['output'] = 'ok'
    elif args.action == 'render':
        if args.command == 'getVolume':
            result['output'] = rc.getVolume()
        if args.command == 'getMute':
            result['output'] = rc.getMute()
    elif args.action == 'set':
        if args.command == 'setVolume':
            rc.setVolume(args.value)
        if args.command == 'setMute':
            rc.setMute(args.value)
    elif args.action == 'find':
        result['output'] = rc.find()
    elif args.action == 'version':
        if (args.instance == 'local'):
            result['output'] = panasonic_viera.__version__
        else:
            result['output'] = panasonic_viera.getOnlineVersion()
    elif args.action == 'informations' and Version and Version('1.1.0') <= Version(panasonic_viera.__version__):
        result['output'] = rc.informations()
    else:
        raise panasonic_viera.RemoteControlException("L'action " + args.action + " n'est pas disponible.")
except panasonic_viera.RemoteControlException as e:
    result['output'] = 'nok'
    result['status'] = 1
    result['error'] = str(e)
    if getattr(e, "getCode", None) and callable(e.getCode):
        result['error_code'] = e.getCode()

logging.shutdown()
result['log'] = logs
print(json.dumps(result))
sys.exit(result['status'])
