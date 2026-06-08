<?php
$page_title = "Koleksi Artikel Lengkap";
include "includes/koneksi.php";

// Sanitizing search inputs and categories
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'latest';

// Pagination setup
$limit = 6;
$page = isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Building dynamic queries
$where_clauses = [];
$params = [];
$types = '';

if ($search !== '') {
    $where_clauses[] = "(judul LIKE ? OR isi LIKE ? OR penulis LIKE ?)";
    $search_param = '%' . $search . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'sss';
}

if ($kategori !== '') {
    $where_clauses[] = "kategori = ?";
    $params[] = $kategori;
    $types .= 's';
}

$where_sql = '';
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where_clauses);
}

// Order by clause
$order_sql = "ORDER BY tanggal DESC";
if ($sort === 'popular') {
    $order_sql = "ORDER BY views DESC";
} elseif ($sort === 'oldest') {
    $order_sql = "ORDER BY tanggal ASC";
}

// Total records query
$total_query_sql = "SELECT COUNT(*) as total FROM artikel $where_sql";
if ($types !== '') {
    $total_stmt = $conn->prepare($total_query_sql);
    $total_stmt->bind_param($types, ...$params);
    $total_stmt->execute();
    $total_result = $total_stmt->get_result();
    $total_stmt->close();
} else {
    $total_result = mysqli_query($conn, $total_query_sql);
}
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Main articles query with limit & offset
$main_query_sql = "SELECT * FROM artikel $where_sql $order_sql LIMIT ? OFFSET ?";
$main_params = $params;
$main_params[] = $limit;
$main_params[] = $offset;
$main_types = $types . 'ii';

$stmt = $conn->prepare($main_query_sql);
$stmt->bind_param($main_types, ...$main_params);
$stmt->execute();
$articles = $stmt->get_result();
$stmt->close();

// Fetch categories for sidebar/filter
$cat_query = mysqli_query($conn, "SELECT kategori, COUNT(*) as total FROM artikel GROUP BY kategori");

include "includes/header.php";
include "includes/navbar.php";
?>

