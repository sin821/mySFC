<?php
include('db_conn.php');

$errorFlag = FALSE;

$input = $_POST['flightList'];

if (preg_match("#^\d{2}/\d{2}/\d{4}#sm", $input, $date)) {

    $date = date_create_from_format('d/m/Y', $date[0]);
    $date = date_format($date, 'Y-m-d');

    $query = "SELECT EXISTS(SELECT 1 FROM tbl_tms2flightlist WHERE tms2flightlist_date='$date') AS total";
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_array($result)) {
        if($row['total']!=0) {
            //if there is existing data for this date, delete all data for this date before updating.
            $query = "DELETE FROM tbl_tms2flightlist WHERE tms2flightlist_date='$date'";
            mysqli_query($link, $query);
        }
        //do regex check for required information
        preg_match_all("#^\d+\t (PostFlight|Cancelled)\t([ABJ]\d{3}S? ?.?[1A-Z]?|[ABJ]IPTS?\d{3} ?[A-Z]?|M2-\d{3}[AFS].?1?-?S? ?[A-Z]?|M\d{3}[AFS].?1?-?S? ?[A-Z]?)\t (VH-\w{3}|F141-G|F242-G)\t (\d{2}:\d{2})\t (\w+ ?-?'?/?\w* ?-?'?/?\w* ?-?'?/?\w* ?-?'?/?\w* ?-?'?/?\w* ?-?'?/?\w*)\t (\w+ ?-?'?/?\w* ?-?'?/?\w* ?-?'?/?\w* ?-?'?/?\w* ?-?'?/?\w* ?-?'?/?\w*)?#sm", $input, $matches);

        $arrlength = count($matches[1]);

        //insert each piece of data into the database
        for($x = 0; $x < $arrlength; $x++) {
            $status = $matches[1][$x];
            $sortie = $matches[2][$x];
            //special correction for 328S which has 3 parts to it
            if($sortie=='B328S.1') {
                $sortie = 'B328S';
            }
            if($sortie=='A328S.1') {
                $sortie = 'A328S';
            }
            if($sortie=='J328S.1') {
                $sortie = 'J328S';
            }
            //special correction for M163A-S which has 3 parts to it
            if($sortie=='M2-163A.1-S') {
                $sortie = 'M2-163A-S';
            }
            if($sortie=='M163A.1-S') {
                $sortie = 'M163A-S';
            }
            $aircraft = $matches[3][$x];
            $etd = $matches[4][$x];
            $pilot1 = addslashes($matches[5][$x]);
            $pilot2 = addslashes($matches[6][$x]);
            $query = "INSERT INTO tbl_tms2flightlist(tms2flightlist_status, tms2flightlist_sortie, tms2flightlist_aircraft, tms2flightlist_etd, tms2flightlist_pilot1, tms2flightlist_pilot2, tms2flightlist_date) 
            VALUES ('$status','$sortie','$aircraft','$etd','$pilot1','$pilot2','$date')";
            if(mysqli_query($link, $query)) {
                $errorFlag = FALSE;
            }
            else{
                $msg = "Seems like a database error. Please try again or contact the administrator.";
                $errorFlag = TRUE;
            }
        }
    }
} else {
    $msg = "A valid date was not found, please check that you have copied the correct portion of TMS2 and try again.";
    $errorFlag = TRUE;
}

if($errorFlag==FALSE) {
    $msg = $arrlength." entries have been updated. Please check if it matches the number of PostFlight entries in TMS2";
	header('location: ../planning/update_TMS2_flightlist.php?status=success&msg='.$msg);
}
else {
	header('location: ../planning/update_TMS2_flightlist.php?status=failed&msg='.$msg);
}
?>