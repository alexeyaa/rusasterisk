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

if(!$_SESSION['login']==1) { echo "ERROR : no access ..."; exit;}

// editieren kommt E kommt von book.php
if($E AND !$Eedit)
{
$result = mysql_query("select * from pbook where id='$E'",$conn)or die (mysql_error());
$set = mysql_fetch_array($result);
$name=$set["name"];
$call=$set["calld"];
$bemerkung=$set["bemerkung"];
}

// Neue Einträge kontrollieren und eintragen
$p=trim($phone);
$p2 = ereg_replace (' ', '', $p);

if($p2>"")
{
if(trim($phone)>"")
$result = mysql_query("select * from pbook where calld='$p2'",$conn)or die (mysql_error());
if(mysql_num_rows($result) AND !$E)
{
echo "$p2 : Eintrag schon vorhanden !!!!!!";
} // endif if(mysql_num_rows($result))
else
{

if($E)  // editieren
{

$name=str_replace("ä","ae",$name); 
$name=str_replace("ö","oe",$name); 
$name=str_replace("ü","ue",$name); 
$name=str_replace("ß","ss",$name); 
$name=str_replace("é","e",$name);
$name=str_replace("è","e",$name);
$name=str_replace("à","a",$name);
$name=str_replace("'","`",$name);
$name=strtr($name, "+\"*ç%&/()=?!£#{}[]<>;", "                      ");

mysql_query ("update pbook set
calld = '$p2',
name = '$name',
bemerkung = '$comment'
where id='$E'",$conn)or die (mysql_error());

$put=$p2;
$pname=$name;
require("cli.php");

header ("location:book.php?P=$P&X=$X");exit;

} // endif if($E)
else
{

$name=str_replace("ä","ae",$name); 
$name=str_replace("ö","oe",$name); 
$name=str_replace("ü","ue",$name); 
$name=str_replace("ß","ss",$name); 
$name=str_replace("é","e",$name);
$name=str_replace("è","e",$name);
$name=str_replace("à","a",$name);
$name=str_replace("'","`",$name);
$name=strtr($name, "+\"*ç%&/()=?!£#{}[]<>;", "                      ");

mysql_query("insert into pbook (
calld,
name,
bemerkung
) VALUES (
'$p2',
'$name',
'$comment'
)
",$conn)or die (mysql_error());

$put=$p2;
$pname=$name;
require("cli.php");

header ("location:book.php?P=$P&X=$X");exit;

} // endif else if($E)


} // // endif ifelse (mysql_num_rows($result))



} // endif if($p2>"")


require("head2.php");

?>


<form method="post" action="new_number.php">
<input type=HIDDEN name=E value="<?echo $E;?>">
<input type=HIDDEN name=P value="<?echo $P;?>">
<input type=HIDDEN name=X value="<?echo $X;?>">
<input type=HIDDEN name=Eedit value="<?echo $E;?>">
  <br>
  <table border="0" cellpadding="2" cellspacing="0" width="100%">
    <tr bgcolor="#CCCCCC"> 
      <td colspan="2" align="left" valign="top"> &nbsp;&nbsp;&nbsp;&nbsp;<b><?echo $new_txt4;?></b></td>
    </tr>
    <tr>
      <td colspan="2" align="left" valign="top"> 
        <table width="100%" border="0" cellspacing="3" cellpadding="10">
          <tr>
            <td>
              <table border="0" cellpadding="5" cellspacing="1" bgcolor="#666666">
                <tr bgcolor="#CCCCCC"> 
                  <td width="109" nowrap valign="middle" align="center"> 
                    <div align="left"><b><?echo $new_txt1;?></b></div>
                  </td>
                  <td colspan="2" nowrap> 
                    <input name="phone" type="text" id="phone" value="<?echo $call;?>">
                  </td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td nowrap width="109" align="center" valign="middle"> 
                    <div align="left"><b><?echo $new_txt2;?></b></div>
                  </td>
                  <td nowrap colspan="2"> 
                    <input name="name" type="text" id="name" value="<?echo $name;?>">
                  </td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td nowrap width="109" align="center" valign="middle"> 
                    <div align="left"><b><?echo $new_txt3;?></b></div>
                  </td>
                  <td nowrap colspan="2"> 
                    <textarea name="comment" id="comment" cols="50" rows="8"><? echo $bemerkung;?></textarea>
                  </td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td nowrap colspan="3"> 
                    <div align="right"> 
                      <input type="submit" value="     OK     "width="50" name="submit" >
                    </div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <div align="center"> </div>
      </form>

<br>

<?require("http_end.php");?>                                                                                      