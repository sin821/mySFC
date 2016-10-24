<?php
session_start([
    'cookie_lifetime' => 86400,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');
// The message
$cadet = $_SESSION['name'];
$cadet_id = $_SESSION['cadet'];
$message = $_POST['message'];

$sanitised_message = addslashes($_POST['message']);
$query = "INSERT INTO tbl_requests (request_type, request_remarks, request_cadet) VALUES ('message', '$sanitised_message', '$cadet_id')";
$result = mysqli_query($link, $query);

$message = $cadet.' has the following planning request: '.$message;

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70, "\r\n");

$query = "SELECT login_email FROM tbl_login WHERE login_role = '1'";

// Send to all planners
if(mail('jer821@gmail.com', 'Planning Request', $message)) {
	$msg = "Your request has been sent. Thank you!";
	header('location: ../flight/planning_request.php?status=success&msg='.$msg);
}
else {
	$msg = "Your request could not be sent, please try again or contact a planner directly.";
	header('location: ../flight/planning_request.php?status=failed&msg='.$msg);
}
?>