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


$logfile = "/var/log/asterisk/cdr-csv/Master.csv";

if(!file_exists($logfile))
{echo "ERROR: no $logfile"; exit;}

$rows = 0;
$handle = fopen($logfile, "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
list($accountcode,$src, $dst, $dcontext, $clid, $channel, $dstchannel, $lastapp, $lastdata, $start, $answer, $end, $duration, $billsec, $disposition, $amaflags ) = $data;
$accountcode=str_replace("'","`",$accountcode);
$src=str_replace("'","`",$src);
$dst=str_replace("'","`",$dst);
$dcontext=str_replace("'","`",$context);
$clid=str_replace("'","`",$clid);
$channel=str_replace("'","`",$channel);
$dstchannel=str_replace("'","`",$dstchannel);
$lastapp=str_replace("'","`",$lastapp);
$lastdata=str_replace("'","`",$lastdata);
$start=str_replace("'","`",$start);
$answer=str_replace("'","`",$answer);
$end=str_replace("'","`",$end);
$duration=str_replace("'","`",$duration);
$billsec=str_replace("'","`",$billsec);
$disposition=str_replace("'","`",$disposition);
$amaflags=str_replace("'","`",$amaflags);
$sql="SELECT calldate, src, duration".
" FROM cdr".
" WHERE calldate='$end'".
" AND src='$src'".
" AND duration='$duration'".
" LIMIT 1";
if(!($result = mysql_query($sql, $cdr_conn))) {
if($debug) print("Invalid query: " . mysql_error()."\n");
if($debug)print("SQL: $sql\n");
die();
}
if(mysql_num_rows($result) == 0) { // we found a new record so add it to the DB
// 3) insert each row in the database
$sql = "INSERT INTO cdr (calldate, clid, src, dst, dcontext, channel, dstchannel, lastapp, lastdata, duration, billsec, disposition, amaflags, accountcode) VALUES('$end', '".mysql_real_escape_string($clid)."', '$src', '$dst', '$dcontext', '$channel', '$dstchannel', '$lastapp', '$lastdata', '$duration', '$billsec', '$disposition', '$amaflags', '$accountcode')";
if(!($result2 = mysql_query($sql, $cdr_conn))) {
if($debug)print("Invalid query: " . mysql_error()."\n");
if($debug)print("SQL: $sql\n");
die();
}
if($debug)print("Inserted: $end $src $duration\n");
$rows++;
} else {
if($debug)print("Not unique: $end $src $duration\n");
}
}
fclose($handle);
if($debug)print("$rows imported\n");

?>