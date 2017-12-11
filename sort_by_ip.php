<?php
//Cookieless Web Counter by Lucio Marinelli
//Please see attached GNU GENERAL PUBLIC LICENSE version 3

require ("config.inc.php"); //include configuration file

//Function to detect bots
function is_bot($text) {
	$botkey=array("bot","bots", "spider","slurp","search","crawl","favicon","qwant");
	foreach ($botkey as $letter) {
		if (stripos($text,$letter) !== false) {
			return true;
		}
	}
	return false;
}

//get site id for <TITLE> & dump page, preventing injection
if ($_GET[action]=="dump" && is_numeric($_GET[id])) $siteid=$_GET[id];
?>

<html>
<head>
<title>Cookieless Web Counter - <?=$sitename[$siteid] ?></title>
</head>

<body>
<h1>Cookieless Web Counter <span style="font-size: 15px; font-style: italic"><a href="http://www.luciomarinelli.com" target="_external" style="text-decoration: none; color: black">by Lucio Marinelli</a></span></h1>
Modified by JF

<?php

//Detect language from HTTP_ACCEPT_LANGUAGE string
$language=($_SERVER[HTTP_ACCEPT_LANGUAGE]);
$lang=substr($language,0,2);

switch ($lang) {
case 'it': //ITALIAN LANGUAGE
	//pagina principale
	$site_label="Sito";
	$today_visits="Visite odierne";
	$today_visitors="Visitatori odierni";
	$yesterday_visits="Visite di ieri";
	$yesterday_visitors="Visitatori di ieri";
	$last_visits="Ultime visite";

	//dump page
	$back="Ritorna alla pagina principale";
	$timestamp_label="Data & ora";
	$php_self_label="Pagina visitata";
	$remote_host_label="Italy";
	$remote_addr_label="Indirizzo IP";
	$http_host_label="Italy";
	$request_uri_label="Italy";
	$http_referer_label="Pagina di provenienza";
	$http_user_agent_label="Browser del visitatore";

	//errori
	$mysql_server_error="Errore nella connessione con il server MySQL!";
	$db_connection_error1="Errore nella connessione al database ";
	$db_connection_error2="";
	$attack="Attacco rilevato!";
	break;

default: //DEFAULT ENGLISH LANGUAGE
	//main page
	$site_label="Site";
	$today_visits="Today's visits";
	$today_visitors="Today's visitors";
	$yesterday_visits="Yesterday's visits";
	$yesterday_visitors="Yesterday's visitors";
	$last_visits="Last visits";

	//dump page
	$back="Back to the main page";
	$timestamp_label="Timestamp";
	$php_self_label="php_self";
	$remote_host_label="Remote Host";
	$remote_addr_label="IP";
	$http_host_label="http_host";
	$request_uri_label="URL";
	$http_referer_label="Referrer";
	$http_user_agent_label="User Agent";

	//errors
	$mysql_server_error="Error connecting to MySQL server!";
	$db_connection_error1="Error connecting to ";
	$db_connection_error2=" database!";
	$attack="Attack detected!";
	break;
}


//count the number of sites
$number_of_sites=count($sitename)+1;

