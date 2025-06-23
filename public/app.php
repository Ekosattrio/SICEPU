<?php
if (!isset($_SESSION)) { session_start(); }
$conn = mysqli_connect("localhost", "root", "", "laporan_pengaduan");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
function tambahAduan($data) {
    global $conn;

    // Ambil dan sanitize inputan
    $tgl = htmlspecialchars($data["tgl_pengaduan"]);
    $nim = htmlspecialchars($data["nim"]);
    $isi = htmlspecialchars($data["isi_laporan"]);
    $status = htmlspecialchars($data["status"]);

    // Handle upload foto
    $foto = $_FILES['foto']['name'] ?? null;
    $tmp_name = $_FILES['foto']['tmp_name'] ?? null;
    $error = $_FILES['foto']['error'] ?? 4; // 4 = no file uploaded

    // Default kalau gak ada foto, kasih string kosong
    $fotoNameFinal = '';

    if ($error === 0) {
        $file_ext = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png'];

        if (!in_array($file_ext, $allowed_exts)) {
            return -2; // ekstensi file tidak diizinkan
        }

        $upload_dir = '../../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $new_file_name = uniqid('img_', true) . '.' . $file_ext;
        $file_destination = $upload_dir . $new_file_name;

        if (!move_uploaded_file($tmp_name, $file_destination)) {
            return -3; // gagal upload file
        }

        $fotoNameFinal = $new_file_name;
    }

    // Tambahkan isi default untuk kolom disposisi
    $disposisi = 'belum';

    // Query insert termasuk kolom disposisi
    $query = "INSERT INTO pengaduan 
        (tgl_pengaduan, nim, isi_laporan, foto, status, disposisi) 
        VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $tgl, $nim, $isi, $fotoNameFinal, $status, $disposisi);
    $execute = mysqli_stmt_execute($stmt);

    if (!$execute) {
        return -4; // gagal insert ke database
    }

    return mysqli_stmt_affected_rows($stmt);
}


function verify($data)
{
    global $conn;
// ngambil data yang ada di sql taro di inputan
    $id = htmlspecialchars($data["id_pengaduan"]);
    $tgl = htmlspecialchars($data["tgl_pengaduan"]);
    $nim = htmlspecialchars($data["nim"]);
    $isi = htmlspecialchars($data["isi_laporan"]);
    $foto = htmlspecialchars($data["foto"]);
    $status = htmlspecialchars($data["status"]);
    $disposisi = htmlspecialchars($data["disposisi"]);

    $query = "UPDATE pengaduan SET
                id_pengaduan = '$id',
                tgl_pengaduan = '$tgl',
                nim = '$nim',
                isi_laporan = '$isi',
                foto = '$foto',
                status = '$status',
                disposisi = '$disposisi'
                WHERE id_pengaduan = '$id' ";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}   

if (!function_exists('tanggapan')) {
    function tanggapan($data)
    {
        global $conn;

        $id_pengaduan = htmlspecialchars($data["id_pengaduan"]);
        $tgl_tanggapan = htmlspecialchars($data["tgl_tanggapan"]);
        $tanggapan = htmlspecialchars($data["tanggapan"]);

        if (!isset($_SESSION['id_petugas'])) {
            return 0;  // belum login petugas
        }

        $id_petugas = $_SESSION['id_petugas'];

        // Cek apakah id_petugas valid
        $petugasCheckQuery = "SELECT * FROM petugas WHERE id_petugas = '$id_petugas'";
        $petugasResult = mysqli_query($conn, $petugasCheckQuery);

        if(mysqli_num_rows($petugasResult) > 0) {
            $query = "INSERT INTO tanggapan (id_pengaduan, tgl_tanggapan, tanggapan, id_petugas) 
                      VALUES ('$id_pengaduan', '$tgl_tanggapan', '$tanggapan', '$id_petugas')";

            $result = mysqli_query($conn, $query);

            if (!$result) {
                // Debug: tampilkan error query
                echo "Error query insert tanggapan: " . mysqli_error($conn);
                return 0;
            }

            $updateStatusQuery = "UPDATE pengaduan SET status='selesai' WHERE id_pengaduan = '$id_pengaduan'";
            mysqli_query($conn, $updateStatusQuery);

            return mysqli_affected_rows($conn);
        } else {    
            return 0; // petugas tidak ditemukan
        }
    }
}

// function regisUser($data) {
//     global $conn;

//     $nim = htmlspecialchars($data["nim"]);
//     $nama = htmlspecialchars($data["nama"]);
//     $username = htmlspecialchars($data["username"]);
//     $password = htmlspecialchars($data["password"]);
//     $telp = htmlspecialchars($data["telp"]);

//     if (strlen($nim) > 16) {
//         return -1;
//     }

//     if (strlen($telp) > 13) {
//         return -1;
//     }

//     $query = "INSERT INTO mahasiswa (nim, nama, username, password, telp) VALUES ('$nim', '$nama', '$username', '$password', '$telp')";

//     mysqli_query($conn, $query);

//     return mysqli_affected_rows($conn);
// }

function addPetugas($data)
{
    global $conn;

    $nama = htmlspecialchars($data["nama_petugas"]);
    $username = htmlspecialchars($data["username"]);
    $password = htmlspecialchars($data["password"]);
    $telp = htmlspecialchars($data["telp"]);
    $posisi = htmlspecialchars($data["posisi"]);

    $query = "INSERT INTO petugas (nama_petugas, username, password, telp, posisi) VALUES ('$nama', '$username', '$password', '$telp', '$posisi')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function editPetugas($data)
{
    global $conn;

    $id = htmlspecialchars($data["id_petugas"]);
    $nama = htmlspecialchars($data["nama_petugas"]);
    $username = htmlspecialchars($data["username"]);
    $password = htmlspecialchars($data["password"]);
    $telp = htmlspecialchars($data["telp"]);
    $posisi = htmlspecialchars($data["posisi"]);

    $query = "UPDATE petugas SET
                nama_petugas = '$nama',
                username = '$username',
                password = '$password',
                telp = '$telp',
                posisi = '$posisi'
                WHERE id_petugas = '$id'";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function deletePetugas($id)
{
    global $conn;

    $checkQuery = "SELECT * FROM tanggapan WHERE id_petugas = $id";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        return -1;
    }

    $query = "DELETE FROM petugas WHERE id_petugas = $id";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function deletemahasiswa($nim) {
    global $conn;
    $query = "DELETE FROM mahasiswa WHERE nim = '$nim'";
    if (mysqli_query($conn, $query)) {
        return 1;
    } else {
        if (mysqli_errno($conn) == 1451) {
            return -1; // related entry exists
        } else {
            return 0; // general error
        }
    }
}
?>
