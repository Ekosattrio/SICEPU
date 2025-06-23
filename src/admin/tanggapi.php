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
      $title = 'Tanggapan';

      require '../../public/app.php';

      $id = $_GET["id_pengaduan"];
      $result = mysqli_query($conn, "SELECT * FROM pengaduan WHERE id_pengaduan = $id");

      if (isset($_POST["submit"])) {
        if (tanggapan($_POST) > 0) {
          $sukses = true;
        } else {
          $error = true;
        }
      }
      ?>

      <div class="d-flex justify-content-center">
        <div class="card w-75 shadow">
          <div class="card-body">
            <?php if (isset($sukses)) : ?>
              <div class="alert alert-dismissible fade show" data-aos="zoom-in" style="background-color: #3bb849;" role="alert">
                <h6 class="text-gray-100 mt-2">Berhasil menanggapi, Terima kasih sudah menanggapi aduan Mahasiswa Fasilkom </h6>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true" class="text-light">&times;</span>
                </button>
              </div>
            <?php endif; ?>

            <?php if (isset($error)) : ?>
              <div class="alert alert-dismissible fade show" data-aos="zoom-in" style="background-color: #b52d2d;" role="alert">
                <h6 class="text-light mt-2">Maaf Laporan sudah di tanggapi</h6>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true" class="text-light">&times;</span>
                </button>
              </div>
            <?php endif; ?>

            <div class="row">
              <div class="col-6">
                <div class="image">
                  <img src="../../assets/img/tanggapan.svg" width="350" alt="">
                </div>
              </div>
              <div class="col-6">
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                  <form action="" method="POST">
                    <input type="hidden" name="id_pengaduan" value="<?= $row['id_pengaduan']; ?>">
                    <div class="form-row mb-2">
                      <div class="col">
                        <label for="nim">NIM</label>
                        <input type="text" class="form-control" id="nim" name="nim" value="<?= $row['nim']; ?>" readonly>
                      </div>
                      <div class="col">
                        <label for="tgl_tanggapan">Tanggal Tanggapan</label>
                        <input type="date" class="form-control" id="tgl_tanggapan" name="tgl_tanggapan" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="isi_laporan">Aduan</label>
                      <input type="text" class="form-control" id="isi_laporan" name="isi_laporan" value="<?= $row['isi_laporan']; ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label for="tanggapan">Tanggapan</label>
                      <textarea class="form-control" id="tanggapan" name="tanggapan" required></textarea>
                    </div>
                    <div class="form-group">
                      <label for="id_petugas">Petugas</label>
                      <select name="id_petugas" id="id_petugas" class="form-control" required>
                        <option disabled selected>Pilih Petugas</option>
                        <option value="1">Admin</option>
                        <!-- Tambahkan daftar petugas lain jika perlu -->
                      </select>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Tanggapi</button>
                  </form>
                <?php endwhile; ?>
              </div>
            </div>

          </div>
        </div>
      </div>

    <?php include '../layout/footer.php'; ?>

<?php include '../layout/scripts.php'; ?>
