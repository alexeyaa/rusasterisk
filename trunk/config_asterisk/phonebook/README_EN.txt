//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
//IPline DialFox, The Open Source Asterisk Phone Directory
//Copyright (C) 2008 A-Enterprise GmbH Switzerland - Claude Fanac 
//
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details. 
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////

A common shared phone book directory based on CMS/LAMP and build for
Asterisk PBX, store name and number into MySQL which will be used by each
workstation browser, also by telephones with embedded browser feature.
Free to use and free for everyone, unlimited copying and distributable.

Simply install estimate time less then 5 minutes, the code was written
on Debian GNU/Linux, any other distribution should work well, like ubuntu
or Fedora. surely trixbox too, it's build on CentOS (RedHat).



CONTENT
   INSTALLATION
   MySQL DATABASE
   OPTIONAL STEP
   CHANGE SETTINGS
   AUXILIARY

****************************************************************************
INSTALLATION:

The source probably you can but directly under the directory of the apache
doc-root, here on Debian GNU/Linux as an example: /var/www/html/phonebook/*.*

pbx:~# cd /var/www/html

download tar source, may use wget
pbx:~# wget http://www.a-enterprise.ch/ipline/phonebook/phonebook.tar.gz

untar the archive
pbx:~# tar -xzvf phonebook.tar.gz

make and set the upload folder to rwxrwxrwx
pbx:~# mkdir phonebook/upload
pbx:~# chmod 777 /var/www/html/phonebook/upload

on apache2 web-server, the file /etc/apache2/conf.d/charset must be empty
pbx:~# echo "" > /etc/apache2/conf.d/charset

php.ini
if the .htaccess file not work (empty web-sites) with register_globals=on  then
set this in the php.ini:
register_globals = On



ATTENTION:
please use the phonebook only within your intranet, in case of security reason
the user of the web-server must be provide sudo rights,
here this are user name asterisk, use visudo

/etc/sudoers  :   asterisk  ALL=(ALL) NOPASSWD: ALL

Note. PHP should be run exec()


****************************************************************************
MySQL DATABASE:

Note. You must have MySQL installed with asterisk-addons - An addons package, 
which includes MySQL support (FreePBX and trixbox have asterisk cdr MySQL support.


Generate the table structure of the DialFox phonebook belong copy & paste this code
and inserting from myadmin or from console by using mysqladmin

CREATE DATABASE IF NOT EXISTS `asterisk` ;

CREATE TABLE `pbook` (
  `id` int(11) NOT NULL auto_increment,
  `calld` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `bemerkung` text NOT NULL,
  `Diverses` int(1) NOT NULL default '0',
  KEY `id` (`id`),
  KEY `calld` (`calld`),
  KEY `name` (`name`)
)  ;


CREATE TABLE `pbook_directory` (
  `id` int(9) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `strasse` varchar(50) NOT NULL,
  `ort` varchar(50) NOT NULL,
  KEY `tel` (`tel`),
  KEY `name` (`name`)
) ;


CREATE TABLE IF NOT EXISTS `users` (
  `extension` varchar(20) NOT NULL default '',
  `password` varchar(20) default NULL,
  `name` varchar(50) default NULL,
  `voicemail` varchar(50) default NULL,
  `ringtimer` int(3) default NULL,
  `noanswer` varchar(100) default NULL,
  `recording` varchar(50) default NULL,
  `outboundcid` varchar(50) default NULL,
  `directdid` varchar(50) default NULL,
  `didalert` varchar(50) default NULL,
  `faxexten` varchar(20) default NULL,
  `faxemail` varchar(50) default NULL,
  `answer` tinyint(1) default NULL,
  `wait` int(2) default NULL,
  `privacyman` tinyint(1) default NULL
) ;


CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `timestamp` datetime NOT NULL default '0000-00-00 00:00:00',
  `event` longtext,
  `uxtime` int(11) NOT NULL,
  `DEST` varchar(20) NOT NULL,
  `SRC` varchar(20) NOT NULL,
  `UID` varchar(20) NOT NULL,
  `CID` varchar(20) NOT NULL,
  `CIDNAME` varchar(50) NOT NULL,
  `IDdest` varchar(20) NOT NULL,
  `IDsrc` varchar(20) NOT NULL,
  `EVNT` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
)


;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

CREATE DATABASE IF NOT EXISTS `asteriskcdrdb` ;


CREATE TABLE IF NOT EXISTS `cdr` (
  `calldate` datetime NOT NULL default '0000-00-00 00:00:00',
  `clid` varchar(80) NOT NULL default '',
  `src` varchar(80) NOT NULL default '',
  `dst` varchar(80) NOT NULL default '',
  `dcontext` varchar(80) NOT NULL default '',
  `channel` varchar(80) NOT NULL default '',
  `dstchannel` varchar(80) NOT NULL default '',
  `lastapp` varchar(80) NOT NULL default '',
  `lastdata` varchar(80) NOT NULL default '',
  `duration` int(11) NOT NULL default '0',
  `billsec` int(11) NOT NULL default '0',
  `disposition` varchar(45) NOT NULL default '',
  `amaflags` int(11) NOT NULL default '0',
  `accountcode` varchar(20) NOT NULL default '',
  `uniqueid` varchar(32) NOT NULL default '',
  `userfield` varchar(255) NOT NULL default ''
) ;


****************************************************************************
CHANGE SETTINGS:

Made your changes along your preferences and open config.php

// Begin of configuration, change your preferences here
// mysql settings DB asterisk for phonebook tables
$mysql_host="localhost"; // eg, localhost - should not be empty for productive servers
$mysql_user="root";  // mysql db user, if you not shure prefer root
$mysql_password="********";  // insert your password
$mysql_db_book="asterisk"; // should be well

// mysql settings DB asteriskcdrdb for cdr tables
$cdr_mysql_host="localhost"; // eg, localhost - should not be empty for productive servers
$cdr_mysql_user="root";  // mysql db user, if you not shure prefer root
$cdr_mysql_password="********";  // insert your password
$cdr_mysql_db_book="asteriskcdrdb"; // should be well

// Language
$language="en";

// A-Z register 
$anz_numbers=40; // number of entries per register

// select local phonebook-numbers:
$local_from=1;  // range begin
$local_to=5000; // range end
$local_list="500,209"; // additional individual extensions add comma separated
$local_freepbx=1; // local number from freepbx  if no freepbx:  $local_freepbx=0 ;

// SET show CLIP Len 
$clidlen=25;

// Include the events box (show the active call)
// for this feature we need python-mysql support on the pbx-server 
// $show_events=0;
$show_events=1;

// LEN SNOM Report Call Name
$XML_NAME_LEN=20;

// events :  the first Extension in the list = my sip phone extension !!!! 
// EXAMPLE sip list: $sip_monitoring="200,201,211,212,213";  //     200 = my extension 
$sip_monitoring="200,201,211,212,213";

// 1 = show all report entries | 0 = only the local phone number
$report_show_all=1;

// You must have MySQL installed with asterisk-addons - An addon package, 
// which includes MySQL support (FreePbx and Trixbox have asterisk cdr MySQL support) 
// then set this to '0',  '1' will update the cdr-database without asterisk MySQL support

// LOG facility format
$modul_cdr=0;  

// external phonebook
// OPTION: VAR NUMBER = phonenumber
// example: www swisscom directory http://tel.local.ch
// $external_phonebook_link=""; // without directory lookup
$external_phonebook_link="http://tel.search.ch?tel=NUMBER"; // external directory lookup

// set the 'admin_pwd' password only in admin - mode
// $_SESSION['admin_pwd'] = '1234';
// if change this setting, you have to restart the browser
// start admin mode with http//****/phonebook/admin.php
$_SESSION['admin_pwd'] = '';

