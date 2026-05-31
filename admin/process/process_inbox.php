<?php
// admin/process/process_inbox.php
include "../auth_check.php";

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__, 2));
}

include_once ROOTPATH . "/config/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Hapus pesan berdasarkan ID
    $stmt = $conn->prepare("DELETE FROM pesan_masuk WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['sukses'] = "Pesan berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus pesan.";
    }

    header("Location: ../inbox_manage.php");
    exit();
}
