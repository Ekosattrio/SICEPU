<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$namaTampil = 'Mahasiswa'; // default nama
if (!empty($_SESSION['nama_petugas'])) {
    $namaTampil = $_SESSION['nama_petugas'];
} elseif (!empty($_SESSION['nama'])) {
    $namaTampil = $_SESSION['nama'];
}
?>
<nav class="flex-row p-0 navbar default-layout col-lg-12 col-12 fixed-top d-flex align-items-top">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <div class="me-3">
      <button class="navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
        <span class="icon-menu"></span>
      </button>
    </div>
    <div>
      <a class="navbar-brand brand-logo" href="index.php">
        <img src="../../assets/images/sicepupaint.png" alt="logo" />
      </a>
      <a class="navbar-brand brand-logo-mini" href="index.php">
        <img src="../../assets/images/sicepupaint.png" alt="SICEPU" />
      </a>
    </div>
  </div>
  
  <div class="navbar-menu-wrapper d-flex align-items-top"> 
    <ul class="navbar-nav">
      <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
        <h1 class="welcome-text">
          Selamat datang, 
          <span class="text-black fw-bold">
            <?= htmlspecialchars($namaTampil) ?>
          </span>
        </h1>
      </li>
    </ul>

    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>
