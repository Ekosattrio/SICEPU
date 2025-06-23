<?php
// src/admin/tanggapan.php

$title = 'Tanggapan';
require '../../public/app.php';
include '../layout/header.php';
include '../layout/nav.php';
?>

<div class="container-fluid page-body-wrapper">
  <?php include '../layout/sidebar_petugas.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">

<?php
$query = "SELECT * FROM ((tanggapan 
          INNER JOIN pengaduan ON tanggapan.id_pengaduan = pengaduan.id_pengaduan)
          INNER JOIN petugas ON tanggapan.id_petugas = petugas.id_petugas)
          ORDER BY id_tanggapan DESC";

$result = mysqli_query($conn, $query);
?>

<style>
    .table {
        width: 95%;
        margin: 20px auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .table th, .table td {
        padding: 15px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }
    .table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .rating {
        display: flex;
        justify-content: center;
        direction: rtl;
        unicode-bidi: bidi-override;
        font-size: 2.5rem;
    }
    .rating label {
        cursor: pointer;
    }
    .rating label:hover,
    .rating label:hover ~ label,
    .rating input:checked ~ label {
        color: #f5b301;
    }
</style>

<!-- Versi Tabel (Desktop) -->
<div class="table-responsive shadow d-none d-md-block" data-aos="fade-up" data-aos-duration="900">
  <table class="table table-bordered text-center mb-0">
    <thead class="thead-dark">
      <tr>
        <th>No</th>
        <th>NIM</th>
        <th>Tanggal Laporan</th>
        <th>Laporan</th>
        <th>Tanggal Tanggapan</th>
        <th>Tanggapan</th>
        <th>Nama Petugas</th>
        <th>Rating</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; ?>
      <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
          <td><?= $i++; ?></td>
          <td><?= $row["nim"]; ?></td>
          <td><?= $row["tgl_pengaduan"]; ?></td>
          <td><?= $row["isi_laporan"]; ?></td>
          <td><?= $row["tgl_tanggapan"]; ?></td>
          <td><?= $row["tanggapan"]; ?></td>
          <td><?= $row["nama_petugas"]; ?></td>
          <td>
            <?php if ($row["rating"] !== NULL) : ?>
              <div class="rating">
                <?php for ($r = 5; $r >= 1; $r--) : ?>
                  <label style="color: <?= ($row['rating'] >= $r) ? '#f5b301' : '#ddd' ?>;">★</label>
                <?php endfor; ?>
              </div>
            <?php else : ?>
              No Rating
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Versi Card (Mobile) -->
<div class="d-block d-md-none" data-aos="fade-up" data-aos-duration="900">
  <?php $i = 1; mysqli_data_seek($result, 0); ?>
  <?php while ($row = mysqli_fetch_assoc($result)) : ?>
    <div class="card shadow mb-3">
      <div class="card-body">
        <h5 class="text-primary font-weight-bold mb-2">Laporan #<?= $i++; ?></h5>
        <p><strong>NIM:</strong> <?= $row["nim"]; ?></p>
        <p><strong>Tanggal Laporan:</strong> <?= $row["tgl_pengaduan"]; ?></p>
        <p><strong>Laporan:</strong> <?= $row["isi_laporan"]; ?></p>
        <p><strong>Tanggal Tanggapan:</strong> <?= $row["tgl_tanggapan"]; ?></p>
        <p><strong>Tanggapan:</strong> <?= $row["tanggapan"]; ?></p>
        <p><strong>Nama Petugas:</strong> <?= $row["nama_petugas"]; ?></p>
        <p><strong>Rating:</strong>
          <?php if ($row["rating"] !== NULL) : ?>
            <?php for ($r = 5; $r >= 1; $r--) : ?>
              <label style="color: <?= ($row['rating'] >= $r) ? '#f5b301' : '#ddd' ?>;">★</label>
            <?php endfor; ?>
          <?php else : ?>
            No Rating
          <?php endif; ?>
        </p>
      </div>
    </div>
  <?php endwhile; ?>
</div>

    <?php include '../layout/footer.php'; ?>

<?php include '../layout/scripts.php'; ?>
