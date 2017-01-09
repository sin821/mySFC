<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');

$cadet_id = $_SESSION['cadet'];
$instruction_id = $_POST['id'];
$instruction = addslashes($_POST['instruction']);

$query = "REPLACE INTO tbl_plannerinstructions (plannerinstruction_id, plannerinstruction_content, plannerinstruction_creator) VALUES ('$instruction_id', '$instruction', $cadet_id)";
$result = mysqli_query($link, $query);

if($result) {
	$msg = "You have updated the planner instructions. Thank you!";
	header('location: ../planning/planner_dashboard.php?status=success&msg='.$msg);
}
else {
	$msg = "Unable to update instructions in database, please try again or contact a planner directly.";
	header('location: ../planning/planner_dashboard.php?status=failed&msg='.$msg);
}

?>