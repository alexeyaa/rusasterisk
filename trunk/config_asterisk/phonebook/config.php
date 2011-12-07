<?
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
//Source of IPline DialFox, The Open Source Asterisk Phone Directory
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




if(!$events_config)session_start();




// Begin of configuration, change your preferences here
// mysql settings DB asterisk for phonebook tables
$mysql_host="localhost"; // eg, localhost - should not be empty for productive servers
$mysql_user="root";  // mysql db user, if you not shure prefer root
$mysql_password="AlExAnDeRpWd";  // insert your password
$mysql_db_book="asterisk"; // should be well

// mysql settings DB asteriskcdrdb for cdr tables
$cdr_mysql_host="localhost"; // eg, localhost - should not be empty for productive servers
$cdr_mysql_user="root";  // mysql db user, if you not shure prefer root
$cdr_mysql_password="AlExAnDeRpWd";  // insert your password
$cdr_mysql_db_book="asterisk"; // should be well




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
$_SESSION['admin_pwd'] = '1234pwDD';

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
$Secret="1234pwD";

// context allocated from extensions.conf
$context="from-internal";


// Custom Myyql Querry Reports only for all incomming 
// EXAMPLE :  OR src LIKE '0%' =  report all 0xxxxxxxxxxx from source destination 
$custom_report="OR src LIKE '0%'";

//// end of configuration ////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////



require("languages/$language.php");

// globals variable POST & GET

foreach ($_POST as $GLOBAL_key=>$GLOBAL_val);
foreach ($_GET as $GLOBAL_key=>$GLOBAL_val);

/*

while(list($GLOBAL_key, $GLOBAL_val) = each($HTTP_POST_VARS)) 
{
$GLOBALS[$GLOBAL_key] = $GLOBAL_val;
$$GLOBAL_key=$GLOBAL_val;
}

while(list($GLOBAL_key, $GLOBAL_val) = each($HTTP_GET_VARS)) 
{
$GLOBALS[$GLOBAL_key] = $GLOBAL_val;
$$GLOBAL_key=$GLOBAL_val;
}
*/



if($local_to>0 AND $local_from>0)
$local_where=" ( CAST(extension as DECIMAL) >= '$local_from' AND CAST(extension as DECIMAL) <= '$local_to') ";
else $local_where=" 1 ";


foreach (explode(",",$local_list) as $arr_value) 
{
    if($arr_value>0)
	{
	$local_where=$local_where."OR extension LIKE '$arr_value' ";
	}
}

$PHP_SELF=$_SERVER["PHP_SELF"];
$URL_ARR=explode(strrchr($PHP_SELF,"/"),$PHP_SELF);
$URL_PATH=$URL_ARR[0];
$URL_HTTP="http://".$_SERVER["SERVER_NAME"].$URL_PATH;


function CONN(){ global $conn,$mysql_host,$mysql_user,$mysql_password,$mysql_db_book;
$conn = mysql_connect($mysql_host,$mysql_user,$mysql_password);
mysql_select_db($mysql_db_book, $conn);}

function CDR_CONN(){ global $cdr_conn,$cdr_mysql_host,$cdr_mysql_user,$cdr_mysql_password,$cdr_mysql_db_book;
$cdr_conn = mysql_connect($cdr_mysql_host,$cdr_mysql_user,$cdr_mysql_password);
mysql_select_db($cdr_mysql_db_book, $cdr_conn);}


// auflösung des 'lastdata' eintrags im ast cdr log
function CALL_DST($call6)	
{
$array = explode("/",$call6);
$dest1=$array[2];
$array = explode("|",$dest1);
return $array[0];
}





CONN();

if(!$events_config)
{
// set admin mode
if($pwd==$_SESSION['admin_pwd'] AND $_SESSION['admin_pwd'] > '' )$_SESSION['login']=1;

// without admin mode
if($_SESSION['admin_pwd'] == '' ) $_SESSION['login']=1;
}

// if register_glogas != ON , STOP the Script
if(!ini_get('register_globals')==1)
{
echo "
<h1><font color=\"#FF0000\">ERROR</font></h1>
<p><font color=\"#FF0000\">register_globals=OFF</font><br>
  <br>
  Pleace set the register_globals in the file php.ini to ON!<br>
  <br>
  Setze das register_globals im php.ini auf ON</p>
<p>Vorgehen:<br>
  Wo finde ich das php.ini ?</p>
<p>Je nach Distribution in:<br>
  /etc<b>/php.ini</b><br>
  /etc/php5/apache2/<b>php.ini</b></p>
<p>Hier folgendes setzen:<br>
  <b>register_globals=off </b><br>
</p>
<p>Dann den Webserver restarten!</p>";
exit;
}


?>