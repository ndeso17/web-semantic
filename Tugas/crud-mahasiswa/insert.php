<?php
include 'auth.php';
include 'koneksi.php';

$nim = mysqli_real_escape_string($conn, $_POST['nim']);
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);

$namaFile = $_FILES['foto']['name'];
$tmpFile = $_FILES['foto']['tmp_name'];
$ukuran = $_FILES['foto']['size'];
$ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
$ekstensi = ['png', 'jpg', 'jpeg'];

if (!in_array($ext, $ekstensi)) {
    $_SESSION['error'] = 'Format file tidak valid. Gunakan png, jpg, atau jpeg.';
    header('Location: tambah.php');
    exit;
}

if ($ukuran > 2000000) {
    $_SESSION['error'] = 'File terlalu besar. Maksimal 2 MB.';
    header('Location: tambah.php');
    exit;
}

$namaBaru = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '_', $namaFile);
move_uploaded_file($tmpFile, "uploads/" . $namaBaru);

mysqli_query($conn, "INSERT INTO mahasiswa (nim, nama, jurusan, foto) VALUES ('$nim', '$nama', '$jurusan', '$namaBaru')");
$_SESSION['success'] = 'Data mahasiswa berhasil disimpan';
header("Location: index.php");
exit;
?>
