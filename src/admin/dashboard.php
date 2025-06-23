<?php
session_start();
if (!isset($_SESSION['posisi']) || $_SESSION['posisi'] !== 'admin') {
  echo "<div class='alert alert-danger text-center mt-5'>Silakan login sebagai admin terlebih dahulu.</div>";
  header("Location: ../../index.php");
  exit;
}

$title = 'Dashboard';
require '../../public/app.php';
include '../layout/header.php';
include '../layout/nav.php';

// Query data summary
$pengaduanCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan"))['total'];
$tanggapanCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tanggapan"))['total'];
$mahasiswaCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM mahasiswa"))['total'];

// Query detail tanggapan terbaru
$query = "SELECT tanggapan.*, pengaduan.nim, pengaduan.foto, pengaduan.tgl_pengaduan, pengaduan.isi_laporan, petugas.nama_petugas
          FROM tanggapan
          INNER JOIN pengaduan ON tanggapan.id_pengaduan = pengaduan.id_pengaduan
          INNER JOIN petugas ON tanggapan.id_petugas = petugas.id_petugas
          ORDER BY tanggapan.tgl_tanggapan DESC
          LIMIT 10";
$result = mysqli_query($conn, $query);
?>

<style>
  /* Card summary styling */
  .summary-card {
    border-radius: 15px;
    background: linear-gradient(135deg, #4e73df, #224abe);
    color: white;
    box-shadow: 0 4px 10px rgba(34, 74, 190, 0.4);
    transition: transform 0.3s ease;
    position: relative;
    padding: 1.5rem;
  }
  .summary-card:hover {
    transform: translateY(-5px);
  }
  .summary-icon {
    font-size: 3.5rem;
    opacity: 0.15;
    position: absolute;
    right: 20px;
    top: 20px;
    transition: opacity 0.3s ease;
  }
  .summary-card:hover .summary-icon {
    opacity: 0.3;
  }

  /* Table styling */
  table {
    width: 100%;
    border-collapse: collapse;
  }
  thead th {
    background-color: #224abe;
    color: white;
    padding: 12px;
    text-align: left;
  }
  tbody tr:hover {
    background-color: #f1f3ff;
  }
  tbody td {
    padding: 12px;
    vertical-align: middle;
  }
  .report-img {
    max-width: 100px;
    cursor: pointer;
    border-radius: 8px;
    transition: transform 0.25s ease;
  }
  .report-img:hover {
    transform: scale(1.1);
  }

  /* Modal image zoom */
  .modal {
    display: none; 
    position: fixed; 
    z-index: 1050;
    left: 0; top: 0; width: 100%; height: 100%;
    overflow: auto; background-color: rgba(0,0,0,0.85);
  }
  .modal-content {
    margin: 5% auto; display: block; max-width: 700px; border-radius: 10px;
  }
  .close {
    position: absolute; top: 15px; right: 35px; color: white; font-size: 40px; font-weight: bold;
    cursor: pointer;
  }
  .close:hover { color: #bbb; }

  /* Responsive */
  @media (max-width: 768px) {
    .summary-card { margin-bottom: 1rem; }
    .report-img { max-width: 70px; }
  }
</style>

<div class="container-fluid page-body-wrapper">
  <?php include '../layout/sidebar_admin.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper p-4">

      <!-- Summary cards -->
      <div class="row mb-5">
        <div class="col-md-4">
          <div class="summary-card shadow">
            <div class="h4 font-weight-bold">Pengaduan</div>
            <div class="display-4 font-weight-bold"><?= $pengaduanCount; ?></div>
            <i class="fas fa-comment summary-icon"></i>
          </div>
        </div>
        <div class="col-md-4">
          <div class="summary-card shadow" style="background: linear-gradient(135deg, #1cc88a, #17a673); box-shadow: 0 4px 10px rgba(23, 166, 115, 0.4);">
            <div class="h4 font-weight-bold">Tanggapan</div>
            <div class="display-4 font-weight-bold"><?= $tanggapanCount; ?></div>
            <i class="fas fa-comments summary-icon"></i>
          </div>
        </div>
        <div class="col-md-4">
          <div class="summary-card shadow" style="background: linear-gradient(135deg, #f6c23e, #dda20a); box-shadow: 0 4px 10px rgba(221, 162, 10, 0.4);">
            <div class="h4 font-weight-bold">Akun Mahasiswa</div>
            <div class="display-4 font-weight-bold"><?= $mahasiswaCount; ?></div>
            <i class="fas fa-users summary-icon"></i>
          </div>
        </div>
      </div>

      <!-- Latest tanggapan table -->
      <div class="card-body p-0">
        <table>
          <thead>
            <tr>
              <th>NIM</th>
              <th>Foto Laporan</th>
              <th>Tanggal Pengaduan</th>
              <th>Tanggal Tanggapan</th>
              <th>Isi Laporan</th>
              <th>Tanggapan</th>
              <th>Ditanggapi Oleh</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
              <tr>
                <td><?= htmlspecialchars($row['nim']); ?></td>
                <td>
                  <?php if ($row['foto']) : ?>
                    <img src="../../uploads/<?= htmlspecialchars($row['foto']); ?>" alt="Foto Laporan" class="report-img" data-alt="<?= htmlspecialchars($row['isi_laporan']); ?>">
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['tgl_pengaduan']); ?></td>
                <td><?= htmlspecialchars($row['tgl_tanggapan']); ?></td>
                <td><?= htmlspecialchars(substr($row['isi_laporan'], 0, 50)); ?>...</td>
                <td><?= htmlspecialchars(substr($row['tanggapan'], 0, 50)); ?>...</td>
                <td><?= htmlspecialchars($row['nama_petugas']); ?></td>
                <td><a href="preview.php?id_tanggapan=<?= htmlspecialchars($row['id_tanggapan']); ?>" class="btn btn-sm btn-outline-primary">Preview</a></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    </div> <!-- content-wrapper -->
  </div> <!-- main-panel -->
</div> <!-- container-fluid -->

<!-- Modal gambar -->
<div id="imgModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="modalImg">
  <div id="caption" style="color:#fff; text-align:center; padding:10px 0;"></div>
</div>

<script>
  const modal = document.getElementById("imgModal");
  const modalImg = document.getElementById("modalImg");
  const captionText = document.getElementById("caption");
  const closeBtn = modal.querySelector(".close");

  document.querySelectorAll('.report-img').forEach(img => {
    img.addEventListener('click', function() {
      modal.style.display = "block";
      modalImg.src = this.src;
      captionText.textContent = this.getAttribute('data-alt') || '';
    });
  });

  closeBtn.onclick = function() {
    modal.style.display = "none";
  }

  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
</script>

<?php include '../layout/footer.php'; ?>
<?php include '../layout/scripts.php'; ?>
