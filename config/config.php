<?php

// Mengecek apakah server dijalankan secara lokal (localhost)
if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1') {

    // Jika dijalankan di komputer lokal, gunakan konfigurasi lokal
    $host = "localhost";        // Alamat server database lokal
    $user = "root";             // Username MySQL lokal
    $pass = ""; // Password MySQL lokal
    $db = "vibewebs_db"; // Nama database lokal

} else {
    // Jika dijalankan di jaringan (bukan localhost), gunakan konfigurasi server
    $host = "localhost"; // Biasanya hosting menggunakan localhost untuk koneksi DB internal
    $user = "vibewebs_id_rsa"; // User yang kamu buat di cPanel
    $pass = "Aryasaputra23"; // Password yang kamu reset tadi
    $db   = "vibewebs_db"; // Nama database di cPanel
}

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    // Di server online, sebaiknya jangan pakai die() agar tidak muncul 500 tanpa pesan
    error_log("Koneksi gagal: " . mysqli_connect_error());
    die("Maaf, terjadi gangguan pada sistem.");
}
