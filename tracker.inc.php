<?php
//Cookieless Web Counter - tracker code starts here
//Include this tracked in each page you want to count.


//CONFIGURATION

//MySQL database host
$dbhost="sql424.your-server.de";
//Database name
$dbname="netzjap_db0";
//Database user name
$dbuser="netzjap_0";
//Database password
$dbpass="VAA2euCJruBKQDV8";
//Table name (default is "contatore")
$tablename="contatore";

//-------CONFIGURATION ENDS HERE-----

$php_self=$_SERVER['PHP_SELF'];
$remote_addr=$_SERVER['REMOTE_ADDR'];
$http_host=$_SERVER['HTTP_HOST'];
$request_uri=$_SERVER['REQUEST_URI'];
$http_referer=$_SERVER['HTTP_REFERER'];
$http_user_agent=$_SERVER['HTTP_USER_AGENT'];


$mysqli = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
if ($mysqli->connect_errno) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
	}
$query = ("INSERT INTO $tablename (php_self,remote_addr,http_host,request_uri,http_referer,http_user_agent)
			VALUES ('$php_self','$remote_addr','$http_host','$request_uri','$http_referer','$http_user_agent')");
$mysqli->query($query);
//Cookieless Web Counter - tracker code ends here
?>

