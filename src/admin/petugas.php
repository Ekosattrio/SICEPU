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
      // Masukin kode data petugas di sini, tanpa ubah layout

      $title = 'Data Petugas';

      require '../../public/app.php';

      // Query data petugas yang sesuai posisi
      $result = mysqli_query($conn, "SELECT * FROM petugas WHERE posisi IN ('petugasKebersihan', 'teknisi', 'petugasPeralatan')");
      ?>

      <table class="table table-bordered text-center shadow">
        <thead class="thead-dark">
          <tr>
            <th scope="col">No</th>
            <th scope="col">Nama</th>
            <th scope="col">Username</th>
            <th scope="col">Password</th>
            <th scope="col">Telepon</th>
            <th scope="col">Posisi</th>
            <th scope="col">
              <a href="tambah.php" class="btn btn-primary">Tambah Petugas</a>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; ?>
          <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
              <th scope="row"><?= $i; ?>.</th>
              <td><?= $row['nama_petugas']; ?></td>
              <td><?= $row['username']; ?></td>
              <td>*****</td>
              <td><?= $row['telp']; ?></td>
              <td><?= $row['posisi']; ?></td>
              <td>
                <a href="edit.php?id_petugas=<?= $row['id_petugas']; ?>" class="btn btn-outline-success">Edit</a> |
                <a href="hapus.php?id_petugas=<?= $row['id_petugas']; ?>" class="btn btn-outline-danger">Hapus</a>
              </td>
            </tr>
            <?php $i++; ?>
          <?php endwhile; ?>
        </tbody>
      </table>


    <?php include '../layout/footer.php'; ?>

<?php include '../layout/scripts.php'; ?>
