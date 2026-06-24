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

    // Tangkap Deskripsi
    $deskripsi_id   = mysqli_real_escape_string($conn, $_POST['deskripsi_id'] ?? '');
    $deskripsi_en   = mysqli_real_escape_string($conn, $_POST['deskripsi_en'] ?? '');

    // Tangkap Spesifikasi
    $tipe_id        = mysqli_real_escape_string($conn, $_POST['tipe_spesifikasi_id'] ?? '');
    $tipe_en        = mysqli_real_escape_string($conn, $_POST['tipe_spesifikasi_en'] ?? '');
    $warna_id       = mysqli_real_escape_string($conn, $_POST['warna_id'] ?? '');
    $warna_en       = mysqli_real_escape_string($conn, $_POST['warna_en'] ?? '');
    $berat          = mysqli_real_escape_string($conn, $_POST['berat'] ?? '');

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

        // Simpan ke galeri utama beserta deskripsinya
        $query_insert = "INSERT INTO galeri_utama 
            (nama_produk, nama_produk_en, harga, kategori, deskripsi_id, deskripsi_en, gambar, status) 
            VALUES ('$nama_produk', '$nama_produk_en', '$harga_sql', '$kategori', '$deskripsi_id', '$deskripsi_en', '$gambar', 'aktif')";

        if (mysqli_query($conn, $query_insert)) {
            // Ambil ID produk yang baru saja dimasukkan
            $id_baru = mysqli_insert_id($conn);

            // Simpan ke tabel spesifikasi menggunakan ID baru tersebut
            $query_spek = "INSERT INTO spesifikasi_produk 
                           (id_galeri, tipe_spesifikasi_id, tipe_spesifikasi_en, warna_id, warna_en, berat) 
                           VALUES ('$id_baru', '$tipe_id', '$tipe_en', '$warna_id', '$warna_en', '$berat')";
            mysqli_query($conn, $query_spek);

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

    // Data Deskripsi (Ini ada di tabel galeri_utama)
    $deskripsi_id   = mysqli_real_escape_string($conn, $_POST['deskripsi_id'] ?? '');

    // Data Spesifikasi (Ini ada di tabel spesifikasi_produk)
    $tipe_id        = mysqli_real_escape_string($conn, $_POST['tipe_spesifikasi_id'] ?? '');
    $tipe_en        = mysqli_real_escape_string($conn, $_POST['tipe_spesifikasi_en'] ?? '');
    $warna_id       = mysqli_real_escape_string($conn, $_POST['warna_id'] ?? '');
    $warna_en       = mysqli_real_escape_string($conn, $_POST['warna_en'] ?? '');
    $berat          = mysqli_real_escape_string($conn, $_POST['berat'] ?? '');

    if ($id_item === '') {
        $_SESSION['error'] = "ID produk tidak valid.";
        header("Location: ../gallery_manage.php?tab=perhiasan");
        exit();
    }

    // Proses Gambar
    $q_old_prod = mysqli_query($conn, "SELECT gambar FROM galeri_utama WHERE id = '$id_item' LIMIT 1");
    $old_prod   = mysqli_fetch_assoc($q_old_prod);
    $nama_file  = $old_prod['gambar'];

    if (!empty($_FILES['gambar']['name']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $nama_file_baru = 'prod_' . time() . '_' . rand(100, 999) . '.' . $ext;
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], ROOTPATH . "/assets/imgs/" . $nama_file_baru)) {
                if (!empty($old_prod['gambar']) && file_exists(ROOTPATH . "/assets/imgs/" . $old_prod['gambar'])) {
                    unlink(ROOTPATH . "/assets/imgs/" . $old_prod['gambar']);
                }
                $nama_file = $nama_file_baru;
            }
        }
    }

    // 1. UPDATE TABEL GALERI_UTAMA (Hapus kolom spesifikasi dari sini)
    $query_utama = "UPDATE galeri_utama SET 
         nama_produk = '$nama_produk', 
         nama_produk_en = '$nama_produk_en', 
         harga = '$harga', 
         kategori = '$kategori', 
         deskripsi_id = '$deskripsi_id',
         gambar = '$nama_file' 
         WHERE id = '$id_item'";

    if (mysqli_query($conn, $query_utama)) {
        // 2. UPDATE/INSERT TABEL SPESIFIKASI_PRODUK
        // Menggunakan ON DUPLICATE KEY agar datanya ter-update jika sudah ada, atau tambah baru jika belum
        $query_spek = "INSERT INTO spesifikasi_produk (id_galeri, tipe_spesifikasi_id, tipe_spesifikasi_en, warna_id, warna_en, berat) 
                       VALUES ('$id_item', '$tipe_id', '$tipe_en', '$warna_id', '$warna_en', '$berat')
                       ON DUPLICATE KEY UPDATE 
                       tipe_spesifikasi_id = '$tipe_id', 
                       tipe_spesifikasi_en = '$tipe_en', 
                       warna_id = '$warna_id', 
                       warna_en = '$warna_en', 
                       berat = '$berat'";

        mysqli_query($conn, $query_spek);

        $_SESSION['sukses'] = "Produk dan spesifikasi berhasil diperbarui!";
    } else {
        $_SESSION['error'] = "Gagal memperbarui produk: " . mysqli_error($conn);
    }

    header("Location: ../gallery_manage.php?tab=perhiasan");
    exit();
}

