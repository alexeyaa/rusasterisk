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

function m2t($datetime){
       $val = explode(" ",$datetime);
       $date = explode("-",$val[0]);
       $time = explode(":",$val[1]);
       return mktime($time[0],$time[1],$time[2],$date[1],$date[2],$date[0]);
}

if($actual_call>"" AND $P)// if realtime number with $actual_call
{
$scall=$actual_call;
$ringimage="<img src=\"images/ringingphone.gif\">";
require("report_include.php");
}


unset($actual_call);
unset($ringimage);


if($modul_cdr==1)require("cdr.php");  // imput log asterisk

$za=0;
if(!$L)$L=25;

if($P>0)$where=$where."AND dstchannel LIKE 'SIP/$P-%'";

$result3 = _query("select * from cdr where src LIKE '0%' $where order by calldate DESC LIMIT $L",$conn)or die (_error());
WHILE($set = _fetch_array($result3))
{
require("report_include.php");
}
   

require("head2.php");

$date_actual=date("j.n.Y H:i",time());
?>


<FORM METHOD=POST>
<input type=HIDDEN name=P value="<?echo $P?>">
<input type=HIDDEN name=X value="<?echo $X?>">

<br>

<table width="1" border="0" cellspacing="0" cellpadding="2">
  <tr align="left" valign="top" bgcolor="#CCCCCC"> 
    <td> 
        <table width="363" border="0" cellspacing="0" cellpadding="2">
          <tr> 
            <td nowrap><b>LOG Report </b></td>
            <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td nowrap> 
              <select name="L">
                <option value="25" <?if($L==25)echo "selected";?>>25</option>
                <option value="50" <?if($L==50)echo "selected";?>>50</option>
                <option value="100" <?if($L==100)echo "selected";?>>100</option>
                <option value="500" <?if($L==500)echo "selected";?>>500</option>
              </select>
              <input type="submit" name="Button" value="limit">
            </td>
            <td nowrap> 
              <table width="1" border="0" cellspacing="0" cellpadding="1">
                <tr> 
                  <td nowrap><font size="1">SERVER DATE</font></td>
                  <td colspan="2">
                    <table width="1" border="0" cellspacing="1" cellpadding="2">
                      <tr>
                        <td nowrap><font size="5" color="#FFFFFF"><b><font size="4"><?echo "$date_actual"; ?></font></b></font></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
    </td>
  </tr>
  <tr align="left" valign="top"> 
    <td> 
        <table  border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF" width="100%">
          <tr align="center" bgcolor="#FFFFFF" valign="middle"> 
            <td ><b>&nbsp;&nbsp;<img src="images/date.gif" width="40" height="40"></b></td>
            <td >&nbsp;</td>
            <td ><b><img src="images/extension.gif" width="56" height="42"></b></td>
            <td ><b>&nbsp;&nbsp;<img src="images/phone.button.gif" width="33" height="33"></b></td>
            <td ><b>&nbsp;<img src="images/user.jpg" width="40" height="40"></b></td>
            <td colspan="2" nowrap ><img src="images/www.jpg" width="40" height="40"></td>
          </tr>
          <? echo "$i";?> 
        </table>
    </td>
  </tr>
</table>
 
</form>


<?require("http_end.php");?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table border="1" cellspacing="1" cellpadding="8" bordercolor="#666666">
  <tr bgcolor="#BBC9A7"> 
    <td height="68"> 
      <table width="500" border="0" cellspacing="2" cellpadding="1">
        <tr bgcolor="#ACBC94"> 
          <td width="425"><font face="Arial, Helvetica, sans-serif" size="4" color="#365178"><b>TEXT<br>
            </b></font></td>
          <td align="left" valign="top" width="71"> 
            <table width="1" border="0" cellspacing="0" cellpadding="1">
              <tr> 
                <td><b><font face="Arial, Helvetica, sans-serif" size="4">12</font></b></td>
                <td><b><font face="Arial, Helvetica, sans-serif" size="4"><blink>:</blink></font></b></td>
                <td><b><font face="Arial, Helvetica, sans-serif" size="4">24</font></b></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr bgcolor="#ACBC94"> 
          <td width="425"><font face="Arial, Helvetica, sans-serif" size="4" color="#365178"><b>tEXT 
            2 </b></font></td>
          <td width="71"><font face="Arial, Helvetica, sans-serif" size="4" color="#FF3333"><b></b></font></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
<table width="531" border="0" cellspacing="2" cellpadding="12">
  <tr>
    <td>Enigehende Anrufe</td>
    <td>Ausgehende Anrufe</td>
  </tr>
  <tr>
    <td>
      <p>5</p>
      <p>6</p>
      <p>7</p>
      <p>8</p>
    </td>
    <td>
      <p>5</p>
      <p>6</p>
      <p>7</p>
      <p>8</p>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
