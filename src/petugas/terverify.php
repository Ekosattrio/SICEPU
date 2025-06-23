<?php
session_start();

$title = 'Laporan Terverifikasi';

require '../../public/app.php';
include '../layout/header.php';
include '../layout/nav.php';

// Cek apakah user sudah login sebagai petugas
if (!isset($_SESSION['posisi'])) {
    echo "<div class='alert alert-danger text-center mt-5'>Silakan login terlebih dahulu sebagai petugas.</div>";
    exit;
}

$posisi = $_SESSION['posisi'];
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Query ambil laporan yang status = 'proses' dan disposisi ke posisi login
if ($searchQuery) {
    $stmt = $conn->prepare("SELECT * FROM pengaduan WHERE status = 'proses' AND disposisi = ? AND nim LIKE ? ORDER BY id_pengaduan DESC");
    $likeSearchQuery = '%' . $searchQuery . '%';
    $stmt->bind_param('ss', $posisi, $likeSearchQuery);
} else {
    $stmt = $conn->prepare("SELECT * FROM pengaduan WHERE status = 'proses' AND disposisi = ? ORDER BY id_pengaduan DESC");
    $stmt->bind_param('s', $posisi);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<style>
.main-panel {
  background-color: #f8f9fa;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
  padding: 20px;
  margin: 15px;
  min-height: 80vh;
}
</style>

<div class="container-fluid page-body-wrapper">
  <?php include '../layout/sidebar_petugas.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">

      <!-- Judul -->
      <div class="row" data-aos="fade-up">
        <div class="col-12">
          <h3 class="text-gray-800 fs-4">Laporan Status Proses</h3>
        </div>
      </div>
      <hr>

      <!-- Tabel Desktop -->
      <div class="table-responsive d-none d-md-block" data-aos="fade-up" data-aos-duration="700">
        <table class="table table-striped table-hover align-middle shadow-sm" style="border-radius: 8px; overflow: hidden; background: #F4F5F7; color: #1F3BB3;">
          <thead style="background: #1F3BB3; color: white;">
            <tr class="text-center">
              <th>No</th>
              <th>Tanggal</th>
              <th>NIM</th>
              <th>Isi Laporan</th>
              <th>Foto</th>
              <th>Status</th> <!-- kolom status ditambahkan -->
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; $rows = []; while ($row = $result->fetch_assoc()) : $rows[] = $row; ?>
            <tr>
              <td class="text-center"><?= $i++; ?></td>
              <td><?= htmlspecialchars($row["tgl_pengaduan"]); ?></td>
              <td style="font-weight: 600;"><?= htmlspecialchars($row["nim"]); ?></td>
              <td style="white-space: pre-wrap; max-width: 400px;"><?= htmlspecialchars($row["isi_laporan"]); ?></td>
              <td class="text-center">
                <?php if ($row['foto']) : ?>
                  <img src="../../uploads/<?= htmlspecialchars($row['foto']); ?>" 
                       alt="Foto Laporan" class="img-thumbnail report-img" 
                       style="max-height: 70px; border-radius: 6px; cursor:pointer;" 
                       onclick="openModal(this)">
                <?php else : ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td class="text-center"><?= htmlspecialchars($row["status"]); ?></td> <!-- tampilkan status -->
              <td class="text-center">
                <a href="tanggapi.php?id_pengaduan=<?= $row["id_pengaduan"]; ?>" class="btn btn-outline-success">Tanggapi</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- Card Mobile -->
      <div class="mobile-card d-block d-md-none">
        <?php $i = 1; foreach ($rows as $row) : ?>
        <div class="card shadow-sm mb-3" data-aos="fade-up">
          <div class="card-body">
            <h5 class="card-title mb-2">#<?= $i++; ?> - <?= htmlspecialchars($row["nim"]); ?></h5>
            <p><strong>Tanggal:</strong> <?= htmlspecialchars($row["tgl_pengaduan"]); ?></p>
            <p><strong>Isi:</strong><br> <?= htmlspecialchars($row["isi_laporan"]); ?></p>
            <?php if ($row['foto']) : ?>
              <p><strong>Foto:</strong><br>
                <img src="../../uploads/<?= htmlspecialchars($row['foto']); ?>" class="report-img img-fluid mt-1" width="100" onclick="openModal(this)">
              </p>
            <?php endif; ?>
            <p><strong>Status:</strong> <?= htmlspecialchars($row["status"]); ?></p> <!-- status di mobile card -->
            <a href="tanggapi.php?id_pengaduan=<?= $row["id_pengaduan"]; ?>" class="btn btn-success btn-sm">Lihat Tanggapan</a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Modal Foto -->
      <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
      </div>

      <style>
      .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
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
        text-align: center;
        color: #ccc;
        padding: 10px 0;
      }
      @keyframes zoom {
        from {transform: scale(0)}
        to {transform: scale(1)}
      }
      .modal-content, #caption {
        animation: zoom 0.6s;
      }
      .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: white;
        font-size: 40px;
        font-weight: bold;
      }
      .close:hover { color: #bbb; cursor: pointer; }
      </style>

      <script>
      function openModal(img) {
        var modal = document.getElementById("myModal");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        modal.style.display = "block";
        modalImg.src = img.src;
        captionText.innerHTML = img.alt;
      }
      document.querySelector(".close").onclick = function () {
        document.getElementById("myModal").style.display = "none";
      };
      </script>

<?php 
include '../layout/footer.php'; 
include '../layout/scripts.php'; 
?>
