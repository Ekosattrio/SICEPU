<?php
// src/user/tanggapan.php
session_start();

// Cek apakah user sudah login sebagai mahasiswa
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'mahasiswa') {
    header("Location: ../../index.php"); // Redirect ke login jika bukan mahasiswa
    exit;
}

$title = 'Tanggapan';
require '../../public/app.php';
require '../layout/header.php';
require '../layout/nav.php';
?>

<div class="container-fluid page-body-wrapper">
  <?php include '../layout/sidebar.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">
      <!-- isi disini -->
      <?php
      if (!isset($_SESSION['nim']) || !$_SESSION['nim']) {
          header("Location: ../../login.php");
          exit;
      }

      $nim = $_SESSION['nim'];

      if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rating'], $_POST['id_tanggapan'])) {
          $rating = (int)$_POST['rating'];
          $id_tanggapan = (int)$_POST['id_tanggapan'];

          $updateQuery = "UPDATE tanggapan SET rating = ? WHERE id_tanggapan = ? AND EXISTS (SELECT 1 FROM pengaduan WHERE pengaduan.id_pengaduan = tanggapan.id_pengaduan AND pengaduan.nim = ?)";
          $stmt = $conn->prepare($updateQuery);
          if ($stmt) {
              $stmt->bind_param('iii', $rating, $id_tanggapan, $nim);
              $stmt->execute();
              $stmt->close();
          } else {
              echo "Error preparing statement: " . $conn->error;
          }
      }

      $query = "SELECT * FROM ((tanggapan INNER JOIN pengaduan ON tanggapan.id_pengaduan = pengaduan.id_pengaduan AND pengaduan.nim = $nim) INNER JOIN petugas ON tanggapan.id_petugas = petugas.id_petugas) ORDER BY id_tanggapan DESC";
      $result = mysqli_query($conn, $query);
      if (!$result) {
          die("Query failed: " . mysqli_error($conn));
      }
      ?>

      <!-- Style dan Script -->
      <style>
        .table {
          border-collapse: collapse;
          width: 90%;
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
        }
        .rating input { display: none; }
        .rating label {
          font-size: 2rem;
          color: #ddd;
          cursor: pointer;
        }
        .rating label:hover,
        .rating label:hover ~ label,
        .rating input:checked ~ label {
          color: #f5b301;
        }
        .modal {
          display: none;
          position: fixed;
          z-index: 1;
          left: 0;
          top: 0;
          width: 100%;
          height: 100%;
          overflow: auto;
          background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
          background-color: #fefefe;
          margin: 15% auto;
          padding: 20px;
          border: 1px solid #888;
          width: 50%;
          max-width: 500px;
          border-radius: 10px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .close {
          color: #aaa;
          float: right;
          font-size: 28px;
          font-weight: bold;
        }
        .close:hover,
        .close:focus {
          color: black;
          text-decoration: none;
          cursor: pointer;
        }
        .button {
          background-color: #4CAF50;
          border: none;
          color: white;
          padding: 7px 18px;
          text-align: center;
          font-size: 14px;
          margin: 4px 2px;
          cursor: pointer;
          border-radius: 10px;
        }
        .button:hover {
          background-color: #45a049;
          color: white;
        }
      </style>

      <script>
        function showRatingModal(tanggapanId) {
          var modal = document.getElementById("ratingModal");
          modal.style.display = "block";
          document.getElementById("tanggapanId").value = tanggapanId;
        }

        function closeModal() {
          var modal = document.getElementById("ratingModal");
          modal.style.display = "none";
        }

        function submitRating() {
          var tanggapanId = document.getElementById("tanggapanId").value;
          var rating = document.querySelector('input[name="modal-rating"]:checked').value;

          var xhr = new XMLHttpRequest();
          xhr.open("POST", "", true);
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

          xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
              var starsHtml = '';
              for (var r = 5; r >= 1; r--) {
                starsHtml += '<label style="color: ' + (rating >= r ? '#f5b301' : '#ddd') + '">★</label>';
              }
              document.querySelector('button[data-id="' + tanggapanId + '"]').outerHTML = '<div class="rating">' + starsHtml + '</div>';
              closeModal();
            }
          };

          xhr.send("rating=" + rating + "&id_tanggapan=" + tanggapanId);
        }
      </script>

      <!-- Tabel untuk Desktop -->
      <div class="d-none d-md-block">
        <table class="table shadow text-center">
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
            <?php $i = 1; mysqli_data_seek($result, 0); ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
              <tr>
                <th><?= $i++; ?></th>
                <td><?= htmlspecialchars($row["nim"]); ?></td>
                <td><?= htmlspecialchars($row["tgl_pengaduan"]); ?></td>
                <td><?= nl2br(htmlspecialchars($row["isi_laporan"])); ?></td>
                <td><?= htmlspecialchars($row["tgl_tanggapan"]); ?></td>
                <td><?= nl2br(htmlspecialchars($row["tanggapan"])); ?></td>
                <td><?= htmlspecialchars($row["nama_petugas"]); ?></td>
                <td>
                  <?php if (isset($row["rating"])) : ?>
                    <div class="rating">
                      <?php for ($r = 5; $r >= 1; $r--) : ?>
                        <label style="color: <?= ($row['rating'] >= $r) ? '#f5b301' : '#ddd'; ?>">★</label>
                      <?php endfor; ?>
                    </div>
                  <?php else : ?>
                    <button class="button" data-id="<?= $row['id_tanggapan']; ?>" onclick="showRatingModal(<?= $row['id_tanggapan']; ?>)">Beri Rating</button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- Tampilan Mobile -->
      <div class="d-block d-md-none">
        <?php $i = 1; mysqli_data_seek($result, 0); ?>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
          <div class="card shadow-sm mb-3">
            <div class="card-body">
              <p><strong>NIM:</strong> <?= htmlspecialchars($row["nim"]); ?></p>
              <p><strong>Tanggal Laporan:</strong> <?= htmlspecialchars($row["tgl_pengaduan"]); ?></p>
              <p><strong>Isi Laporan:</strong><br><?= nl2br(htmlspecialchars($row["isi_laporan"])); ?></p>
              <p><strong>Tanggal Tanggapan:</strong> <?= htmlspecialchars($row["tgl_tanggapan"]); ?></p>
              <p><strong>Tanggapan:</strong><br><?= nl2br(htmlspecialchars($row["tanggapan"])); ?></p>
              <p><strong>Nama Petugas:</strong> <?= htmlspecialchars($row["nama_petugas"]); ?></p>
              <p><strong>Rating:</strong><br>
                <?php if (isset($row["rating"])) : ?>
                  <div class="rating">
                    <?php for ($r = 5; $r >= 1; $r--) : ?>
                      <label style="color: <?= ($row['rating'] >= $r) ? '#f5b301' : '#ddd'; ?>">★</label>
                    <?php endfor; ?>
                  </div>
                <?php else : ?>
                  <button class="button" data-id="<?= $row['id_tanggapan']; ?>" onclick="showRatingModal(<?= $row['id_tanggapan']; ?>)">Beri Rating</button>
                <?php endif; ?>
              </p>
            </div>
          </div>
        <?php endwhile; ?>
      </div>

      <!-- Modal Rating -->
      <div id="ratingModal" class="modal">
        <div class="modal-content">
          <span class="close" onclick="closeModal()">&times;</span>
          <h2>Apakah Anda puas dengan tanggapan kami?</h2>
          <div class="rating">
            <?php for ($r = 5; $r >= 1; $r--) : ?>
              <input type="radio" name="modal-rating" value="<?= $r; ?>" id="modal-rating-<?= $r; ?>">
              <label for="modal-rating-<?= $r; ?>">★</label>
            <?php endfor; ?>
          </div>
          <input type="hidden" id="tanggapanId">
          <button class="button" onclick="submitRating()">Submit</button>
        </div>
      </div>

    <?php include '../layout/footer.php'; ?>

<?php include '../layout/scripts.php'; ?>
