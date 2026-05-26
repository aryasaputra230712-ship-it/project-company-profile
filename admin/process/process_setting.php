<?php
// 1. Perbaiki ROOTPATH: Naik 2 tingkat (dari 'process' -> 'admin' -> 'root')
if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(dirname(__DIR__)));
}

// 2. Hubungkan ke database (Sekarang jalurnya sudah benar ke /config/config.php di luar admin)
include_once ROOTPATH . "/config/config.php";

// 3. Logika Base URL
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
// Hapus /admin/process dari path untuk mendapatkan base_url yang benar
$base_path = str_replace('/admin/process', '', $script_name);
$base_url = $protocol . "://" . $host . ($base_path == '/' ? '' : $base_path);

if (!defined('BASE_URL')) {
    define('BASE_URL', $base_url);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pastikan variabel $conn tersedia dari config.php
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    $id     = mysqli_real_escape_string($conn, $_POST['id']);
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_perusahaan']);
    $wa     = mysqli_real_escape_string($conn, $_POST['whatsapp']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $ig     = mysqli_real_escape_string($conn, $_POST['instagram']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    $sql = "UPDATE pengaturan SET 
            nama_perusahaan = '$nama', 
            whatsapp = '$wa', 
            email = '$email', 
            instagram = '$ig', 
            alamat = '$alamat' 
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        // KARENA FILE INI DI DALAM FOLDER 'process', 
        // MAKA UNTUK KEMBALI KE 'setting.php' HARUS NAIK SATU TINGKAT (../)
        header("Location: ../setting.php?status=success");
    } else {
        header("Location: ../setting.php?status=error");
    }
    exit;
} else {
    header("Location: ../setting.php");
    exit;
}
