<?php
// src/user/buatLaporan.php
session_start();

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'mahasiswa') {
    header("Location: ../../index.php");
    exit;
}

include '../layout/header.php';
include '../layout/nav.php';
require '../../public/app.php';

$sukses = false;
$error = false;

// Proses form
if (isset($_POST["submit"])) {
    $_POST['nim'] = $_SESSION['nim']; // Isikan NIM dari session
    if (tambahAduan($_POST) > 0) {
        $sukses = true;
        unset($_POST); // Clear input setelah berhasil
    } else {
        $error = true;
    }
}
?>

<div class="container-fluid page-body-wrapper" style="margin-top:-50px;">
  <?php include '../layout/sidebar.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">

      <h3 class="text-gray-900 text-center text-md-start mb-4" data-aos="fade-left">Buat laporan keluh kesah anda disini</h3>
      <hr>

      <div class="card border-bottom-primary shadow" data-aos="fade-up">
        <div class="card-body">
          <div class="container">

            <?php if ($sukses): ?>
              <div class="alert alert-success alert-dismissible fade show" id="alertBox" role="alert">
                <strong>Sukses!</strong> Laporan anda telah dikirim dan sedang diproses.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php elseif ($error): ?>
              <div class="alert alert-danger alert-dismissible fade show" id="alertBox" role="alert">
                <strong>Gagal!</strong> Laporan tidak dapat diproses. Silakan coba lagi.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <div class="row align-items-center">
              <div class="col-md-4 col-12 text-center mb-4 mb-md-0">
                <img src="../../assets/images/img-buat-laporan.svg" class="img-fluid" alt="Buat Laporan">
              </div>

              <div class="col-md-8 col-12">
                <form action="" method="POST" enctype="multipart/form-data">
                  <div class="row mb-3">
                    <div class="col-md-6 col-12">
                      <label for="tanggal" class="form-label">Tanggal</label>
                      <input type="date" class="form-control mb-3" id="tanggal" name="tgl_pengaduan"
                        value="<?= isset($_POST['tgl_pengaduan']) ? htmlspecialchars($_POST['tgl_pengaduan']) : date('Y-m-d') ?>">

                      <label for="nim" class="form-label">NIM</label>
                      <input type="text" class="form-control mb-3" id="nim" name="nim" disabled value="<?= $_SESSION['nim'] ?>">

                      <label for="foto" class="form-label">Foto</label>
                      <input type="file" class="form-control mb-3" id="foto" name="foto">
                    </div>

                    <div class="col-md-6 col-12">
                      <label for="isi" class="form-label">Isi laporan</label>
                      <textarea class="form-control mb-3" id="isi" rows="7" name="isi_laporan"><?= isset($_POST['isi_laporan']) ? htmlspecialchars($_POST['isi_laporan']) : '' ?></textarea>

                      <input type="hidden" name="status" value="terkirim">

                      <div class="button mt-3">
                        <button class="btn btn-primary" name="submit">Kirim</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
    <?php include '../layout/footer.php'; ?>
  </div>
</div>

<?php include '../layout/scripts.php'; ?>

<!-- Scroll otomatis ke alert jika ada -->
<?php if ($sukses || $error): ?>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const alertBox = document.getElementById("alertBox");
    if (alertBox) {
      alertBox.scrollIntoView({ behavior: "smooth" });
    }
  });
</script>
<?php endif; ?>
