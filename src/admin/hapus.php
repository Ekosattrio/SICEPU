<?php
// src/admin/hapus_petugas.php

session_start();
$title = 'Hapus';

require '../../public/app.php';

if (!isset($_SESSION['posisi'])) {
    echo "<div class='alert alert-danger text-center mt-5'>Silakan login terlebih dahulu.</div>";
     header("Location: ../../index.php");
    exit;
}
?>
<?php include '../layout/header.php'; ?>
<?php include '../layout/nav.php'; ?>

<div class="container-fluid page-body-wrapper">
  <?php include '../layout/sidebar_admin.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">

      <?php
      $id = $_GET["id_petugas"];

      $result = deletePetugas($id);

      if ($result > 0) {
          $sukses = true;
      } elseif ($result == -1) {
          $error = "Tidak dapat menghapus petugas karena ada entri tanggapan terkait.";
      } else {
          $error = "Terjadi kesalahan: " . mysqli_error($conn);
      }
      ?>

      <?php if (isset($sukses)) : ?>
          <div class="d-flex justify-content-center py-5 mt-5">
              <div class="card shadow bg-success">
                  <div class="card-body">
                      <h4 class="text-center text-light">Data Berhasil dihapus!</h4>
                      <hr>
                      <img src="../../assets/img/sukses.svg" width="250" alt="" data-aos="zoom-in" data-aos-duration="700">
                      <div class="button mt-3 text-center">
                          <a href="petugas.php" class="btn btn-light text-success shadow">OK!</a>
                      </div>
                  </div>
              </div>
          </div>
      <?php elseif (isset($error)) : ?>
          <div class="d-flex justify-content-center py-5 mt-5">
              <div class="card shadow bg-danger">
                  <div class="card-body">
                      <h4 class="text-center text-light"><?= $error; ?></h4>
                      <hr>
                      <div class="button mt-3 text-center">
                          <a href="petugas.php" class="btn btn-light text-danger shadow">OK!</a>
                      </div>
                  </div>
              </div>
          </div>
      <?php endif; ?>


    <?php include '../layout/footer.php'; ?>

<?php include '../layout/scripts.php'; ?>
