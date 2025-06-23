<?php
// src/admin/preview_laporan.php

session_start();
if (!isset($_SESSION['posisi']) || $_SESSION['posisi'] !== 'admin') {
 header("Location: ../../index.php");
  exit;
}

$title = 'Preview Laporan';

require '../../public/app.php';
include '../layout/header.php';
include '../layout/nav.php';

$search_nim = '';
if (isset($_GET['search_nim'])) {
    $search_nim = $_GET['search_nim'];
    $query = "SELECT * FROM ((tanggapan 
                INNER JOIN pengaduan ON tanggapan.id_pengaduan = pengaduan.id_pengaduan)
                INNER JOIN petugas ON tanggapan.id_petugas = petugas.id_petugas)
              WHERE pengaduan.nim LIKE '%$search_nim%'
              ORDER BY pengaduan.tgl_pengaduan DESC";
} else {
    $query = "SELECT * FROM ((tanggapan 
                INNER JOIN pengaduan ON tanggapan.id_pengaduan = pengaduan.id_pengaduan)
                INNER JOIN petugas ON tanggapan.id_petugas = petugas.id_petugas)
              ORDER BY pengaduan.tgl_pengaduan DESC";
}

$result = mysqli_query($conn, $query);
?>

<div class="container-fluid page-body-wrapper">
  <?php include '../layout/sidebar_admin.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">

      <!-- Form Cari NIM -->
      <div class="row mb-3">
        <div class="col-12 col-sm-8 col-md-6">
          <form class="form-inline" method="GET" action="">
            <div class="input-group w-100">
              <input 
                type="text" 
                name="search_nim" 
                class="form-control bg-gray-300 border-0 small" 
                placeholder="Cari NIM..." 
                aria-label="Search" 
                aria-describedby="basic-addon2" 
                value="<?= htmlspecialchars($search_nim); ?>">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- List Preview -->
      <div class="row mt-4">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
          <div class="col-12 col-md-6 d-flex align-items-stretch mb-4">
            <div class="card shadow w-100" data-aos="fade-up">
              <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">NIM: <?= htmlspecialchars($row['nim']); ?></h6>
              </div>
              <div class="collapse show" id="generate">
                <div class="card-body d-flex flex-column">
                  <div class="row">
                    <div class="col-12 col-sm-4 text-center mb-3 mb-sm-0">
                      <h6 class="text-primary font-weight-bold mb-2">Foto:</h6>
                      <?php if ($row['foto']): ?>
                        <img src="../../uploads/<?= htmlspecialchars($row['foto']); ?>" alt="Foto Laporan" class="img-fluid rounded report-img" style="max-width: 100px; cursor: pointer;">
                      <?php else: ?>
                        <small class="text-muted">Tidak ada foto</small>
                      <?php endif; ?>
                    </div>
                    <div class="col-12 col-sm-8">
                      <h6><span class="text-primary font-weight-bold">Tanggal Pengaduan:</span> <?= htmlspecialchars($row['tgl_pengaduan']); ?></h6>
                      <h6><span class="text-primary font-weight-bold">Tanggal Tanggapan:</span> <?= htmlspecialchars($row['tgl_tanggapan']); ?></h6>
                    </div>
                  </div>
                  <hr class="bg-primary">
                  <h6><span class="text-primary font-weight-bold">Laporan:</span> <?= nl2br(htmlspecialchars($row['isi_laporan'])); ?></h6>
                  <h6><span class="text-primary font-weight-bold">Tanggapan:</span> <?= nl2br(htmlspecialchars($row['tanggapan'])); ?></h6>
                  <hr class="bg-primary mt-auto">
                  <div class="row align-items-center">
                    <div class="col-8">
                      <h5><span class="text-primary font-weight-bold">Ditanggapi oleh:</span> <?= htmlspecialchars($row['nama_petugas']); ?></h5>
                    </div>
                    <div class="col-4 d-flex justify-content-end">
                      <a href="preview.php?id_tanggapan=<?= htmlspecialchars($row['id_tanggapan']); ?>" class="btn btn-outline-primary btn-sm">Preview</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>

    <?php include '../layout/footer.php'; ?>

<?php include '../layout/scripts.php'; ?>

<!-- Modal Image Preview -->
<style>
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  padding-top: 100px;
  left: 0; top: 0;
  width: 100%; height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.9);
}
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}
.modal-content, #caption {
  animation-name: zoom;
  animation-duration: 0.6s;
}
@keyframes zoom {
  from {transform: scale(0)}
  to {transform: scale(1)}
}
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}
.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}
</style>

<!-- Modal Script -->
<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>

<script>
var modal = document.getElementById("myModal");
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");

document.querySelectorAll('.report-img').forEach(img => {
  img.onclick = function () {
    modal.style.display = "block";
    modalImg.src = this.src;
    captionText.innerHTML = this.alt || "Foto Laporan";
  };
});

document.querySelector(".close").onclick = function () {
  modal.style.display = "none";
};
</script>
