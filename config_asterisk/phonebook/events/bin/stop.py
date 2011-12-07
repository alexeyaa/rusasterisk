#!/usr/bin/env python

#
# basis source from
# Copyright (C) 2006 Earl Terwilliger
#               EMAIL: earl@micpc.com
# Changed 2008 from Claude Fanac  A-Enterprise GmbH


# //////////////////////////////////////////////////////////////////////
# //////////////////////////////////////////////////////////////////////
# //Source of IPline DialFox, The Open Source Asterisk Phone Directory
# //Copyright (C) 2008 A-Enterprise GmbH Switzerland - Claude Fanac 
# //
# //This program is free software; you can redistribute it and/or
# //modify it under the terms of the GNU General Public License
# //as published by the Free Software Foundation; either version 2
# //of the License, or (at your option) any later version.
# //
# //This program is distributed in the hope that it will be useful,
# //but WITHOUT ANY WARRANTY; without even the implied warranty of
# //MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# //GNU General Public License for more details. 
# //////////////////////////////////////////////////////////////////////
# //////////////////////////////////////////////////////////////////////


import sys,os

def get_processes():
  output = []
  ps = os.popen('ps -eo pid,command')
  ps.readline()
  for line in ps:
    parts  = line.lstrip()[:-1].split(' ')
    output.append( (int(parts[0]), ' '.join(parts[1:])) )
  return output

def find_cmd(cmd):
  parts = cmd.split('/')
  l = len(parts)
  if (l > 0): 
    cp = parts[l-1].split(' ')
  else :
    cp = cmd.split(' ')
  return cp[0]

def kill_process(cmd):
  ids = get_processes()
  rcmd = find_cmd(cmd)
  for i in range(len(ids)):
    ps =  find_cmd(ids[i][1])
    if ps == rcmd:
      killcmd = "kill -9 " + str(ids[i][0])
      os.system(killcmd)

if __name__ == '__main__':
  from config import PYPATH
  kill_process(PYPATH+"/start.py")
  kill_process(PYPATH+"/ipline.py")
