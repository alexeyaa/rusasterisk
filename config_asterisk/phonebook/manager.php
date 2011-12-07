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

$callnow = trim($callnow);
$Xdirect = trim($Xdirect);

if($callnow){$call=$Xdirect.$callnow;}
$call=strtr(trim($call)," .,-_'","");
$num=$call;

if ($num AND $ext)
{

// namelookup
// search in the phonebook
$cl2=substr($call,strlen($Xdirect));  // search without Prefix
$result55 = mysql_query("select * from pbook  where calld='$cl2'",$conn)or die ( mysql_error() );
$set55 = mysql_fetch_array($result55);
$mm = $set55["name"]; 
if($mm>"") echo $mm;
else
{
$result51 = mysql_query("select * from pbook_directory where tel='$cl2'",$conn)or die (mysql_error());
$set51 = mysql_fetch_array($result51);
if(mysql_num_rows($result51)) $mm=$set51["name"]; 
}
if($mm>"")$mm=substr($mm,0,20);
else $mm="$ext to $call ...";  
 
 
 $timeout = 10;
 $asterisk_ip = "127.0.0.1";
 $socket = fsockopen($asterisk_ip,"5038", $errno, $errstr, $timeout);
 fputs($socket, "Action: Login\r\n");
 fputs($socket, "UserName: $UserName\r\n");
 fputs($socket, "Secret: $Secret\r\n\r\n");
 fputs($socket, "Action: Originate\r\n" );
 fputs($socket, "Channel: SIP/$ext\r\n" );
 fputs($socket, "Exten: $num\r\n" );
 fputs($socket, "Callerid: $mm <$ext>\r\n");
 fputs($socket, "Timeout: 15000\r\n" );
 fputs($socket, "Context: $context\r\n" );
 fputs($socket, "Priority: 1\r\n" );
 fputs($socket, "Async: yes\r\n\r\n" );
 fputs($socket, "Action: Logoff\r\n\r\n");
 $wrets=fgets($socket,128);

fclose($socket);
}

?>

<html><head>
<script language="javascript">
function schliessen() { self.close(); }
function autoschliessen() { setTimeout("self.close();",8000); }
</script>
<link rel="stylesheet" type="text/css" href="gtw.css">
<title>CallTo</title></head>
<body onload=" autoschliessen()" onblur="schliessen()" bgcolor="#CCC9C8">
<form name="frm">
  <table border="0" cellpadding="5" cellspacing="1" bgcolor="#666666">
    <tr>
  <td colspan="2" nowrap bgcolor="#FFFFFF">  <table border="0" cellpadding="5" cellspacing="1">
    <tr>
      <td rowspan="2"><img src="images/dialfox_logo.png"></td>
            <td><b>Call To:</b> </td>
    </tr>
    <tr>
            <td nowrap>&nbsp;</td>
    </tr>
  </table></td>
</tr>
<tr>
  <td colspan="2"  nowrap bgcolor="#FFFFFF">
        <table border="0" cellpadding="2" cellspacing="1" bgcolor="#FFFFFF">
          <tr align="center" valign="middle"> 
            <td  nowrap bgcolor="#FFFFFF"><b><font size="3"><?echo $manager_from;?></font></b></td>
            <td  nowrap bgcolor="#FFFFFF" rowspan="2"> 
              <p><img src="images/ct.gif" width="40" height="40"></p>
            </td>
            <td  nowrap bgcolor="#FFFFFF"><font size="3"><b>&nbsp;&nbsp;<?echo $ext;?>&nbsp;&nbsp;</b></font></td>
          </tr>
          <tr align="center" valign="middle"> 
            <td  nowrap bgcolor="#FFFFFF"><b><font size="3"><?echo $manager_to;?></font></b></td>
            <td  nowrap bgcolor="#FFFFFF"><font size="3"><b>&nbsp;&nbsp;<?echo $num?>&nbsp;&nbsp;</b></font></td>
          </tr>
        </table>
    </td>
  </tr>
</table> 
</form>

</body>
</html>
                