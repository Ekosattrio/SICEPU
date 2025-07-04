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

      $query = "SELECT * FROM ( ( tanggapan INNER JOIN pengaduan ON tanggapan.id_pengaduan = pengaduan.id_pengaduan )
                INNER JOIN petugas ON tanggapan.id_petugas = petugas.id_petugas ) ORDER BY id_tanggapan DESC";

      $result = mysqli_query($conn, $query);
      ?>

      <table class="table table-bordered shadow text-center" data-aos="fade-up" data-aos-duration="900">
        <thead>
          <tr>
            <th scope="col">No</th>
            <th scope="col">NIM</th>
            <th scope="col">Tanggal Laporan</th>
            <th scope="col">Laporan</th>
            <th scope="col">Tanggal Tanggapan</th>
            <th scope="col">Tanggapan</th>
            <th scope="col">Nama Petugas</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; ?>
          <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
              <th scope="row"><?= $i; ?>.</th>
              <td><?= $row["nim"]; ?></td>
              <td><?= $row["tgl_pengaduan"]; ?></td>
              <td><?= $row["isi_laporan"]; ?></td>
              <td><?= $row["tgl_tanggapan"]; ?></td>
              <td><?= $row["tanggapan"]; ?></td>
              <td><?= $row["nama_petugas"]; ?></td>
            </tr>
            <?php $i++; ?>
          <?php endwhile; ?>
        </tbody>
      </table>

    </div> <!-- content-wrapper -->

    <?php include '../layout/footer.php'; ?>
  </div> <!-- main-panel -->
</div> <!-- page-body-wrapper -->

<?php include '../layout/scripts.php'; ?>
