<?php
// src/user/layout.php
session_start();

// Cek apakah user sudah login sebagai mahasiswa
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'mahasiswa') {
 header("Location: ../../index.php"); // Redirect ke login jika bukan mahasiswa
    exit;
}

?>
<?php
// src/admin/layout.php
include '../layout/header.php';
include '../layout/nav.php';
?>

<div class="container-fluid page-body-wrapper" style="margin-top:-50px;">
  <?php include '../layout/sidebar.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">
      <!-- ini kalau mau isi disini --> 
       <div class="row py-5 align-items-center">
  <div class="col-md-6 col-12 text-center text-md-start mt-3">
    <div class="desc px-3 px-md-5" data-aos="fade-down">
      <h2 class="text-gray-800">Selamat datang di SICEPU</h2>
      <p>Website ini dibuat untuk melihat laporan atau keluh kesah Mahasiswa Fasilkom dan menjawabnya dengan satu platform.</p>
      <a href="buatLaporan.php" class="btn btn-primary shadow me-2" data-aos="fade-up">Buat Laporan</a>
      <a href="lihatLaporan.php" class="btn btn-outline-primary" data-aos="fade-up" data-aos-duration="500">Lihat Laporan</a>
    </div>
  </div>
  <div class="col-md-6 col-12 text-center mt-4 mt-md-0" data-aos="fade-left">
    <div class="image">
      <img src="../../assets/images/img-dashboard-user.svg" class="img-fluid" alt="Dashboard Image">
    </div>
  </div>
</div>
    </div> 

    <?php include '../layout/footer.php'; ?>

<?php include '../layout/scripts.php'; ?>
