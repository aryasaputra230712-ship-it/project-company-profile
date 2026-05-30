<?php
include "../auth_check.php";

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__, 2));
}
include_once ROOTPATH . "/config/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nomor = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if ($nomor === '') {
    $_SESSION['error'] = "ID motto tidak valid.";
    header("Location: ../content_manage.php?tab=motto");
    exit();
}

$sql = "DELETE FROM motto_utama WHERE nomor = '$nomor'";

if (mysqli_query($conn, $sql)) {
    $_SESSION['sukses'] = "Item motto berhasil dihapus.";
} else {
    $_SESSION['error'] = "Gagal menghapus motto: " . mysqli_error($conn);
}

header("Location: ../content_manage.php?tab=motto");
exit();
