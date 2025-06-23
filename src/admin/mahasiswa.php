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
      $title = 'Data Mahasiswa Fasilkom';

      require '../../public/app.php';

      // Tangkap input pencarian dan filter dari form
      $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
      $filter = isset($_GET['filter']) ? $_GET['filter'] : '';

      // Buat query dasar
      $query = "SELECT * FROM mahasiswa";

      if (!empty($search)) {
          if ($filter == 'nim') {
              $query .= " WHERE nim LIKE '%$search%'";
          } elseif ($filter == 'nama') {
              $query .= " WHERE nama LIKE '%$search%'";
          } else {
              $query .= " WHERE nim LIKE '%$search%' OR nama LIKE '%$search%'";
          }
      }

      $result = mysqli_query($conn, $query);

      $data = [];
      while ($row = mysqli_fetch_assoc($result)) {
          $data[] = $row;
      }
      ?>

      <!-- FORM SEARCH & FILTER -->
  <!-- Search form -->
 <div class="d-flex justify-content-end mb-4" style="max-width: 100%;">
  <form method="GET" style="max-width: 600px; width: 100%;">
    <div class="d-flex gap-2">
      <input 
        type="text" 
        name="search" 
        class="form-control shadow-sm" 
        placeholder="Cari mahasiswa..." 
        value="<?= htmlspecialchars($search) ?>"
        style="height: 42px; flex: 2;"
      >
      <select 
        name="filter" 
        class="form-select shadow-sm" 
        style="height: 42px; flex: 1;"
      >
        <option value="" <?= $filter == '' ? 'selected' : '' ?>>Semua</option>
        <option value="nim" <?= $filter == 'nim' ? 'selected' : '' ?>>NIM</option>
        <option value="nama" <?= $filter == 'nama' ? 'selected' : '' ?>>Nama</option>
      </select>
      <button 
        type="submit" 
        class="btn btn-primary shadow-sm" 
        style="height: 42px; flex: 1;"
      >
        Cari
      </button>
    </div>
  </form>
</div>


      <!-- TABEL UNTUK DESKTOP -->
      <div class="table-responsive d-none d-md-block">
        <table class="table table-bordered text-center shadow">
          <thead class="thead-dark">
            <tr>
              <th>No</th>
              <th>NIM</th>
              <th>Nama</th>
              <th>Username</th>
              <th>Password</th>
              <th>Telepon</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (count($data) > 0) : ?>
              <?php foreach ($data as $index => $row) : ?>
                <tr>
                  <th><?= $index + 1; ?>.</th>
                  <td><?= $row['nim']; ?></td>
                  <td><?= $row['nama']; ?></td>
                  <td><?= $row['username']; ?></td>
                  <td>*****</td>
                  <td><?= $row['telp']; ?></td>
                  <td>
                    <a href="edit_mahasiswa.php?id=<?= $row['nim']; ?>" class="btn btn-outline-success btn-sm">Edit</a>
                    <a href="hapus_mahasiswa.php?id=<?= $row['nim']; ?>" class="btn btn-outline-danger btn-sm">Hapus</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr><td colspan="7">No data available.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- CARD UNTUK MOBILE -->
      <div class="d-block d-md-none">
        <?php if (count($data) > 0) : ?>
          <?php foreach ($data as $index => $row) : ?>
            <div class="card shadow-sm mb-3">
              <div class="card-body">
                <h5 class="card-title"><?= $index + 1; ?>. <?= $row['nama']; ?></h5>
                <p class="mb-1"><strong>NIM:</strong> <?= $row['nim']; ?></p>
                <p class="mb-1"><strong>Username:</strong> <?= $row['username']; ?></p>
                <p class="mb-1"><strong>Password:</strong> *****</p>
                <p class="mb-2"><strong>Telepon:</strong> <?= $row['telp']; ?></p>
                <div class="d-flex justify-content-between">
                  <a href="edit_mahasiswa.php?id=<?= $row['nim']; ?>" class="btn btn-outline-success btn-sm">Edit</a>
                  <a href="hapus_mahasiswa.php?id=<?= $row['nim']; ?>" class="btn btn-outline-danger btn-sm">Hapus</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <div class="alert alert-info text-center">No data available.</div>
        <?php endif; ?>
      </div>


    <?php include '../layout/footer.php'; ?>

<?php include '../layout/scripts.php'; ?>
