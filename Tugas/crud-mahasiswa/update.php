<?php
include 'auth.php';
include 'koneksi.php';

$nim = mysqli_real_escape_string($conn, $_POST['nim']);
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);

if (!empty($_FILES['foto']['name'])) {
    $namaFile = $_FILES['foto']['name'];
    $tmpFile = $_FILES['foto']['tmp_name'];
    $ukuran = $_FILES['foto']['size'];
    $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    $ekstensi = ['png', 'jpg', 'jpeg'];

    if (!in_array($ext, $ekstensi)) {
        $_SESSION['error'] = 'Format file tidak valid. Gunakan png, jpg, atau jpeg.';
        header('Location: edit.php?nim=' . urlencode($nim));
        exit;
    }

    if ($ukuran > 2000000) {
        $_SESSION['error'] = 'File terlalu besar. Maksimal 2 MB.';
        header('Location: edit.php?nim=' . urlencode($nim));
        exit;
    }

    $namaBaru = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '_', $namaFile);
    move_uploaded_file($tmpFile, "uploads/" . $namaBaru);

    mysqli_query($conn, "UPDATE mahasiswa SET nama='$nama', jurusan='$jurusan', foto='$namaBaru' WHERE nim='$nim'");
} else {
    mysqli_query($conn, "UPDATE mahasiswa SET nama='$nama', jurusan='$jurusan' WHERE nim='$nim'");
}

$_SESSION['success'] = 'Data mahasiswa berhasil diperbarui';
header("Location: index.php");
exit;
?>
