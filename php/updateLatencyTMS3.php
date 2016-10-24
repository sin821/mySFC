<?php
include('db_conn.php');

$errorFlag = FALSE;

$input = $_POST['flightList'];

//do regex check for required information
preg_match_all("#^(VH-\w{3}|F141G|F242G)\t([AB] \d{3}S? ?.?[1A-Z]?|[AB]IPTS?\d{3} ?[A-Z]?|M2-\d{3}[AFS]-?S? ?[A-Z]?|M\d{3}[AFS] ?[A-Z]?)\t(\d{2}/\d{2}/\d{4}) (\d{2}:\d{2})\t\d{2}/\d{2}/\d{4} (\d{2}:\d{2})\t(\w+ ?-?\w* ?-?\w* ?-?\w* ?-?\w* ?-?\w* ?-?\w*)\t(\w+ ?-?\w* ?-?\w* ?-?\w* ?-?\w* ?-?\w* ?-?\w*)\t(PostFlight|Cancelled)#sm", $input, $matches);

$date = date_create_from_format('d/m/Y', $matches[3][0]);
$date = date_format($date, 'Y-m-d');

$query = "SELECT EXISTS(SELECT 1 FROM tbl_tms3flightlist WHERE tms3flightlist_date='$date') AS total";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
    if($row['total']!=0) {
        //if there is existing data for this date, delete all data for this date before updating.
        $query = "DELETE FROM tbl_tms3flightlist WHERE tms3flightlist_date='$date'";
        mysqli_query($link, $query);
    }
}

$arrlength = count($matches[1]);

//insert each piece of data into the database
for($x = 0; $x < $arrlength; $x++) {
    $status = $matches[8][$x];
    $sortie = $matches[2][$x];
    //special correction for 328S which has 3 parts to it
    if($sortie=='B 328S.1') {
        $sortie = 'B 328S';
    }
    if($sortie=='A 328S.1') {
        $sortie = 'A 328S';
    }
    $aircraft = $matches[1][$x];
    $etd = $matches[4][$x];
    $pilot1 = $matches[7][$x];
    $pilot2 = $matches[6][$x];
    if($pilot1==''||$pilot1==NULL) {
        $pilot1 = $pilot2;
        $pilot2 = '';
    }
    $query = "INSERT INTO tbl_tms3flightlist(tms3flightlist_status, tms3flightlist_sortie, tms3flightlist_aircraft, tms3flightlist_etd, tms3flightlist_pilot1, tms3flightlist_pilot2, tms3flightlist_date) 
    VALUES ('$status','$sortie','$aircraft','$etd','$pilot1','$pilot2','$date')";
    if(mysqli_query($link, $query)) {
        $errorFlag = FALSE;
    }
    else{
        $msg = "Seems like a database error. Please try again or contact the administrator.";
        $errorFlag = TRUE;
    }
}

if($errorFlag==FALSE) {
    $msg = $arrlength." entries have been updated. Please check if it matches the number of PostFlight entries in TMS3";
	header('location: ../planning/update_TMS3_flightlist.php?status=success&msg='.$msg);
}
else {
	header('location: ../planning/update_TMS3_flightlist.php?status=failed&msg='.$msg);
}

?>