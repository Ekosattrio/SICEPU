<?php
// tanggapi.php

session_start();

$title = 'Tanggapan';

require '../../public/app.php';

// Cek login & posisi user
if (!isset($_SESSION['posisi'])) {
    echo "<div class='alert alert-danger text-center mt-5'>Silakan login terlebih dahulu.</div>";
    exit;
}

$id = isset($_GET['id_pengaduan']) ? intval($_GET['id_pengaduan']) : 0;
if ($id === 0) {
    echo "<div class='alert alert-danger text-center mt-5'>ID Pengaduan tidak valid!</div>";
    exit;
}

$posisi_petugas = $_SESSION['posisi'] ?? '';

// Ambil data pengaduan hanya jika disposisi sesuai posisi petugas yang login
$stmt = $conn->prepare("SELECT * FROM pengaduan WHERE id_pengaduan = ? AND disposisi = ?");
$stmt->bind_param("is", $id, $posisi_petugas);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='alert alert-danger text-center mt-5'>Pengaduan tidak ditemukan atau Anda tidak berhak mengakses pengaduan ini.</div>";
    exit;
}
$pengaduan = $result->fetch_assoc();

$sukses = false;
$error = false;

// Proses form submit tanggapan
if (isset($_POST['submit'])) {
    // Validasi input minimal
    $tgl_tanggapan = $_POST['tgl_tanggapan'] ?? '';
    $tanggapan_text = trim($_POST['tanggapan'] ?? '');
    $id_pengaduan = intval($_POST['id_pengaduan'] ?? 0);

    if (!$tgl_tanggapan || !$tanggapan_text || $id_pengaduan !== $id) {
        $error = true;
    } else {
        // Fungsi tanggapan() harus sudah didefinisikan di app.php
        if (tanggapan($_POST) > 0) {
            $sukses = true;
        } else {
            $error = true;
        }
    }
}

include '../layout/header.php';
include '../layout/nav.php';
?>

<style>
  .main-panel {
    background: #f8f9fa;
    min-height: 85vh;
    padding: 40px 0;
  }
  .card-custom {
    max-width: 900px;
    margin: auto;
    border-radius: 15px;
    box-shadow: 0 6px 15px rgb(31 59 179 / 0.15);
  }
  .img-thumbnail-custom {
    cursor: pointer;
    border-radius: 12px;
    object-fit: cover;
    width: 180px;
    height: 180px;
    border: 3px solid #1F3BB3;
  }
  @media (max-width: 576px) {
    .img-thumbnail-custom {
      width: 100% !important;
      height: auto !important;
      max-width: 300px;
      margin-bottom: 20px;
    }
  }
  .modal-img {
    width: 100%;
    border-radius: 12px;
  }
  .form-control {
    border-radius: 10px;
  }
  label {
    font-weight: 600;
  }
  .textarea-custom {
    border-radius: 10px;
    padding: 12px;
    font-size: 1rem;
    resize: vertical;
    min-height: 130px;
    box-shadow: inset 0 1px 5px rgba(0,0,0,0.1);
    transition: border-color 0.3s ease;
  }
  .textarea-custom:focus {
    border-color: #1F3BB3;
    box-shadow: 0 0 8px #1F3BB3AA;
    outline: none;
  }
</style>

<div class="container-fluid page-body-wrapper">
  <?php include '../layout/sidebar_petugas.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">

      <div class="card card-custom p-4 bg-white">

        <h4 class="mb-4 text-primary">
          Tanggapi Laporan <span class="fw-bold">NIM: <?= htmlspecialchars($pengaduan['nim']); ?></span>
        </h4>

        <div class="row g-4 align-items-start">

          <div class="col-auto text-center">
            <?php if ($pengaduan['foto'] && file_exists("../../uploads/" . $pengaduan['foto'])) : ?>
              <img src="../../uploads/<?= htmlspecialchars($pengaduan['foto']); ?>" alt="Foto Laporan" class="img-thumbnail-custom" data-bs-toggle="modal" data-bs-target="#fotoModal" title="Klik untuk lihat gambar lebih besar">
            <?php else : ?>
              <img src="../../assets/img/no-image.png" alt="Tidak ada foto" class="img-thumbnail-custom" style="opacity: 0.4;" title="Tidak ada foto">
            <?php endif; ?>
          </div>

          <div class="col">

            <div class="mb-4 p-3 bg-light rounded" style="white-space: pre-wrap; min-height: 130px; color:#333; font-size:1rem; box-shadow: inset 0 0 6px #ccc;">
              <?= htmlspecialchars($pengaduan['isi_laporan']); ?>
            </div>

            <?php if ($sukses) : ?>
              <div class="alert alert-success text-center">
                Berhasil menanggapi, terima kasih sudah menanggapi aduan Mahasiswa Fasilkom.
              </div>
            <?php endif; ?>

            <?php if ($error) : ?>
              <div class="alert alert-danger text-center">
                Gagal menanggapi aduan, pastikan data sudah terisi dengan benar dan Anda berhak menanggapi.
              </div>
            <?php endif; ?>

            <form method="post" action="">
              <div class="row g-3 align-items-start">

                <div class="col-auto" style="min-width: 160px;">
                  <label for="tgl_tanggapan" class="form-label">Tanggal Tanggapan</label>
                  <input type="date" class="form-control" id="tgl_tanggapan" name="tgl_tanggapan" required value="<?= isset($_POST['tgl_tanggapan']) ? htmlspecialchars($_POST['tgl_tanggapan']) : ''; ?>">
                </div>

                <div class="col">
                  <label for="tanggapan" class="form-label">Tanggapan</label>
                  <textarea class="form-control textarea-custom" id="tanggapan" name="tanggapan" rows="5" placeholder="Tulis tanggapan kamu di sini..." required><?= isset($_POST['tanggapan']) ? htmlspecialchars($_POST['tanggapan']) : ''; ?></textarea>
                </div>

              </div>

              <input type="hidden" name="id_pengaduan" value="<?= $id; ?>">

              <button type="submit" name="submit" class="btn btn-primary mt-4 w-100">Kirim Tanggapan</button>
            </form>

          </div>
        </div>

      </div>

    </div>
  </div>

  <?php include '../layout/footer.php'; ?>
</div>

<!-- Modal Foto -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="fotoModalLabel">Foto Laporan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0 text-center">
        <?php if ($pengaduan['foto'] && file_exists("../../uploads/" . $pengaduan['foto'])) : ?>
          <img src="../../uploads/<?= htmlspecialchars($pengaduan['foto']); ?>" alt="Foto Laporan" class="modal-img">
        <?php else : ?>
          <img src="../../assets/img/no-image.png" alt="Tidak ada foto" class="modal-img" style="opacity: 0.4;">
        <?php endif; ?> 
      </div>
    </div>
  </div>
</div>

<?php include '../layout/scripts.php'; ?>
