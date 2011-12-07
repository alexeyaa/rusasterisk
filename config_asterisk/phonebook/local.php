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
require_once("config.php");

putenv("PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin");	

exec ("asterisk -rx \"sip show peers\" | awk '{print $1 \": \" $6}'  ",$o); // alles löschen


foreach($o as $key => $val)
{
if ($key>0)

{
echo "$val <br>"; 

$arr_val=explode(":",trim($val));
$arr_exten=explode("/",trim($arr_val[0]));
$extension=$arr_exten[0];
$exten_name=$arr_exten[1];
$exten_status=$arr_val[1];

echo "---------,,$extension=$exten_name=$exten_status=,,
---------";

}
}


echo "<br>.....<br>";
if( in_array("209", $o)) echo "OK";












foreach($peers as $key => $val)
{
	if ($key>0)
	{
	$arr_val=explode(":",trim($val));
	$exten_status=$arr_val[1];
	}
}


?>