// exclude search numbers 
$exclude_numbers="076|077|078|079";
 
/// Asterisk ////////////////////////////////////
// Location of Asterisk LOGFILE
$logfile = "/var/log/asterisk/cdr-csv/Master.csv";

// Manager API 
// Asterisk Call Management support  'manager.conf'

// [phonebook]
// secret = 1234
// deny=0.0.0.0/0.0.0.0
// permit=127.0.0.1/255.255.255.0
// read = system,call,log,verbose,command,agent,user
// write = system,call,log,verbose,command,agent,user

$UserName="phonebook";
$Secret="1234";

// context allocated from extensions.conf
$context="from-internal";

//// end of config.php //////////////////////////

****************************************************************************
Enable Asterisk CID name lookup for FreePBX

edit /etc/asterisk/extensions.conf and add two lines see below

//-bof- [extensions.conf]

# ;; find the following lines
[macro-user-callerid]
;;;; here after this first command in this macro
exten => s,1,Noop(user-callerid: ${CALLERID(name)} ${CALLERID(number)})

;;;; insert here this next two lines
exten => s,n,DBGet(name=cidname/${CALLERIDNUM})
exten => s,n,SetCIDName(${name})

;;;; further existing code to keep
exten => s,n,GotoIf($["${CHANNEL:0:5}" = "Local"]?report)
exten => s,n,GotoIf($["${REALCALLERIDNUM:1:2}" != ""]?start)
exten => s,n,Set(REALCALLERIDNUM=${CALLERID(number)})

-eof-// [extensions.conf]

now activate changes will enter does command from CLI
CLI> reload

Congratulation you'r finish! ***********************************************


Run from browser http://your_pbx/phonebook/book.php
use settings and add your extension and prefix if it's appreciated
additionally you may create your shortcut with drag & drop the url to desktop


****************************************************************************
AUXILIARY:

If you prefer to limit the access to phonebook from which network that allowed
modify the .htaccess file, add this lines along your subnet addresses
<Limit GET>
        Order deny,allow
        Allow from localhost
        Allow from 192.168.1.
        Deny from all
        Satisfy any
</Limit>


Suggestions
-----------
Suggestions to improvements and optimization are very welcome.
There is ongoing further development, please visit www.a-enterprise.ch
Try to lookup the latest releases including bugfixes and improvements.
For bug reports please post this to http://forum.a-enterprise.ch

Licence
-------
Released under the GNU General Public License. See attached LICENSE file.

Donation
--------
Donation are welcome http://www.a-enterprise.ch/ipline/phonebook/donate.html
