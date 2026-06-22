<?php
include 'auth.php';
include 'koneksi.php';
$pageTitle = 'Edit Mahasiswa';
$nim = mysqli_real_escape_string($conn, $_GET['nim']);
$query = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE nim='$nim'");
$data = mysqli_fetch_assoc($query);
if (!$data) { $_SESSION['error'] = 'Data tidak ditemukan.'; header('Location: index.php'); exit; }
include 'partials/header.php';
?>
<div class="row justify-content-center">
  <div class="col-12 col-lg-8">
    <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
      <div class="card-header border-0 rounded-top-4 py-3">
        <h1 class="h4 fw-bold mb-1">Edit Data Mahasiswa</h1>
        <p class="mb-0 small text-white-50">Perubahan data mahasiswa akan disimpan ke sistem.</p>
      </div>
      <div class="card-body p-4 p-md-5">
        <form method="POST" action="update.php" enctype="multipart/form-data" class="row g-3" id="editForm">
          <div class="col-12 col-md-4">
            <label class="form-label">NIM</label>
            <input type="text" class="form-control form-control-lg" name="nim" value="<?= htmlspecialchars($data['nim']); ?>" readonly>
          </div>
          <div class="col-12 col-md-8">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control form-control-lg" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required>
          </div>
          <div class="col-12">
            <label class="form-label">Jurusan</label>
            <input type="text" class="form-control form-control-lg" name="jurusan" value="<?= htmlspecialchars($data['jurusan']); ?>" required>
          </div>
          <div class="col-12">
            <label class="form-label">Foto Lama</label>
            <div class="mb-3">
              <?php if (!empty($data['foto'])) { ?>
                <img src="uploads/<?= htmlspecialchars($data['foto']); ?>" class="img-thumbnail rounded-3" style="max-width:140px;max-height:140px;" alt="Foto lama">
              <?php } else { ?>
                <div class="text-muted small">Tidak ada foto</div>
              <?php } ?>
            </div>
          </div>
          <div class="col-12">
            <label class="form-label">Upload Foto Baru</label>
            <input type="file" class="form-control form-control-lg" name="foto" accept="image/png,image/jpg,image/jpeg" id="fotoInput">
          </div>
          <div class="col-12" id="previewWrap" style="display:none;">
            <label class="form-label">Preview Foto</label>
            <div class="border rounded-4 p-3 bg-light">
              <img id="fotoPreview" class="img-fluid rounded-3 img-thumbnail" style="max-height:260px;" alt="Preview Foto">
            </div>
          </div>
          <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save2 me-1"></i> Update</button>
            <a href="index.php" class="btn btn-outline-secondary btn-lg">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
document.getElementById('fotoInput').addEventListener('change', function(event) {
  const file = event.target.files[0];
  const previewWrap = document.getElementById('previewWrap');
  const preview = document.getElementById('fotoPreview');
  if (!file) { previewWrap.style.display = 'none'; return; }
  const reader = new FileReader();
  reader.onload = e => { preview.src = e.target.result; previewWrap.style.display = 'block'; };
  reader.readAsDataURL(file);
});

document.getElementById('editForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const form = this;
  Swal.fire({
    title: 'Simpan Perubahan?',
    text: 'Perubahan data mahasiswa akan disimpan.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Simpan',
    cancelButtonText: 'Batal',
    confirmButtonColor: '#9d2b22',
    cancelButtonColor: '#6c757d'
  }).then((result) => { if (result.isConfirmed) form.submit(); });
});
</script>
<?php include 'partials/alerts.php'; include 'partials/footer.php'; ?>
