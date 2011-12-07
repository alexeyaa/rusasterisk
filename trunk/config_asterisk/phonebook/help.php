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

if($ok)

{
$P=trim($P);
$X=trim($X);
$URL3=$URL_HTTP."/book.php?P=$P&X=$X";
header ("location:$URL3");exit;
}

if(!$X)$X=0;

require("head2.php");
?>

    <br>
    <br>
  </p>
  <table width="553" border="0" cellspacing="0" cellpadding="5" bgcolor="#FFFFFF">
    <tr> 
      <td> <b>&nbsp; Documentation</b>
             <p>&nbsp;</p>
        <table width="547" border="0" cellspacing="0" cellpadding="1">
          <tr>
            <td colspan="3"> 
              <hr size="1">
            </td>
          </tr>
          <tr>
            <td rowspan="3" width="78" align="center" valign="middle"><img src="images/de.gif" width="24" height="24"></td>
            <td colspan="2" rowspan="3"><a href="http://support.a-enterprise.ch/ipline/phonebook/de" target="_blank"> Benutzerhandbuch (deutsch)</a></td>
          </tr>
          <tr> </tr>
          <tr> </tr>
          <tr> 
            <td colspan="3" align="center" valign="middle">
              <hr size="1">
            </td>
          </tr>
          <tr> 
            <td width="78" align="center" valign="middle"><img src="images/us.gif" width="24" height="24"></td>
            <td colspan="2"><a href="http://support.a-enterprise.ch/ipline/phonebook/en" target="_blank"> Users Guide (english)</a></td>
          </tr>
          <tr> 
            <td colspan="3" align="center" valign="middle"> 
              <hr size="1">
            </td>
          </tr>
          <tr> 
            
          <td width="78" align="center" valign="middle"><a href="http://www.a-enterprise.ch/component/option,com_fireboard/Itemid,146/" target="_blank"><img src="images/L9.png" width="24" height="24" border="0" alt="Forum"></a></td>
            <td colspan="2"><a href="http://www.a-enterprise.ch/component/option,com_fireboard/Itemid,146/" target="_blank"> Users Forum</a></td>
          </tr>
          <tr> 
            <td colspan="3" align="center" valign="middle"> 
              <hr size="1">
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <p> <br>
    <br>
  </p>
  
<?require("http_end.php");?>