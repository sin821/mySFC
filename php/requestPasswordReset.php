<?php
// The message
$user = $_GET['user'];

// Send
if(mail('jer821@gmail.com', 'mySFC request for password reset', $user)) {
	$msg = "Your request for a password reset has been sent. Please give up to 3 working days for the request to be carried out.";
	header('location: ../index.php?status=success&msg='.$msg);
}
else {
	$msg = "Your request could not be sent, please try again or contact me directly.";
	header('location: ../index.php?status=failed&msg='.$msg);
}
?>