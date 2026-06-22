<?php
include 'auth.php';
include 'koneksi.php';

$nim = mysqli_real_escape_string($conn, $_GET['nim']);
$query = mysqli_query($conn, "SELECT foto FROM mahasiswa WHERE nim='$nim'");
$data = mysqli_fetch_assoc($query);

if ($data && !empty($data['foto']) && file_exists('uploads/'.$data['foto'])) {
    unlink('uploads/'.$data['foto']);
}

mysqli_query($conn, "DELETE FROM mahasiswa WHERE nim='$nim'");
$_SESSION['success'] = 'Data mahasiswa berhasil dihapus.';
header("Location: index.php");
exit;
?>
