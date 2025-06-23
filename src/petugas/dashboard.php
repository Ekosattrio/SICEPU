<?php
// src/admin/layout.php
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
          <!-- ini kalau mau isi disini -->
                <div class="d-flex justify-content-center text-center py-5" data-aos="zoom-in">
            <div class="content col-lg-8 col-md-10 col-sm-12"> <!-- Menggunakan col untuk ukuran grid -->
              <i class="fas fa-atlas fa-5x mb-2"></i>
              <h1 class="mb-3">Selamat datang kembali di <span class="text-primary">SICEPU</span> <br><br>
                <span class="text-primary">Selamat bekerja</span> dan melakukan aktivitas.
              </h1>
              <a href="laporan.php" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-book-open"></i>
                </span>
                <span class="text">Lihat Laporan</span>
              </a>
            </div>
    </div>
    </div> 

    <?php include '../layout/footer.php'; ?>
  </div> <!-- main-panel -->
</div> <!-- page-body-wrapper -->

<?php include '../layout/scripts.php'; ?>
