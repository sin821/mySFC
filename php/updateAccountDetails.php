<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');
$cadet_id = $_SESSION['cadet'];

$weight = $_POST['weight'];
$date = $_POST['date'];
$repeatedsorties = $_POST['repeatedsorties'];

$query = "UPDATE tbl_cadets SET cadet_weight = '$weight', cadet_startOfCourse ='$date', cadet_repeatedsorties ='$repeatedsorties' WHERE cadet_id = '$cadet_id'";
$result = mysqli_query($link,$query);

if($result) {
    $msg = "You have successfully updated your details.";
	header('location: ../user_options/update_details.php?status=success&msg='.$msg);
}
else {
	$msg = "We were unable to update your account details. Please try again or contact an administrator.";
	header('location: ../user_options/update_details.php?status=failed&msg='.$msg);
}
?>