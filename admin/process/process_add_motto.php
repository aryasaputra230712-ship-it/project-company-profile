<?php
include "../auth_check.php";

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__, 2));
}
include_once ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor = mysqli_real_escape_string($conn, $_POST['nomor']);
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $judul_en = mysqli_real_escape_string($conn, $_POST['judul_en']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $deskripsi_en = mysqli_real_escape_string($conn, $_POST['deskripsi_en']);

    $sql = "INSERT INTO motto_utama (nomor, judul, judul_en, deskripsi, deskripsi_en, status) VALUES ('$nomor', '$judul', '$judul_en', '$deskripsi', '$deskripsi_en', 'aktif')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['sukses'] = "Motto berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal: " . mysqli_error($conn);
    }
    header("Location: ../content_manage.php?tab=motto");
    exit();
}

header("Location: ../content_manage.php?tab=motto");
exit();
