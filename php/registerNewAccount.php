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
$message = "$cadet_name has registered with the following email ($email) with a weight of ($weight) kg and s/he is in course ($course)";

$query = "INSERT INTO tbl_login(login_email, login_cadet, login_password) VALUES ('$email','$cadet','$password')";
$result = mysqli_query($link, $query);

// Send mail
if($result) {
	if(mail('jer821@gmail.com', 'mySFC request for new account', $message)) {
		$msg = "You may log in with your new account. Please give up to 3 working days for your weight and course details to be updated.";
		header('location: ../index.php?status=success&msg='.$msg);
	}
	else {
		$msg = "Your registration could not be completed, please try again or contact me directly.";
		header('location: ../index.php?status=failed&msg='.$msg);
	}
}
else {
	$msg = "Your registration could not be completed, please try again or contact me directly.";
	header('location: ../index.php?status=failed&msg='.$msg);
}
?>