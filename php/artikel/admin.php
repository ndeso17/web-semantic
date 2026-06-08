<?php
$page_title = "Dasbor Admin Portal Berita";
include "includes/koneksi.php";

$message = '';
$message_type = '';

// Handle actions (Delete, Create, Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        // DELETE action
        if ($_POST['action'] === 'delete') {
            if (isset($_POST['id']) && filter_var($_POST['id'], FILTER_VALIDATE_INT)) {
                $id = (int) $_POST['id'];
                $stmt = $conn->prepare("DELETE FROM artikel WHERE id = ?");
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $message = "Artikel berhasil dihapus!";
                    $message_type = "success";
                } else {
                    $message = "Gagal menghapus artikel: " . $stmt->error;
                    $message_type = "danger";
                }
                $stmt->close();
            }
        }
        
        // ADD/CREATE action
        elseif ($_POST['action'] === 'add') {
            $judul = trim($_POST['judul']);
            $isi = trim($_POST['isi']);
            $penulis = trim($_POST['penulis']);
            $kategori = trim($_POST['kategori']);
            $tanggal = trim($_POST['tanggal']);
            $ringkasan = mb_substr($isi, 0, 150) . '...';
            
            if (!empty($judul) && !empty($isi) && !empty($penulis) && !empty($kategori) && !empty($tanggal)) {
                $stmt = $conn->prepare("INSERT INTO artikel (judul, isi, ringkasan, penulis, kategori, tanggal, views) VALUES (?, ?, ?, ?, ?, ?, 0)");
                $stmt->bind_param("ssssss", $judul, $isi, $ringkasan, $penulis, $kategori, $tanggal);
                if ($stmt->execute()) {
                    $message = "Artikel baru berhasil ditambahkan!";
                    $message_type = "success";
                } else {
                    $message = "Gagal menambahkan artikel: " . $stmt->error;
                    $message_type = "danger";
                }
                $stmt->close();
            } else {
                $message = "Semua field input wajib diisi!";
                $message_type = "warning";
            }
        }

        // UPDATE action
        elseif ($_POST['action'] === 'edit') {
            if (isset($_POST['id']) && filter_var($_POST['id'], FILTER_VALIDATE_INT)) {
                $id = (int) $_POST['id'];
                $judul = trim($_POST['judul']);
                $isi = trim($_POST['isi']);
                $penulis = trim($_POST['penulis']);
                $kategori = trim($_POST['kategori']);
                $tanggal = trim($_POST['tanggal']);
                $ringkasan = mb_substr($isi, 0, 150) . '...';
                
                if (!empty($judul) && !empty($isi) && !empty($penulis) && !empty($kategori) && !empty($tanggal)) {
                    $stmt = $conn->prepare("UPDATE artikel SET judul = ?, isi = ?, ringkasan = ?, penulis = ?, kategori = ?, tanggal = ? WHERE id = ?");
                    $stmt->bind_param("ssssssi", $judul, $isi, $ringkasan, $penulis, $kategori, $tanggal, $id);
                    if ($stmt->execute()) {
                        $message = "Artikel berhasil diperbarui!";
                        $message_type = "success";
                    } else {
                        $message = "Gagal memperbarui artikel: " . $stmt->error;
                        $message_type = "danger";
                    }
                    $stmt->close();
                } else {
                    $message = "Semua field input wajib diisi untuk pembaruan!";
                    $message_type = "warning";
                }
            }
        }
    }
}

// Fetch all articles for dashboard listing
$query = mysqli_query($conn, "SELECT * FROM artikel ORDER BY tanggal DESC");

// Fetch statistics
$stats_articles = mysqli_num_rows($query);
$views_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(views) as total FROM artikel"));
$stats_views = isset($views_row['total']) ? $views_row['total'] : 0;
$cat_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT kategori) as total FROM artikel"));
$stats_cats = isset($cat_row['total']) ? $cat_row['total'] : 0;

include "includes/header.php";
include "includes/navbar.php";
?>

