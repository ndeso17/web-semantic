<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$pageTitle = $pageTitle ?? 'Sistem Data Mahasiswa';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="assets/css/theme.css" rel="stylesheet">
  <style>
    body { background: linear-gradient(180deg, #f8fafc 0%, #f3e9e7 100%); }
    .brand-mark { width: 72px; height: 72px; }
    .glass-card { backdrop-filter: blur(12px); background: rgba(255,255,255,.92); }
    .table > :not(caption) > * > * { vertical-align: middle; }
    .object-fit-cover { object-fit: cover; }
    .page-shell { min-height: 100vh; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
  <div class="container-fluid px-3 px-lg-4">
    <a class="navbar-brand fw-semibold" href="index.php"><i class="bi bi-mortarboard-fill me-2"></i>Sistem Data Mahasiswa</a>
    <div class="ms-auto d-flex flex-column flex-md-row align-items-md-center gap-1 gap-md-3 text-white">
      <span class="small text-white-50">Pertemuan 11, 12, dan 13</span>
      <span class="small text-white-50">PHP &amp; MySQL</span>
      <span class="small d-none d-md-inline"><i class="bi bi-person-circle me-1"></i>Admin</span>
      <a class="btn btn-light btn-sm" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
    </div>
  </div>
</nav>
<main class="page-shell py-4 py-lg-5">
  <div class="container">