<main class="container my-4">
    <!-- Search / Filter header -->
    <div class="row mb-4 align-items-center" data-aos="fade-down">
        <div class="col-md-6">
            <h1 class="h3 mb-1 fw-bold">
                <?php if ($kategori !== ''): ?>
                    Kategori: <span class="text-primary"><?= htmlspecialchars($kategori) ?></span>
                <?php elseif ($search !== ''): ?>
                    Hasil Pencarian: "<span class="text-primary"><?= htmlspecialchars($search) ?></span>"
                <?php else: ?>
                    Semua Artikel
                <?php endif; ?>
            </h1>
            <p class="text-muted mb-0">Menampilkan <?= $total_records ?> artikel berkualitas.</p>
        </div>
        
        <!-- Sorting controls -->
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="btn-group btn-group-sm">
                <a href="?kategori=<?= urlencode($kategori) ?>&q=<?= urlencode($search) ?>&sort=latest" class="btn btn-outline-primary <?= $sort == 'latest' ? 'active' : '' ?>">Terbaru</a>
                <a href="?kategori=<?= urlencode($kategori) ?>&q=<?= urlencode($search) ?>&sort=popular" class="btn btn-outline-primary <?= $sort == 'popular' ? 'active' : '' ?>">Terpopuler</a>
                <a href="?kategori=<?= urlencode($kategori) ?>&q=<?= urlencode($search) ?>&sort=oldest" class="btn btn-outline-primary <?= $sort == 'oldest' ? 'active' : '' ?>">Terlama</a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Articles grid -->
        <div class="col-lg-9">
            <?php if ($total_records > 0): ?>
            <!-- Desktop: 4 columns structure inside articles grid (col-xl-3 for desktop 4 columns, col-md-6 for tablet 2 columns, col-12 for mobile 1 column) -->
            <div class="row g-3">
                <?php 
                $i = 0;
                while ($row = $articles->fetch_assoc()): 
                    $badge_color = 'bg-primary';
                    if ($row['kategori'] == 'Pemrograman') $badge_color = 'bg-success';
                    if ($row['kategori'] == 'Desain') $badge_color = 'bg-warning text-dark';
                    if ($row['kategori'] == 'Keamanan') $badge_color = 'bg-danger';
                    if ($row['kategori'] == 'Karir') $badge_color = 'bg-info text-dark';
                ?>
                <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 100 ?>">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="card-img-wrapper">
                            <span class="badge <?= $badge_color ?> card-category-badge"><?= htmlspecialchars($row['kategori']) ?></span>
                            <img src="https://images.unsplash.com/photo-1504639725590-34d0984388bd?auto=format&fit=crop&w=600&q=80" alt="<?= htmlspecialchars($row['judul']) ?>">
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-2 mb-2 text-muted small">
                                <span><i class="bi bi-calendar-event"></i> <?= date('d M Y', strtotime($row['tanggal'])) ?></span>
                                <span>&bull;</span>
                                <span><i class="bi bi-eye"></i> <?= $row['views'] ?> views</span>
                            </div>
                            <h2 class="card-title h6" style="line-height: 1.4;">
                                <a href="detail.php?id=<?= $row['id'] ?>" class="text-decoration-none text-dark hover-text-primary"><?= htmlspecialchars($row['judul']) ?></a>
                            </h2>
                            <p class="card-text text-secondary small">
                                <?= htmlspecialchars(isset($row['ringkasan']) && !empty($row['ringkasan']) ? $row['ringkasan'] : substr($row['isi'], 0, 95) . '...') ?>
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0 pb-3 d-flex justify-content-between align-items-center">
                            <span class="small text-muted"><i class="bi bi-person"></i> <?= htmlspecialchars($row['penulis']) ?></span>
                            <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-link btn-sm text-primary p-0 text-decoration-none fw-semibold">Baca <i class="bi bi-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php 
                $i++;
                endwhile; 
                ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <nav class="mt-5" aria-label="Pagination halaman artikel" data-aos="fade-up">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?kategori=<?= urlencode($kategori) ?>&q=<?= urlencode($search) ?>&sort=<?= $sort ?>&page=<?= $page - 1 ?>"><i class="bi bi-chevron-left"></i> Sebelumnya</a>
                    </li>
                    <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                    <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?kategori=<?= urlencode($kategori) ?>&q=<?= urlencode($search) ?>&sort=<?= $sort ?>&page=<?= $p ?>"><?= $p ?></a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?kategori=<?= urlencode($kategori) ?>&q=<?= urlencode($search) ?>&sort=<?= $sort ?>&page=<?= $page + 1 ?>">Selanjutnya <i class="bi bi-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>

            <?php else: ?>
            <div class="alert alert-info py-5 text-center" data-aos="fade-up">
                <i class="bi bi-info-circle display-4 mb-3 d-block text-primary"></i>
                <h4 class="fw-bold">Tidak Ada Artikel</h4>
                <p class="text-secondary mb-0">Maaf, tidak ada artikel yang sesuai dengan pencarian atau filter Anda.</p>
                <a href="artikel.php" class="btn btn-primary mt-3">Reset Filter</a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar filters -->
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4" data-aos="fade-up">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-search me-2 text-primary"></i>Cari Cepat</h5>
                </div>
                <div class="card-body">
                    <form action="artikel.php" method="GET">
                        <?php if ($kategori !== ''): ?>
                            <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori) ?>">
                        <?php endif; ?>
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Cari..." value="<?= htmlspecialchars($search) ?>">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Categories -->
            <div class="card shadow-sm mb-4" data-aos="fade-up">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-grid-fill me-2 text-primary"></i>Semua Kategori</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="artikel.php?q=<?= urlencode($search) ?>&sort=<?= $sort ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= $kategori === '' ? 'active fw-semibold' : '' ?>">
                            <span><i class="bi bi-folder-fill me-2"></i>Semua Kategori</span>
                        </a>
                        <?php while ($cat = mysqli_fetch_assoc($cat_query)): ?>
                        <a href="artikel.php?kategori=<?= urlencode($cat['kategori']) ?>&q=<?= urlencode($search) ?>&sort=<?= $sort ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= $kategori === $cat['kategori'] ? 'active fw-semibold' : '' ?>">
                            <span><i class="bi bi-folder2-open me-2"></i><?= htmlspecialchars($cat['kategori']) ?></span>
                            <span class="badge rounded-pill category-badge-pill"><?= $cat['total'] ?></span>
                        </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include "includes/footer.php";
?>
