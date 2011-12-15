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
require("config.php");
$result = mysql_query("select * from pbook  order by name",$conn)or die (mysql_error());

WHILE($set = mysql_fetch_array($result))
{
$name=trim($set["name"]);
$call=trim($set["calld"]);
$bemerkung=trim($set["bemerkung"]);
	if($call>0)
	{
$csv=$csv."$call;$name;$bemerkung
";
	}
}

$date_actual=date("j-n-Y",time());
$application="text/txt";
header( "Content-Type: $application" ); 
header( "Content-Disposition: attachment; filename=phonebook-$date_actual.txt"); 
header( "Content-Description: txt File" ); 
header( "Pragma: no-cache" ); 
header( "Expires: 0" );
echo trim($csv);
?>


