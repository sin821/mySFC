<?php
session_start([
    'cookie_lifetime' => 86400,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');
$cadet_id = $_SESSION['cadet'];

$crosswind = $_POST['crosswind'];
if($_POST['rwy0624']=='on') {
	$rwy0624 = 1;
}
else {
	$rwy0624 = 0;
}
if($_POST['rwy12']=='on') {
	$rwy12 = 1;
}
else {
	$rwy12 = 0;
}
if($_POST['rwy30']=='on') {
	$rwy30 = 1;
}
else {
	$rwy30 = 0;
}
$signedCCT = $_POST['signedCCT'];
$signedGH = $_POST['signedGH'];
$signedNav = $_POST['signedNav'];

$query = "UPDATE tbl_cadets SET cadet_crosswind = '$crosswind', cadet_rwy0624 ='$rwy0624', cadet_rwy12 = '$rwy12', cadet_rwy30 = '$rwy30', cadet_signedCCT = '$signedCCT', cadet_signedGH = '$signedGH', cadet_signedNav = '$signedNav' WHERE cadet_id = '$cadet_id'";
$result = mysqli_query($link,$query);

if($result) {
    $msg = "You have successfully updated your details.";
	header('location: ../user_options/update_details.php?status=success&msg='.$msg);
}
else {
	$msg = "We were unable to update your details. Kindly contact Jerome.";
	header('location: ../user_options/update_details.php?status=failed&msg='.$msg);
}
?>