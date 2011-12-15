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

Auf CMS/LAMP basierendes, gemeinsam nutzbares Telefonbuch mit web-basiertem
Front-End für Asterisk-PBX-Anlagen, etwa in Unternehmen; speichert eingehende
Telefonnummern automatisch in einer MySQL-Datenbank mit zugehörigem Namen ab;
bietet unter anderem Funktionen wie Direktwahl aus dem Browser heraus,
Anruferliste, SNOM-Integration oder CTI.


INSTALLATION
   
***********************************************************************************************
Source phonebook.tar.gz
Download http://www.a-enterprise.ch/ipline/phonebook/phonebook.tar.gz

pbx:/# tar -xzf phonebook.tar.gz

Der Source darf direkt in ein Verzeichnis des Webservers gelegt werden oder so wie
hier auf einem Debian GNU/Linux als Beispiel:

/var/www/html/phonebook/*.*

Das upload-Verzeichnis auf CHMOD 777 setzen
pbx:/# chmod 777 /var/www/html/phonebook/upload

Bei apache2 Webserver muss das /etc/apache2/conf.d/charset leer sein
pbx:/# echo "" > /etc/apache2/conf.d/charset

Wenn das .htaccess mit register_globals nicht funktioniert, (es werden leere Seiten agezeigt)
dann muss dieses in der php.ini Datei angepasst werden auf :
register_globals = On



(!!! Phonebook bitte nur lokal nutzen !!!)
Der user des webservers muss sudo rechte haben:
Hier ist das der user asterisk:
etc/sudoers  :   asterisk  ALL=(ALL) NOPASSWD: ALL

PHP muss exec() ausführen können.

***********************************************************************************************

1.  MySQL Anpassen (Datenbank generieren):


;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

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


;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;


Konfiguration:

Öffne die Konfigurations-Datei config.php in einem editor und ändere anhand der vorgaben.

//MySQL Datenbank Phonebook
$mysql_host="localhost"; // eg, localhost, sollte nicht leer sein!
$mysql_user="root";  // mysql db user, falls nicht sicher dann root
$mysql_password="........";  // mein passwort
$mysql_db_book="asterisk"; // sollte gut sein

// MySQL Datenbank CDR LOG
$cdr_mysql_host="localhost"; // eg, localhost, sollte nicht leer sein!
$cdr_mysql_user="root";  // mysql db user, falls nicht sicher dann root
$cdr_mysql_password="........";  // mein passwort
$cdr_mysql_db_book="asteriskcdrdb"; // sollte gut sein

Sprache einstellen, "de" für Deutsch
$language="en";

Minimale Anzeige Anzahl im A-Z Register
$anz_numbers=40;

Lokale Asterisk Extensions Eingrenzung zur Anzeige im Telefonbuch
Hier als Beispiel 200 bis 300
$local_from=1;     
$local_to=1000;
$local_list="5500,209";  // Liste einzelner zusätzlicher Extensions durch komma getrennt
$local_freepbx=1;  // wenn trixbox oder freepbx installiert ist, hier 1 setzen sonst 0 


Aktivierung der Event Box (Anruf Anzeige in echt zeit)
Für dieses feature wird auf der PBX Python-MySQL Unterstützung benötigt 
// $show_events=0;
$show_events=1;

// Namen Länge für SNOM Anruf Report Anzeige
$XML_NAME_LEN=20;

Events: erste Nebenstelle (extension) in der liste = meine sip phone extension !!!! 
Beispiel sip list: $sip_monitoring="200,201,211,212,213";  //    200 = meine extension 
$sip_monitoring="200,201,211,212,213";

1 = Alle Report einträge anzeigen | 0 = nur Lokale einträge anzeigen
$report_show_all=1;

Wurde Asterisk mit dem MySQL add-on installiert, wird das Log direkt in die Datenbank geschrieben
Das ist bei FreePBX und Trixbox bereits der Fall.
Ohne direkte MySQL Unterstützung von Asterisk, muss das modul_cdr auf '1' gestellt werden.
Dann wird das Standard Asterisk Text-Log-File ausgelesen.
 
Log datei format
// $modul_cdr=1 ;  // Ohne freepbx cdr LOGFILE
$modul_cdr=0 ;  // Asterisk mit cdr MySQL support (freepbx/trixbox)

// diese Nummern werden im Online Telefonbuch nicht gesucht
$exclude_numbers="076|077|078|079";


dieses Passwort nur setzen, wenn im Admin mode gestartet werden soll
// $_SESSION['admin_pwd'] = '1234';
Nach dem setzten des Passwortes muss der Browser neu gestartet werden
Start admin mode mit http//****/phonebook/admin.php
beim Admin mode werden Icons ohne Login nicht angezeigt, welche änderungen im Telefonbuch vornehmen lassen.
$_SESSION['admin_pwd'] = '';

