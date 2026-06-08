<?php
include "includes/koneksi.php";

// Validate and sanitize the ID
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// Increment views
mysqli_query($conn, "UPDATE artikel SET views = views + 1 WHERE id = $id");

// Fetch article via prepared statement
$stmt = $conn->prepare("SELECT * FROM artikel WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();
$stmt->close();

if (!$article) {
    header("Location: index.php");
    exit;
}

$page_title = $article['judul'];

// Fetch related articles (same category, exclude current)
$rel_stmt = $conn->prepare("SELECT * FROM artikel WHERE kategori = ? AND id != ? ORDER BY tanggal DESC LIMIT 3");
$rel_stmt->bind_param("si", $article['kategori'], $id);
$rel_stmt->execute();
$related = $rel_stmt->get_result();
$rel_stmt->close();

// Fetch sidebar popular articles
$sidebar_pop_query = mysqli_query($conn, "SELECT * FROM artikel WHERE id != $id ORDER BY views DESC LIMIT 5");

include "includes/header.php";
include "includes/navbar.php";
?>

<main class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down">
        <ol class="breadcrumb bg-white p-3 rounded shadow-sm">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none"><i class="bi bi-house"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="artikel.php" class="text-decoration-none">Artikel</a></li>
            <li class="breadcrumb-item"><a href="artikel.php?kategori=<?= urlencode($article['kategori']) ?>" class="text-decoration-none"><?= htmlspecialchars($article['kategori']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars(mb_substr($article['judul'], 0, 40)) ?>...</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Article Content -->
        <div class="col-lg-8">
            <article class="card shadow-sm border-0 mb-4" data-aos="fade-up">
                <!-- Article Header Image -->
                <div class="card-img-wrapper" style="aspect-ratio: 21/9;">
                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80" class="card-img-top" alt="<?= htmlspecialchars($article['judul']) ?>">
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <!-- Category Badge -->
                    <span class="badge bg-primary mb-3 text-uppercase px-3 py-2"><?= htmlspecialchars($article['kategori']) ?></span>
                    
                    <!-- Title -->
                    <h1 class="fw-bold mb-3" style="font-size: 2rem; line-height: 1.3;"><?= htmlspecialchars($article['judul']) ?></h1>
                    
                    <!-- Meta Info -->
                    <div class="d-flex flex-wrap align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <span class="fw-semibold d-block small"><?= htmlspecialchars($article['penulis']) ?></span>
                                <small class="text-muted">Penulis</small>
                            </div>
                        </div>
                        <div class="text-muted">
                            <i class="bi bi-calendar3 me-1"></i><?= date('l, d F Y', strtotime($article['tanggal'])) ?>
                        </div>
                        <div class="text-muted">
                            <i class="bi bi-eye me-1"></i><?= number_format($article['views']) ?> views
                        </div>
                    </div>
                    
                    <!-- Article Body -->
                    <div class="article-content lh-lg text-justify" style="font-size: 1.05rem;">
                        <?= nl2br(htmlspecialchars($article['isi'])) ?>
                    </div>

                    <!-- Tags/Share -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-3 border-top">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="fw-semibold small">Tags:</span>
                            <span class="badge bg-light text-dark"><?= htmlspecialchars($article['kategori']) ?></span>
                            <span class="badge bg-light text-dark">Berita</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="fw-semibold small">Share:</span>
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-circle"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-info rounded-circle"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-success rounded-circle"><i class="bi bi-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Related Articles -->
            <section class="mb-4">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <span class="p-2 bg-primary rounded-circle d-inline-block"></span>
                    <h2 class="h4 mb-0 fw-bold border-bottom border-primary border-3 pb-1">Artikel Terkait</h2>
                </div>
                <div class="row g-3">
                    <?php while ($rel = $related->fetch_assoc()): ?>
                    <div class="col-md-4" data-aos="fade-up">
                        <div class="card h-100 shadow-sm hover-card">
                            <div class="card-img-wrapper">
                                <img src="https://images.unsplash.com/photo-1504639725590-34d0984388bd?auto=format&fit=crop&w=600&q=80" alt="<?= htmlspecialchars($rel['judul']) ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title h6"><a href="detail.php?id=<?= $rel['id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($rel['judul']) ?></a></h5>
                                <small class="text-muted"><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($rel['tanggal'])) ?></small>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </section>
        </div>

        <!-- Sidebar -->
        <aside class="col-lg-4">
            <div class="card shadow-sm mb-4" data-aos="fade-up">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-star-fill me-2 text-warning"></i>Artikel Populer</h5>
                </div>
                <div class="card-body">
                    <?php while ($pop = mysqli_fetch_assoc($sidebar_pop_query)): ?>
                    <div class="sidebar-list-item d-flex gap-3 align-items-start">
                        <div class="bg-light rounded p-2 text-center text-primary fw-bold" style="min-width: 48px;">
                            <i class="bi bi-eye"></i>
                            <div class="small fw-normal text-muted"><?= $pop['views'] ?></div>
                        </div>
                        <div>
                            <h6 class="mb-1" style="font-size: 0.9rem; line-height: 1.3;">
                                <a href="detail.php?id=<?= $pop['id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($pop['judul']) ?></a>
                            </h6>
                            <small class="text-muted"><i class="bi bi-calendar"></i> <?= date('d M Y', strtotime($pop['tanggal'])) ?></small>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Back to home CTA -->
            <div class="card shadow-sm bg-primary text-white border-0" data-aos="fade-up">
                <div class="card-body text-center p-4">
                    <i class="bi bi-newspaper display-4 mb-3"></i>
                    <h5 class="fw-bold">Jelajahi Lebih Banyak</h5>
                    <p class="text-white-50 small">Temukan ratusan artikel menarik lainnya</p>
                    <a href="artikel.php" class="btn btn-light fw-semibold px-4">Semua Artikel <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </aside>
    </div>
</main>

<?php
include "includes/footer.php";
?>
