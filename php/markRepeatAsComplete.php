<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');

$repeat_id = $_GET['id'];

echo $query = "UPDATE tbl_repeatedsorties SET repeatedsortie_done='1' WHERE repeatedsortie_id='$repeat_id'";
$result = mysqli_query($link, $query);

if($result) {
	$msg = "You have updated the repeat sortie table. Thank you!";
	header('location: ../planning/planner_dashboard.php?status=success&msg='.$msg);
}
else {
	$msg = "Unable to update repeated sortie status, please try again or contact a planner directly.";
	header('location: ../planning/planner_dashboard.php?status=failed&msg='.$msg);
}

?>