//dump last visits
if ($_GET[action]=="dump" && $_GET[id]<$number_of_sites) {
	$mysqli = new mysqli($dbhost[$siteid],$dbuser[$siteid],$dbpass[$siteid],$dbname[$siteid]) or die ("$mysqli_connnect_error()");
	
	//show last 50-100-200-1000(n) records for the selected site

	echo "<form action = 'delete_check.php' method = 'POST'>";
	
	//get number of visits preventing injection
	if (is_numeric($_GET[n])) $n_vis=$_GET[n]+1;
	else die ("$attack");
	
	echo "<h2>$sitename[$siteid]</h2>";
	#echo "<h3>$last_visits ($_GET[n])</h3><table border='0px' style='font-size: 12px' width='100%'>";
	echo "<h3>$last_visits ($_GET[n])</h3><table border='0px' style='font-size: 12px'>";
	echo "<p><a href='cwc.php'>$back</a></p>";
	echo "<table style='border-spacing:5px;'>";
	echo "<tr style='background-color:#dcfcf9;text-align: left;'>
		<th>Id</th>
		<th>$timestamp_label</th>
		<th>$php_self_label</th>
		<th>$remote_host_label</th>
		<th>SORT BY<br />$remote_addr_label</th>
		<th>$request_uri_label</th>
		<th>$http_referer_label</th>
		<th>$http_user_agent_label</th>
		<th>Select to delete</th>
		<th>Count</th>
		</tr>";
	
	if($stmt = $mysqli->prepare
			("SELECT id,timestamp,php_self,remote_addr,http_host,request_uri,http_referer,http_user_agent FROM $tablename[$siteid] ORDER BY remote_addr , timestamp;"))
	{
		//$stmt->bind_param("s",$tablename[$siteid]);
		$stmt->execute();
		//$stmt->store_result();
		$numrows = $stmt->num_rows;
		$stmt->bind_result($id,$timestamp,$php_self,$remote_addr,$http_host,$request_uri,$http_referer,$http_user_agent);

		$i=1;
		$count2=$i;
		while($stmt->fetch())
		{
			$a = $remote_addr;
			if ( $a <> $b && $i>1)
			{
				$iminus=$i-1;
				echo "<tr><td>.</td><td>.</td><td>.</td><td>.</td><td>"."count = ".$count2."</td><td>.</td><td>.</td><td>.</td><td>.</td><td>.</tr>";
			}
			
			if (((($i)%2)==0)) {$stile="style= 'background-color: #F6CEEC;'";} //Change background
			if (((($i)%2)==0) && is_bot($http_user_agent)) {$stile="style= 'background-color: #F6CEEC;color: magenta'";} //Change background and text
			if (((($i)%2)>0)) {$stile="style= 'background-color: #F2F5A9;'";} //Change background
			if (((($i)%2)>0) && is_bot($http_user_agent)) {$stile="style= 'background-color: #F2F5A9;color: magenta'";} //Change background and text
			$hostname = gethostbyaddr($remote_addr);
			echo "<tr $stile>
			<td>$id</td>
			<td>$timestamp</td>
			<td>$php_self</td>
			<td>$hostname</td>
			<td><a href=\"http://whatismyipaddress.com/ip/$remote_addr\" target=\"_blank\">$remote_addr</a></td>
			<td style='word-break: break-all; word-wrap: break-word;'>$http_host$request_uri</td>
			<td style='word-break: break-all; word-wrap: break-word;'>$http_referer</td>
			<td style='word-break: break-all; word-wrap: break-word;'>$http_user_agent</td>";
			echo "<td><input type='checkbox' name='cbox[$id]'/></td>";
			echo "<td>".$i."</td></tr>";
			$i=$i+1;
			$b = $remote_addr;
			$count2= $i -$iminus -1;
		}
		$iplus=$iminus+1;
		echo "<tr><td>.</td><td>.</td><td>.</td><td>.</td><td>"."count = ".$count2."</td><td>.</td><td>.</td><td>.</td><td>.</td><td>.</tr>";
		$stmt->free_result();
		$stmt->close();
	}
	$mysqli->close();
	
	echo "</table>";
	echo "<p align='right'><input type='submit' name='rubber' value='Delete checked rows'/></p>";
	echo "</form>";
	echo "<form action = 'delete_timestamp.php' method = 'POST'>";
	echo "<p align='right'>or insert a part of   <input type='text' name='timestamp' value='timestamp' maxlength='18' size='18'>";
	echo " and <input type='submit' name='timerubber' value='delete rows'></p>";
	echo "</form>";
	echo "<form action = 'delete_ip.php' method = 'POST'>";
	echo "<p align='right'>or insert a part of   <input type='text' name='ip' value='remote_address' maxlength='15' size='15'>";
	echo " and <input type='submit' name='iprubber' value='delete rows'></p>";
	echo "</form>";
	echo "<form action = 'delete_useragent.php' method = 'POST'>";
	echo "<p align='right'>or insert a part of   <input type='text' name='useragent' value='http_user_agent' maxlength='15' size='15'>";
	echo " and <input type='submit' name='agentrubber' value='delete rows'></p>";
	echo "</form>";
	echo "<p align='right'>Inserted or pasted text must be left-aligned.</p>";
	echo "<div style = 'text-align: center'><a href='cwc.php'>$back</a></div>";
	echo "<br />";
	echo "<div style = 'text-align: center'><a href='sort_by_uri.php'>Sort list by URL</a></div>";
}
//
//
//  
//  
//show the main page
else {
	echo "<table cellpadding='5' style='border-spacing:5px;'>";
	echo "<tr style=\"text-align: left; background-color: #dcfcf9\"><th>$site_label</th><th>$today_visits</th><th>$today_visitors</th><th>$yesterday_visits</th><th>$yesterday_visitors</th><th>$last_visits</th></tr>";

	for ($siteid=1; $siteid<$number_of_sites; $siteid++)
	{
		$mysqli = new mysqli($dbhost[$siteid],$dbuser[$siteid],$dbpass[$siteid],$dbname[$siteid]) or die ("$mysqli_connnect_error()");

		//count today's visits
		if ($stmt = $mysqli->prepare("SELECT timestamp FROM $tablename[$siteid] WHERE DATE(timestamp)=CURDATE()"))
		{
			$stmt->execute();
			$stmt->store_result();
			$visite_odierne = $stmt->num_rows;
			$stmt->bind_result($timestamp);
			$stmt->free_result();
			$stmt->close();
		}

		//count today's visitors
		if ($stmt = $mysqli->prepare("SELECT remote_addr FROM $tablename[$siteid] WHERE DATE(timestamp)=CURDATE()GROUP BY remote_addr"))
		{
			$stmt->execute();
			$stmt->store_result();
			$visitatori_odierni = $stmt->num_rows;
			$stmt->bind_result($remote_addr);
			$stmt->free_result();
			$stmt->close();
		}		

		//count yesterday's visits
		if ($stmt = $mysqli->prepare("SELECT timestamp FROM $tablename[$siteid] WHERE DATE(timestamp)=CURDATE()- INTERVAL 1 DAY"))
		{
			$stmt->execute();
			$stmt->store_result();
			$visite_ieri = $stmt->num_rows;
			$stmt->bind_result($timestamp);
			$stmt->free_result();
			$stmt->close();
		}

		//count yesterday's visitors
		if ($stmt = $mysqli->prepare("SELECT remote_addr FROM $tablename[$siteid] WHERE DATE(timestamp)=CURDATE()- INTERVAL 1 DAY GROUP BY remote_addr"))
		{
			$stmt->execute();
			$stmt->store_result();
			$visitatori_ieri = $stmt->num_rows;
			$stmt->bind_result($remote_addr);
			$stmt->free_result();
			$stmt->close();
		}

		echo "<tr style='background-color:#F2F5A9;'>
			<td>$sitename[$siteid]</td>
			<td>$visite_odierne</td>
			<td>$visitatori_odierni</td>
			<td>$visite_ieri</td>
			<td>$visitatori_ieri</td>
			<td><a href=\"$_SERVER[PHP_SELF]?id=$siteid&amp;action=dump&amp;n=50\">50</a>&nbsp;&nbsp;
				<a href=\"$_SERVER[PHP_SELF]?id=$siteid&amp;action=dump&amp;n=100\">100</a>&nbsp;&nbsp;
				<a href=\"$_SERVER[PHP_SELF]?id=$siteid&amp;action=dump&amp;n=200\">200</a>&nbsp;&nbsp;
				<a href=\"$_SERVER[PHP_SELF]?id=$siteid&amp;action=dump&amp;n=1000\">1000</a></td></tr>";		
	}
	echo "</table>";
}

?>

<div style="font-family: sans serif; font-size: 15px; margin-top: 1em; text-align: center">v. 20150324 (L.Marinelli), modified by JF</div>
</body>
</html>

