<?php
session_start([
    'cookie_lifetime' => 86400,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');

$instruction_id = $_GET['id'];

$query = "UPDATE tbl_plannerinstructions SET plannerinstruction_done='1' WHERE plannerinstruction_id='$instruction_id'";
$result = mysqli_query($link, $query);

if($result) {
	$msg = "You have updated the planner instructions. Thank you!";
	header('location: ../planning/planner_dashboard.php?status=success&msg='.$msg);
}
else {
	$msg = "Unable to update planner instructions, please try again or contact Jerome directly.";
	header('location: ../planning/planner_dashboard.php?status=failed&msg='.$msg);
}

?>