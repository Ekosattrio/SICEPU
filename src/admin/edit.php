<?php
// src/admin/edit_petugas.php

session_start();
if (!isset($_SESSION['posisi']) || $_SESSION['posisi'] !== 'admin') {
  header('Location: ../../index.php');
  exit;
}

$title = 'Edit Petugas';

require '../../public/app.php';
include '../layout/header.php';
include '../layout/nav.php';

$id = $_GET["id_petugas"] ?? 0;
$result = mysqli_query($conn, "SELECT * FROM petugas WHERE id_petugas = $id");

if (isset($_POST["submit"])) {
  if (editPetugas($_POST) > 0) {
    $sukses = true;
    $result = mysqli_query($conn, "SELECT * FROM petugas WHERE id_petugas = $id"); // refresh data
  } else {
    $error = mysqli_error($conn);
  }
}
?>

<div class="container-fluid page-body-wrapper">
  <?php include '../layout/sidebar_admin.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">

      <?php if (isset($sukses)) : ?>
        <div class="alert alert-dismissible fade show" style="background-color: #3bb849;" role="alert">
          <h5 class="text-gray-100 mt-2">Akun Petugas Berhasil Diubah!</h5>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true" class="text-light">&times;</span>
          </button>
        </div>
      <?php endif; ?>

      <?php if (isset($error)) : ?>
        <div class="alert alert-dismissible fade show" style="background-color: #b52d2d;" role="alert">
          <h6 class="text-light mt-2">Maaf akun petugas gagal diubah</h6>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true" class="text-light">&times;</span>
          </button>
        </div>
      <?php endif; ?>

      <div class="p-5">
        <div class="row">
          <div class="col-6 text-center" data-aos="fade-right">
            <div class="image">
              <img src="../../assets/img/officer.svg" width="450" alt="">
            </div>
          </div>

          <div class="col-6" data-aos="fade-left">
            <form action="" method="POST">
              <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <div class="form-group">
                  <input type="hidden" class="form-control py-4 shadow-sm" value="<?= $row['id_petugas']; ?>" name="id_petugas" style="border-radius: 25px;">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control py-4 shadow-sm" value="<?= $row['nama_petugas']; ?>" name="nama_petugas" placeholder="Nama Petugas" required style="border-radius: 25px;">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control py-4 shadow-sm" value="<?= $row['username']; ?>" name="username" placeholder="Username" required style="border-radius: 25px;">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control py-4 shadow-sm" value="<?= $row['password']; ?>" name="password" placeholder="Password" required style="border-radius: 25px;">
                </div>
                <div class="form-group">
                  <input type="number" class="form-control py-4 shadow-sm" value="<?= $row['telp']; ?>" name="telp" placeholder="Telepon" required style="border-radius: 25px;">
                </div>
                <div class="form-group">
                  <input type="hidden" class="form-control py-4 shadow-sm" value="<?= $row['posisi']; ?>" name="posisi" style="border-radius: 25px;">
                </div>
                <div class="button">
                  <button class="btn btn-primary shadow-sm py-2 col-12" name="submit" style="border-radius: 25px;">Submit</button>
                </div>
              <?php endwhile; ?>
            </form>
          </div>
        </div>
      </div>

    <?php include '../layout/footer.php'; ?>

<?php include '../layout/scripts.php'; ?>
