<?php
include "../auth_check.php";

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__, 2));
}
include_once ROOTPATH . "/config/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if ($id === '') {
    $_SESSION['error'] = "ID produk tidak valid.";
    header("Location: ../content_manage.php?tab=masterpieces");
    exit();
}

$target_dir = ROOTPATH . "/assets/imgs/";
$res = mysqli_query($conn, "SELECT gambar FROM produk_pilihan WHERE id = '$id' LIMIT 1");

if ($row = mysqli_fetch_assoc($res)) {
    $gambar = $row['gambar'] ?? '';
    if ($gambar !== '' && $gambar !== 'default.jpg' && file_exists($target_dir . $gambar)) {
        unlink($target_dir . $gambar);
    }
}

if (mysqli_query($conn, "DELETE FROM produk_pilihan WHERE id = '$id'")) {
    $_SESSION['sukses'] = "Masterpiece berhasil dihapus.";
} else {
    $_SESSION['error'] = "Gagal menghapus masterpiece: " . mysqli_error($conn);
}

header("Location: ../content_manage.php?tab=masterpieces");
exit();
