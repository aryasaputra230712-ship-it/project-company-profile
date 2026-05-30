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
    $nomor         = mysqli_real_escape_string($conn, $_POST['nomor']);
    $judul         = mysqli_real_escape_string($conn, $_POST['judul']);
    $judul_en      = mysqli_real_escape_string($conn, $_POST['judul_en']);
    $deskripsi     = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $deskripsi_en  = mysqli_real_escape_string($conn, $_POST['deskripsi_en']);

    if (empty($nomor) || empty($judul) || empty($judul_en) || empty($deskripsi) || empty($deskripsi_en)) {
        $_SESSION['error'] = "All fields are required to update Motto content.";
        header("Location: ../content_manage.php?tab=motto");
        exit();
    }

    $sql_update = "UPDATE motto_utama SET 
                    judul = '$judul', 
                    judul_en = '$judul_en', 
                    deskripsi = '$deskripsi', 
                    deskripsi_en = '$deskripsi_en' 
                   WHERE nomor = '$nomor'";

    if (mysqli_query($conn, $sql_update)) {
        $_SESSION['sukses'] = "Motto data has been successfully updated.";
    } else {
        $_SESSION['error'] = "Failed to update database system. Error: " . mysqli_error($conn);
    }

    header("Location: ../content_manage.php?tab=motto");
    exit();
} else {
    header("Location: ../content_manage.php?tab=motto");
    exit();
}