<main class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down">
        <ol class="breadcrumb bg-white p-3 rounded shadow-sm">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none"><i class="bi bi-house"></i> Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Admin Dashboard</li>
        </ol>
    </nav>

    <!-- Feedback Banner -->
    <?php if ($message !== ''): ?>
    <div class="alert alert-<?= $message_type ?> alert-dismissible fade show shadow-sm mb-4" role="alert" data-aos="zoom-in">
        <i class="bi <?= $message_type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> me-2"></i>
        <?= htmlspecialchars($message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <!-- Dashboard Statistics Overview -->
    <section class="row g-3 mb-4" data-aos="fade-up">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <h6 class="text-uppercase text-white-50 small mb-1 fw-bold">Total Artikel</h6>
                        <h2 class="display-6 fw-bold mb-0"><?= $stats_articles ?></h2>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-newspaper"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <h6 class="text-uppercase text-white-50 small mb-1 fw-bold">Total Pengunjung</h6>
                        <h2 class="display-6 fw-bold mb-0"><?= number_format($stats_views) ?></h2>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-eye"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <h6 class="text-uppercase text-dark-50 small mb-1 fw-bold">Total Kategori</h6>
                        <h2 class="display-6 fw-bold mb-0"><?= $stats_cats ?></h2>
                    </div>
                    <div class="fs-1 text-dark-50"><i class="bi bi-tags"></i></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main CRUD Table Section -->
    <section class="card shadow-sm" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="bi bi-folder-symlink me-2 text-primary"></i>Kelola Artikel</h5>
            <button class="btn btn-primary btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addArticleModal">
                <i class="bi bi-plus-lg"></i> Tambah Artikel Baru
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Views</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if (mysqli_num_rows($query) > 0):
                            while ($row = mysqli_fetch_assoc($query)): 
                                $badge_color = 'bg-primary';
                                if ($row['kategori'] == 'Pemrograman') $badge_color = 'bg-success';
                                if ($row['kategori'] == 'Desain') $badge_color = 'bg-warning text-dark';
                                if ($row['kategori'] == 'Keamanan') $badge_color = 'bg-danger';
                                if ($row['kategori'] == 'Karir') $badge_color = 'bg-info text-dark';
                        ?>
                        <tr>
                            <td class="ps-4 text-muted fw-semibold"><?= $no++ ?></td>
                            <td>
                                <a href="detail.php?id=<?= $row['id'] ?>" class="text-decoration-none text-dark fw-bold text-truncate-1" style="max-width: 300px; display: block;" target="_blank">
                                    <?= htmlspecialchars($row['judul']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($row['penulis']) ?></td>
                            <td><span class="badge <?= $badge_color ?>"><?= htmlspecialchars($row['kategori']) ?></span></td>
                            <td class="small text-muted"><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                            <td class="fw-semibold text-secondary"><i class="bi bi-eye small"></i> <?= $row['views'] ?></td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm">
                                    <!-- Edit Trigger -->
                                    <button class="btn btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal<?= $row['id'] ?>"
                                            title="Ubah Artikel">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <!-- Delete Trigger -->
                                    <button class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal<?= $row['id'] ?>"
                                            title="Hapus Artikel">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- EDIT MODAL -->
                        <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold" id="editModalLabel<?= $row['id'] ?>"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Artikel</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="" method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="action" value="edit">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            
                                            <div class="row g-3">
                                                <div class="col-md-8">
                                                    <label class="form-label fw-semibold">Judul Artikel</label>
                                                    <input type="text" class="form-control" name="judul" value="<?= htmlspecialchars($row['judul']) ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold">Kategori</label>
                                                    <select class="form-select" name="kategori" required>
                                                        <option value="Teknologi" <?= $row['kategori'] === 'Teknologi' ? 'selected' : '' ?>>Teknologi</option>
                                                        <option value="Pemrograman" <?= $row['kategori'] === 'Pemrograman' ? 'selected' : '' ?>>Pemrograman</option>
                                                        <option value="Desain" <?= $row['kategori'] === 'Desain' ? 'selected' : '' ?>>Desain</option>
                                                        <option value="Karir" <?= $row['kategori'] === 'Karir' ? 'selected' : '' ?>>Karir</option>
                                                        <option value="Keamanan" <?= $row['kategori'] === 'Keamanan' ? 'selected' : '' ?>>Keamanan</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Penulis</label>
                                                    <input type="text" class="form-control" name="penulis" value="<?= htmlspecialchars($row['penulis']) ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Tanggal Terbit</label>
                                                    <input type="date" class="form-control" name="tanggal" value="<?= $row['tanggal'] ?>" required>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Isi Artikel</label>
                                                    <textarea class="form-control" name="isi" rows="10" required><?= htmlspecialchars($row['isi']) ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary btn-sm px-3">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- DELETE CONFIRMATION MODAL -->
                        <div class="modal fade" id="deleteModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus artikel secara permanen?<br>
                                        <strong class="text-dark">"<?= htmlspecialchars($row['judul']) ?>"</strong>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="" method="POST">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger btn-sm px-3">Ya, Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php 
                            endwhile;
                        else:
                        ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-folder2-open display-4 mb-3 d-block"></i>
                                Belum ada artikel terbit. Klik tombol di atas untuk membuat artikel pertama.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<!-- ADD ARTICLE MODAL -->
<div class="modal fade" id="addArticleModal" tabindex="-1" aria-labelledby="addArticleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addArticleLabel"><i class="bi bi-file-earmark-plus text-primary me-2"></i>Tambah Artikel Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Judul Artikel</label>
                            <input type="text" class="form-control" name="judul" placeholder="Masukkan judul menarik..." required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select class="form-select" name="kategori" required>
                                <option value="Teknologi" selected>Teknologi</option>
                                <option value="Pemrograman">Pemrograman</option>
                                <option value="Desain">Desain</option>
                                <option value="Karir">Karir</option>
                                <option value="Keamanan">Keamanan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Penulis</label>
                            <input type="text" class="form-control" name="penulis" placeholder="Nama penulis..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Terbit</label>
                            <input type="date" class="form-control" name="tanggal" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Isi Artikel</label>
                            <textarea class="form-control" name="isi" rows="10" placeholder="Tulis konten berita secara lengkap di sini..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Terbitkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include "includes/footer.php";
?>