Asterisk logfile (hier Standard Asterisk path)
$logfile = "/var/log/asterisk/cdr-csv/Master.csv";


// Manager API 
// Asterisk Call Management support  'manager.conf'

// Beispiel wie asterisk konfiguriert werden kann
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


Nun noch die änderungen übernehmen, dazu gehen wir in den CLI Prompt mit asterisk -r
CLI> reload

***********************************************************************************************

Damit Nummern zu Namen aufgelöst werden können muss die Konfigurations-Datei extensions.conf
angepasst werden.

Wir editieren die datei /etc/asterisk/extensions.conf

Asterisk Beispiel für die extension 200:
exten => 200,n,DBGet(name=cidname/${CALLERIDNUM})
exten => 200,n,SetCIDName(${name})


extensions.conf bei FreePBX anpassen, falls die Auflösung nicht schon automatisch funktioniert:

;; Finde Zeile mit folgendem inhalt
[macro-user-callerid]
exten => s,1,Noop(user-callerid: ${CALLERID(name)} ${CALLERID(number)})

;;;; folgende zwei zeilen hier einfach einfügen
exten => s,n,DBGet(name=cidname/${CALLERIDNUM})
exten => s,n,SetCIDName(${name})

;;; hier die nächsten bestehenden code zeilen ;;;;;;;;;;;;;;;;;
exten => s,n,GotoIf($["${CHANNEL:0:5}" = "Local"]?report)
exten => s,n,GotoIf($["${REALCALLERIDNUM:1:2}" != ""]?start)
exten => s,n,Set(REALCALLERIDNUM=${CALLERID(number)})



Herzlichen Glückwunsch, das Asterisk Telefonbuch ist fertig!

***********************************************************************************************

Starte jetzt aus dem Browser http://meine_pbx/phonebook/book.php
Nutze die Einstellungen und füge die eigene Nebenstelle fgg. mit Präfix ein, lege eine Verknüpfung
auf dem Desktop an, mit Drag & Drop der Browser URL-Zeile auf den Desktop.


****************************************************************************
Zusatz:

Soll der Zugang auf das Telefonbuch aus dem Netzwerk limitiert werden, kann dies mit
einer htaccess-Datei geschehen, ändere die "Allow from" Zeile anhand der Subnet-Adresse.
<Limit GET>
        Order deny,allow
        Allow from localhost
        Allow from 192.168.1.
        Deny from all
        Satisfy any
</Limit>


Vorschläge
-----------

Vorschläge zur Verbesserung und/oder Optimierung sind herzlich willkommen.
Es gibt laufende Weiterentwicklungen, besuche bitte www.a-enterprise.ch
Vorschläge und Bug reports posten auf http://forum.a-enterprise.ch

Lizenz
-------
Released under the GNU General Public License. See attached LICENSE file.

Spenden
--------
Spenden sind willkommen http://www.a-enterprise.ch/ipline/phonebook/donate.html

