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

require ("head_link.php");
?>


<br><br><br><br><br><br><br><br>


<table width="268" border="0" cellspacing="1" cellpadding="12" bgcolor="#003399" align="center">
  <tr align="center" valign="middle" bgcolor="#CCCCCC"> 
    <td> 
      <form name="login" method="post" action="book.php">
        <font size="4" color="#FFFFFF">Administration</font><br><br>
        <input type="text" name="pwd" size="8">
  <input type="submit" name="login" value="login">
      </form>
</td>
  </tr>
</table>



<br><br><br><br><br><br><br><br>

<?require("http_end.php");?>




