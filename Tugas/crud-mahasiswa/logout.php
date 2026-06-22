<?php
session_start();
$_SESSION = [];
session_destroy();
session_start();
$_SESSION['success'] = 'Berhasil Logout';
header("Location: login.php");
exit;
?>
