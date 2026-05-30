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
    $id             = mysqli_real_escape_string($conn, $_POST['id']);
    $nama_produk    = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $nama_produk_en = mysqli_real_escape_string($conn, $_POST['nama_produk_en']);

    if (empty($id) || empty($nama_produk) || empty($nama_produk_en)) {
        $_SESSION['error'] = "Product name fields cannot be left blank.";
        header("Location: ../content_manage.php?tab=masterpieces");
        exit();
    }

    // Ambil data gambar lama untuk persiapan unlink jika ada gambar baru
    $query_old = mysqli_query($conn, "SELECT gambar FROM produk_pilihan WHERE id = '$id' LIMIT 1");
    $old_data  = mysqli_fetch_assoc($query_old);
    $old_file  = $old_data['gambar'] ?? '';

    // Logika Validasi File Upload Gambar Baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($file_ext, $allowed_exts)) {
            $_SESSION['error'] = "Invalid image extension format. Allowed: JPG, JPEG, PNG, WEBP.";
            header("Location: ../content_manage.php?tab=masterpieces");
            exit();
        }

        // Generate nama file unik agar tidak duplikat
        $new_filename = "masterpiece_" . time() . "_" . uniqid() . "." . $file_ext;
        $target_dir   = ROOTPATH . "/assets/imgs/";

        if (move_uploaded_file($file_tmp, $target_dir . $new_filename)) {
            // Hapus file gambar lama jika filenya ada di server
            if (!empty($old_file) && file_exists($target_dir . $old_file) && $old_file !== 'default.jpg') {
                unlink($target_dir . $old_file);
            }

            // Update beserta nama file gambar baru
            $sql_update = "UPDATE produk_pilihan SET 
                            nama_produk = '$nama_produk', 
                            nama_produk_en = '$nama_produk_en', 
                            gambar = '$new_filename' 
                           WHERE id = '$id'";
        } else {
            $_SESSION['error'] = "Failed uploading physical image file to asset storage server.";
            header("Location: ../content_manage.php?tab=masterpieces");
            exit();
        }
    } else {
        // Update data tekstual saja jika admin tidak merubah gambar asset
        $sql_update = "UPDATE produk_pilihan SET 
                        nama_produk = '$nama_produk', 
                        nama_produk_en = '$nama_produk_en' 
                       WHERE id = '$id'";
    }

    if (mysqli_query($conn, $sql_update)) {
        $_SESSION['sukses'] = "Masterpiece details have been successfully updated.";
    } else {
        $_SESSION['error'] = "System failed processing system update query.";
    }

    header("Location: ../content_manage.php?tab=masterpieces");
    exit();
} else {
    header("Location: ../content_manage.php?tab=masterpieces");
    exit();
}
