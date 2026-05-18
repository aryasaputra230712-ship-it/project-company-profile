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
    $host = "185.151.49.65"; // Tetap localhost karena kodingan & DB sama-sama di hosting
    $user = "vibewebs_id_rsa"; // User yang kamu buat di cPanel
    $pass = "Aryasaputra23"; // Password yang kamu reset tadi
    $db   = "vibewebs_db"; // Nama database di cPanel
}

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal : " . mysqli_connect_error());
}
?>
