<?php
session_start();
$pageTitle = 'Login - Sistem Data Mahasiswa';
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="assets/css/theme.css" rel="stylesheet">
    <style>
    body {
        background: radial-gradient(circle at top, #681726 0%, #55111c 35%, #2d0b12 100%);
    }

    .login-shell {
        min-height: 100vh;
    }

    .brand-mark {
        width: 96px;
        height: 96px;
    }
    </style>
</head>

<body>
    <div class="container login-shell d-flex align-items-center justify-content-center py-5">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden" data-aos="zoom-in">
                    <div class="card-body p-4 p-md-5 bg-white">
                        <div class="text-center mb-4">
                            <div class="mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center brand-mark"
                                style="background:rgba(157,43,34,.12);">
                                <i class="bi bi-mortarboard-fill display-4 text-brand"></i>
                            </div>
                            <h1 class="h3 fw-bold mb-1 text-brand">Sistem Data Mahasiswa</h1>
                        </div>
                        <form method="POST" action="proses_login.php" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" name="username"
                                        placeholder="Masukkan username" required autofocus>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control" name="password"
                                        placeholder="Masukkan password" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    AOS.init({
        duration: 700,
        once: true
    });
    </script>
    <?php include 'partials/alerts.php'; ?>
    <?php if (isset($_GET['logout']) && $_GET['logout'] === '1'): ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil Logout',
        confirmButtonColor: '#9d2b22'
    });
    </script>
    <?php endif; ?>
</body>

</html>