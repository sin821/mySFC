<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');
$errorflag = FALSE;
$cadet_id = $_SESSION['cadet'];

if(isset($_POST['oldPassword'])) {
	$query = "SELECT login_password FROM tbl_login WHERE login_cadet = '$cadet_id'";
	$result = mysqli_query($link,$query);
	while($row = mysqli_fetch_array($result)){
		$login_password = $row['login_password'];
	}

	$oldPassword = crypt($_POST['oldPassword'], 'SingaporeAirlines');
	$newPassword = crypt($_POST['newPassword'], 'SingaporeAirlines');

	if($oldPassword == $login_password) {
		$query = "UPDATE tbl_login SET login_password = '$newPassword' WHERE login_cadet = '$cadet_id'";
		if($result = mysqli_query($link, $query)) {
			header('location: logout.php');
			die();
		}
		else {
			$errorflag = TRUE;
			$msg = "We were unable to update your password. Please try again or contact your administrator.";
		}
	}
	else {
		$errorflag = TRUE;
		$msg = "Your old password does not match the password in our database. Please try again.";
	}
}

if($errorflag) {
    header('location: ../user_options/change_password.php?status=failed&msg='.$msg);
}
?>