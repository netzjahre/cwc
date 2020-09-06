<html>
<head>
<title>Cookieless Web Counter - </title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
<meta name="robots" content="noindex"/>
<style>
</style>
</head>

<body>
<h1>Cookieless Web Counter <span style="font-size: 20px; font-style: italic"><a href="http://www.luciomarinelli.com" target="_external" style="text-decoration: none; color: black">by Lucio Marinelli</a></span></h1>
Modified by JF



<?php
require ("config.inc.php"); //include configuration file
$siteid=$id;
$id="0";
?>


<?php
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
		
		if(isset ($_POST[ip]))
			{
			$ipnum = htmlentities(stripslashes($_POST[ip]));
			$ipnumber = "%$ipnum%";
				if($stmt = $mysqli->prepare("DELETE FROM $tablename[$siteid] WHERE remote_addr LIKE ?"))
					{
					$stmt->bind_param("s", $ipnumber);
					$stmt->execute();
					$stmt->store_result();
					$rows_del = $stmt->affected_rows;
					$stmt->free_result();
					$stmt->close();
					}
			}
echo "<p>{$rows_del}  rows deleted</p>";
$mysqli->close();
		
echo "<div><a href='cwc.php'>Back zu main page</a></div>";
?>

<div style="font-family: sans serif; font-size: 15px; margin-top: 5em; text-align: left">v. 20150324 , modified by JF</div>

</body>
</html>

