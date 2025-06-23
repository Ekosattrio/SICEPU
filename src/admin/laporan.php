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
      $title = 'Laporan mahasiswa';

      require '../../public/app.php';

      $result = mysqli_query($conn, "SELECT * FROM pengaduan WHERE status = 'terkirim' ORDER BY id_pengaduan DESC");
      ?>
      <div class="container-fluid">
        <div class="row mb-3" data-aos="fade-up">
          <div class="col-12">
            <h3 class="text-gray-800">Daftar Laporan Mahasiswa</h3>
          </div>
        </div>

        <hr>

        <!-- TABLE for Desktop -->
        <div class="table-responsive" data-aos="fade-up" data-aos-duration="700">
          <table>
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>NIM</th>
                <th>Isi Laporan</th>
                <th>Foto</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
              <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                  <td><?= $i; ?>.</td>
                  <td><?= htmlspecialchars($row["tgl_pengaduan"]); ?></td>
                  <td><?= htmlspecialchars($row["nim"]); ?></td>
                  <td><?= htmlspecialchars($row["isi_laporan"]); ?></td>
                  <td>
                    <?php if ($row['foto']) : ?>
                      <img src="../../uploads/<?= htmlspecialchars($row['foto']); ?>" 
                           alt="Foto Laporan" class="report-img"
                           onclick="openModal(this)">
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="verify.php?id_pengaduan=<?= $row["id_pengaduan"]; ?>" class="btn btn-success btn-sm">Verify</a>
                    <button class="btn btn-danger btn-sm" onclick="confirmTolak(<?= $row['id_pengaduan']; ?>)">Tolak</button>
                  </td>
                </tr>
                <?php $i++; ?>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

        <!-- CARD for Mobile -->
        <div class="mobile-card">
          <?php mysqli_data_seek($result, 0); // reset pointer ?>
          <?php $i = 1; ?>
          <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="summary-card" data-aos="fade-up" style="margin-bottom: 1rem; position: relative;">
              <div>
                <h5>#<?= $i; ?> - <?= htmlspecialchars($row["nim"]); ?></h5>
                <p><strong>Tanggal:</strong> <?= htmlspecialchars($row["tgl_pengaduan"]); ?></p>
                <p><strong>Isi Laporan:</strong><br> <?= htmlspecialchars($row["isi_laporan"]); ?></p>
                <?php if ($row['foto']) : ?>
                  <p>
                    <strong>Foto:</strong><br>
                    <img src="../../uploads/<?= htmlspecialchars($row['foto']); ?>" 
                         alt="Foto Laporan" class="report-img mt-1" style="max-width: 100px;"
                         onclick="openModal(this)">
                  </p>
                <?php endif; ?>
                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                  <a href="verify.php?id_pengaduan=<?= $row["id_pengaduan"]; ?>" class="btn btn-success btn-sm">Verify</a>
                  <button class="btn btn-danger btn-sm" onclick="confirmTolak(<?= $row['id_pengaduan']; ?>)">Tolak</button>
                </div>
              </div>
              <i class="summary-icon bi bi-file-earmark-text"></i>
            </div>
            <?php $i++; ?>
          <?php endwhile; ?>
        </div>

        <!-- Modal Preview -->
        <div id="myModal" class="modal" onclick="closeModal()">
          <span class="close" onclick="closeModal()">&times;</span>
          <img class="modal-content" id="img01">
          <div id="caption" class="text-white text-center mt-3"></div>
        </div>
      </div>

      <style>
  /* Table styling */
  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem; /* Untuk memberikan jarak di bagian bawah table */
  }
  thead th {
    background-color: #224abe;
    color: white;
    padding: 12px;
    text-align: left;
    border-bottom: 2px solid #ddd; /* Garis bawah header */
  }
  tbody tr:hover {
    background-color: #f1f3ff;
  }
  tbody td {
    padding: 12px;
    vertical-align: middle;
    border-bottom: 1px solid #ddd; /* Garis antar baris */
  }
  .report-img {
    max-width: 100px;
    cursor: pointer;
    border-radius: 8px; /* Sudut membulatkan untuk gambar */
    transition: transform 0.25s ease;
  }
  .report-img:hover {
    transform: scale(1.1);
  }

  /* Modal */
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
    user-select: none;
  }
  .close:hover { color: #bbb; }

  /* Summary card for mobile */
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

  /* Responsive */
  @media (max-width: 767.98px) {
    .table-responsive { display: none; }
    .report-img { max-width: 70px; }
  }
  @media (min-width: 768px) {
    .mobile-card { display: none; }
  }
</style>


      <script>
        // Modal open and close
        function openModal(img) {
          const modal = document.getElementById('myModal');
          const modalImg = document.getElementById('img01');
          const caption = document.getElementById('caption');

          modal.style.display = 'block';
          modalImg.src = img.src;
          caption.textContent = img.alt;
        }

        function closeModal() {
          document.getElementById('myModal').style.display = 'none';
        }

        // Confirm Tolak action
        function confirmTolak(id_pengaduan) {
          if (confirm('Apakah Anda yakin ingin menolak laporan ini?')) {
            window.location.href = 'tolak.php?id_pengaduan=' + id_pengaduan;
          }
        }
      </script>


    <?php include '../layout/footer.php'; ?>

<?php include '../layout/scripts.php'; ?>
