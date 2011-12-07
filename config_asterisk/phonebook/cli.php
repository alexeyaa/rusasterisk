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

// Eintrag editieren oder neu einfügen
if($put>0 AND $pname>"")
{

//echo "database put $cidname $put \"$pname\"";

exec ("asterisk -rx 'database del cidname $put'   ",$o); // alles löschen
exec ("asterisk -rx 'database put cidname $put \"$pname\"'   ",$o); // alles löschen
}


// Eintrag löschen
if($del>0)
{

//echo "database del cidname $del";
exec ("asterisk -rx 'database del cidname $del'",$o); // alles löschen
}



if($putall==1)
{
echo "<br><br>... OK ...  Datenbank wird nun neu indexiert. <br>Das kann einen Moment dauern..... <br><br>";
$result = mysql_query("select * from pbook",$conn)or die (mysql_error());
exec ("asterisk -rx 'database deltree cidname'   ",$o); // alles löschen

WHILE($set = mysql_fetch_array($result))
{
$name=trim($set["name"]);
$call=trim($set["calld"]);

	if($name>"" AND $call>"")
	{
	echo "$call - $name <br>";
	flush();
	exec ("asterisk -rx 'database put cidname $call \"$name\" '   ",$o2);
	}
}


$result5 = mysql_query("select * from pbook_directory",$conn)or die (mysql_error());
WHILE($set5 = mysql_fetch_array($result5))
{
$dname=trim($set5["name"]);
$dcall=trim($set5["tel"]);

		if($dname>"" AND $dcall>100 )
		{
		$result = mysql_query("select * from pbook where calld='$dcall'",$conn)or die (mysql_error()); 
		if(!mysql_num_rows($result))
		exec ("asterisk -rx 'database put cidname $dcall \"$dname\" '   ",$o2);
		}
}

echo "<br><br>... OK ...  Datenbank wurde neu indexiert.";

}// end of if($putall==1)



?>