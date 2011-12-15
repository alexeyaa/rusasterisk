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


<form name="book" method="post" action="administration.php">
  <p> 
    <input type=HIDDEN name=P value="<?echo $P?>">
    <input type=HIDDEN name=X value="<?echo $X?>">
  </p>
  <table border="0" cellpadding="2" cellspacing="0" width="100%">
    <tr bgcolor="#CCCCCC"> 
      <td colspan="2" align="left" valign="top"> <br>
        &nbsp;&nbsp;&nbsp;&nbsp;Administration</td>
    </tr>
    <tr> 
      <td colspan="2" align="left" valign="top"> 
        <table width="100%" border="0" cellspacing="3" cellpadding="10">
          <tr> 
            <td>
              <table width="700" border="0" cellspacing="0" cellpadding="2" bgcolor="#FFFFFF">
                <tr> 
                  <td> 
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr> 
                        <td colspan="3"> 
                          <hr size="1">
                        </td>
                      </tr>
                      <tr> 
                        <td rowspan="3" align="center" valign="middle" width="100" nowrap bgcolor="#CCCCCC"> 
                          <div align="left"><b>IP Telefon</b></div>
                        </td>
                        <td width="116" align="left" valign="middle" nowrap> 
                          <div align="left"><b>Local Nr. </b> </div>
                        </td>
                        <td width="449" nowrap> 
                          <input type="text" name="P" size="5" maxlength="8" value="<?echo $P?>">
                        </td>
                      </tr>
                      <tr> 
                        <td width="116" align="left" valign="middle" nowrap> 
                          <div align="left"><b>Prefix Nr.</b></div>
                        </td>
                        <td width="449" nowrap> 
                          <input type="text" name="X" size="3" maxlength="8" value="<?echo $X?>">
                        </td>
                      </tr>
                      <tr> 
                        <td width="116" align="left" valign="middle" nowrap> 
                          <div align="left"></div>
                        </td>
                        <td width="449" nowrap> 
                          <input type="submit" name="ok" value="Start Browser Link">
                        </td>
                      </tr>
                      <tr bgcolor="#FFFFFF"> 
                        <td colspan="3" align="center" valign="middle" nowrap> 
                          <hr size="1" align="left">
                        </td>
                      </tr>
                      <tr> 
                        <td align="center" valign="middle" width="100" nowrap bgcolor="#CCCCCC"> 
                          <div align="left"><b>SNOM</b></div>
                        </td>
                        <td width="116" align="left" valign="middle" nowrap> 
                          <div align="left"><b>Actions URL<br>
                            Phonebook </b></div>
                        </td>
                        <td width="449" nowrap><?echo $URL_HTTP?>/snom.php</td>
                      </tr>
                      <tr bgcolor="#FFFFFF"> 
                        <td colspan="3" align="center" valign="middle" nowrap> 
                          <hr size="1" align="left">
                        </td>
                      </tr>
                      <tr> 
                        <td align="center" valign="middle" width="100" nowrap bgcolor="#CCCCCC"> 
                          <div align="left"><b>SNOM&nbsp;</b><a href="snomhelp.php" target="_blank"><b>- 
                            ? </b></a> </div>
                        </td>
                        <td width="116" align="left" valign="middle" nowrap> 
                          <div align="left"><b>Actions URL<br>
                            Reportlist IN</b></div>
                        </td>
                        <td width="449" nowrap><?echo $URL_HTTP?>/snom-report.php?<?echo "X=$X&P=$P"; ?> 
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                      </tr>
                      <tr bgcolor="#FFFFFF"> 
                        <td colspan="3" align="center" valign="middle" nowrap> 
                          <hr size="1" align="left">
                        </td>
                      </tr>
                      <tr> 
                        <td align="center" valign="middle" width="100" nowrap bgcolor="#CCCCCC"> 
                          <div align="left"><b>SNOM<a href="snomhelp.php" target="_blank"> 
                            - ?</a> </b></div>
                        </td>
                        <td width="116" align="left" valign="middle" nowrap> 
                          <div align="left"><b>Actions URL<br>
                            Reportlist OUT</b></div>
                        </td>
                        <td width="449" nowrap><?echo $URL_HTTP?>/snom-report.php?<?echo "X=$X&I=2&P=$P"; ?> 
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                      </tr>
                      <tr bgcolor="#FFFFFF"> 
                        <td colspan="3" align="center" valign="middle" nowrap> 
                          <hr size="1" align="left">
                        </td>
                      </tr>
                      <tr> 
                        <td align="center" valign="middle" width="100" nowrap bgcolor="#CCCCCC"> 
                          <div align="left"><b>Excel / CSV</b></div>
                        </td>
                        <td width="116" align="left" valign="middle" nowrap> 
                          <div align="left"><b>Import</b></div>
                        </td>
                        <td width="449" nowrap><a href="import.php" target="_blank">CSV 
                          EXCEL Data</a></td>
                      </tr>
                      <tr bgcolor="#FFFFFF"> 
                        <td colspan="3" align="center" valign="middle" nowrap> 
                          <hr size="1" align="left">
                        </td>
                      </tr>
                      <tr> 
                        <td align="center" valign="middle" width="100" nowrap bgcolor="#CCCCCC"> 
                          <div align="left"><b>Excel / CSV</b></div>
                        </td>
                        <td width="116" align="left" valign="middle" nowrap> 
                          <div align="left"><b>Export</b></div>
                        </td>
                        <td width="449" nowrap><a href="export.php" target="_blank">Backup 
                          Data</a></td>
                      </tr>
                      <tr bgcolor="#FFFFFF"> 
                        <td colspan="3" align="center" valign="middle" nowrap> 
                          <hr size="1" align="left">
                        </td>
                      </tr>
                      <tr> 
                        <td align="center" valign="middle" width="100" nowrap bgcolor="#CCCCCC"> 
                          <div align="left"><b>Database</b></div>
                        </td>
                        <td width="116" align="left" valign="middle" nowrap> 
                          <div align="left"> </div>
                        </td>
                        <td width="449" nowrap> <a href="cli.php?putall=1" target="_blank">Data 
                          Regeneration</a> - MySQL &lt;&gt; Asterisk</td>
                      </tr>
                      <tr> 
                        <td colspan="3" nowrap> 
                          <hr size="1">
                        </td>
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
  </table>
  <p>&nbsp; </p>
  <p><br>
    <br>
  </p>
  </form>
  
  <?require("http_end.php");?>

