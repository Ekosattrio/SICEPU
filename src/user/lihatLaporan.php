<?php
// src/user/lihatLaporan.php
session_start();

// Cek user sudah login dan levelnya mahasiswa
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'mahasiswa') {
    header("Location: ../../index.php");
    exit;
}

// Koneksi database, sesuaikan path relatif ini dengan struktur foldermu
require '../../public/app.php';

// Ambil nim dari session
$nim = $_SESSION['nim'] ?? null;

if (!$nim) {
    // Kalau nim gak ada, redirect ke login
    header("Location: ../../login.php");
    exit;
}

// Query ambil data laporan milik user sesuai NIM
$query = "SELECT p.*, t.tanggapan 
          FROM pengaduan p 
          LEFT JOIN tanggapan t ON p.id_pengaduan = t.id_pengaduan
          WHERE p.nim = ? 
          ORDER BY p.tgl_pengaduan DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nim);
$stmt->execute();
$result = $stmt->get_result();

// Include layout
include '../layout/header.php';
include '../layout/nav.php';
?>

<div class="container-fluid page-body-wrapper" >
  <?php include '../layout/sidebar.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">

      <div class="row align-items-center mb-3" data-aos="fade-up">
        <div class="col-md-6 col-12 mb-2 mb-md-0">
          <h3 class="text-gray-800 text-center text-md-start">Daftar Laporan Saya</h3>
        </div>
        <div class="col-md-6 col-12 text-center text-md-end">
          <a href="buatLaporan.php" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
              <i class="fas fa-plus"></i>
            </span>
            <span class="text">Buat Laporan</span>
          </a>
        </div>
      </div>

      <hr>

      <!-- Tampilan Desktop (Tabel) -->
      <div class="table-responsive d-none d-md-block">
        <table class="table table-striped table-bordered text-center">
          <thead class="thead-dark">
            <tr>
              <th>No</th>
              <th>NIM</th>
              <th>Tanggal Laporan</th>
              <th>Laporan</th>
              <th>Tanggapan</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
              <tr>
                <td><?= $i++; ?></td>
                <td><?= htmlspecialchars($row["nim"]); ?></td>
                <td><?= htmlspecialchars($row["tgl_pengaduan"]); ?></td>
                <td><?= htmlspecialchars($row["isi_laporan"]); ?></td>
                <td><?= !empty($row["tanggapan"]) ? htmlspecialchars($row["tanggapan"]) : '-' ?></td>
                <td>
                  <?php if ($row["status"] == 'selesai') : ?>
                    <span class="badge bg-success">Selesai</span>
                  <?php elseif ($row["status"] == 'proses') : ?>
                    <span class="badge bg-warning text-dark">Proses</span>
                  <?php else : ?>
                    <span class="badge bg-secondary">Terkirim</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- Tampilan Mobile: Card -->
      <div class="d-block d-md-none">
        <?php 
          $i = 1; 
          mysqli_data_seek($result, 0); // reset pointer supaya bisa di-loop ulang
        ?>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
          <div class="card shadow-sm mb-3">
            <div class="card-body">
              <h5 class="card-title mb-2">Laporan #<?= $i++; ?></h5>
              <p><strong>NIM:</strong> <?= htmlspecialchars($row["nim"]); ?></p>
              <p><strong>Tanggal:</strong> <?= htmlspecialchars($row["tgl_pengaduan"]); ?></p>
              <p><strong>Isi Laporan:</strong><br><?= nl2br(htmlspecialchars($row["isi_laporan"])); ?></p>
              <p><strong>Tanggapan:</strong><br><?= !empty($row["tanggapan"]) ? nl2br(htmlspecialchars($row["tanggapan"])) : '-'; ?></p>
              <p><strong>Status:</strong>
                <?php if ($row["status"] == 'selesai') : ?>
                  <span class="badge bg-success">Selesai</span>
                <?php elseif ($row["status"] == 'proses') : ?>
                  <span class="badge bg-warning text-dark">Proses</span>
                <?php else : ?>
                  <span class="badge bg-secondary">Terkirim</span>
                <?php endif; ?>
              </p>
            </div>
          </div>
        <?php endwhile; ?>
      </div>

      <!-- Modal Gambar -->
      <div id="myModal" class="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.8);">
        <span class="close text-white" style="position:absolute; top:15px; right:35px; font-size:40px; cursor:pointer;">&times;</span>
        <img class="modal-content d-block mx-auto mt-5" id="img01" style="max-width:90%; max-height:80%;">
        <div id="caption" class="text-center text-white mt-3"></div>
      </div>

      <!-- Script Modal Gambar -->
      <script>
        function openModal(img) {
          var modal = document.getElementById("myModal");
          var modalImg = document.getElementById("img01");
          var captionText = document.getElementById("caption");
          modal.style.display = "block";
          modalImg.src = img.src;
          captionText.innerHTML = img.alt;
        }

        document.querySelector(".modal .close").onclick = function () {
          document.getElementById("myModal").style.display = "none";
        }
      </script>

    </div>

    <?php include '../layout/footer.php'; ?>

<?php include '../layout/scripts.php'; ?>
