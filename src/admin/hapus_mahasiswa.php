<?php
// src/admin/hapus.php

session_start();
$title = 'Hapus';

require '../../public/app.php';

// Cek apakah sudah login
if (!isset($_SESSION['posisi'])) {
    echo "<div class='alert alert-danger text-center mt-5'>Silakan login terlebih dahulu.</div>";
     header("Location: ../../index.php");
    exit;
}

include '../layout/header.php';
include '../layout/nav.php';
?>

<div class="container-fluid page-body-wrapper">
  <?php include '../layout/sidebar_admin.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">

      <?php
      if (!isset($_GET['id'])) {
          echo "<script>window.location.href = 'mahasiswa.php';</script>";
          exit;
      }

      $nim = $_GET['id'];
      $result = deletemahasiswa($nim);

      if ($result > 0) {
          $sukses = true;
      } elseif ($result == -1) {
          $error = "Tidak dapat menghapus mahasiswa karena ada entri terkait.";
      } else {
          $error = "Error deleting record: " . mysqli_error($conn);
      }
      ?>

      <?php if (isset($sukses)) : ?>
        <div class="d-flex justify-content-center py-5 mt-5">
          <div class="card shadow bg-success">
            <div class="card-body text-center">
              <h4 class="text-light">Data Berhasil di Hapus!</h4>
              <hr>
              <img src="../../assets/img/sukses.svg" width="250" alt="Sukses" data-aos="zoom-in" data-aos-duration="700">
              <div class="mt-3">
                <a href="mahasiswa.php" class="btn btn-light text-success shadow">OK!</a>
              </div>
            </div>
          </div>
        </div>
      <?php elseif (isset($error)) : ?>
        <div class="d-flex justify-content-center py-5 mt-5">
          <div class="card shadow bg-danger">
            <div class="card-body text-center">
              <h4 class="text-light"><?= $error; ?></h4>
              <hr>
              <div class="mt-3">
                <a href="mahasiswa.php" class="btn btn-light text-danger shadow">OK!</a>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>

    <?php include '../layout/footer.php'; ?>

<?php include '../layout/scripts.php'; ?>
