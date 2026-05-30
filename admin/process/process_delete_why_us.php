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
    $_SESSION['error'] = "ID keunggulan tidak valid.";
    header("Location: ../content_manage.php?tab=why_us");
    exit();
}

$sql = "DELETE FROM keunggulan_utama WHERE id = '$id'";

if (mysqli_query($conn, $sql)) {
    $_SESSION['sukses'] = "Item keunggulan berhasil dihapus.";
} else {
    $_SESSION['error'] = "Gagal menghapus keunggulan: " . mysqli_error($conn);
}

header("Location: ../content_manage.php?tab=why_us");
exit();
