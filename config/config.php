<?php

$host = "localhost"; // Tetap localhost karena kodingan & DB sama-sama di hosting
$user = "vibewebs_id_rsa"; // User yang kamu buat di cPanel
$pass = "Vibewebs0708"; // Password yang kamu reset tadi
$db   = "vibewebs_db"; // Nama database di cPanel

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal : " . mysqli_connect_error());
}
