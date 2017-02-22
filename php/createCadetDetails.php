<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');

$cadet_name = addslashes($_POST['cadetName']);
$cadet_opsname = addslashes($_POST['cadetOpsname']);
$cadet_course = $_POST['cadetCourse'];
$cadet_instructor = $_POST['cadetInstructor'];
$cadet_syllabus = $_POST['cadetSyllabus'];

$query = "INSERT INTO tbl_cadets (cadet_name, cadet_opsname, cadet_course, cadet_instructor, cadet_syllabus) VALUES ('$cadet_name', '$cadet_opsname', '$cadet_course', '$cadet_instructor', '$cadet_syllabus')";
$result = mysqli_query($link, $query);

if($result) {
	$msg = "You have created a new database record for ".$cadet_opsname.". Thank you!";
	header('location: ../planning/cadet_list.php?status=success&msg='.$msg);
}
else {
	$msg = "Unable to add cadet's details, please try again or contact the administrator directly.";
	header('location: ../planning/cadet_list.php?status=failed&msg='.$msg);
}

?>