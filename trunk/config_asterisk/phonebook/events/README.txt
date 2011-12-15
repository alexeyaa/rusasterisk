# basis source from
# Copyright (C) 2006 Earl Terwilliger
#               EMAIL: earl@micpc.com
# Changed 2008 from Claude Fanac  A-Enterprise GmbH

The Asterisk Event Monitor is a WEB/AJAX based 'Switch Board' of sorts with more functionality. 
Asterisk Events are captured as the occur via a python script called ipline.py and logged to 
a MySQl database table. Since Asterisk is sending these events as they occur 
(and not being polled for these events), this leads to a more efficient Asterisk interface.

ipline.py is a Python script which connects to the Asterisk Manager
Interface via a TCP/IP connection, listens for any events or messages coming from
the Manager Interface, time stamps each event and logs them to a MySQL database table.

When either of these (AJAX enabled) PHP scripts are invoked they check for updates
every several seconds. The page is only updated if there are any changes (i.e., 
new events).


1. Install python and python-mysqldb
 
2. Change the Config File Settings 
   phonebook\events\bin\config.py

3. Start the enets deamon with phonebook\events\bin\start.py
   Stop the enets deamon with phonebook\events\bin\stop.py

4. You can also install the init-script for start and stop : 
   phonebook\events\etc\init.d\events 

