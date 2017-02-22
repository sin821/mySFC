<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');

$instructor_id = $_POST['instId'];
$instructor_name = addslashes($_POST['instName']);
$instructor_initials = addslashes($_POST['instOpsname']);
$instructor_weight = $_POST['instWeight'];

$query = "UPDATE tbl_instructors SET instructor_name='$instructor_name', instructor_initials='$instructor_initials', instructor_weight='$instructor_weight' WHERE instructor_id='$instructor_id'";
$result = mysqli_query($link, $query);

if($result) {
	$msg = "You have updated the information for ".$instructor_initials.". Thank you!";
	header('location: ../planning/instructor_list.php?status=success&msg='.$msg);
}
else {
	$msg = "Unable to update instructor's details, please try again or contact the administrator directly.";
	header('location: ../planning/instructor_list.php?status=failed&msg='.$msg);
}

?>