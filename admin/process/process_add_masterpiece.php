<?php
include "../auth_check.php";

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__, 2));
}
include_once ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $nama_en = mysqli_real_escape_string($conn, $_POST['nama_produk_en']);

    // Proses Upload Gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $ext = pathinfo($gambar, PATHINFO_EXTENSION);
    $new_name = "masterpiece_" . time() . "." . $ext;

    $target_dir = ROOTPATH . "/assets/imgs/";
    if (move_uploaded_file($tmp, $target_dir . $new_name)) {
        $sql = "INSERT INTO produk_pilihan (nama_produk, nama_produk_en, gambar, status) VALUES ('$nama', '$nama_en', '$new_name', 'aktif')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['sukses'] = "Masterpiece berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal simpan database.";
        }
    } else {
        $_SESSION['error'] = "Gagal upload gambar.";
    }
    header("Location: ../content_manage.php?tab=masterpieces");
    exit();
}

header("Location: ../content_manage.php?tab=masterpieces");
exit();
