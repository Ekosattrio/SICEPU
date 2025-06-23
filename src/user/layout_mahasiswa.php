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
require '../../public/app.php';

?>

<div class="container-fluid page-body-wrapper" >
  <?php include '../layout/sidebar.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">
      <!-- ini kalau mau isi disini --> 
    </div> 

    <?php include '../layout/footer.php'; ?>
  
<?php include '../layout/scripts.php'; ?>
