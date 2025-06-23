<?php
session_start();  // wajib ada supaya session bisa dipakai

require 'public/app.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Cek sebagai mahasiswa
    $stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $resultMahasiswa = $stmt->get_result();

    if ($resultMahasiswa->num_rows === 1) {
        $data = $resultMahasiswa->fetch_assoc();
        $_SESSION['level'] = 'mahasiswa';
        $_SESSION['nim'] = $data['nim'];
        $_SESSION['nama'] = $data['nama'];
        header("Location: src/user/dashboard.php");
        exit;
    }

    // Cek sebagai petugas / admin
    $stmt = $conn->prepare("SELECT * FROM petugas WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $resultPetugas = $stmt->get_result();

    if ($resultPetugas->num_rows === 1) {
        $data = $resultPetugas->fetch_assoc();
        $_SESSION['level'] = ($data['posisi'] === 'admin') ? 'admin' : 'petugas';
        $_SESSION['id_petugas'] = $data['id_petugas'];
        $_SESSION['nama_petugas'] = $data['nama_petugas'];
        $_SESSION['posisi'] = $data['posisi'];  // **PASTIKAN INI ADA**

        if ($_SESSION['level'] === 'admin') {
            header("Location: src/admin/dashboard.php");
        } else {
            header("Location: src/petugas/dashboard.php");
        }
        exit;
    }

    $error = "Username atau Password salah!";
}
?>

<?php require 'src/layout/header.php'; ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        background-color: #f3f4f6;
        font-family: 'Inter', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    .login-card {
        background-color: white;
        padding: 48px 36px;
        border-radius: 16px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        width: 100%;
        max-width: 420px;
        text-align: center;
    }

    .login-card img.logo {
        width: 160px;
        margin-bottom: 20px;
    }

    .login-card h2 {
        font-weight: 700;
        font-size: 28px;
        margin-bottom: 6px;
        color: #1e293b;
    }

    .login-card p {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 30px;
    }

    .form-label {
        text-align: left;
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        font-size: 14px;
        color: #334155;
    }

    .form-control {
        width: 100%;
        padding: 12px 14px;
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        background-color: #f9fafb;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: #3b82f6;
        outline: none;
        background-color: #fff;
    }

    .btn-login {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 10px;
        background-color: #3b82f6;
        color: white;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-login:hover {
        background-color: #2563eb;
    }

    .alert {
        background-color: #fee2e2;
        color: #991b1b;
        padding: 10px 14px;
        border-radius: 8px;
        margin-bottom: 16px;
        font-size: 14px;
        text-align: left;
    }
</style>

<div class="login-card">
    <img src="assets/images/sicepupaint.png" alt="SICEPU Logo" class="logo" />
    <p>Masuk ke <strong style="color:#3b82f6;">SICEPU</strong> untuk melanjutkan</p>

    <?php if ($error): ?>
        <div class="alert"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" class="form-control" required autofocus>

        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>

        <button type="submit" class="btn-login">Masuk</button>
    </form>
</div>

<?php require 'src/layout/footer.php'; ?>
