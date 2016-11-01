<?php
session_start([
    'cookie_lifetime' => 86400,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');

$request_id = $_GET['id'];

echo $query = "UPDATE tbl_requests SET request_done='1' WHERE request_id='$request_id'";
$result = mysqli_query($link, $query);

if($result) {
	$msg = "You have updated the request table. Thank you!";
	header('location: ../planning/planner_dashboard.php?status=success&msg='.$msg);
}
else {
	$msg = "Unable to update request status, please try again or contact a planner directly.";
	header('location: ../planning/planner_dashboard.php?status=failed&msg='.$msg);
}

?>