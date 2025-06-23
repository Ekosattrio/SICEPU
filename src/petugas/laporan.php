<?php
// src/admin/laporan.php

$title = 'Laporan Mahasiswa Fasilkom';

// koneksi database
require '../../public/app.php';

// panggil template utama
include '../layout/header.php';
include '../layout/nav.php';
?>
<style>
    .main-panel {
  background-color: #f8f9fa; /* warna abu terang */
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
  padding: 20px;
  margin: 15px;
  min-height: 80vh; /* biar gak terlalu kecil */
}
</style>
<div class="container-fluid page-body-wrapper">
  <?php include '../layout/sidebar_petugas.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">
      
      <?php
      // ambil parameter pencarian jika ada
      $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

      if ($searchQuery) {
          $stmt = $conn->prepare("SELECT * FROM pengaduan WHERE status = 'proses' AND nim LIKE ? ORDER BY id_pengaduan DESC");
          $likeSearchQuery = '%' . $searchQuery . '%';
          $stmt->bind_param('s', $likeSearchQuery);
          $stmt->execute();
          $result = $stmt->get_result();
      } else {
          $result = mysqli_query($conn, "SELECT * FROM pengaduan WHERE status = 'proses' ORDER BY id_pengaduan DESC");
      }
      ?>

      <div class="row" data-aos="fade-up">
        <div class="col-12">
          <h3 class="text-gray-800 fs-4 fs-sm-3 fs-md-2">Laporan Mahasiswa Fasilkom</h3>
        </div>
      </div>

      <hr>

      <!-- TABLE untuk desktop -->
      <!-- TABLE untuk desktop -->
<!-- TABLE untuk desktop -->
<div class="table-responsive d-none d-md-block" data-aos="fade-up" data-aos-duration="700">
  <table class="table table-striped table-hover align-middle shadow-sm" style="border-radius: 8px; overflow: hidden; background: #F4F5F7; color: #1F3BB3;">
    <thead style="background: #1F3BB3; color: white;">
      <tr>
        <th scope="col" style="width: 50px; text-align:center;">No</th>
        <th scope="col" style="min-width: 120px;">Tanggal</th>
        <th scope="col" style="min-width: 100px;">NIM</th>
        <th scope="col">Isi Laporan</th>
        <th scope="col" style="width: 100px;">Foto</th>
        <th scope="col" style="min-width: 130px;">Disposisi</th>
      </tr>
    </thead>
    <tbody>
      <?php 
        $i = 1;
        mysqli_data_seek($result, 0);
        while ($row = mysqli_fetch_assoc($result)) : 
      ?>
        <tr style="color: #1F3BB3;">
          <th scope="row" style="text-align:center; vertical-align: middle;"><?= $i; ?></th>
          <td style="vertical-align: middle;"><?= htmlspecialchars($row["tgl_pengaduan"]); ?></td>
          <td style="vertical-align: middle; font-weight: 600;"><?= htmlspecialchars($row["nim"]); ?></td>
          <td style="vertical-align: middle; white-space: pre-wrap; max-width: 400px;"><?= htmlspecialchars($row["isi_laporan"]); ?></td>
          <td style="vertical-align: middle; text-align:center;">
            <?php if ($row['foto']): ?>
              <img src="../../uploads/<?= htmlspecialchars($row['foto']); ?>" 
                   alt="Foto Laporan" class="img-thumbnail cursor-pointer" 
                   style="max-height: 70px; border-radius: 6px;" 
                   onclick="openModal(this)">
            <?php else: ?>
              <span class="text-muted">-</span>
            <?php endif; ?>
          </td>
          <td style="vertical-align: middle;">
            <span class="badge" style="background-color: #1F3BB3; color: white; font-size: 0.9rem; border-radius: 12px; padding: 0.35em 0.75em;">
              <?= htmlspecialchars($row["disposisi"]); ?>
            </span>
          </td>
        </tr>
      <?php 
        $i++;
        endwhile; 
      ?>
    </tbody>
  </table>
</div>



      <!-- CARD untuk mobile -->
      <div class="mobile-card d-block d-md-none">
        <?php 
          $i = 1;
          mysqli_data_seek($result, 0); // reset pointer
          while ($row = mysqli_fetch_assoc($result)) : 
        ?>
          <div class="card shadow-sm mb-3" data-aos="fade-up">
            <div class="card-body">
              <h5 class="card-title mb-2">#<?= $i; ?> - <?= htmlspecialchars($row["nim"]); ?></h5>
              <p class="mb-1"><strong>Tanggal:</strong> <?= htmlspecialchars($row["tgl_pengaduan"]); ?></p>
              <p class="mb-1"><strong>Isi Laporan:</strong><br> <?= htmlspecialchars($row["isi_laporan"]); ?></p>
              <?php if ($row['foto']) : ?>
                <p class="mb-2">
                  <strong>Foto:</strong><br>
                  <img src="../../uploads/<?= htmlspecialchars($row['foto']); ?>" 
                       alt="Foto Laporan" class="report-img img-fluid mt-1" width="100"
                       onclick="openModal(this)">
                </p>
              <?php endif; ?>
              <p class="mb-1"><strong>Disposisi:</strong> <?= htmlspecialchars($row["disposisi"]); ?></p>
            </div>
          </div>
        <?php 
          $i++; 
          endwhile; 
        ?>
      </div>

      <!-- Modal untuk foto -->
      <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
      </div>

      <!-- CSS Modal + Responsive -->
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
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
      }

      .modal-content, #caption {
        animation: zoom 0.6s;
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

      .close:hover, .close:focus {
        color: #bbb;
        cursor: pointer;
      }
      @media (max-width: 576px) {
        h3.text-gray-800 {
          font-size: 1.2rem;
        }}
      /* Responsive Helper */
      @media (max-width: 768px) {
        .table-responsive {
          display: none !important;
        }
        .mobile-card {
          display: block !important;
        }
      }
      </style>

      <!-- JS Modal -->
      <script>
      var modal = document.getElementById("myModal");
      var modalImg = document.getElementById("img01");
      var captionText = document.getElementById("caption");

      document.querySelectorAll('.report-img').forEach(img => {
        img.onclick = function() {
          modal.style.display = "block";
          modalImg.src = this.src;
          captionText.innerHTML = this.alt;
        }
      });

      document.querySelector(".close").onclick = function() {
        modal.style.display = "none";
      }
      </script>

    </div> <!-- content-wrapper -->
  </div> <!-- main-panel -->
</div> <!-- page-body-wrapper -->

<?php 
include '../layout/footer.php'; 
include '../layout/scripts.php'; 
?>
