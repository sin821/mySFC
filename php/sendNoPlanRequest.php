<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');
// The message
$cadet = $_SESSION['name'];
$cadet_id = $_SESSION['cadet'];
$startdate = date('Y-m-d', strtotime($_POST['startDate']));
$enddate = date('Y-m-d', strtotime($_POST['endDate']));
$remarks = $_POST['reason'];

$sanitised_message = addslashes($_POST['reason']);
$query = "INSERT INTO tbl_requests (request_type, request_noplanstart, request_noplanend, request_remarks, request_cadet) VALUES ('no-plan', '$startdate', '$enddate', '$sanitised_message', '$cadet_id')";
$result = mysqli_query($link, $query);

if($result) {
	$msg = "Your no-plan has been requested. Thank you!";
	header('location: ../flight/planning_request.php?status=success&msg='.$msg);
}
else {
	$msg = "Your no-plan request could not be sent, please try again or contact a planner directly.";
	header('location: ../flight/planning_request.php?status=failed&msg='.$msg);
}
?>