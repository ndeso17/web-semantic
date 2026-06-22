<?php
$conn = mysqli_connect("localhost", "ndeso17", "ndeso17", "kampus");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
