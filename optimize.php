<?php
//include configuration file
include ("config.inc.php");
// Site-ID fest auf 1 setzen, da nur eine Site in config definiert ist
$siteid = 1;
/*if (is_numeric($_GET['id']))
	{
	$siteid=$_GET['id'];
	$siteid=htmlentities($siteid,ENT_QUOTES);
	}else{
	$siteid=0;
	echo "Siteid  ".$siteid."<hr>";
	}*/
?>
<html>
<head>
<title>Cookieless Web Counter - Database<?=$sitename[$siteid] ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
<meta name="robots" content="noindex"/>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<?php
	$mysqli = new mysqli($dbhost[$siteid],$dbuser[$siteid],$dbpass[$siteid],$dbname[$siteid]) or die ("Connection error");
	
	// Prepared statement für sicherere SQL-Ausführung
	$stmt = $mysqli->prepare("OPTIMIZE TABLE " . $mysqli->real_escape_string($tablename[$siteid]));
	$stmt->execute();
	$stmt->close();
	
	echo "ready";
?>
</body>
</html>