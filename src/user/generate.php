<?php
// src/user/generate.php

session_start();
require_once '../../public/app.php'; // Pastikan path benar ke file koneksi

// Cek apakah user sudah login sebagai mahasiswa
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'mahasiswa') {
     header("Location: ../../index.php");
    exit;
}

// Ambil data tanggapan
$query = "SELECT tanggapan.*, pengaduan.tgl_pengaduan, pengaduan.isi_laporan, pengaduan.foto, petugas.nama_petugas
          FROM tanggapan
          INNER JOIN pengaduan ON tanggapan.id_pengaduan = pengaduan.id_pengaduan
          INNER JOIN petugas ON tanggapan.id_petugas = petugas.id_petugas
          ORDER BY tanggapan.tgl_tanggapan DESC";

$result = mysqli_query($conn, $query);

// Tampilkan halaman
include '../layout/header.php';
include '../layout/nav.php';
?>

<div class="container-fluid page-body-wrapper" >
  <?php include '../layout/sidebar.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">

      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
          <div class="card shadow mb-4 laporan-card" data-aos="fade-up">
            <div class="card-header">
              <div class="row">
                <div class="col-6"></div>
                <div class="col-6 d-flex justify-content-end"></div>
              </div>
            </div>

            <div class="collapse show" id="generate">
              <div class="card-body">
                <div class="row">
                  <div class="col-5">
                    <h6 class="text-primary font-weight-bold">Foto:
                      <?php if ($row['foto']): ?>
                        <img src="../../uploads/<?= htmlspecialchars($row['foto']); ?>" alt="Foto Laporan" class="enlargeable" style="max-width:100px;">
                      <?php else: ?>
                        <span>Tidak ada foto</span>
                      <?php endif; ?>
                    </h6>
                  </div>
                  <div class="col-7">
                    <h6><span class="text-primary font-weight-bold">Tanggal Pengaduan:</span> <?= htmlspecialchars($row['tgl_pengaduan']); ?></h6>
                    <h6><span class="text-primary font-weight-bold">Tanggal Tanggapan:</span> <?= htmlspecialchars($row['tgl_tanggapan']); ?></h6>
                  </div>
                </div>
                <hr class="bg-primary">
                <h6 class="mb-3"><span class="text-primary font-weight-bold">Laporan:</span> <?= nl2br(htmlspecialchars($row['isi_laporan'])); ?></h6>
                <h6><span class="text-primary font-weight-bold">Tanggapan:</span> <?= nl2br(htmlspecialchars($row['tanggapan'])); ?></h6>
                <hr class="bg-primary">
                <div class="row">
                  <div class="col-8 mt-2">
                    <h5><span class="text-primary font-weight-bold">Ditanggapi oleh:</span> <?= htmlspecialchars($row['nama_petugas']); ?></h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center">Belum ada laporan atau tanggapan.</p>
      <?php endif; ?>

    </div>

    <!-- Modal Gambar -->
    <div id="imageModal" class="modal">
      <span class="close-modal">&times;</span>
      <img class="modal-content" id="modalImage">
    </div>

    <?php include '../layout/footer.php'; ?>
  </div>
</div>

<?php include '../layout/scripts.php'; ?>
