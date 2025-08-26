<?php
error_reporting(E_ALL);
$client_ip = $_SERVER['REMOTE_ADDR'];
$content = file_get_contents('IPaddresses.txt');
if (strpos($content, $client_ip) === false)
	{
    $handle = fopen("IPaddresses.txt","a");
    fputs($handle, $client_ip);
    #fputs($handle, "\n");
    fclose($handle);
	}

$fp = fopen("IPaddresses.txt","r");
if(!$fp)
	{echo "Datei kann nicht geoeffnet werden.";}
$field=file('IPaddresses.txt');
print_r($field);
foreach($field as $zeile)
	{
	if ($zeile = ("\n" OR "\r\n"))
		{
		$zeile = array_pop($field);
		}
	}
#print_r($field);
if  (count($field) > 4)
	{
	for ($i=0;$i <= (count($field)-3); $i++)
		{
		$row = array_pop($field);
		}
	}
print_r($field);	
fclose($fp);

$fp = fopen("IPaddresses.txt","w");
foreach($field as $zeile)
	{

		#fputs($fp, $zeile ."\r\n"); # Verbleibende Zeilen schreiben
		fputs($fp, $zeile); # Verbleibende Zeilen schreiben
		#fputs($fp, "\n");
	}
fclose($fp);
?>
