<?php
include "../auth_check.php";

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__, 2));
}
include_once ROOTPATH . "/config/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id            = mysqli_real_escape_string($conn, $_POST['id']);
    $ikon          = mysqli_real_escape_string($conn, $_POST['ikon']);
    $judul         = mysqli_real_escape_string($conn, $_POST['judul']);
    $judul_en      = mysqli_real_escape_string($conn, $_POST['judul_en']);
    $deskripsi     = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $deskripsi_en  = mysqli_real_escape_string($conn, $_POST['deskripsi_en']);

    if (empty($id) || empty($ikon) || empty($judul) || empty($judul_en) || empty($deskripsi) || empty($deskripsi_en)) {
        $_SESSION['error'] = "All fields are required to update Core Value.";
        header("Location: ../content_manage.php?tab=why_us");
        exit();
    }

    $sql_update = "UPDATE keunggulan_utama SET 
                    ikon = '$ikon', 
                    judul = '$judul', 
                    judul_en = '$judul_en', 
                    deskripsi = '$deskripsi', 
                    deskripsi_en = '$deskripsi_en' 
                   WHERE id = '$id'";

    if (mysqli_query($conn, $sql_update)) {
        $_SESSION['sukses'] = "Core Value data has been successfully updated.";
    } else {
        $_SESSION['error'] = "Database insertion malfunctioned. Error: " . mysqli_error($conn);
    }

    header("Location: ../content_manage.php?tab=why_us");
    exit();
} else {
    header("Location: ../content_manage.php?tab=why_us");
    exit();
}
