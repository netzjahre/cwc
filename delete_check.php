<html>
<head>
<title>Cookieless Web Counter - </title>
<style>
</style>
</head>

<body>
<h1>Cookieless Web Counter <span style="font-size: 20px; font-style: italic"><a href="http://www.luciomarinelli.com" target="_external" style="text-decoration: none; color: black">by Lucio Marinelli</a></span></h1>
Adopted to own requirements by JF



<?php
require ("config.inc.php"); //include configuration file
$siteid=$id;
echo "<p>siteid=$siteid</p>";
$id="text";

//Detect language from HTTP_ACCEPT_LANGUAGE string
$language=($_SERVER[HTTP_ACCEPT_LANGUAGE]);
$lang=substr($language,0,2);

switch ($lang) {
	case 'it': //ITALIAN LANGUAGE
	//errori
	$mysql_server_error="Errore nella connessione con il server MySQL!";
	$db_connection_error1="Errore nella connessione al database ";
	$db_connection_error2="";
	$attack="Attacco rilevato!";
	break;

	default: //DEFAULT ENGLISH LANGUAGE
	//errors
	$mysql_server_error="Error connecting to MySQL server!";
	$db_connection_error1="Error connecting to ";
	$db_connection_error2=" database!";
	$attack="Attack detected!";
	break;
	}


//connect to MySQL
$mysqli = new mysqli($dbhost[$siteid],$dbuser[$siteid],$dbpass[$siteid],$dbname[$siteid]) or die ("$db_connection_error1");
	
//delete entries
		if(isset ($_POST[cbox]) && is_array($_POST[cbox]))
			{
			$cbox = $_POST[cbox];
			//echo "<p>Rows with these IDs deleted--->"; print_r($cbox); echo "</p>";
			foreach($cbox as $key => $value)
				{
				if($stmt = $mysqli->prepare("DELETE FROM $tablename[$siteid] WHERE id = ?"))
					{
					$stmt->bind_param("i", $key);
					$stmt->execute();
					$stmt->store_result();
					$numrows = $stmt->num_rows;
					$stmt->free_result();
					$stmt->close();
					}
				}
				$count = count($_POST[cbox]);
				echo "<p>{$count} rows deleted</p>";
			}
$mysqli->close();			
echo "<div><a href='cwc.php'>Back zu main page</a></div>";
?>

<div style="font-family: sans serif; font-size: 15px; margin-top: 5em; text-align: left">v. 20150324 , modified by JF to meet own requirements</div>

</body>
</html>

