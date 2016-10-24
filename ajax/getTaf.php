<?php
include('../php/db_conn.php');

$taf_xml = simplexml_load_file('https://www.aviationweather.gov/adds/dataserver_current/httpparam?dataSource=tafs&requestType=retrieve&format=xml&hoursBeforeNow=3&timeType=issue&mostRecent=true&stationString=YPJT');
$taf = $taf_xml->data->TAF->raw_text;

$date = date('Y-m-d', strtotime('now'));

if($taf==null) {
  $query = "SELECT * FROM tbl_taf WHERE taf_date='$date' ORDER BY taf_date DESC LIMIT 1";
  $result = mysqli_query($link, $query);
  while($row = mysqli_fetch_array($result)) {
    $taf = $row['taf_raw'];
    if($taf==NULL||$taf=='') {
      $taf = "No online TAF available.";
    }
    else {
      $taf = $row['taf_raw'];
    }
  }
}
else {
  $query = "REPLACE INTO tbl_taf (taf_date, taf_raw) VALUES ('$date', '$taf')";
  mysqli_query($link, $query);
}

echo $taf;
?>