<?php
$page_title = "Portal Berita Teknologi Terkini";
include "includes/koneksi.php";

// Fetch 1 main hero article (most viewed/popular or latest)
$hero_query = mysqli_query($conn, "SELECT * FROM artikel ORDER BY tanggal DESC, views DESC LIMIT 1");
$hero = mysqli_fetch_assoc($hero_query);

$hero_id = isset($hero['id']) ? $hero['id'] : 0;

// Fetch 3 trending articles (excluding hero)
$trending_query = mysqli_query($conn, "SELECT * FROM artikel WHERE id != $hero_id ORDER BY views DESC LIMIT 3");

// Fetch latest articles (excluding hero)
$latest_query = mysqli_query($conn, "SELECT * FROM artikel WHERE id != $hero_id ORDER BY tanggal DESC LIMIT 8");

// Fetch categories and their count
$cat_query = mysqli_query($conn, "SELECT kategori, COUNT(*) as total FROM artikel GROUP BY kategori");

// Fetch sidebar popular articles
$sidebar_pop_query = mysqli_query($conn, "SELECT * FROM artikel ORDER BY views DESC LIMIT 5");

// Fetch statistics
$total_articles_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM artikel"));
$total_views_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(views) as count FROM artikel"));
$total_authors_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT penulis) as count FROM artikel"));

include "includes/header.php";
include "includes/navbar.php";
?>

