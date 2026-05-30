<?php
include "../auth_check.php";

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__, 2));
}

include_once ROOTPATH . "/config/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$redirect_tab = 'perhiasan';

// ==========================================
// BACKEND ACTION HANDLER
// ==========================================

// 1. UPDATE HERO BANNER GALERI (kolom bilingual sesuai skema DB)
if (isset($_POST['action']) && $_POST['action'] === 'update_header') {
    $redirect_tab = 'hero';
    $judul_id    = mysqli_real_escape_string($conn, $_POST['judul_id'] ?? $_POST['judul'] ?? '');
    $judul_en    = mysqli_real_escape_string($conn, $_POST['judul_en'] ?? '');
    $subjudul_id = mysqli_real_escape_string($conn, $_POST['subjudul_id'] ?? $_POST['subjudul'] ?? '');
    $subjudul_en = mysqli_real_escape_string($conn, $_POST['subjudul_en'] ?? '');

    $q_old_img = mysqli_query($conn, "SELECT gambar FROM header_galeri WHERE id = 1 LIMIT 1");
    $old_img   = mysqli_fetch_assoc($q_old_img);
    $nama_file = $old_img['gambar'] ?? 'gallery-hero.webp';

    if (!empty($_FILES['gambar']['name']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
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
    if (mysqli_query(
        $conn,
        "UPDATE header_galeri SET 
         judul_id = '$judul_id', judul_en = '$judul_en', 
         subjudul_id = '$subjudul_id', subjudul_en = '$subjudul_en', 
         gambar = '$nama_file_esc' WHERE id = 1"
    )) {
        $_SESSION['sukses'] = "Header Hero Galeri berhasil diperbarui!";
    } else {
        $_SESSION['error'] = "Gagal menyimpan header: " . mysqli_error($conn);
    }

    header("Location: ../gallery_manage.php?tab=hero");
    exit();
}

// 2. TAMBAH ITEM PERHIASAN BARU
if (isset($_POST['action']) && $_POST['action'] === 'tambah_item') {
    $nama_produk    = mysqli_real_escape_string($conn, $_POST['nama_produk'] ?? '');
    $nama_produk_en = mysqli_real_escape_string($conn, $_POST['nama_produk_en'] ?? '');
    $harga          = (int) ($_POST['harga'] ?? 0);
    $kategori       = mysqli_real_escape_string($conn, strtolower($_POST['kategori'] ?? 'rings'));
    $gambar         = "";

    if (!empty($_FILES['gambar']['name']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $nama_file_baru = 'prod_' . time() . '_' . rand(100, 999) . '.' . $ext;
            $target_file    = ROOTPATH . "/assets/imgs/" . $nama_file_baru;

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                $gambar = $nama_file_baru;
            }
        }
    }

    if ($gambar !== '') {
        $harga_sql = mysqli_real_escape_string($conn, (string) $harga);
        if (mysqli_query(
            $conn,
            "INSERT INTO galeri_utama (nama_produk, nama_produk_en, harga, kategori, gambar, status) 
             VALUES ('$nama_produk', '$nama_produk_en', '$harga_sql', '$kategori', '$gambar', 'aktif')"
        )) {
            $_SESSION['sukses'] = "Koleksi perhiasan baru berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menyimpan ke database: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "Gagal mengunggah gambar produk perhiasan.";
    }

    header("Location: ../gallery_manage.php?tab=perhiasan");
    exit();
}

// 3. EDIT ITEM PERHIASAN
if (isset($_POST['action']) && $_POST['action'] === 'edit_item') {
    $id_item        = mysqli_real_escape_string($conn, $_POST['id_item'] ?? '');
    $nama_produk    = mysqli_real_escape_string($conn, $_POST['nama_produk'] ?? '');
    $nama_produk_en = mysqli_real_escape_string($conn, $_POST['nama_produk_en'] ?? '');
    $harga          = (int) ($_POST['harga'] ?? 0);
    $kategori       = mysqli_real_escape_string($conn, strtolower($_POST['kategori'] ?? 'rings'));

    if ($id_item === '') {
        $_SESSION['error'] = "ID produk tidak valid.";
        header("Location: ../gallery_manage.php?tab=perhiasan");
        exit();
    }

    $q_old_prod = mysqli_query($conn, "SELECT gambar FROM galeri_utama WHERE id = '$id_item' LIMIT 1");
    $old_prod   = mysqli_fetch_assoc($q_old_prod);

    if (!$old_prod) {
        $_SESSION['error'] = "Produk tidak ditemukan.";
        header("Location: ../gallery_manage.php?tab=perhiasan");
        exit();
    }

    $nama_file = $old_prod['gambar'];

    if (!empty($_FILES['gambar']['name']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $nama_file_baru = 'prod_' . time() . '_' . rand(100, 999) . '.' . $ext;
            $target_file    = ROOTPATH . "/assets/imgs/" . $nama_file_baru;

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                if (!empty($old_prod['gambar']) && file_exists(ROOTPATH . "/assets/imgs/" . $old_prod['gambar'])) {
                    unlink(ROOTPATH . "/assets/imgs/" . $old_prod['gambar']);
                }
                $nama_file = $nama_file_baru;
            }
        }
    }

    $nama_file_esc = mysqli_real_escape_string($conn, $nama_file);
    $harga_sql     = mysqli_real_escape_string($conn, (string) $harga);

    if (mysqli_query(
        $conn,
        "UPDATE galeri_utama SET 
         nama_produk = '$nama_produk', 
         nama_produk_en = '$nama_produk_en', 
         harga = '$harga_sql', 
         kategori = '$kategori', 
         gambar = '$nama_file_esc' 
         WHERE id = '$id_item'"
    )) {
        $_SESSION['sukses'] = "Detail perhiasan berhasil diperbarui!";
    } else {
        $_SESSION['error'] = "Gagal memperbarui produk: " . mysqli_error($conn);
    }

    header("Location: ../gallery_manage.php?tab=perhiasan");
    exit();
}

// 4. HAPUS ITEM PERHIASAN
if (isset($_GET['action']) && $_GET['action'] === 'hapus') {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['id'] ?? '');

    if ($id_hapus !== '') {
        $res_prod = mysqli_query($conn, "SELECT gambar FROM galeri_utama WHERE id = '$id_hapus' LIMIT 1");
        if ($prod = mysqli_fetch_assoc($res_prod)) {
            $file_path = ROOTPATH . "/assets/imgs/" . $prod['gambar'];
            if (!empty($prod['gambar']) && file_exists($file_path)) {
                unlink($file_path);
            }
        }

        if (mysqli_query($conn, "DELETE FROM galeri_utama WHERE id = '$id_hapus'")) {
            $_SESSION['sukses'] = "Koleksi perhiasan berhasil dihapus secara permanen.";
        } else {
            $_SESSION['error'] = "Gagal menghapus produk: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "ID produk tidak valid.";
    }

    header("Location: ../gallery_manage.php?tab=perhiasan");
    exit();
}

header("Location: ../gallery_manage.php?tab=" . $redirect_tab);
exit();
