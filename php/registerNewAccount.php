<?php
include('db_conn.php');
$cadet = $_POST['cadet'];
$email = strtolower($_POST['email']);
$password = crypt($_POST['password'], 'SingaporeAirlines');
$weight = $_POST['weight'];
$course = $_POST['course'];

$query = "SELECT cadet_name FROM tbl_cadet WHERE cadet_id='$cadet'";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
	$cadet_name = $row['cadet_name'];
}

$query = "UPDATE tbl_cadets SET cadet_weight='$weight' WHERE cadet_id='$cadet'";
$result = mysqli_query($link, $query);

$query = "INSERT INTO tbl_login(login_email, login_cadet, login_password) VALUES ('$email','$cadet','$password')";
$result = mysqli_query($link, $query);

if($result) {
	$msg = "You may log in with your new account.";
	header('location: ../index.php?status=success&msg='.$msg);
}
else {
	$msg = "Your registration could not be completed, please try again or contact a planner directly.";
	header('location: ../index.php?status=failed&msg='.$msg);
}
?>