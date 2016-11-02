<?php
include('db_conn.php');

$email = strtolower($_POST['email']);
$course = strtolower($_POST['course']);
$weight = $_POST['weight'];

//check for a single entry
$query = "SELECT COUNT(login_email) AS total_count FROM tbl_login WHERE LOWER(login_email)=LOWER('$email')";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
	$account_exists = $row['total_count'];
}

if($account_exists) {

	//pull out database records
	$query = "SELECT cadet_weight, cadet_course FROM tbl_login JOIN tbl_cadets ON login_cadet=cadet_id WHERE LOWER(login_email)=LOWER('$email')";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		$cadet_weight = $row['cadet_weight'];
		$cadet_course = $row['cadet_course'];
	}

	//perform comparison with given data
	if($course==$cadet_course && $weight==$cadet_weight) {
		//probably authenticated, reset password
		
		//generate new random 8-character password
	    $newPass = bin2hex(openssl_random_pseudo_bytes(4));

	    $cryptedPass = crypt($newPass, 'SingaporeAirlines');
	    
	    //update database with new password
	    $query = "UPDATE tbl_login SET login_password='$cryptedPass' WHERE LOWER(login_email)=LOWER('$email')";
	    $result = mysqli_query($link, $query);

	    $message = "You are receiving this message because you have requested for your mySFC password to be reset. Your new password is ".$newPass." . If you did not authorise this, please contact a planner immediately.";

	    if($result) {
	    	if(mail($email, 'mySFC password reset', $message)) {
				$msg = "Your request for a password reset has been sent. You should receive an email with your new password, please change your password on your first login.";
				header('location: ../index.php?status=success&msg='.$msg);
			}
			else {
				$msg = "Your request for a password reset has been sent. We could not send you an email with your new password, please contact a planner.";
				header('location: ../index.php?status=failed&msg='.$msg);
			}
	    }
	    else {
			$msg = "There was an error updating the database. Please contact a planner.";
			header('location: ../index.php?status=failed&msg='.$msg);
		}
	}
	else {
		$msg = "You could not be authorised as the information given does not match our records. Please try again.";
		header('location: ../index.php?status=failed&msg='.$msg);
	}
}
else {
	$msg = "Your record could not be found, check your email again or create a new account if you have not done so.";
	header('location: ../index.php?status=failed&msg='.$msg);
}

?>