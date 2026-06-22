<?php
include "../auth_check.php";

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__, 2));
}

include_once ROOTPATH . "/config/config.php";

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// --- FUNGSI TAMBAH GAMBAR (MASSAL) ---
if ($action == 'tambah_gambar') {
    $id_galeri = intval($_POST['id_galeri']);
    
    // Looping file yang diupload (karena pakai atribut multiple)
    foreach ($_FILES['gambar_detail']['tmp_name'] as $key => $tmp_name) {
        $nama_file = $_FILES['gambar_detail']['name'][$key];
        $file_ext = pathinfo($nama_file, PATHINFO_EXTENSION);
        $new_name = 'detail_' . time() . '_' . $key . '.' . $file_ext;
        
        if (move_uploaded_file($tmp_name, "../../assets/imgs/" . $new_name)) {
            mysqli_query($conn, "INSERT INTO gambar_detail_produk (id_galeri, gambar) VALUES ('$id_galeri', '$new_name')");
        }
    }
    $_SESSION['sukses'] = "Gambar detail berhasil ditambahkan!";
    header("Location: ../gallery_manage.php?tab=gambar");
}

// --- FUNGSI HAPUS GAMBAR ---
if ($action == 'hapus' || (isset($_GET['action']) && $_GET['action'] == 'hapus')) {
    $id = intval($_GET['id']);
    
    // Ambil nama file untuk dihapus dari folder
    $query = mysqli_query($conn, "SELECT gambar FROM gambar_detail_produk WHERE id = $id");
    $data = mysqli_fetch_assoc($query);
    
    if ($data) {
        unlink("../../assets/imgs/" . $data['gambar']); // Hapus file fisik
        mysqli_query($conn, "DELETE FROM gambar_detail_produk WHERE id = $id"); // Hapus dari DB
    }
    
    $_SESSION['sukses'] = "Gambar berhasil dihapus!";
    header("Location: ../gallery_manage.php?tab=gambar");
}
?>