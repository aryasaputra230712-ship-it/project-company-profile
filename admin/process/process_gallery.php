<?php
session_start();

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__, 2));
}

include_once ROOTPATH . "/config/config.php";

// ==========================================
// BACKEND ACTION HANDLER
// ==========================================

// 1. UPDATE HERO BANNER GALERI (kolom bilingual sesuai skema DB)
if (isset($_POST['action']) && $_POST['action'] == 'update_header') {
    $judul_id    = mysqli_real_escape_string($conn, $_POST['judul_id'] ?? $_POST['judul'] ?? '');
    $judul_en    = mysqli_real_escape_string($conn, $_POST['judul_en'] ?? '');
    $subjudul_id = mysqli_real_escape_string($conn, $_POST['subjudul_id'] ?? $_POST['subjudul'] ?? '');
    $subjudul_en = mysqli_real_escape_string($conn, $_POST['subjudul_en'] ?? '');

    $q_old_img = mysqli_query($conn, "SELECT gambar FROM header_galeri WHERE id = 1 LIMIT 1");
    $old_img   = mysqli_fetch_assoc($q_old_img);
    $nama_file = $old_img['gambar'] ?? 'gallery-hero.webp';

    if (!empty($_FILES['gambar']['name'])) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $nama_file_baru = 'hero_galeri_' . time() . '.' . $ext;
        $target_file    = ROOTPATH . "/assets/imgs/" . $nama_file_baru;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            if (!empty($old_img['gambar']) && file_exists(ROOTPATH . "/assets/imgs/" . $old_img['gambar'])) {
                unlink(ROOTPATH . "/assets/imgs/" . $old_img['gambar']);
            }
            $nama_file = $nama_file_baru;
        }
    }

    $nama_file_esc = mysqli_real_escape_string($conn, $nama_file);
    mysqli_query(
        $conn,
        "UPDATE header_galeri SET 
         judul_id = '$judul_id', judul_en = '$judul_en', 
         subjudul_id = '$subjudul_id', subjudul_en = '$subjudul_en', 
         gambar = '$nama_file_esc' WHERE id = 1"
    );
    $_SESSION['sukses'] = "Header Hero Galeri berhasil diperbarui!";
    header("Location: ../gallery_manage.php?tab=hero");
    exit();
}

// 2. TAMBAH ITEM PERHIASAN BARU (Sudah Mendukung Dua Bahasa)
if (isset($_POST['action']) && $_POST['action'] == 'tambah_item') {
    $nama_produk    = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $nama_produk_en = mysqli_real_escape_string($conn, $_POST['nama_produk_en']);
    $harga          = mysqli_real_escape_string($conn, $_POST['harga']);
    $kategori       = mysqli_real_escape_string($conn, $_POST['kategori']);
    $gambar         = "";

    if (!empty($_FILES['gambar']['name'])) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $nama_file_baru = 'prod_' . time() . '_' . rand(100, 999) . '.' . $ext;
        $target_file    = ROOTPATH . "/assets/imgs/" . $nama_file_baru;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            $gambar = $nama_file_baru;
        }
    }

    if (!empty($gambar)) {
        mysqli_query($conn, "INSERT INTO galeri_utama (nama_produk, nama_produk_en, harga, kategori, gambar, status) VALUES ('$nama_produk', '$nama_produk_en', '$harga', '$kategori', '$gambar', 'aktif')");
        $_SESSION['sukses'] = "Koleksi perhiasan baru berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal mengunggah gambar produk perhiasan.";
    }
    header("Location: ../gallery_manage.php?tab=items-section");
    exit();
}

// 3. EDIT ITEM PERHIASAN (Sudah Mendukung Dua Bahasa)
if (isset($_POST['action']) && $_POST['action'] == 'edit_item') {
    $id_item        = mysqli_real_escape_string($conn, $_POST['id_item']);
    $nama_produk    = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $nama_produk_en = mysqli_real_escape_string($conn, $_POST['nama_produk_en']);
    $harga          = mysqli_real_escape_string($conn, $_POST['harga']);
    $kategori       = mysqli_real_escape_string($conn, $_POST['kategori']);

    $q_old_prod = mysqli_query($conn, "SELECT gambar FROM galeri_utama WHERE id = '$id_item' LIMIT 1");
    $old_prod   = mysqli_fetch_assoc($q_old_prod);
    $nama_file  = $old_prod['gambar'];

    if (!empty($_FILES['gambar']['name'])) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $nama_file_baru = 'prod_' . time() . '_' . rand(100, 999) . '.' . $ext;
        $target_file    = ROOTPATH . "/assets/imgs/" . $nama_file_baru;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            if (!empty($old_prod['gambar']) && file_exists(ROOTPATH . "/assets/imgs/" . $old_prod['gambar'])) {
                unlink(ROOTPATH . "/assets/imgs/" . $old_prod['gambar']);
            }
            $nama_file = $nama_file_baru;
        }
    }

    mysqli_query($conn, "UPDATE galeri_utama SET nama_produk = '$nama_produk', nama_produk_en = '$nama_produk_en', harga = '$harga', kategori = '$kategori', gambar = '$nama_file' WHERE id = '$id_item'");
    $_SESSION['sukses'] = "Detail perhiasan berhasil diperbarui!";
    header("Location: ../gallery_manage.php?tab=items-section");
    exit();
}

// 4. HAPUS ITEM PERHIASAN
if (isset($_GET['action']) && $_GET['action'] == 'hapus') {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['id']);

    $res_prod = mysqli_query($conn, "SELECT gambar FROM galeri_utama WHERE id = '$id_hapus' LIMIT 1");
    if ($prod = mysqli_fetch_assoc($res_prod)) {
        if (!empty($prod['gambar']) && file_exists(ROOTPATH . "/assets/imgs/" . $prod['gambar'])) {
            unlink(ROOTPATH . "/assets/imgs/" . $prod['gambar']);
        }
    }

    mysqli_query($conn, "DELETE FROM galeri_utama WHERE id = '$id_hapus'");
    $_SESSION['sukses'] = "Koleksi perhiasan berhasil dihapus secara permanen.";
    header("Location: ../gallery_manage.php?tab=items-section");
    exit();
}
