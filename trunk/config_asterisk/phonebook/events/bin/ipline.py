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


from config import MANAGER,MANAGERPORT,USERNAME,SECRET,debug,SQLhost,SQLuser,SQLpass,SQLdb

import sys,thread,socket,os,MySQLdb,time,datetime,string,re

alive = 0
conns = []
tlock = thread.allocate_lock()

def server_AGI():
  global alive,tlock,conns,msconn,debug,SQLhost,SQLuser,SQLpass,SQLdb
  db = None
  if (debug): print "Connecting to Asterisk Manager....."
  try:
    msconn = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    msconn.connect((MANAGER,MANAGERPORT))
  except socket.error, err:
    if (debug):
      print "\nManager Connection Error %d: %s\nExiting." % (err.args[0],err.args[1])
  if (debug): print "Sending Login....."
  msconn.send("Action: Login\r\n")
  msconn.send("UserName: " + USERNAME + "\r\n")
  msconn.send("Secret: " + SECRET + "\r\n\r\n");
  if (debug): print "Sending Action: Events Eventmask: On"
  msconn.send("Action: Events\r\nEventmask: On\r\n\r\n")
  if (debug): print "Waiting for Events.....\n"
  dbdata = ""
 
  while (1):
    try: data = msconn.recv(1024)
    except socket.error, err:
      if (debug):
        print "\nManager Receive Error %d: %s\nExiting." % (err.args[0],err.args[1])
      alive = 0
      return 1
    if not data: break
    ct = datetime.datetime.now()
    if (debug) :
      print ct 
      print data
    tlock.acquire()
    for i in range(len(conns)):
      conn = conns[i]
      conn.send(data)
    tlock.release()
    dbdata += data
    if (dbdata[-4:] != "\r\n\r\n"): continue
    events = dbdata.split("\r\n\r\n")
    
    qUID = ""
    qSRC = ""
    qDEST = ""
    qCIDNAME = ""
    qCID = ""
    qIDsrc = ""
    qIDdest = ""
    qEVNT = ""  
    HUNGUP = ""   
 
    for i in range(len(events)):
#      print events[i]

 ############################################################################      
      if re.search('Event: Dial', events[i]):
        qEVNT="Dial"  
	events2 = events[i]
	events3 = events2.split("\r")    
        for Q in range(len(events3)): 
	  if re.search('DestUniqueID:', events3[Q]): 
	   S1=events3[Q]
	   qIDdest=S1.replace("DestUniqueID:","")
	   
	  if re.search('SrcUniqueID:', events3[Q]): 
	   S1=events3[Q]
	   qIDsrc=S1.replace("SrcUniqueID:","")
	   qUID=qIDsrc
	        	   
          if re.search('Source:', events3[Q]): 
           S1=events3[Q]
	   S1=S1.replace("Source:","")
	   if re.search('SIP/',S1 ):
	    ## split SIP/EXTEN-yyyyyyy  OUTPUT = EXTEN
	    S2=S1.split('SIP/')
	    S3=S2[1].split('-')
	    qSRC=S3[0]
	   if re.search('mISDN',S1):
	    qSRC="MISDN"
	    
	  if re.search('Destination:', events3[Q]):	  
	   S1=events3[Q]
	   S1=S1.replace("Destination:","")
	   if re.search('SIP/',S1 ):
	    ## split SIP/EXTEN-yyyyyyy  OUTPUT = EXTEN
	    S2=S1.split('SIP/')
	    S3=S2[1].split('-')
	    qDEST=S3[0]
	   if re.search('mISDN',S1):
	    qDEST="MISDN"

	   
          if re.search('CallerIDName:', events3[Q]): 
	   qCIDNAME=events3[Q]
	   qCIDNAME=qCIDNAME.replace("CallerIDName:","")
	   
           	  
          if re.search('CallerID:', events3[Q]): 
	   S1=events3[Q]
           qCID=S1.replace("CallerID:","")

############################################################################	   
      if re.search('State: Ringing', events[i]):
        qEVNT="Ringing"      
        events2 = events[i]
        events3 = events2.split("\r") 
        for Q in range(len(events3)): 
	  if re.search('Uniqueid:', events3[Q]): 
	   S1 = events3[Q]
	   qUID = S1.replace('Uniqueid:', '')
	  if re.search('CallerID: ', events3[Q]): 
	   S1 = events3[Q]
	   qCID = S1.replace('CallerID: ', '')	   
	  if re.search('Channel:', events3[Q]): 
	   S1=events3[Q]
	   S1=S1.replace("Channel:","")
	   if re.search('SIP/',S1 ):
	    ## split SIP/EXTEN-yyyyyyy  OUTPUT = EXTEN
	    S2=S1.split('SIP/')
	    S3=S2[1].split('-')
	    qDEST=S3[0]
	   if re.search('mISDN',S1):
	    qSRC="MISDN"   
	    qDEST="MISDN"
	   
	   
