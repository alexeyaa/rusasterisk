<?PHP
// NICHT löschen das braucht es für AJAX !!!!!!!!!!!!!!!!!!!!!!!
 echo"sip|";
// NICHT löschen das braucht es für AJAX !!!!!!!!!!!!!!!!!!!!!!! 

$events_config=1;
require("config.php");

$sip_array = explode(",",$sip_monitoring);
$sip_main=0;
$color_link="#ECFFEC";
$color_ring="#FFFFD2";

$Pi=$sip_array[0]; // my extension = the first extension in the list

////////// begin events read /////////////////////////////////////
foreach($sip_array as $sip_key => $sip_val)
{

$tb++;  // conter for the monitioring tabel

$T="T$tb";
$$T="$sip_val<br>&nbsp;";
$TCC="#FFFFFF";

	$Link=0;
	
	$result = mysql_query("select * from events where EVNT LIKE '%Dial%' AND DEST='$sip_val'",$conn)or die (mysql_error());
	if(mysql_num_rows($result)) 
	{
	$set = mysql_fetch_array($result);
	$SRC=$set["SRC"];
	$CID=$set["CID"];
	$CIDNAME=$set["CIDNAME"];
	$IDdest=$set["IDdest"];
	$IDsrc=$set["IDsrc"];
	
	/*
		// MISDN > SIP
		if($SRC=="MISDN")
		{
		$CID_SRC=$CID;
		// $CLIP_NAME muss ncoh geholt werden lookup
		}
		else // SIP > SIP
		{
		$CID_SRC=$SRC;
		$CLIP_NAME=$CIDNAME;
		}
	 */
	 
	 $CID_SRC=$CID;
	 $CLIP_NAME=$CIDNAME;
	 
	 
	 
	 
		// LINK or Ringing ???
		$r2 = mysql_query("select * from events where EVNT LIKE '%Link%' AND IDdest='$IDdest' AND IDsrc='$IDsrc'",$conn)or die (mysql_error());
		if(mysql_num_rows($r2)) $LINK=1;

		if($CID_SRC>"" AND !$CLIP_NAME)
		{
		$actual_call=trim($CID_SRC);
		require("report_include.php");
		$CLIP_NAME=$events_input;
			}
		
		if (trim($CLIP_NAME)>"") $from1="$CID_SRC [$CLIP_NAME]";
		else $from1=$CID_SRC;
		
		$color_link1="#ECFFEC";
		$color_ring1="#FFFFD2";
		if($LINK==1){$color_link1="#33FF33";$TCC=$color_link1;}
		if($LINK==0 AND $IDdest) {$color_ring1="#FFFF00";$TCC=$color_ring1;}
	
		if($sip_main==0)
		{
		$color_link=$color_link1;
		$color_ring=$color_ring1;
		$from=$from1;
		}
		else
		{
		$T="T$tb";
		$$T="$sip_val<br>$CID_SRC";
		$TColor="TBG$tb";			
		$$TColor="bgcolor='$TCC'";
		}
	
	} // end of if(mysql_num_rows($result)) 

	else

	{  // if event:DIAL SIP - SIP  search in database again
	$result = mysql_query("select * from events where EVNT LIKE '%Dial%' AND SRC='$sip_val'",$conn)or die (mysql_error());
		if(mysql_num_rows($result)) 
		{
		$set = mysql_fetch_array($result);
		$DEST=$set["DEST"];
		$CID=$set["CID"];
		$IDdest=$set["IDdest"];
		$IDsrc=$set["IDsrc"];		
		
		// LINK or Ringing ???
		$r2 = mysql_query("select * from events where EVNT LIKE '%Link%' AND IDdest='$IDdest' AND IDsrc='$IDsrc'",$conn)or die (mysql_error());
		if(mysql_num_rows($r2)) $LINK=1;
		if($LINK==1){$TCC="#33FF33";}
		if($LINK==0 AND $IDdest) {$TCC="#FFFF00";}
		
		$T="T$tb";
		$$T="$sip_val<br>$DEST";
		$TColor="TBG$tb";		
		$$TColor="bgcolor='$TCC'";	
		}
	} // // end of else - if(mysql_num_rows($result))  

	$sip_main=1;

} // end of foreach($sip_array as $sip_key => $sip_val)

///////////////// end of events read ///////////////////////////////////////////////

$h=date("H",time());
$m=date("i",time());

$color_digit="#365178";
$color_hg1="#ACBC94";
$color_hg2="#BBC9A7";
$color_border="#666666";

?> 
<table width="1" border="0" cellspacing="2" cellpadding="2" bgcolor="#333333">
  <tr> 
    <td> 
      <table border="1" cellspacing="1" cellpadding="8">
        <tr bgcolor="<?echo $color_hg2?>"> 
          <td height="68"> 
            <table width="500" border="0" cellspacing="2" cellpadding="10">
              <tr bgcolor="<?echo $color_hg1?>"> 
                <td width="431"><font face="Arial" size="4" color="<?echo $color_digit?>"><?echo $Pi?><br>
                  </font></td>
                <td align="right" valign="top" width="118" colspan="2"> 
                  <table width="1" border="0" cellspacing="0" cellpadding="1">
                    <tr> 
                      <td><b><font face="Arial" size="4" color="<?echo $color_digit?>"><?echo $h?></font></b></td>
                      <td><b><font face="Arial" size="4" color="<?echo $color_digit?>"><blink>:</blink></font></b></td>
                      <td><b><font face="Arial" size="4" color="<?echo $color_digit?>"><?echo $m?></font></b></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr bgcolor="<?echo $color_hg1?>"> 
                <td width="431"><font face="Arial" size="4" color="#365178"><?echo $from?>&nbsp;</font></td>
                <td valign="top" align="right"><a href="events.php?<?echo "P=$P&X=$X&RESET=1";?>"><img src="images/R.png" border="0"></a> 
                </td>
                <td valign="top" align="right">
                  <table width="1" border="0" cellspacing="0" cellpadding="1">
                    <tr> 
                      <td><b></b></td>
                      <td><b><font face="Arial" size="4" color="<?echo $color_digit?>"><blink><a href="report.php?<?echo "P=$P&X=$X";?>" target='blank_'><img src="images/book.gif" width="20" height="18" border="0"></a></blink></font></b></td>
                      <td><b></b></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
    <td> 
      <table width="1" border="0" cellspacing="1" cellpadding="1">
        <tr> 
          <td> 
            <table width="1" border="1" cellspacing="1" cellpadding="2">
              <tr bgcolor="<?echo $color_link?>"> 
                <td>&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr> 
          <td> 
            <table width="1" border="1" cellspacing="1" cellpadding="2">
              <tr bgcolor="<?echo $color_ring?>"> 
                <td>&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td colspan="2">
      <table border="0" cellspacing="1" cellpadding="0" bgcolor="#003399">
        <tr align="center" valign="middle" bgcolor="#FFFFFF"> 
          <td <?echo $TBG1?> width="110"><font face="Arial" size="2"><?echo $T1?>&nbsp;</font></td>
          <td <?echo $TBG2?> width="110"><font face="Arial" size="2"><?echo $T2?>&nbsp;</font></td>
          <td <?echo $TBG3?> width="110"><font face="Arial" size="2"><?echo $T3?>&nbsp;</font></td>
          <td <?echo $TBG4?> width="110"><font face="Arial" size="2"><?echo $T4?>&nbsp;</font></td>
          <td <?echo $TBG5?> width="110"><font face="Arial" size="2"><?echo $T5?>&nbsp;</font></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
<br>
