<?php
// 1. SESSION & AUTH CHECK
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../auth_check.php"; // Pastikan admin login

// 2. DEFINISI ROOTPATH (Naik 2 tingkat ke folder utama project)
if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(dirname(__DIR__)));
}

// 3. LOGIKA BASE URL (Untuk kebutuhan dynamic linking jika diperlukan)
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_path = str_replace('/admin/process', '', $script_name);
$base_url = $protocol . "://" . $host . ($base_path == '/' ? '' : $base_path);

if (!defined('BASE_URL')) {
    define('BASE_URL', $base_url);
}

// 4. MEMANGGIL KONEKSI DATABASE
include_once ROOTPATH . "/config/config.php";

// 5. PROCESS FORM SETTINGS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_settings'])) {

    // Validasi ID
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if ($id === 0) {
        $_SESSION['error'] = "ID tidak valid!";
        header("Location: ../setting.php");
        exit();
    }

    // Ambil input dari form dengan sanitasi
    $nama     = isset($_POST['nama_perusahaan']) ? trim($_POST['nama_perusahaan']) : '';
    $wa       = isset($_POST['whatsapp']) ? trim($_POST['whatsapp']) : '';
    $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
    $alamat   = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';
    $ig       = isset($_POST['instagram']) ? trim($_POST['instagram']) : '';
    $fb       = isset($_POST['facebook']) ? trim($_POST['facebook']) : '';
    $tiktok   = isset($_POST['tiktok']) ? trim($_POST['tiktok']) : '';
    $telegram = isset($_POST['telegram']) ? trim($_POST['telegram']) : '';
    $twitter  = isset($_POST['twitter']) ? trim($_POST['twitter']) : '';

    // Validasi field yang wajib diisi
    if (empty($nama)) {
        $_SESSION['error'] = "Nama perusahaan tidak boleh kosong!";
        header("Location: ../setting.php");
        exit();
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email tidak valid!";
        header("Location: ../setting.php");
        exit();
    }

    // Escape untuk keamanan SQL
    $nama     = mysqli_real_escape_string($conn, $nama);
    $wa       = mysqli_real_escape_string($conn, $wa);
    $email    = mysqli_real_escape_string($conn, $email);
    $alamat   = mysqli_real_escape_string($conn, $alamat);
    $ig       = mysqli_real_escape_string($conn, $ig);
    $fb       = mysqli_real_escape_string($conn, $fb);
    $tiktok   = mysqli_real_escape_string($conn, $tiktok);
    $telegram = mysqli_real_escape_string($conn, $telegram);
    $twitter  = mysqli_real_escape_string($conn, $twitter);

    // Query Update untuk SEMUA kolom
    $sql = "UPDATE pengaturan SET 
            nama_perusahaan = '$nama', 
            whatsapp = '$wa', 
            email = '$email', 
            alamat = '$alamat', 
            instagram = '$ig',
            facebook = '$fb',
            tiktok = '$tiktok',
            telegram = '$telegram',
            twitter = '$twitter'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['sukses'] = "✓ Profil perusahaan & Media Sosial berhasil diperbarui!";
        header("Location: ../setting.php?status=success");
    } else {
        $_SESSION['error'] = "✗ Gagal memperbarui data: " . mysqli_error($conn);
        header("Location: ../setting.php");
    }
    exit();
}

// Redirect jika akses langsung tanpa POST
header("Location: ../setting.php");
exit();
