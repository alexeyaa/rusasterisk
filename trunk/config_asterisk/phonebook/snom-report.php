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

/*
vom snom kommt mit dem Link :  
default  eingehende Anrufe  ($I nicht gesetzt)
$I=2    ausgehende Anrufe aktiviert $I=2
*/

$_SNOM_=1;

require("report.php");

header("Content-Type: text/xml; charset=UTF-8");


if($G==1) // global phonebook
{
$result = mysql_query("select * from pbook order by name",$conn)or die (mysql_error());
}
else
{
$result = mysql_query("select * from users order by name",$conn)or die (mysql_error());
}

// XML Header
$x="<?xml version=\"1.0\" encoding=\"utf-8\"?>
<SnomIPPhoneDirectory>
<Title>$snom6</Title>
<Prompt>Prompt</Prompt>

$_SNOM_XML

</SnomIPPhoneDirectory>";
print $x;
?>