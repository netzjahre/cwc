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
Adopted to own requirements by JF



<?php
	function is_bot($text) {
		$botkey=array("bot","bots","spider","crawl");
		foreach ($botkey as $letter) {
			if (stripos($text,$letter) !== false) {
				return true;
				}
			}
		return false;
		}
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
//https://phpdelusions.net/mysqli_examples/prepared_statement_with_in_clause
//https://www.sourcecodester.com/tutorials/php/6164/multiple-delete-data-using-phpmysql-and-pdo-query.html
	
// Create connection
$mysqli = new mysqli($dbhost[$siteid],$dbuser[$siteid],$dbpass[$siteid],$dbname[$siteid]) or die ("$db_connection_error1");

/*$entries_to_delete = array('amazon', 'bing', 'google', 'msn', 'yahoo');
$entries_to_delete_esc = mysqli_real_escape_string($entries_to_delete);
$delete_values = implode(',', $entries_to_delete_esc);
// Construct the SQL query
$sql="SELECT FROM $tablename[$siteid] WHERE http_user_agent IN ($delete_values)";
print_r($sql);*/

//delete entries

			$com = ".com";
			$com = "%$com%";
			$claude = "ClaudeBot";
			$claude = "%$claude%";
//stackoverflow.com/questions/43890814/android-sqlite-like-operator-with-or-statements
				if($stmt = $mysqli->prepare("DELETE FROM $tablename[$siteid] WHERE http_user_agent LIKE ? OR http_user_agent LIKE ?"))
					{
					$stmt->bind_param("ss", $com,$claude);
					$stmt->execute();
					$rows_del = $stmt->affected_rows;
					$stmt->close();
					}
echo "<p>{$rows_del}  rows deleted</p>";
$mysqli->close();
echo "<div><a href='cwc.php'>Back zu main page</a></div>";
?>

<div style="font-family: sans serif; font-size: 15px; margin-top: 5em; text-align: left">v. 20150324 , modified by JF to meet own requirements</div>

</body>
</html>

