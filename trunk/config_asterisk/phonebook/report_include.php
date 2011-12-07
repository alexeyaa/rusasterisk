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



$call01="";
$call02="";
$call03="";
$call04="";
$call05="";
$VM=0;
$dname="";
$directory="";

if($actual_call)
{
$call=$actual_call;
$dst=$P;
$dat=time();
}
else
{


if($I==1) // eingehende anrufe
{
	$call=trim($set["src"]);
	if($set["lastapp"]=="VoiceMail")
	{
	$dst=$set["dst"];
	$VM=1;
	}
	else $dst=$set["dstchannel"];
}
else // ausgehnde anrufe
{
$call=CALL_DST(trim($set["lastdata"]));
$dst=$set["channel"];
}


//$duration=round($set["duration"]/60);
if($set["calldate"]>"") $dat=m2t($set["calldate"]); else $dat=time();
//$dat=m2t($set["calldate"]);
$disposition=$set["disposition"];
$duration=round($set["duration"]/60);

$clid_=$set["clid"];
$clid_array = explode('"',$clid_);

$clid=trim($clid_array[1]);

// CLIPlänge definieren, nur wenn grösser als im config angegeben
if(strlen($clid)>$clidlen) $clid=substr($clid,0,$clidlen)."...";

}

if(ereg("SIP", $dst))
{
$array = explode("/",$dst);
$dest1=$array[1];
$array = explode("-",$dest1);
$dst=$array[0];
$array = explode("@",$dst);
$dst=$array[0];
}


// auflösung 9-stellige nummern
if(strlen($call)==9)
{
$call01=substr($call,0,2);
$call02=substr($call,2,3);
$call03=substr($call,5,2);
$call04=substr($call,7,2);
$call05= "$call01 <b>$call02 $call03 $call04</b>";
}

// auflösung 10-stellige nummern
if(strlen($call)==10)
{
$call01=substr($call,0,3);
$call02=substr($call,3,3);
$call03=substr($call,6,2);
$call04=substr($call,8,2);
$call05= "$call01 <b>$call02 $call03 $call04</b>";
}

// internationale Nummern
if(strlen($call)>10)
{
$call05= $call;
}

if(!$call05)$call05=$call;

$scall=trim($call);
$XML_CALL=$call;
$call=$X.$call;



if($P>0)$href="<A HREF=\"manager.php?call=$call&ext=$P\" TARGET=\"fen\" onclick=\"window.open('', 'fen','width=400, height=250, toolbar=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no')\">$call05</A>";

else $href=$call05;

$nolokal=0;

// search in the phonebook
$result = mysql_query("select * from pbook  where calld='$scall'",$conn)or die (mysql_error());
$set2 = mysql_fetch_array($result);
$name=$set2["name"];
$name_S=$name;
if(!$name){$nolokal=1;}

$datum=date("j.n.Y H:i",$dat); 

$d1=date("j.n.y",$dat); 
$d2=date("H:i",$dat); 
$d3=date("j.n",$dat);

if(!$bdat)$bdat=date("j.n.Y",$dat); 

if(ereg("#", $dst)) $dst=""; // no show forwarding/trunk sip numbers

////////// directory //////////////////////////////////////////////////////////////////////////////////////
// nur neue Einträge testen:

$nn=2;
if(ereg("^0[1-9]", $scall) AND !ereg("^$exclude_numbers", $scall)  AND $nolokal==1)
{

$nn=0;
$dname="";
$dort="";
$dstrasse="";

// search in the directory cache_phonebook
$result5 = mysql_query("select * from pbook_directory where tel='$scall'",$conn)or die (mysql_error());
$set5 = mysql_fetch_array($result5);
	if($set5)
	{
	$dname=trim($set5["name"]);
	$dort=$set5["ort"];
	}
else {$nn=1;}

if($nn==1)
	{
  			if($za<3)
			{
			
			//echo "z= $za <br>";
			
			$U=$scall;
			
			require("directory.php");
			
			if(!$dort)$dort=" ";
			
					if($U>0 AND $dname>"")
					{
					$put=$U;
					$pname=$dname;
					require("cli.php");  // schreibe in astdb
					
					// wenn im telserach gefunden dann rein in die DB
					mysql_query("insert into pbook_directory (
					tel,
					name,
					ort
					) VALUES (
					'$scall',
					'$dname',
					'$dort'
					)
					",$conn)or die (mysql_error());
					}
			$za++;
				} // end of if($za<3)

	} // end of if($nn==1)

$bg="#FCFA9C";
if($bdat <> date("j.n.Y",$dat)){$bg="#FFFFFF";}

$external_phonebook_link2=str_replace("NUMBER",$scall,$external_phonebook_link);

if($external_phonebook_link2>"")
$href_ext="<a href=\"$external_phonebook_link2\" target=\"_blank\"><img src=\"images/phonebook.gif\" border=\"0\" alt=\"Directory\"></a>";
else
$href_ext="&nbsp;";

$directory="
              
			 <table border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                <tr>
                  <td nowrap>$href_ext</a></td>
                  <td nowrap>&nbsp; $dname $dort &nbsp;</td>
                </tr>
              </table>
            
 ";

} // end of if(ereg("^0[1-9]", $scall) AND !ereg("^$exclude_numbers", $scall)  AND $nolokal==1)
//else
//end of //////// directory //////////////////////////////////////////////////////////////////////////////////////


