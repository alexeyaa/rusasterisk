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

header("Content-Type: text/xml; charset=UTF-8");

$URL=$URL_HTTP;

if($_GET['searchfor'])$searchfor=$_GET['searchfor'];

if(!$searchfor)
{
$x="<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<SnomIPPhoneInput>
<Title>$snom5</Title>
<Prompt>Prompt</Prompt>
<URL>$URL/snom-search.php</URL>
<InputItem>
<DisplayName>$snom4</DisplayName>
<QueryStringParam>searchfor</QueryStringParam>
<DefaultValue></DefaultValue>
<InputFlags>a</InputFlags>
</InputItem>
</SnomIPPhoneInput>
";
}

else

{
$result = mysql_query("select * from pbook  where name LIKE '$searchfor%' order by name",$conn)or die (mysql_error());
$result2 = mysql_query("select * from users where name LIKE '$searchfor%' order by name",$conn)or die (mysql_error());

// XML Header
$x="<?xml version=\"1.0\" encoding=\"utf-8\"?>
<SnomIPPhoneDirectory>
<Title>$snom6</Title>
<Prompt>Prompt</Prompt>
";


$name="";
$call="";

// search global
WHILE($set = mysql_fetch_array($result))
{
$name="";
$call="";

$name=$set["name"];
$call=$set["calld"];
$x=$x."
<DirectoryEntry>
<Name>$name ($call)</Name>
<Telephone>$call</Telephone>
</DirectoryEntry>
";
} 


// search lokal
WHILE($set = mysql_fetch_array($result2))
{
$name="";
$call="";

$name=$set["name"];
$call=$set["calld"];
$x=$x."
<DirectoryEntry>
<Name>$name ($call)</Name>
<Telephone>$call</Telephone>
</DirectoryEntry>
";
} 
  
$x=$x." </SnomIPPhoneDirectory>";

} // end of else if(!$searchfor)

print $x;
?>