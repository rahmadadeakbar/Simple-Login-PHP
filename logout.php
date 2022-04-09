<?php
// menginisialisasi session
session_start();
// Unset semua session variables
$_SESSION = [];
// menghapus session.
session_destroy();
// Redirect ke login page
header("location: login.php");
exit();
