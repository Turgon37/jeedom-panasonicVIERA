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
import panasonic_viera
import io
import sys


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


parser = argparse.ArgumentParser(description="Adapter for panasonic-viera for jeedom")
parser.add_argument("host", help="The hostname of the TV")
parser.add_argument("type", help="The type of query")
parser.add_argument("command", help="The command's code")
args = parser.parse_args()

logs = []
hdlr = ArrayHandler(logs)
panasonic_viera.getLogger().setLevel(logging.DEBUG)
panasonic_viera.getLogger().addHandler(hdlr)

result = dict({'status': 0})
rc = panasonic_viera.RemoteControl(args.host)
try:
    rc.send_key(args.command)
except panasonic_viera.RemoteControlException as e:
    result['status'] = 1
    result['error'] = str(e)

logging.shutdown()
result['log'] = logs
print(json.dumps(result))
sys.exit(result['status'])
