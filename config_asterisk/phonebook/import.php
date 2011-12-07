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

CONN();

$DocRoot="upload/";

if($i=="ok")
{

$file=$DocRoot."book.csv";
	if(file_exists($file))
	{
	
	$fp = fopen($file, "r");
		while (!feof($fp)) 
		{
		$zeile = fgets($fp,500);
		$array = explode(";",$zeile);
		$call=$array[0];
		$name=$array[1];
		$comment=$array[2];
		
		// Neue Einträge kontrollieren und eintragen
		$p=trim($call);
		$p2 = ereg_replace (' ', '', $p);
		
				if($p2>"")
				{
				$result = mysql_query("select * from pbook where calld='$p2'",$conn)or die (mysql_error());
				if(mysql_num_rows($result) AND !$E)
				{
								$t=$t."
				  <tr>
				    <td nowrap>&nbsp; schon vorhanden : </td>
				    <td nowrap>&nbsp; $p2</td>
				    <td nowrap>&nbsp; $name</td>
				    <td nowrap>&nbsp; $comment</td>
				  </tr>
				";

				
				
				} // endif if(mysql_num_rows($result))
				else
				{
				
				$name=str_replace (array("ä", "ö", "ü", "ß","'"), array("ae", "oe", "ue", "ss","`"), $name);
				
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
				
				$t=$t."
				  <tr>
				    <td nowrap>&nbsp; importiert: </td>
				    <td nowrap>&nbsp; $p2</td>
				    <td nowrap>&nbsp; $name</td>
				    <td nowrap>&nbsp; $comment</td>
				  </tr>
				";
				}
				} // end of if($p2>"")
	
	}// end of while (!feof($fp))
	
	// daten in astdb schreiben:
	$putall=1;
	require("cli.php");
	
	fclose($fp);
	
	} // end of if(file_exists($file))
	else
	{echo "ERROR: kein File";}



} // end of if $i=ok


if($img_submit)
{

$DocRoot="upload/";
exec ("rm -f $DocRoot/*.csv",$ooo);

$max_size=10000000;

    $size = $new_file_size; // Größe der Datei 
    $name = $new_file_name; // Der Originalname 
    $type = $new_file_type; // Der MIME Type der Datei
    $ext  = strrchr($name,".");  // setzt die ID des Inserates vor die extenchen gif/jpg ec.

    $file=$DocRoot."book.csv"; // Verzeichnis der Uplodfiles CHMOD 777
    if(eregi("(.csv)$",$name)) // Alle Ext. hier eintragen
	{ 
    	if($size<$max_size AND $size!=0) 
		{ 
      	copy($new_file,$file); // Kopiert das new_file 
      	$size=$size; 
      	$typ=$type;
    	} 
		else 
		{ $status= "ERROR File Upload";  } 
	} else {$status= "ERROR File Upload: ".$name; } 

if($status) echo "<br><br><br>$status<br><br><br>"; 


$file=$DocRoot."book.csv";
if(file_exists($file))
{

$fp = fopen($file, "r");
	while (!feof($fp)) 
	{
	 $zeile = fgets($fp,500);

$array = explode(";",$zeile);
$call=$array[0];
$name=$array[1];
$comment=$array[2];

$href="<a href=\"import.php?i=ok\">&nbsp;&nbsp;&nbsp;&nbsp;Liste OK jetzt importieren&nbsp;&nbsp;&nbsp;&nbsp;</a>";


$t=$t."
  <tr>
    <td nowrap>&nbsp;</td>
    <td nowrap>&nbsp; $call</td>
    <td nowrap>&nbsp; $name</td>
    <td nowrap>&nbsp; $comment</td>
  </tr>
";
	}
fclose($fp);

}else{echo "ERROR: kein File";}








}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Telefonbuch</title>
<link rel="stylesheet" type="text/css" href="gtw.css">
</head>
<body bgcolor="#FFFFFF" leftmargin="8" topmargin="8" marginwidth="8" marginheight="8">

<form enctype="multipart/form-data"  method=POST>
  <table border="0" cellspacing="3" cellpadding="5" bgcolor="#CCCCCC" width="391">
    <tr>
      <td colspan="3" nowrap><font size="5" color="#FFFFFF"><b>Telefonbuch CSV 
        IMPORT</b></font></td>
    </tr>
    <tr> 
      <td colspan="3" nowrap><b>CSV Format beachten:</b></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="3" nowrap> 
        <p>078123456;name1;bemekung1<br>
          076223344;name2;bemekung2 <br>
          ........<br>
          ........ </p>
      </td>
    </tr>
    <tr> 
      <td colspan="3" nowrap>Dateiname :<b> </b><font size="1">*****</font><b>.csv</b></td>
    </tr>
    <tr> 
      <td colspan="3" nowrap>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3" nowrap> 
        <input type=HIDDEN name=up value="doit">
        <input name="new_file" type="file"  class="bnt">
        <input type="submit" value="upload" name="img_submit" >
      </td>
    </tr>
  </table>
  </form>

<? if($href) {?>

<table border="0" cellspacing="0" cellpadding="5" bgcolor="#FF0000">
  <tr align="center" valign="middle"> 
    <td> 
      <table width="202" border="0" cellspacing="0" cellpadding="10" align="left">
        <tr bgcolor="#FFFFCC" valign="middle" align="center"> 
          <td><?echo $href;?><br>
            <br>
            es werden nur neue Telefonnummer importiert !</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
<?}?> 
<table  border="1" cellspacing="1" cellpadding="2">
  <tr bgcolor="#CCCCCC"> 
    <td><b>Import</b></td>
    <td><b>Nummer</b></td>
    <td><b>Name</b></td>
    <td><b>Bemerkung</b></td>
  </tr>
  <?echo $t;?> 
</table>



<p>&nbsp;</p>

<?require("http_end.php");?>
