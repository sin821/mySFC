<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
// The message
$message = $_POST['message'];

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70, "\r\n");

// Send
if(mail('jer821@gmail.com', 'A Suggestion for mySFC App', $message)) {
	$msg = "Your comment has been sent. Thank you!";
	header('location: ../others/contact.php?status=success&msg='.$msg);
}
else {
	$msg = "Your comment could not be sent, please try again or contact me directly.";
	header('location: ../others/contact.php?status=failed&msg='.$msg);
}
?>