<?php
session_start([
    'cookie_lifetime' => 86400,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');

$errorFlag = FALSE;
$valid_date = FALSE;

$input = $_POST['flightList'];

//check if the user is using IE. Must include the date if user is using IE.
preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
if(count($matches)<2){
  preg_match('/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
}

if (count($matches)>1){
    //Then we're using IE
    echo $date[0] = date('d/m/Y', strtotime('now + 6 hours'));
    $valid_date = TRUE;
}
else {
    preg_match("#^\d{2}/\d{2}/\d{4}#sm", $input, $date);
    $valid_date = TRUE;
}

if ($valid_date) {

    $date = date_create_from_format('d/m/Y', $date[0]);
    $date = date_format($date, 'Y-m-d');
    $today = date('Y-m-d', strtotime('now +6 hours'));

    if($date==$today) {
        //clear the old info before starting
        $query = "DELETE FROM tbl_tms2currentplan WHERE 1";
        mysqli_query($link, $query);

        //do regex check for required information
        preg_match_all("#^\d+\t (PostFlight|Authorized|Current|Cancelled)\t([AB]\d{3}S? ?.?[1A-Z]?|[AB]IPTS?\d{3} ?[A-Z]?|M2-\d{3}[AFS]-?S? ?[A-Z]?)\t (VH-\w{3}|F141-G|F242-G)\t (\d{2}:\d{2})\t (\w+ ?-?/?\w* ?-?/?\w* ?-?/?\w* ?-?/?\w* ?-?/?\w* ?-?/?\w*)\t (\w+ ?-?/?\w* ?-?/?\w* ?-?/?\w* ?-?/?\w* ?-?/?\w* ?-?/?\w*)?#sm", $input, $matches);

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
            $aircraft = $matches[3][$x];
            $etd = $matches[4][$x];
            $pilot1 = $matches[5][$x];
            $pilot2 = $matches[6][$x];
            $query = "INSERT INTO tbl_tms2currentplan(flightlist_status, flightlist_sortie, flightlist_aircraft, flightlist_etd, flightlist_pilot1, flightlist_pilot2, flightlist_date) 
            VALUES ('$status','$sortie','$aircraft','$etd','$pilot1','$pilot2','$date')";
            if(mysqli_query($link, $query)) {
                //on successful update of current flightlist, generate list of dual candidates.
            }
            else {
                $msg = "Database error. Unable to update database. Contact administrator.";
                $errorFlag = TRUE;
            }
        }
    }
    else {
        $msg = "The plan is not a current plan, please select the correct plan and try again.";
        $errorFlag = TRUE;
    }
}

if($errorFlag==TRUE) {
    header('location: ../planning/generate_TMS2_plan.php?status=failed&msg='.$msg);
}
else {
    $msg = "You have successfully updated the plan for today.";
    header('location: ../planning/planner_dashboard.php?status=success&msg='.$msg);
}
?>