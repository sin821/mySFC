<?php
/*$hostname = "localhost";
$username = "root";
$password = "root";
$database = "db_sfc";

if($link = mysqli_connect($hostname, $username, $password, $database)) {
}
else {*/
  	$hostname = "sydneydbinstance.cg29lezslm4j.ap-southeast-2.rds.amazonaws.com";
	$username = "sin821";
	$password = "j1551990";
	$database = "db_sfc";

	if($link = mysqli_connect($hostname, $username, $password, $database)) {
	}
	else {
	  echo "Cannot connect to DB, contact Jerome...";
	}
/*}*/

?>