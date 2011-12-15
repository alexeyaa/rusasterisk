<? 
$file="VERSION";
if(file_exists($file))
{
$fp = fopen($file, "r");
	while (!feof($fp)) 
	{
	 $zeile = fgets($fp,500);
	 if($zeile>"")echo $zeile;
	}
fclose($fp);
}else{echo "ERROR: kein File";}

?>
