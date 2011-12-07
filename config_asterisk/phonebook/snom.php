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

$URL=$URL_HTTP;

header("Content-Type: text/xml; charset=UTF-8");

$x="<?xml version=\"1.0\" encoding=\"utf-8\"?>

<SnomIPPhoneMenu>
<Title>$snom5</Title>

<MenuItem>
<Name>$snom1</Name>
<URL>$URL/snom-list.php</URL>
</MenuItem>

<MenuItem>
<Name>$snom2</Name>
<URL>$URL/snom-list.php?G=1</URL>
</MenuItem>

<MenuItem>
<Name>$snom3</Name>
<URL>$URL/snom-search.php</URL>
</MenuItem>

</SnomIPPhoneMenu>";

print $x;

?>