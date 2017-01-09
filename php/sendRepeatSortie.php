<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');

$cadet_id = $_SESSION['cadet'];
$sortie = $_POST['sortie'];

$query = "INSERT INTO tbl_repeatedsorties (repeatedsortie_sortie, repeatedsortie_cadet) VALUES ('$sortie', '$cadet_id')";
$result = mysqli_query($link, $query);

if($result) {
	$msg = "You have submitted your repeated sortie to the planners. Thank you!";
	header('location: ../flight/planning_request.php?status=success&msg='.$msg);
}
else {
	$msg = "Your repeated sortie could not be submitted, please try again or contact a planner directly.";
	header('location: ../flight/planning_request.php?status=failed&msg='.$msg);
}
?>