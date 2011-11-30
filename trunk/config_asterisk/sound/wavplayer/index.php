<?
$dirHome = getcwd();
//$dirStart = '/home/asterisk/20110912';
$dirStart = '/home/samba/records';
$dirStartWeb = '/wavplayer/';
$dirGet = ($_GET['dir']!='') ? addslashes($_GET['dir']) : '/';
$dirCurrent = $dirStart."/".$dirGet;
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Запись звонков</title>
<meta name="keywords" content="">
<meta name="description" content="">
<link rel="stylesheet" href="js/DefaultCh.css" type="text/css">
<script type="text/javascript" src="js/mootools-core-1.4.0-full-compat-yc.js"></script>
<script type="text/javascript" src="js/mootools-more-1.4.0.1.js"></script>
<script type="text/javascript" src="js/common.js"></script>

<!--[if lt IE 7]><![if gte IE 5.5]>
<script type="text/javascript" src="js/fixpng.js"></script>
<style rel="stylesheet" type="text/css">
.iePNG, IMG { filter:expression(fixPNG(this)); }
.iePNG A { position: relative; }
body {width: expression(document.body.clientWidth > 1021 ? "100%": "1021px")}
#page {height:1000px}
</style>
<![endif]><![endif]-->

<script>
function getPlayer(pid) {
	var obj = document.getElementById(pid);
	if (obj.doPlay) return obj;
	for(i=0; i<obj.childNodes.length; i++) {
		var child = obj.childNodes[i];
		if (child.tagName == "EMBED") return child;
	}
}
function doPlay(fname) {
	var player = getPlayer('haxe');
	player.doPlay(fname);
}
function doStop() {
	var player = getPlayer('haxe');
	player.doStop();
}
function setVolume(v) {
	var player = getPlayer('haxe');
	player.setVolume(v);
}
function setPan(p) {
	var player = getPlayer('haxe');
	player.setPan(p);
}
var SoundLen = 0;
var SoundPos = 0;
var Last = undefined;
var State = "STOPPED";
var Timer = undefined;
function getPerc(a, b) {
	return ((b==0?0.0:a/b)*100).toFixed(2);
}
function FileLoad(bytesLoad, bytesTotal) {
	document.getElementById('InfoFile').innerHTML = "Loaded "+bytesLoad+"/"+bytesTotal+" bytes ("+getPerc(BytesLoad,BytesTotal)+"%)";
}
function SoundLoad(secLoad, secTotal) {
	document.getElementById('InfoSound').innerHTML = "Available "+secLoad.toFixed(2)+"/"+secTotal.toFixed(2)+" seconds ("+getPerc(secLoad,secTotal)+"%)";
	SoundLen = secTotal;
}
var InfoState = undefined;
function Inform() {
	if (Last != undefined) {
		var now = new Date();
		var interval = (now.getTime()-Last.getTime())/1000;
		SoundPos += interval;
		Last = now;
	}
	InfoState.innerHTML = State + "("+SoundPos.toFixed(2)+"/"+SoundLen.toFixed(2)+") sec ("+getPerc(SoundPos,SoundLen)+"%)";
}
function SoundState(state, position) {
	if (position != undefined) SoundPos = position;
	if (State != "PLAYING" && state=="PLAYING") {
		Last = new Date();
		Timer = setInterval(Inform, 100);
		Inform();
	} else
	if (State == "PLAYING" && state!="PLAYING") {
		clearInterval(Timer);
		Timer = undefined;
		Inform();
	}
	State = state;
	Inform();
}
function init() {
	var player = getPlayer('haxe');
	if (!player || !player.attachHandler) setTimeout(init, 100); // Wait for load
	else {
		player.attachHandler("progress", "FileLoad");
		player.attachHandler("PLAYER_LOAD", "SoundLoad");
		player.attachHandler("PLAYER_BUFFERING", "SoundState", "BUFFERING");
		player.attachHandler("PLAYER_PLAYING", "SoundState", "PLAYING");
		player.attachHandler("PLAYER_STOPPED", "SoundState", "STOPPED");
		player.attachHandler("PLAYER_PAUSED", "SoundState", "PAUSED");
		InfoState = document.getElementById('InfoState')
		Inform();
	}
}
</script>
</head>
<body bgcolor="#dddddd" onload="init()">

<div id="topfix">
<div id="topfixInn">

<div id="InfoFile"></div>
<div id="InfoSound"></div>
<div id="InfoState"></div>
<object    classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
		width="300"
	height="20"
	id="haxe"
	align="middle">
<param name="movie" value="wavplayer.swf?gui=full&h=20&w=300&sound=test-vf-44100.au&"/>
<param name="allowScriptAccess" value="always" />
<param name="quality" value="high" />
<param name="scale" value="noscale" />
<param name="salign" value="lt" />
<param name="bgcolor" value="#dddddd"/>
<embed src="wavplayer.swf?gui=full&h=20&w=300&sound=test-vf-44100.au&"
	   bgcolor="#dddddd"
	   width="300"
	   height="20"
	   name="haxe"
	   quality="high"
	   align="middle"
	   scale="noscale"
	   allowScriptAccess="always"
	   type="application/x-shockwave-flash"
	   pluginspage="http://www.macromedia.com/go/getflashplayer"
/>
</object>

<div id="topfixsets">
Volume: <a href="javascript:setVolume(5.0)">500%</a>
<a href="javascript:setVolume(2.0)">200%</a>
<a href="javascript:setVolume(1.0)">100%</a>
<a href="javascript:setVolume(0.5)">50%</a>
<a href="javascript:setVolume(0.1)">10%</a>
<a href="javascript:setVolume(0.0)">0% (mute)</a>
<br /><br />
Balance: <a href="javascript:setPan(-1.0)">Left</a>
<a href="javascript:setPan(0.0)">Center</a>
<a href="javascript:setPan(1.0)">Right</a>
</div>

</div>
</div>


<div id="toptext">
<h1>Телефонные вызовы<?=($dirGet!="/")?" ".preg_replace("/(\d{4})(\d{2})/","$2 . $1",$dirGet):""?></h1>

<div class="callsItems">
<?
$handle = opendir ($dirCurrent);
$funcd = array();
$funcf = array();

//echo $dirCurrent."<br><br>";

while($file = readdir($handle)) {
	$filename = $dirCurrent."/".$file;
	if (is_dir($filename) && $file!='.' && $file!='..') $funcd[] = $file;
	elseif (is_file($filename)) $funcf[] = $file;
}
if (count($funcd)!=0) sort($funcd);
if (count($funcf)!=0) sort($funcf);

if ($dirGet!="/"):
?>
<div class="callsItem">
<a href="<?=$dirStartWeb?>">..</a>
</div>
<?
endif;

foreach ($funcd as $key=>$value):
?>
<div class="callsItem">
<a href="?dir=<?=$value?>"><?=preg_replace("/(\d{4})(\d{2})/","$2 . $1",$value)?></a>
</div>
<?
endforeach;
foreach ($funcf as $key=>$value):
?>
<div class="callsItem">
<a href="javascript:doPlay('<?=str_replace("/home/samba","",$dirCurrent)."/".$value?>')"><?=$value?></a>
</div>
<?endforeach?>
</div>

</div>
</body>
</html>