$noname=0;
if(!$name)
{
$noname=1;
	  if($dname>"")
	  {
	  $name_S=$dname;
		$name="<table border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
		<tr>
		<td nowrap>$href_ext</a></td>
		<td nowrap><a href=\"new_number.php?call=$scall&name=$dname&P=$P&X=$X\" >$dname $dort</a></td>
		</tr>
		</table>";
	 }
  else
    {
       if($nn<2)
	   {
		$name="<table border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
		<tr>
		<td nowrap>$href_ext</a></td>
		<td nowrap><a href=\"new_number.php?call=$scall&name=$dname&P=$P&X=$X\" >&nbsp;&nbsp;&nbsp;noname&nbsp;&nbsp;&nbsp;</a></td>
		</tr>
		</table>";
	   }
     }
  
//"<a href=\"new_number.php?call=$scall&name=$dname&P=$P&X=$X\" >noname</a>";
}

if($noname==1) $events_input="---";
if(trim($dname)>"")$events_input="$dname $dort";
if($noname==0 AND trim($name)>"")$events_input=$name;

$bg="#FCFA9C";
if($bdat <> date("j.n.Y",$dat)){$bg="#FFFFFF";}

if ($disposition=="ANSWERED"){$answergif="ok.gif";}
else{$answergif="no.gif";unset($disposition);}

if($VM==1) // voicemail
{
$dst="$dst <img src=\"images/VM.png\">";
}

$NNAME="<a href=\"new_number.php?call=$scall&P=$P&X=$X\" >&nbsp;&nbsp;&nbsp;noname&nbsp;&nbsp;&nbsp;</a>";

if( strlen(trim($name))==0 AND ereg("^0", trim($XML_CALL)) ) $name=$NNAME;// eingehende anrufe
//if($I<>1 AND strlen(trim($name))==0 AND ereg("^0", trim($XML_CALL)) ) $name=$NNAME;// eingehende anrufe

$list_ok=0;

$name_S=strtolower(ereg_replace(" ", "", trim($name_S)));

if($search AND $check3=="exact")
{ 
	if ( ereg("^$check$",$XML_CALL) ) $list_ok=1; 
    if ( ereg("^$check$",$name_S) ) $list_ok=1;   
}

if($search AND $check3<>"exact" AND $check>"")
{ 
	if ( ereg($check,$XML_CALL) ) $list_ok=1; 
    if ( ereg($check,$name_S) ) $list_ok=1;   
}

if(!$search) $list_ok=1;


if($list_ok==1)
{


// BETA monitoring:







$i=$i. "
<tr onMouseOver=\"this.style.backgroundColor='#CCFF99';\" onMouseOut=\"this.style.backgroundColor='$bg';\" bgcolor=\"$bg\">
	 <td nowrap>&nbsp;$d1&nbsp;</td> 				<!-- Datum -->
	 <td nowrap>&nbsp;$d2&nbsp;</td>			    <!-- Zeit -->
	 <td  nowrap>$dst &nbsp;</td>                   <!-- von Tel Nummer -->
	 <td><img src=\"images/$answergif\"></td>       <!-- Anruf Status -->
	 <td align=\"right\">$duration</td>             <!-- Laenge in Sec. -->
	 <td  nowrap>&nbsp; $href </td>                 <!-- an Tel Nummer mit Link -->
	 <td  nowrap>$name </td>                        <!-- Name -->
	 <td  nowrap>&nbsp; $clid</td>                  <!-- CLIP -->
</tr>
";
}

$XML_name=substr($events_input,0,$XML_NAME_LEN);
$XML_time="$d3. $d2";


// nur externe Nummern beginnend mit 0 anzeigen
if(ereg("^0", $XML_CALL))

{
$X_=$X;

$_SNOM_XML=$_SNOM_XML."
<DirectoryEntry>
<Name>$XML_time $XML_name ($XML_CALL)</Name>
<Telephone>$X_$XML_CALL</Telephone>
</DirectoryEntry>
";
}



?>