<?php
session_start();

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

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Ambil data user berdasarkan username
    // Catatan: Ganti 'users' dengan nama tabel admin Anda jika berbeda
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_num_rows($query) === 1) {
        $user = mysqli_fetch_assoc($query);

        // Verifikasi password menggunakan password_hash yang dibuat di create_password.php
        if (password_verify($password, $user['password'])) {
            // Set session login
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_nama'] = $user['nama']; // Opsional: simpan nama admin

            header("Location: ../index.php");
            exit;
        }
    }

    // Jika username tidak ditemukan atau password salah
    header("Location: ../login.php?pesan=gagal");
    exit;
} else {
    header("Location: ../login.php");
    exit;
}
