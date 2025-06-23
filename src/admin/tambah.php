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
      $title = 'Tambah Petugas';
      require '../../public/app.php';

      if (isset($_POST["submit"])) {
        if (addPetugas($_POST) > 0) {
          $sukses = true;
        } else {
          $error = true;
        }
      }
      ?>

      <?php if (isset($sukses)) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Akun Petugas berhasil dibuat!</strong>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php endif; ?>

      <?php if (isset($error)) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Gagal membuat akun petugas!</strong>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      <?php endif; ?>

      <style>
        .form-card {
          background: rgba(255, 255, 255, 0.1);
          backdrop-filter: blur(20px);
          border-radius: 20px;
          padding: 40px;
          box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
          border: 1px solid rgba(255, 255, 255, 0.18);
        }
        

        .form-control {
          border-radius: 15px;
          background-color: #f4f5f7;
          border: none;
          transition: all 0.3s ease-in-out;
        }
        

        .form-control:focus {
          box-shadow: 0 0 0 2px #1f3bb3;
          background-color: #fff;
        }

        .form-icon {
          position: absolute;
          top: 12px;
          left: 15px;
          color: #1f3bb3;
        }

        .input-group {
          position: relative;
          margin-bottom: 25px;
        }
::placeholder {
  color: #444444 !important;  /* abu gelap, mudah dibaca */
  opacity: 1;
  font-weight: 500;
  font-size: 0.95rem;
}
        .input-group input {
          padding-left: 40px;
        }

        .form-title {
          font-size: 28px;
          font-weight: 600;
          margin-bottom: 25px;
          color: #1f3bb3;
        }

        .btn-custom {
          background-color: #1f3bb3;
          color: #fff;
          border-radius: 25px;
          padding: 12px;
          font-weight: bold;
          transition: background-color 0.3s ease;
        }

        .btn-custom:hover {
          background-color: #152a80;
        }
      </style>

      <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-lg-8 form-card">
          <div class="row">
            <div class="col-md-6 d-none d-md-block">
              <img src="../../assets/images/login.svg" class="img-fluid" alt="Officer">
            </div>
            <div class="col-md-6">
              <h3 class="form-title">Registrasi Petugas</h3>
              <form method="POST">

                <div class="input-group">
                  <i class="fas fa-user form-icon"></i>
                  <input type="text" name="nama_petugas" class="form-control" placeholder="Nama Petugas" required>
                </div>

                <div class="input-group">
                  <i class="fas fa-user-tag form-icon"></i>
                  <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>

                <div class="input-group">
                  <i class="fas fa-lock form-icon"></i>
                  <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>

                <div class="input-group">
                  <i class="fas fa-phone form-icon"></i>
                  <input type="number" name="telp" class="form-control" placeholder="Nomor Telepon" required>
                </div>

                <div class="form-group">
                  <label for="posisi">Pilih Posisi</label>
                  <select class="form-control" id="posisi" name="posisi" required>
                    <option value="petugasKebersihan">Petugas Kebersihan</option>
                    <option value="petugasPeralatan">Petugas Peralatan</option>
                    <option value="teknisi">Teknisi</option>
                  </select>
                </div>

                <button type="submit" name="submit" class="btn btn-custom col-12 mt-3">Daftar Sekarang</button>

              </form>
            </div>
          </div>
        </div>
      </div>

    <?php include '../layout/footer.php'; ?>
<?php include '../layout/scripts.php'; ?>
