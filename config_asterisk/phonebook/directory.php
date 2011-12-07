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

$dname="";
$dort="";
$dstrasse="";
$val3="";
$end7="";
$NONAME=0;

$U=trim($U);  // input $U =  phonenumber   $external_phonebook_link= URL directory  (config.php)

$url=str_replace("NUMBER",$U,$external_phonebook_link);

// open the website directory ///////////////
$AgetHeaders = @get_headers($url);
if (preg_match("|200|", $AgetHeaders[0]))
////////////////////////////////////////////// 

{

$NONAME=1;
$fp = @fopen ($url,"r");
while (!feof($fp) AND $dort=="") 
{
$z = fgets($fp,5000);  // read etch line of the website

if(ereg('class="rname"', $z)) 
{
$ik=explode('class="rname"',$z);
$nk=$ik[1];
$ik=explode('">',$nk);
$nk=$ik[1];
$ik=explode('<',$nk);
$nk=$ik[0];
$dname=trim($nk);
}

if(ereg('class="tel_addrpart"', $z)) 
{
$ik=explode('class="tel_addrpart"',$z);
$nk=$ik[1];
$ik=explode('>',$nk);
$nk=$ik[1];
$ik=explode('<',$nk);
$nk=$ik[0];
$dort=trim($nk);
}

} // end of while (!feof($fp))
fclose($fp);

// output = $dname and $dort 
if(trim($dname)>"")$NONAME=0; else $NONAME=2;
}// end of if (preg_match("|200|", $AgetHeaders[0])) 

?>