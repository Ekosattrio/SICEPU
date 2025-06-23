<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="../petugas/dashboard.php">
        <i class="mdi mdi-grid-large menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <li class="nav-item nav-category">Laporan</li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <i class="menu-icon mdi mdi-table-edit"></i>
        <span class="menu-title">Laporan Mahasiswa</span>
        <i class="menu-arrow"></i> 
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="../petugas/laporan.php">Laporan Mahasiswa</a></li>
          <li class="nav-item"> <a class="nav-link" href="../petugas/terverify.php">Laporan Terveriviskasi</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item nav-category">Tanggapan</li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
        <i class="menu-icon mdi mdi-account-circle-outline"></i>
        <span class="menu-title">Tanggapan</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="auth">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="../petugas/tanggapan.php"> Tanggapan Mahasiswa </a></li>
          <li class="nav-item"> <a class="nav-link" href="../petugas/chart.php"> Chart </a></li>
        </ul>
      </div>
    </li>

    <!-- Logout Button -->
    <li class="nav-item mt-4">
      <a class="nav-link text-danger" href="logout.php" 
         onmouseover="this.style.backgroundColor='#ffebee'; this.style.color='#b71c1c';" 
         onmouseout="this.style.backgroundColor=''; this.style.color='';">
        <i class="mdi mdi-logout menu-icon"></i>
        <span class="menu-title">Logout</span>
      </a>
    </li>

  </ul>
</nav>