##########################################################################            
      if re.search('State: Ring', events[i]):
      	if re.search('State: Ringing', events[i]): print ""
        else: 
         qEVNT="Ring"  
	 events2 = events[i]
	 events3 = events2.split("\r")    
         for Q in range(len(events3)): 
	  if re.search('Uniqueid:', events3[Q]): 
	   S1 = events3[Q]
	   qUID = S1.replace('Uniqueid:', '')
	  if re.search('Channel:', events3[Q]): 
	   S1=events3[Q]
	   S1=S1.replace("Channel:","")
	   if re.search('SIP/',S1 ):
	    ## split SIP/EXTEN-yyyyyyy  OUTPUT = EXTEN
	    S2=S1.split('SIP/')
	    S3=S2[1].split('-')
	    qSRC=S3[0]
	   if re.search('mISDN',S1):
	    qSRC="MISDN"	   
	   
	   

##########################################################################

      if re.search('Event: Link', events[i]):
        qEVNT="Link"        
        events2 = events[i]
        events3 = events2.split("\r") 
        for Q in range(len(events3)): 
 
	  if re.search('Uniqueid1:', events3[Q]): 
	   S1=events3[Q]
	   S1=S1.replace("Uniqueid1:","")
	   qIDsrc=S1
	   qUID=qIDsrc
	   
          if re.search('Uniqueid2:', events3[Q]):
	   S1=events3[Q]
	   S1=S1.replace("Uniqueid2:","")
	   qIDdest=S1	   

	  if re.search('CallerID1:', events3[Q]): 
	   S1=events3[Q]
	   S1=S1.replace("CallerID1:","")
	   qSRC=S1

	  if re.search('CallerID2:', events3[Q]): 
	   S1=events3[Q]
	   S1=S1.replace("CallerID2:","")
	   qDEST=S1
   
############################################################################
      if re.search('Event: Hangup', events[i]):  
        events2 = events[i]
        events3 = events2.split("\r")
        for Q in range(len(events3)): 
	  if re.search('Uniqueid:', events3[Q]):
	   HUNGUP=events3[Q]
	   HUNGUP=HUNGUP.replace("Uniqueid:","")
	   qUID=HUNGUP	    
########################################################################## 

      if (events[i] == ""): continue 
      if (qUID == ""): continue
      
      if db :
        try:
          db.ping()
        except MySQLdb.Error, err:
          if (debug):
            print "\nMySQLdb Error %d: %s" % (err.args[0], err.args[1])
          db = None
      if db is None: 
        try:
          db = MySQLdb.connect(host=SQLhost,user=SQLuser,passwd=SQLpass,db=SQLdb)
        except MySQLdb.Error, err:
          if (debug):
            print "\nError %d: %s\nExiting." % (err.args[0], err.args[1])
          alive = 0
          return 1  
        cursor = db.cursor()
        db.autocommit(1)
      try:
        uxtime=time.time()

        #cursor.execute("INSERT INTO events (timestamp,event,uxtime,DEST,SRC,UID,CID,CIDNAME,IDsrc,IDdest,EVNT) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)", (ct,events[i],uxtime,qDEST,qSRC,qUID,qCID,qCIDNAME,qIDsrc,qIDdest,qEVNT) )
	
	cursor.execute("INSERT INTO events (uxtime,DEST,SRC,UID,CID,CIDNAME,IDsrc,IDdest,EVNT) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s)", (uxtime,qDEST,qSRC,qUID,qCID,qCIDNAME,qIDsrc,qIDdest,qEVNT) )
	
	if (HUNGUP > ""): 
	  
	  qs = "DELETE FROM events WHERE UID='%s' OR IDsrc='%s' OR IDdest='%s'" % (HUNGUP,HUNGUP,HUNGUP)
	  cursor.execute(qs) 
	  
    	qUID = ""
    	qSRC = ""
	qDEST = ""
    	qCIDNAME = ""
    	qCID = ""
    	qIDsrc = ""
    	qIDdest = ""
    	qEVNT = ""  
    	HUNGUP = ""	  

        if (debug):
          print "Inserted event record id %s\n" % (int(db.insert_id()))
      except db.DatabaseError:
        if (debug):
          print "Database error on MySQL insert. Record was ignored."
    dbdata = ""
  if (debug): print "\nManager closed connection..."
  tlock.acquire()
  for i in range(len(conns)):
    conn = conns[i]
    conn.close()
  tlock.release()
  msconn.close()
  if (debug): print "\nExiting."
  alive = 0
  return 0

if __name__ == '__main__':
  pid = os.fork()
  if pid: sys.exit(0)
  alive = 1
  thread.start_new_thread(server_AGI,())
  if (debug): print 'Started Server AGI task '

  while 1:
      if (alive): time.sleep(10)
      else:
        if (debug): print "\nExiting."
        sys.exit(0)                        
