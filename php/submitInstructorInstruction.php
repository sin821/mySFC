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
$instructor_initials = $_POST['instructor'];

$query = "SELECT instructor_id FROM tbl_instructors WHERE instructor_initials='$instructor_initials'";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
	$instructor_id = $row['instructor_id'];
}

$query = "REPLACE INTO tbl_instructorinstructions (instructorinstruction_id, instructorinstruction_content, instructorinstruction_instructor, instructorinstruction_creator) VALUES ('$instruction_id', '$instruction', '$instructor_id', $cadet_id)";
$result = mysqli_query($link, $query);

if($result) {
	$msg = "You have updated ".$instructor_initials."'s instructions. Thank you!";
	header('location: ../planning/planner_dashboard.php?status=success&msg='.$msg);
}
else {
	$msg = "Unable to update instructions in database, please try again or contact a planner directly.";
	header('location: ../planning/planner_dashboard.php?status=failed&msg='.$msg);
}

?>