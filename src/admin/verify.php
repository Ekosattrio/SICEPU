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
      $title = 'verify';

      require '../../public/app.php';

      $id = isset($_GET['id_pengaduan']) ? intval($_GET['id_pengaduan']) : 0;
      $verify = mysqli_query($conn, "SELECT * FROM pengaduan WHERE id_pengaduan = $id");

      if (isset($_POST['submit'])) {
        if (verify($_POST) > 0) {
          $sukses = true;
        } else {
          $error = true;
        }
      }
      ?>

      <div class="container mt-5">
        <div class="row justify-content-center">
          <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow" data-aos="fade-up">
              <div class="card-body">

                <!-- Notifikasi -->
                <?php if (isset($sukses)) : ?>
                  <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="zoom-in">
                    <strong>Berhasil!</strong> Verifikasi berhasil dilakukan.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                <?php endif; ?>

                <?php if (isset($error)) : ?>
                  <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="zoom-in">
                    <strong>Gagal!</strong> Verifikasi gagal dilakukan.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                <?php endif; ?>

                <!-- Judul -->
                <h4 class="text-center text-primary mb-4">Verifikasi Pengaduan</h4>

                <!-- Form -->
                <form action="" method="POST">
                  <?php while ($row = mysqli_fetch_assoc($verify)) : ?>
                    <input type="hidden" name="id_pengaduan" value="<?= $row['id_pengaduan']; ?>">
                    <input type="hidden" name="foto" value="<?= $row['foto']; ?>">
                    <input type="hidden" name="status" value="proses">

                    <div class="form-group">
                      <label for="tgl_pengaduan">Tanggal Pengaduan</label>
                      <input type="text" class="form-control" id="tgl_pengaduan" name="tgl_pengaduan" value="<?= htmlspecialchars($row['tgl_pengaduan']); ?>" readonly>
                    </div>

                    <div class="form-group">
                      <label for="nim">NIM</label>
                      <input type="text" class="form-control" id="nim" name="nim" value="<?= htmlspecialchars($row['nim']); ?>" readonly>
                    </div>

                    <div class="form-group">
                      <label for="isi_laporan">Isi Laporan</label>
                      <textarea class="form-control" id="isi_laporan" name="isi_laporan" rows="3" readonly><?= htmlspecialchars($row['isi_laporan']); ?></textarea>
                    </div>

                    <div class="form-group">
                      <label for="disposisi">Disposisi</label>
                      <select class="form-control" id="disposisi" name="disposisi" required>
                        <option value="">-- Pilih Disposisi --</option>
                        <option value="petugasKebersihan">Petugas Kebersihan</option>
                        <option value="petugasPeralatan">Petugas Peralatan</option>
                        <option value="teknisi">Teknisi</option>
                      </select>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary btn-block">Verifikasi</button>
                  <?php endwhile; ?>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>

    <?php include '../layout/footer.php'; ?>

<?php include '../layout/scripts.php'; ?>
