<?php
// src/admin/edit_mahasiswa.php

session_start();
if (!isset($_SESSION['posisi']) || $_SESSION['posisi'] !== 'admin') {
 header("Location: ../../index.php");
  exit;
}

$title = 'Edit Mahasiswa';

require '../../public/app.php';
include '../layout/header.php';
include '../layout/nav.php';
?>

<div class="container-fluid page-body-wrapper">
  <?php include '../layout/sidebar_admin.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">

      <?php
      if (!isset($_GET['id'])) {
          header('Location: mahasiswa.php');
          exit;
      }

      $nim = $_GET['id'];
      $result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE nim = '$nim'");
      $data = mysqli_fetch_assoc($result);

      if (isset($_POST['submit'])) {
          $nama = htmlspecialchars($_POST['nama']);
          $username = htmlspecialchars($_POST['username']);
          $telp = htmlspecialchars($_POST['telp']);
          $password = htmlspecialchars($_POST['password']);

          $query = "UPDATE mahasiswa 
                    SET nama = '$nama', username = '$username', telp = '$telp', password = '$password' 
                    WHERE nim = '$nim'";

          if (mysqli_query($conn, $query)) {
              $sukses = true;
              // refresh data
              $result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE nim = '$nim'");
              $data = mysqli_fetch_assoc($result);
          } else {
              $error = mysqli_error($conn);
          }
      }
      ?>

      <?php if (isset($sukses)) : ?>
        <div class="alert alert-dismissible fade show" style="background-color: #3bb849;" role="alert">
          <h5 class="text-gray-100 mt-2">Akun Mahasiswa Fasilkom Berhasil Diubah!</h5>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true" class="text-light">&times;</span>
          </button>
        </div>
      <?php endif; ?>

      <?php if (isset($error)) : ?>
        <div class="alert alert-dismissible fade show" style="background-color: #b52d2d;" role="alert">
          <h6 class="text-light mt-2">Maaf akun Mahasiswa Fasilkom gagal diubah: <?= $error; ?></h6>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true" class="text-light">&times;</span>
          </button>
        </div>
      <?php endif; ?>

      <div class="container-fluid p-4">
        <div class="row align-items-center">
          <!-- Gambar -->
          <div class="col-md-6 text-center" data-aos="fade-right">
            <div class="image mb-4 mb-md-0">
              <img src="../../assets/img/officer.svg" alt="Officer Image" class="img-fluid">
            </div>
          </div>

          <!-- Form -->
          <div class="col-md-6" data-aos="fade-left">
            <div class="form-wrapper">
              <form action="" method="POST">
                <input type="hidden" name="nim" value="<?= $data['nim']; ?>">

                <div class="form-group mb-3">
                  <input type="text" class="form-control py-3 shadow-sm" name="nama" value="<?= $data['nama']; ?>" placeholder="Nama" required>
                </div>

                <div class="form-group mb-3">
                  <input type="text" class="form-control py-3 shadow-sm" name="username" value="<?= $data['username']; ?>" placeholder="Username" required>
                </div>

                <div class="form-group mb-3">
                  <input type="password" class="form-control py-3 shadow-sm" name="password" value="<?= $data['password']; ?>" placeholder="Password" required>
                </div>

                <div class="form-group mb-4">
                  <input type="text" class="form-control py-3 shadow-sm" name="telp" value="<?= $data['telp']; ?>" placeholder="Telepon" required>
                </div>

                <button type="submit" class="btn btn-primary shadow-sm py-2 w-100" name="submit">
                  Submit
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <style>
        @media (max-width: 767.98px) {
          .image img {
            width: 100% !important;
            margin-bottom: 20px;
          }

          .form-wrapper {
            padding-top: 0;
          }
        }

        @media (min-width: 768px) {
          .image img {
            width: 450px;
          }
        }

        .form-control {
          border-radius: 25px;
        }

        .btn-primary {
          border-radius: 25px;
        }

        .form-wrapper {
          padding: 20px 0;
        }
      </style>

    
    <?php include '../layout/footer.php'; ?>

<?php include '../layout/scripts.php'; ?>
