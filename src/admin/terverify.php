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
      $title = 'Laporan Terverifikasi';
      require '../../public/app.php';
      $result = mysqli_query($conn, "SELECT * FROM pengaduan WHERE status = 'proses' ORDER BY id_pengaduan DESC");
      ?>

      <div class="container-fluid">
        <div class="row mb-3" data-aos="fade-up">
          <div class="col-12">
            <h3 class="text-gray-800">Daftar Laporan yang Sudah Terverifikasi</h3>
          </div>
        </div>

        <hr>

        <!-- TABEL UNTUK DESKTOP -->
        <div class="table-responsive" data-aos="fade-up" data-aos-duration="700">
          <table class="table table-bordered shadow-sm text-center">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>NIM</th>
                <th>Isi Laporan</th>
                <th>Foto</th>
                <th>Disposisi</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
              <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                  <td><?= $i++; ?>.</td>
                  <td><?= htmlspecialchars($row["tgl_pengaduan"]); ?></td>
                  <td><?= htmlspecialchars($row["nim"]); ?></td>
                  <td><?= htmlspecialchars($row["isi_laporan"]); ?></td>
                  <td>
                    <?php if ($row['foto']) : ?>
                      <img class="report-img" src="../../uploads/<?= htmlspecialchars($row['foto']); ?>" alt="Foto Laporan" width="50" onclick="showImageModal('<?= htmlspecialchars($row['foto']); ?>')">
                    <?php endif; ?>
                  </td>
                  <td><?= htmlspecialchars($row["disposisi"]); ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

        <!-- KARTU UNTUK MOBILE -->
        <div class="mobile-card">
          <?php
          mysqli_data_seek($result, 0);
          $i = 1;
          ?>
          <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="card shadow-sm mb-3" data-aos="fade-up">
              <div class="card-body">
                <h5 class="card-title">#<?= $i++; ?> - <?= htmlspecialchars($row["nim"]); ?></h5>
                <p class="mb-1"><strong>Tanggal:</strong> <?= htmlspecialchars($row["tgl_pengaduan"]); ?></p>
                <p class="mb-1"><strong>Isi Laporan:</strong><br> <?= htmlspecialchars($row["isi_laporan"]); ?></p>
                <?php if ($row['foto']) : ?>
                  <p class="mb-1"><strong>Foto:</strong><br>
                    <img class="report-img" src="../../uploads/<?= htmlspecialchars($row['foto']); ?>" alt="Foto Laporan" width="100" onclick="showImageModal('<?= htmlspecialchars($row['foto']); ?>')">
                  </p>
                <?php endif; ?>
                <p class="mb-0"><strong>Disposisi:</strong> <?= htmlspecialchars($row["disposisi"]); ?></p>
              </div>
            </div>
          <?php endwhile; ?>
        </div>

        <!-- MODAL GAMBAR -->
        <div id="imageModal" class="modal">
          <span class="close" onclick="closeImageModal()">&times;</span>
          <img class="modal-content" id="img01">
        </div>
      </div>

    </div> <!-- content-wrapper -->

    <?php include '../layout/footer.php'; ?>
  </div> <!-- main-panel -->
</div> <!-- page-body-wrapper -->

<?php include '../layout/scripts.php'; ?>

<!-- CSS Styling -->
<style>
  /* === TABLE STYLING === */
  table.table thead th {
    background-color: #224abe !important;
    color: white !important;
    padding: 12px !important;
    text-align: left !important;
  }

  table.table tbody td {
    padding: 12px !important;
    vertical-align: middle !important;
    border-bottom: 1px solid #ddd !important;
    color: #333 !important;
  }

  table.table tbody tr:hover {
    background-color: #f1f3ff !important;
  }

  /* === RESPONSIVE VIEW === */
  @media (max-width: 767.98px) {
    .table-responsive {
      display: none !important;
    }
  }

  @media (min-width: 768px) {
    .mobile-card {
      display: none !important;
    }
  }

  /* === RESET RADIUS + SHADOW === */
  table,
  .table-bordered,
  thead th,
  tbody td,
  .card,
  .card-body,
  .modal,
  .modal-content {
    border-radius: 0 !important;
    box-shadow: none !important;
  }

  /* === MODAL IMAGE === */
  .modal {
    display: none;
    position: fixed;
    z-index: 1050;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.9);
  }

  .modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    animation: zoom 0.6s;
  }

  @keyframes zoom {
    from {
      transform: scale(0)
    }

    to {
      transform: scale(1)
    }
  }

  .close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
    border-radius: 0 !important;
    cursor: pointer;
  }

  .close:hover,
  .close:focus {
    color: #bbb;
    text-decoration: none;
  }

  .report-img {
    max-width: 100px;
    cursor: pointer;
    border-radius: 0 !important;
    transition: transform 0.25s ease;
  }

  .report-img:hover {
    transform: scale(1.1);
  }
</style>


<!-- JavaScript Modal -->
<script>
  function showImageModal(imageName) {
    var modal = document.getElementById("imageModal");
    var modalImg = document.getElementById("img01");
    modal.style.display = "block";
    modalImg.src = "../../uploads/" + imageName;
  }

  function closeImageModal() {
    var modal = document.getElementById("imageModal");
    modal.style.display = "none";
  }
</script>