// 4. HAPUS ITEM PERHIASANN
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

// ==========================================
// FITUR CRUD KATEGORI
// ==========================================

// Fungsi bantuan untuk membuat slug (contoh: "Cincin Emas" jadi "cincin-emas")
function createSlug($string) {
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim($string)));
    return $slug;
}

// 5. TAMBAH KATEGORI BARU
if (isset($_POST['action']) && $_POST['action'] === 'tambah_kategori') {
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori'] ?? '');
    $slug = createSlug($nama_kategori);

    if ($nama_kategori !== '') {
        if (mysqli_query($conn, "INSERT INTO kategori_galeri (nama_kategori, slug) VALUES ('$nama_kategori', '$slug')")) {
            $_SESSION['sukses'] = "Kategori baru berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambah kategori: " . mysqli_error($conn);
        }
    }
    header("Location: ../gallery_manage.php?tab=kategori");
    exit();
}

// 6. EDIT KATEGORI
if (isset($_POST['action']) && $_POST['action'] === 'edit_kategori') {
    $id_kategori   = mysqli_real_escape_string($conn, $_POST['id_kategori'] ?? '');
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori'] ?? '');
    $slug          = createSlug($nama_kategori);

    if ($id_kategori !== '' && $nama_kategori !== '') {
        if (mysqli_query($conn, "UPDATE kategori_galeri SET nama_kategori = '$nama_kategori', slug = '$slug' WHERE id = '$id_kategori'")) {
            $_SESSION['sukses'] = "Kategori berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "Gagal memperbarui kategori: " . mysqli_error($conn);
        }
    }
    header("Location: ../gallery_manage.php?tab=kategori");
    exit();
}



// 7. HAPUS KATEGORI
if (isset($_GET['action']) && $_GET['action'] === 'hapus_kategori') {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['id'] ?? '');

    if ($id_hapus !== '') {
        // Hapus kategori dari database
        if (mysqli_query($conn, "DELETE FROM kategori_galeri WHERE id = '$id_hapus'")) {
            $_SESSION['sukses'] = "Kategori berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus. Pastikan kategori ini tidak sedang dipakai oleh perhiasan: " . mysqli_error($conn);
        }
    }
    header("Location: ../gallery_manage.php?tab=kategori");
    exit();
}

header("Location: ../gallery_manage.php?tab=" . $redirect_tab);
exit();
