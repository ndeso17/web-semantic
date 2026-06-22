<?php
include 'auth.php';
include 'koneksi.php';
$pageTitle = 'Dashboard - Sistem Data Mahasiswa';
$data = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY nim ASC");
$totalMahasiswa = mysqli_num_rows($data);
mysqli_data_seek($data, 0);
include 'partials/header.php';
?>
<div class="row g-4 mb-4">
    <div class="col-12 col-lg-4" data-aos="fade-up">
        <div class="card border-0 shadow-sm glass-card rounded-4 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-4 text-white d-flex align-items-center justify-content-center"
                    style="width:72px;height:72px;background:var(--primary);">
                    <i class="bi bi-people-fill fs-2"></i>
                </div>
                <div>
                    <p class="text-muted mb-1">Total Mahasiswa</p>
                    <h2 class="fw-bold mb-0 text-brand"><?= $totalMahasiswa; ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-8" data-aos="fade-up" data-aos-delay="100">
        <div class="card border-0 shadow-sm glass-card rounded-4 h-100">
            <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <h1 class="h4 fw-bold mb-1 text-brand">Sistem Data Mahasiswa</h1>
                </div>
                <a class="btn btn-primary btn-lg" href="tambah.php"><i class="bi bi-plus-circle me-1"></i> Tambah
                    Mahasiswa</a>
            </div>
        </div>
    </div>
</div>
<div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="150">
    <div class="card-body">
        <?php if ($totalMahasiswa === 0): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-3 text-brand"></i>
            <h3 class="h5 fw-semibold">Belum ada data mahasiswa</h3>
            <p class="mb-0">Tambahkan data pertama dari tombol di atas.</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table id="mahasiswaTable" class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:70px;">No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th>Foto</th>
                        <th style="width:180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($data)) { ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nim']); ?></td>
                        <td><?= htmlspecialchars($row['nama']); ?></td>
                        <td><?= htmlspecialchars($row['jurusan']); ?></td>
                        <td>
                            <?php if (!empty($row['foto']) && file_exists('uploads/'.$row['foto'])) { ?>
                            <img src="uploads/<?= htmlspecialchars($row['foto']); ?>"
                                class="img-thumbnail rounded object-fit-cover" style="width:80px;height:80px;"
                                alt="Foto <?= htmlspecialchars($row['nama']); ?>">
                            <?php } else { ?>
                            <span class="text-muted small">Tidak ada foto</span>
                            <?php } ?>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                <a class="btn btn-warning btn-sm" href="edit.php?nim=<?= urlencode($row['nim']); ?>"><i
                                        class="bi bi-pencil-square me-1"></i>Edit</a>
                                <a class="btn btn-outline-danger btn-sm btn-delete"
                                    href="delete.php?nim=<?= urlencode($row['nim']); ?>"><i
                                        class="bi bi-trash me-1"></i>Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
<script>
$(function() {
    $('#mahasiswaTable').DataTable({
        pageLength: 10,
        lengthChange: false
    });
});
$(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();
    const url = $(this).attr('href');
    Swal.fire({
        title: 'Hapus Data?',
        text: 'Data yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#9d2b22',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) window.location.href = url;
    });
});
</script>
<?php include 'partials/alerts.php'; include 'partials/footer.php'; ?>