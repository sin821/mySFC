<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');

$cadet_id = $_POST['cadetId'];
$cadet_name = addslashes($_POST['cadetName']);
$cadet_opsname = addslashes($_POST['cadetOpsname']);
$cadet_course = $_POST['cadetCourse'];
$cadet_instructor = $_POST['cadetInstructor'];
$cadet_syllabus = $_POST['cadetSyllabus'];

$query = "UPDATE tbl_cadets SET cadet_name='$cadet_name', cadet_opsname='$cadet_opsname', cadet_course='$cadet_course', cadet_instructor='$cadet_instructor', cadet_syllabus='$cadet_syllabus' WHERE cadet_id='$cadet_id'";
$result = mysqli_query($link, $query);

if($result) {
	$msg = "You have updated the information for ".$cadet_opsname.". Thank you!";
	header('location: ../planning/cadet_list.php?status=success&msg='.$msg);
}
else {
	$msg = "Unable to update cadet's details, please try again or contact Jerome directly.";
	header('location: ../planning/cadet_list.php?status=failed&msg='.$msg);
}

?>