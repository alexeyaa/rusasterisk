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

if($RESET==1) // reset events database
{
mysql_query ("delete from events",$conn)or die (mysql_error()); 
}


?>
<script language='JavaScript'>
<!--
var sbusy = 0;
function change_id(obj,nid) { obj.id=nid; } 
function createRequestObject() {
 var request;
 var browser = navigator.appName;
 if(browser == "Microsoft Internet Explorer") request = new ActiveXObject("Microsoft.XMLHTTP");
 else                                         request = new XMLHttpRequest();
 return request;
}

var https = createRequestObject();

function handleResponses() {
  if (https.readyState == 4) {
    var response = https.responseText;
    var pos      = response.indexOf('|');
    if (pos != -1) {
      divid = "div" + response.substr(0,pos);
      document.getElementById(divid).innerHTML = response.substr(pos+1);
    }
    sbusy = 0;
  }
}

function sndReqs() {
  if (sbusy == 1) return;
  https.open('get', 'events_sip.php?<?echo "P=$P&X=$X";?>');
  https.onreadystatechange = handleResponses
  https.send(null);
  sbusy = 1;
}

function send_request() { sndReqs(); }

window.setInterval("send_request()",2000);

// -->
</script>  
  
<body onload=send_request();    bgcolor="#333333" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id="divsip"><br><br><H2>connecting to SIP ...    </H2></div>

</body>
</html>




