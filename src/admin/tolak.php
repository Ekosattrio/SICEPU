<?php
// src/admin/layout.php
include '../layout/header.php';
include '../layout/nav.php';
?>

<div class="container-fluid page-body-wrapper">
  <?php include '../layout/sidebar_admin.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">

      <?php
      $title = 'Tolak Laporan';

      require '../../public/app.php';

      if (isset($_GET['id_pengaduan'])) {
          $id_pengaduan = intval($_GET['id_pengaduan']); // sanitasi input

          $deleteQuery = "DELETE FROM pengaduan WHERE id_pengaduan = $id_pengaduan";
          if (mysqli_query($conn, $deleteQuery)) {
              $sukses = true;
          } else {
              $error = "Gagal menghapus laporan.";
          }
      } else {
          header('Location: laporan.php');
          exit;
      }
      ?>

      <?php if (isset($sukses)) : ?>
        <div class="d-flex justify-content-center py-5 mt-5">
          <div class="card shadow bg-success">
            <div class="card-body text-center text-light">
              <h4>Laporan berhasil ditolak!</h4>
              <hr>
              <img src="../../assets/img/sukses.svg" width="250" alt="Berhasil" data-aos="zoom-in" data-aos-duration="700">
              <div class="button mt-3">
                <a href="laporan.php" class="btn btn-primary text-light shadow">OK!</a>
              </div>
            </div>
          </div>
        </div>
      <?php elseif (isset($error)) : ?>
        <div class="d-flex justify-content-center py-5 mt-5">
          <div class="card shadow bg-danger">
            <div class="card-body text-center text-light">
              <h4><?= $error; ?></h4>
              <hr>
              <div class="button mt-3">
                <a href="laporan.php" class="btn btn-light shadow">Kembali</a>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>


    <?php include '../layout/footer.php'; ?>

<?php include '../layout/scripts.php'; ?>
