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

if($I<>2)$I=1;

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

CDR_CONN(); // mysql Connect to asterisk-cdr

if($modul_cdr==1)require("cdr.php");  // imput log asterisk


$za=0;
if(!$L)$L=25;

$LLI="LIMIT $L";

if($search)
{
    $check=strtolower(trim($check));
	$check=ereg_replace(" ", "", $check);
	$LLI="LIMIT 5000"; // leine limite beim suchen
}


if($I==1) // eingehende anrufe
{


	if($P>0 AND $report_show_all==0)$where="AND dstchannel LIKE 'SIP/$P-%' OR (dst='$P' AND lastapp='VoiceMail') ";
	if($report_show_all==1)$where="AND dstchannel LIKE 'SIP/%' OR lastapp='VoiceMail' $custom_report";
	$result3 = mysql_query("select * from cdr where 1 $where order by calldate DESC $LLI",$cdr_conn)or die (mysql_error());
}
else // ausgehende anrufe
{
	if($P>0 AND $report_show_all==0)$where="AND channel LIKE 'SIP/$P-%'";
	if($report_show_all==1)$where="AND channel LIKE 'SIP/%'";
	$result3 = mysql_query("select * from cdr where 1 $where order by calldate DESC $LLI",$cdr_conn)or die (mysql_error());
}

// Leeres Array setzen
$double_array=array();

WHILE($set = mysql_fetch_array($result3))
{

// search double calls
$double_call=$set["src"];
array_push($double_array, $double_call);

CONN();
require("report_include.php");

}
   

if($_SNOM_<>1)
{

require("head2.php");
$date_actual=date("j.n.Y H:i",time());
?>

<FORM METHOD=POST>
<input type=HIDDEN name=P value="<?echo $P?>">
<input type=HIDDEN name=X value="<?echo $X?>">
<input type=HIDDEN name=I value="<?echo $I?>">
<br>
<table width="1" border="0" cellspacing="0" cellpadding="2">
  <tr align="left" valign="top" bgcolor="#CCCCCC"> 
    <td> 
        <table width="363" border="0" cellspacing="0" cellpadding="2">
          <tr> 
            <td nowrap colspan="2"><b><a href="report.php?<? echo "P=$P&X=$X" ?>"><?echo $report_table1;?></a> 
              <a href="report.php?<? echo "P=$P&X=$X&I=2" ?>"><?echo $report_table2;?></a> 
              <?echo $report_table3;?> </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td nowrap> 
              <select name="L">
                <option value="25" <?if($L==25)echo "selected";?>>25</option>
                <option value="50" <?if($L==50)echo "selected";?>>50</option>
                <option value="100" <?if($L==100)echo "selected";?>>100</option>
                <option value="500" <?if($L==500)echo "selected";?>>500</option>
                <option value="500" <?if($L==1000)echo "selected";?>>1000</option>
                <option value="500" <?if($L==2000)echo "selected";?>>2000</option>		
				<option value="500" <?if($L==4000)echo "selected";?>>4000</option>							
              </select>
              <input type="submit" name="Button" value="limit">
            </td>
            <td nowrap> 
              <table width="1" border="0" cellspacing="0" cellpadding="1">
                <tr> 
                  <td nowrap colspan="3"><font size="1"> </font> 
                    <table width="1" border="0" cellspacing="1" cellpadding="2">
                      <tr> 
                        <td nowrap><font size="2" color="#FFFFFF"><b><?echo "$date_actual"; ?></b></font></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
            <td nowrap>
              <table width="1" border="0" cellspacing="1" cellpadding="1">
                <tr> 
                  <td nowrap> exact 
                    <input type="checkbox" name="check3" value="exact">
                  </td>
                  <td nowrap> 
                    <input type="text" name="check" size="12">
                  </td>
                  <td nowrap> 
                    <input type="submit" name="search" value="search">
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
            <td > 
              <div align="center"><b><?echo $report_table4;?></b></div>
            </td>
            <td > 
              <div align="center"><b><?echo $report_table5;?></b></div>
            </td>
            <td >
              <div align="center"><b><?echo $report_table6;?></b></div>
            </td>
            <td colspan="2" > 
              <div align="center"><b></b><b>Min.</b></div>
            </td>
            <td > 
              <div align="center"><b><?echo $report_table7;?></b></div>
            </td>
            <td > 
              <div align="center"><b>Name</b></div>
            </td>
            <td colspan="2" nowrap >
              <div align="center"><b>CLIP</b></div>
            </td>
          </tr>
           <? echo "$i";?> 
        </table>
    </td>
  </tr>
</table>
 
</form>


<?

require("http_end.php");

} // end of if($_SNOM_<>1) 

?>