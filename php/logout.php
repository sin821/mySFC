<?php
session_start([
    'cookie_lifetime' => 86400,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
$_SESSION = array();
session_unset();
session_destroy();
header('location: /index.php?status=success&msg=You have been logged out.');
?>