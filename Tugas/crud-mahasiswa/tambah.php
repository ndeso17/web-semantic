<?php
include 'auth.php';
$pageTitle = 'Tambah Mahasiswa';
include 'partials/header.php';
?>
<div class="row justify-content-center">
  <div class="col-12 col-lg-8">
    <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
      <div class="card-header border-0 rounded-top-4 py-3">
        <h1 class="h4 fw-bold mb-1">Tambah Data Mahasiswa</h1>
        <p class="mb-0 small text-white-50">Lengkapi data mahasiswa dan unggah foto.</p>
      </div>
      <div class="card-body p-4 p-md-5">
        <form method="POST" action="insert.php" enctype="multipart/form-data" class="row g-3">
          <div class="col-12 col-md-4">
            <label class="form-label">NIM</label>
            <input type="text" class="form-control form-control-lg" name="nim" required>
          </div>
          <div class="col-12 col-md-8">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control form-control-lg" name="nama" required>
          </div>
          <div class="col-12">
            <label class="form-label">Jurusan</label>
            <input type="text" class="form-control form-control-lg" name="jurusan" required>
          </div>
          <div class="col-12">
            <label class="form-label">Foto</label>
            <input type="file" class="form-control form-control-lg" name="foto" accept="image/png,image/jpg,image/jpeg" required id="fotoInput">
          </div>
          <div class="col-12" id="previewWrap" style="display:none;">
            <label class="form-label">Preview Foto</label>
            <div class="border rounded-4 p-3 bg-light">
              <img id="fotoPreview" class="img-fluid rounded-3 img-thumbnail" style="max-height:260px;" alt="Preview Foto">
            </div>
          </div>
          <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save me-1"></i> Simpan</button>
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
</script>
<?php include 'partials/alerts.php'; include 'partials/footer.php'; ?>
