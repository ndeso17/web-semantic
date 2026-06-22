<?php
session_start();
include 'koneksi.php';

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

$query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username' AND password='$password'");
$data = mysqli_fetch_assoc($query);

if ($data) {
    $_SESSION['username'] = $data['username'];
    $_SESSION['success'] = 'Selamat Datang Admin';
    header("Location: index.php");
    exit;
} else {
    $_SESSION['error'] = 'Username atau password salah';
    header("Location: login.php");
    exit;
}
?>
