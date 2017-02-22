<?php
session_start([
    'cookie_lifetime' => 2592000,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('db_conn.php');

$instructor_id = $_GET['id'];

$query = "DELETE FROM tbl_instructors WHERE instructor_id='$instructor_id'";
$result = mysqli_query($link, $query);
?>