<main class="container my-4">
    <!-- Hero Section -->
    <?php if ($hero): ?>
    <section class="mb-5" data-aos="fade-up">
        <div class="row">
            <div class="col-12">
                <div class="hero-wrapper position-relative text-white" style="min-height: 480px;">
                    <!-- Cover image helper -->
                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80" class="position-absolute w-100 h-100 object-fit-cover" alt="<?= htmlspecialchars($hero['judul']) ?>">
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        <span class="badge bg-primary mb-3 text-uppercase px-3 py-2"><?= htmlspecialchars($hero['kategori']) ?></span>
                        <h1 class="display-5 fw-bold mb-3 text-white"><?= htmlspecialchars($hero['judul']) ?></h1>
                        <p class="lead mb-4 text-light d-none d-md-block col-lg-8">
                            <?= htmlspecialchars(isset($hero['ringkasan']) && !empty($hero['ringkasan']) ? $hero['ringkasan'] : substr($hero['isi'], 0, 150) . '...') ?>
                        </p>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <small class="text-light"><i class="bi bi-person-fill"></i> <?= htmlspecialchars($hero['penulis']) ?></small>
                            <small class="text-light"><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($hero['tanggal'])) ?></small>
                            <small class="text-light"><i class="bi bi-eye"></i> <?= $hero['views'] ?> views</small>
                        </div>
                        <a href="detail.php?id=<?= $hero['id'] ?>" class="btn btn-primary px-4 py-2 fw-semibold">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Trending Section -->
    <section class="mb-5">
        <div class="d-flex align-items-center gap-2 mb-4">
            <span class="p-2 bg-danger rounded-circle d-inline-block pulse-dot"></span>
            <h2 class="h3 mb-0 fw-bold border-bottom border-danger border-3 pb-1">Sedang Tren</h2>
        </div>
        <div class="row g-4">
            <?php 
            $i = 1;
            while ($trend = mysqli_fetch_assoc($trending_query)): 
                // Assign category colors dynamically
                $badge_color = 'bg-primary';
                if ($trend['kategori'] == 'Pemrograman') $badge_color = 'bg-success';
                if ($trend['kategori'] == 'Desain') $badge_color = 'bg-warning text-dark';
                if ($trend['kategori'] == 'Keamanan') $badge_color = 'bg-danger';
                if ($trend['kategori'] == 'Karir') $badge_color = 'bg-info text-dark';
            ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
                <div class="card h-100 shadow-sm hover-card">
                    <div class="card-img-wrapper">
                        <span class="badge <?= $badge_color ?> card-category-badge"><?= htmlspecialchars($trend['kategori']) ?></span>
                        <img src="https://images.unsplash.com/photo-1488590528505-98d2b5aba04b?auto=format&fit=crop&w=600&q=80" alt="<?= htmlspecialchars($trend['judul']) ?>">
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2 text-muted small">
                            <span><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($trend['tanggal'])) ?></span>
                            <span>&bull;</span>
                            <span><i class="bi bi-eye"></i> <?= $trend['views'] ?> views</span>
                        </div>
                        <h3 class="card-title h5 mb-3">
                            <a href="detail.php?id=<?= $trend['id'] ?>" class="text-decoration-none text-dark hover-text-primary"><?= htmlspecialchars($trend['judul']) ?></a>
                        </h3>
                        <p class="card-text text-secondary small">
                            <?= htmlspecialchars(isset($trend['ringkasan']) && !empty($trend['ringkasan']) ? $trend['ringkasan'] : substr($trend['isi'], 0, 100) . '...') ?>
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 pb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                <i class="bi bi-person small"></i>
                            </div>
                            <span class="small fw-semibold text-dark"><?= htmlspecialchars($trend['penulis']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
            $i++;
            endwhile; 
            ?>
        </div>
    </section>

    <!-- Main Content Row -->
    <div class="row g-4">
        <!-- Latest News (Left Column) -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-2 bg-primary rounded-circle d-inline-block"></span>
                    <h2 class="h3 mb-0 fw-bold border-bottom border-primary border-3 pb-1">Berita Terbaru</h2>
                </div>
                <a href="artikel.php" class="btn btn-outline-primary btn-sm">Lihat Semua <i class="bi bi-arrow-right"></i></a>
            </div>
            
            <div class="row g-3">
                <?php 
                $j = 1;
                while ($latest = mysqli_fetch_assoc($latest_query)): 
                    $badge_color = 'bg-primary';
                    if ($latest['kategori'] == 'Pemrograman') $badge_color = 'bg-success';
                    if ($latest['kategori'] == 'Desain') $badge_color = 'bg-warning text-dark';
                    if ($latest['kategori'] == 'Keamanan') $badge_color = 'bg-danger';
                    if ($latest['kategori'] == 'Karir') $badge_color = 'bg-info text-dark';
                ?>
                <!-- Responsive Grid: Desktop 4 col (col-md-3 inside 12/8 is col-md-6, so we do col-md-6 on tablet and col-xl-6 on desktop or grid) -->
                <!-- Desktop has 2 columns in a col-lg-8 section, let's map correctly: col-md-6 for tablet/desktop -->
                <div class="col-md-6 mb-3" data-aos="fade-up" data-aos-delay="<?= ($j % 2) * 100 ?>">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="card-img-wrapper">
                            <span class="badge <?= $badge_color ?> card-category-badge"><?= htmlspecialchars($latest['kategori']) ?></span>
                            <img src="https://images.unsplash.com/photo-1504639725590-34d0984388bd?auto=format&fit=crop&w=600&q=80" alt="<?= htmlspecialchars($latest['judul']) ?>">
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-2 mb-2 text-muted small">
                                <span><i class="bi bi-calendar-event"></i> <?= date('d M Y', strtotime($latest['tanggal'])) ?></span>
                                <span>&bull;</span>
                                <span><i class="bi bi-eye"></i> <?= $latest['views'] ?> views</span>
                            </div>
                            <h4 class="card-title h6">
                                <a href="detail.php?id=<?= $latest['id'] ?>" class="text-decoration-none text-dark hover-text-primary"><?= htmlspecialchars($latest['judul']) ?></a>
                            </h4>
                            <p class="card-text text-secondary small">
                                <?= htmlspecialchars(isset($latest['ringkasan']) && !empty($latest['ringkasan']) ? $latest['ringkasan'] : substr($latest['isi'], 0, 90) . '...') ?>
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0 pb-3 d-flex justify-content-between align-items-center">
                            <span class="small text-muted"><i class="bi bi-pencil-square"></i> <?= htmlspecialchars($latest['penulis']) ?></span>
                            <a href="detail.php?id=<?= $latest['id'] ?>" class="btn btn-link btn-sm text-primary p-0 text-decoration-none fw-semibold">Baca <i class="bi bi-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php 
                $j++;
                endwhile; 
                ?>
            </div>
        </div>

        <!-- Sidebar (Right Column) -->
        <aside class="col-lg-4">
            <!-- Tentang Website -->
            <div id="tentang" class="card shadow-sm mb-4" data-aos="fade-up">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>Tentang TechNews</h5>
                </div>
                <div class="card-body text-secondary">
                    <p class="small mb-0 text-justify">
                        TechNews adalah pusat portal berita profesional, menyajikan analisis mendalam seputar teknologi baru, tutorial pemrograman terlengkap, ulasan desain UI/UX terkini, serta tips karir IT terpercaya.
                    </p>
                </div>
            </div>

            <!-- Kategori List -->
            <div class="card shadow-sm mb-4" data-aos="fade-up">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-grid-fill me-2 text-primary"></i>Kategori Populer</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php while ($cat = mysqli_fetch_assoc($cat_query)): ?>
                        <a href="artikel.php?kategori=<?= urlencode($cat['kategori']) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-folder2-open me-2 text-primary"></i><?= htmlspecialchars($cat['kategori']) ?></span>
                            <span class="badge rounded-pill category-badge-pill"><?= $cat['total'] ?></span>
                        </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <!-- Popular Articles List -->
            <div class="card shadow-sm mb-4" data-aos="fade-up">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-star-fill me-2 text-warning"></i>Paling Banyak Dibaca</h5>
                </div>
                <div class="card-body">
                    <?php while ($pop = mysqli_fetch_assoc($sidebar_pop_query)): ?>
                    <div class="sidebar-list-item d-flex gap-3 align-items-start">
                        <div class="bg-light rounded p-2 text-center text-primary fw-bold" style="min-width: 48px;">
                            <i class="bi bi-eye"></i>
                            <div class="small fw-normal text-muted"><?= $pop['views'] ?></div>
                        </div>
                        <div>
                            <h6 class="mb-1 text-truncate-2" style="font-size: 0.9rem; line-height: 1.3;">
                                <a href="detail.php?id=<?= $pop['id'] ?>" class="text-decoration-none text-dark hover-text-primary"><?= htmlspecialchars($pop['judul']) ?></a>
                            </h6>
                            <small class="text-muted"><i class="bi bi-calendar"></i> <?= date('d M Y', strtotime($pop['tanggal'])) ?></small>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Portal Statistics -->
            <div class="card shadow-sm mb-4 bg-gradient bg-primary text-white border-0" data-aos="fade-up">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-4"><i class="bi bi-bar-chart-fill me-2"></i>Statistik Portal</h5>
                    <div class="row g-2">
                        <div class="col-4 border-end border-white border-opacity-25">
                            <h3 class="fw-bold mb-0"><?= $total_articles_row['count'] ?></h3>
                            <small class="text-white-50 text-uppercase" style="font-size: 0.7rem;">Artikel</small>
                        </div>
                        <div class="col-4 border-end border-white border-opacity-25">
                            <h3 class="fw-bold mb-0"><?= number_format($total_views_row['count']) ?></h3>
                            <small class="text-white-50 text-uppercase" style="font-size: 0.7rem;">Views</small>
                        </div>
                        <div class="col-4">
                            <h3 class="fw-bold mb-0"><?= $total_authors_row['count'] ?></h3>
                            <small class="text-white-50 text-uppercase" style="font-size: 0.7rem;">Penulis</small>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</main>

<?php
include "includes/footer.php";
?>
