<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');

$name = addslashes($_POST['name']);
$course = addslashes($_POST['course']);
$donation = $_POST['donation'];
$transaction = addslashes($_POST['transaction']);

$query = "INSERT INTO tbl_doners (doner_name, doner_course, doner_amount, doner_transactionid) VALUES ('$name', '$course', '$donation', '$transaction')";
$result = mysqli_query($link, $query);

$message = $name.' ('.$course.') has donated $'.$donation.' :)';

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70, "\r\n");

if($result) {
	mail('jer821@gmail.com', 'mySFC App Donation', $message);
	$msg = "I have been informed of your donation. Thank you!";
	header('location: ../others/donation.php?status=success&msg='.$msg);
}
else {
	$msg = "I was not able to be informed of your donation, there may have been an invalid input, please try again or contact me directly on 65 91524295.";
	header('location: ../others/donation.php?status=failed&msg='.$msg);
}
?>