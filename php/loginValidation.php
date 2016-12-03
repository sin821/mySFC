<?php
session_start([
    'cookie_lifetime' => 2592000, //lasts 30 days
    'read_and_close'  => false,
]);
include('db_conn.php');

$email = strtolower($_POST['loginEmail']);
$pass = crypt($_POST['loginPassword'], 'SingaporeAirlines');

//get details from database
$query = "SELECT * FROM tbl_login JOIN tbl_cadets ON login_cadet=cadet_id JOIN tbl_syllabus ON cadet_syllabus=syllabus_id WHERE LOWER(login_email)='$email' LIMIT 1";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
	$db_email = strtolower($row['login_email']);
	$db_pass = $row['login_password'];
	$db_cadet = $row['login_cadet'];
	$db_role = $row['login_role'];
	$db_name = $row['cadet_name'];
	$db_syllabus = $row['syllabus_code'];
}

//check if login details match database details
if($db_email==$email&&$db_pass==$pass) {
	//credentials match
	$_SESSION['cadet'] = $db_cadet;
	$_SESSION['role'] = $db_role;
	$_SESSION['name'] = $db_name;
	$_SESSION['syllabus'] = $db_syllabus;
	session_write_close();
	$msg = "Login successful!";
	header('location: ../index.php?status=success&msg='.$msg);
}
else {
	$msg = "Your login details are invalid please try again or contact the administrator if this is a mistake.";
	header('location: ../index.php?status=failed&msg='.$msg);
}
?>