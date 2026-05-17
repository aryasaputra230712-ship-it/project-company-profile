<?php

$host = "185.151.49.65"; // Tetap localhost karena kodingan & DB sama-sama di hosting
$user = "vibewebs_id_rsa"; // User yang kamu buat di cPanel
$pass = "Aryasaputra23"; // Password yang kamu reset tadi
$db   = "vibewebs_db"; // Nama database di cPanel

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal : " . mysqli_connect_error());
}
