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

if($P>0)
{



CDR_CONN(); // mysql Connect to asterisk-cdr
// letzter Anruf holen 
$resultCN = mysql_query("select * from cdr where  channel LIKE 'SIP/$P-%'  order by calldate DESC LIMIT 1",$cdr_conn)or die (mysql_error());
$setCN = mysql_fetch_array($resultCN);
//$dstCN=$setCN["dst"];
$dstCN=CALL_DST(trim($setCN["lastdata"]));

$dstChannel=$setCN["dstchannel"];
if(ereg("SIP", $dstChannel))$X5="";  else $X5=$X;
$callnow=$dstCN;
CONN(); // mysql Connect to asterisk
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!-- Source of IPline DialFox, The Open Source Asterisk Phone Directory  -->
<!-- Copyright (C) 2008 A-Enterprise GmbH Switzerland - Claude Fanac  -->
<!-- Icons are from http://www.freeiconsweb.com  -->
<html>
<head>
<title>*Book</title>
	
<link rel="stylesheet" type="text/css" href="gtw.css">
<LINK href="<?echo $URL_HTTP?>/favicon.ico" rel="SHORTCUT ICON">

<script language="JavaScript" type="text/javascript">

function OR (AD) {
  MF = window.open(AD, "ZF", "width=380,height=400,left=100,top=200");
  MF.focus();
}

function FFO (Adresse) {
  MFE = window.open(Adresse, "ZFE", "width=610,height=150,left=880,top=0");
  MFE.focus();
}
</script> 

</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?if($P>0){?>

<script language="JavaScript" type="text/javascript">
function oknew()
{
   w = window.open('about:blank', 'MF', 'width=800,height=600');
   w.focus();
}

</script> 

<form action="manager.php" method="GET" target="MF"> 
<form action="#" onSubmit=Start(); method="post">
<input type=HIDDEN name=ext value="<?echo $P?>">
<?}?>


  <table width="900" border="0" cellspacing="0" cellpadding="1">
    <tr> 
      <td rowspan="2" width="100"><a href="book.php?<? echo "P=$P&X=$X"; ?>"><img src="images/dialfox_logo.jpg" border="0"></a></td>
      <td align="left" valign="top"> 
        <table width="1" border="0" cellspacing="0" cellpadding="1">
          <tr> 
            <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
            <td nowrap> <a href="book.php?<? echo "P=$P&X=$X"; ?>"><img src="images/L1.png" border="0"></a> 
            </td>
            <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td nowrap> <a href="report.php<? echo "?P=$P&X=$X";?>"><img src="images/L2.png" border="0"></a> 
            </td>
            <? if($_SESSION['login']==1){ ?> 
            <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td nowrap> <a href="new_number.php<? echo "?P=$P&X=$X&pwd=$pwd";?>"><img src="images/L3.png" border="0"></a> 
            </td>
            <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td nowrap> <a href="administration.php<? echo "?P=$P&X=$X";?>"><img src="images/L4.png" border="0"></a> 
            </td>
            <?}?> <?if($show_events==1){?> 
            <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td nowrap> <a href="events.php<? echo "?P=$P&X=$X";?>" onclick="FFO(this.href); return false"><img src="images/L5.png" border="0"></a> 
            </td>
            <?}?> 
            <td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td nowrap> <a href="help.php<? echo "?P=$P&X=$X";?>"><img src="images/L6.png" border="0"></a> 
            </td>
          </tr>
        </table>
      </td> 
	  
	  <td align="center" valign="top"><font size="5"><b><font color="#FF9933"><?echo $P?></font></b></font>&nbsp;</td>
	  
      <?if($P>0){?> 
      <td align="center" valign="top"> 
        <table border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td> 
<input 
type="text" 
style="text-align: right;" 
size="3" 
name="Xdirect" 
class="Q2" 
value="<?echo trim($X5);?>" 
onfocus="if(this.value==this.defaultValue)this.value='';"
onblur="if(this.value=='')this.value=this.defaultValue;" />		  
            </td>
            <td> 		
<input 
type="text" 
style="text-align: right;"  
size="14"
name="callnow" 
class="Q" 
value="<?echo trim($callnow)?>" 
onfocus="if(this.value==this.defaultValue)this.value='';"
onblur="if(this.value=='')this.value=this.defaultValue;" />	  
            </td>
            <td> 
              <input type="image" src="images/callnow.gif" onClick="oknew()" name="image">
            </td>
          </tr>
        </table>
        <?}?> </td>
     
      <td align="right" valign="top"><font size="5" color="#666633"><img src="images/asterisk.gif" width="34" height="28"></font>&nbsp;</td>
      <td align="left" valign="top"><font size="5" color="#666633"><b>Book </b></font></td>
    </tr>
    <tr> 
      <td colspan="5" align="left" valign="top"> 
        <hr size="1">
      </td>
    </tr>
  </table>
  

</form>

