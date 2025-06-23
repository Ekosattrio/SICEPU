<?php
session_start();

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'mahasiswa') {
     header("Location: ../../index.php");
    exit;
}

$title = 'Preview Tanggapan';

require '../../public/app.php';
include '../layout/header.php';
include '../layout/nav.php';
?>

<div class="container-fluid page-body-wrapper">
  <?php include '../layout/sidebar.php'; ?>

  <div class="main-panel">
    <div class="content-wrapper">

      <?php
      $id = $_GET['id_tanggapan'];
      $query = "SELECT * FROM (( tanggapan 
        INNER JOIN pengaduan ON tanggapan.id_pengaduan = pengaduan.id_pengaduan ) 
        INNER JOIN petugas ON tanggapan.id_petugas = petugas.id_petugas) 
        WHERE id_tanggapan = $id";
      $result = mysqli_query($conn, $query);
      ?>

      <div class="d-flex justify-content-center py-5">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
          <div class="card shadow mb-4 w-50" data-aos="fade-up">
            <div class="card-header">
              <h6 class="font-weight-bold text-primary mt-2">NIM: <?= $row['nim']; ?></h6>
            </div>
            <div class="collapse show" id="generate">
              <div class="card-body">
                <div class="row">
                  <div class="col-5">
                    <h6 class="text-primary font-weight-bold">Foto:</h6>
                    <?php if ($row['foto']) : ?>
                      <img src="../../uploads/<?= $row['foto']; ?>" alt="Foto Laporan" width="80" class="enlargeable">
                    <?php endif; ?>
                  </div>
                  <div class="col-7">
                    <h6><span class="text-primary font-weight-bold">Tanggal Pengaduan:</span> <?= $row['tgl_pengaduan']; ?></h6>
                    <h6><span class="text-primary font-weight-bold">Tanggal Tanggapan:</span> <?= $row['tgl_tanggapan']; ?></h6>
                  </div>
                </div>
                <hr class="bg-primary">
                <h6><span class="text-primary font-weight-bold">Laporan:</span> <?= $row['isi_laporan']; ?></h6>
                <h6><span class="text-primary font-weight-bold">Tanggapan:</span> <?= $row['tanggapan']; ?></h6>
                <hr class="bg-primary">
                <div class="row">
                  <div class="col-8 mt-2">
                    <h5><span class="text-primary font-weight-bold">Ditanggapi oleh:</span> <?= $row['nama_petugas']; ?></h5>
                  </div>
                  <div class="col-4 d-flex justify-content-end">
                    <a href="generate.php" class="btn btn-outline-primary">Kembali</a>
                    <button id="printPDF" class="btn btn-primary ml-2">Cetak PDF</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>

      <div id="imageModal" class="modal">
        <span class="close-modal">&times;</span>
        <img class="modal-content" id="modalImage">
      </div>

    </div>
    <?php include '../layout/footer.php'; ?>
  </div>
</div>

<?php include '../layout/scripts.php'; ?>

<style>
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  padding-top: 60px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.9);
}
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}
.close-modal {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #fff;
  font-size: 40px;
  font-weight: bold;
}
.close-modal:hover,
.close-modal:focus {
  color: #bbb;
  cursor: pointer;
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

<script>
document.getElementById('printPDF').addEventListener('click', function () {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();

  const element = document.querySelector('.card');
  html2canvas(element, {
    onrendered: function(canvas) {
      const imgData = canvas.toDataURL('image/png');
      const imgWidth = 190;
      const pageHeight = 290;
      const imgHeight = canvas.height * imgWidth / canvas.width;
      let heightLeft = imgHeight;
      let position = 10;

      doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
      heightLeft -= pageHeight;

      while (heightLeft >= 0) {
        position = heightLeft - imgHeight;
        doc.addPage();
        doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;
      }

      doc.save('tanggapan.pdf');
    }
  });
});

var modal = document.getElementById("imageModal");
var modalImg = document.getElementById("modalImage");

document.querySelectorAll('.enlargeable').forEach(item => {
  item.addEventListener('click', function() {
    modal.style.display = "block";
    modalImg.src = this.src;
  });
});
document.querySelector(".close-modal").onclick = function () {
  modal.style.display = "none";
}
</script>
