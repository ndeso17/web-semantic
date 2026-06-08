<!-- Top Bar -->
<div class="top-bar bg-primary text-white py-1 d-none d-md-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <small>
                    <i class="bi bi-calendar3 me-1"></i>
                    <?= date('l, d F Y') ?>
                </small>
            </div>
            <div class="col-md-6 text-end">
                <small>
                    <a href="#" class="text-white text-decoration-none me-2"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white text-decoration-none me-2"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="text-white text-decoration-none me-2"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white text-decoration-none"><i class="bi bi-youtube"></i></a>
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm" id="mainNavbar">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-4" href="index.php">
            <span class="logo-icon"><i class="bi bi-broadcast"></i></span>
            <span class="logo-text">Tech<span class="text-primary">News</span></span>
        </a>

        <!-- Mobile toggler -->
        <div class="d-flex align-items-center gap-2 d-lg-none">
            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#searchOffcanvas">
                <i class="bi bi-search"></i>
            </button>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <!-- Nav Links -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active fw-semibold' : '' ?>" href="index.php">
                        <i class="bi bi-house me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'artikel.php' ? 'active fw-semibold' : '' ?>" href="artikel.php">
                        <i class="bi bi-newspaper me-1"></i>Artikel
                    </a>
                </li>
                <!-- Dropdown Kategori -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-grid me-1"></i>Kategori
                    </a>
                    <ul class="dropdown-menu shadow border-0">
                        <li><a class="dropdown-item" href="artikel.php?kategori=Teknologi"><i class="bi bi-cpu me-2 text-primary"></i>Teknologi</a></li>
                        <li><a class="dropdown-item" href="artikel.php?kategori=Pemrograman"><i class="bi bi-code-slash me-2 text-success"></i>Pemrograman</a></li>
                        <li><a class="dropdown-item" href="artikel.php?kategori=Desain"><i class="bi bi-palette me-2 text-warning"></i>Desain</a></li>
                        <li><a class="dropdown-item" href="artikel.php?kategori=Karir"><i class="bi bi-briefcase me-2 text-info"></i>Karir</a></li>
                        <li><a class="dropdown-item" href="artikel.php?kategori=Keamanan"><i class="bi bi-shield-check me-2 text-danger"></i>Keamanan</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tentang"><i class="bi bi-info-circle me-1"></i>Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#footer"><i class="bi bi-envelope me-1"></i>Kontak</a>
                </li>
            </ul>

            <!-- Desktop Search -->
            <form class="d-none d-lg-flex gap-2" action="artikel.php" method="GET">
                <div class="input-group search-bar">
                    <input type="search" class="form-control form-control-sm" name="q" placeholder="Cari artikel..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                    <button class="btn btn-primary btn-sm" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <!-- Admin Button -->
            <a href="admin.php" class="btn btn-outline-danger btn-sm ms-2 d-none d-lg-inline-flex align-items-center gap-1">
                <i class="bi bi-shield-lock"></i> Admin
            </a>
        </div>
    </div>
</nav>

<!-- Mobile Search Offcanvas -->
<div class="offcanvas offcanvas-top" id="searchOffcanvas" tabindex="-1">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Cari Artikel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form action="artikel.php" method="GET">
            <div class="input-group">
                <input type="search" class="form-control" name="q" placeholder="Ketik kata kunci...">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
            </div>
        </form>
    </div>
</div>
