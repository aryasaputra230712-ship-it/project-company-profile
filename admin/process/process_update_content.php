<?php
// 1. PENGAMAN AKSES UTAMA (Naik 1 folder ke folder admin)
include "../auth_check.php";

// 2. DEFINISI ROOTPATH (Naik 2 tingkat ke folder utama project)
if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(dirname(__DIR__)));
}

// 3. LOGIKA BASE URL (Untuk kebutuhan dynamic linking jika diperlukan)
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_path = str_replace('/admin/process', '', $script_name);
$base_url = $protocol . "://" . $host . ($base_path == '/' ? '' : $base_path);

if (!defined('BASE_URL')) {
    define('BASE_URL', $base_url);
}

// 4. MEMANGGIL KONEKSI DATABASE
include_once ROOTPATH . "/config/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tangkap parameter tab dan aksi (Default aksi adalah update)
$tab    = isset($_REQUEST['tab_name']) ? mysqli_real_escape_string($conn, $_REQUEST['tab_name']) : '';
$action = isset($_REQUEST['action']) ? mysqli_real_escape_string($conn, $_REQUEST['action']) : 'update';

// Pengaman jika parameter diakses secara tidak sah
if (empty($tab)) {
    header("Location: ../content_manage.php");
    exit();
}

$msg_success = "";
$msg_error = "";

// =========================================================================
// STRUKTUR UTAMA KENDALI AKSI (SWITCH-CASE)
// =========================================================================
switch ($action) {

    // ---------------------------------------------------------------------
    // KATEGORI 1: UPDATE DATA (Single Row Konten Homepage)
    // ---------------------------------------------------------------------
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if ($tab == 'hero') {
                $hero_judul_id = mysqli_real_escape_string($conn, $_POST['hero_judul_id']);
                $hero_judul_en = mysqli_real_escape_string($conn, $_POST['hero_judul_en']);
                $hero_sub_id   = mysqli_real_escape_string($conn, $_POST['hero_sub_id']);
                $hero_sub_en   = mysqli_real_escape_string($conn, $_POST['hero_sub_en']);

                $sql = "UPDATE konten_homepage SET 
                        hero_judul_id = '$hero_judul_id', hero_judul_en = '$hero_judul_en', 
                        hero_sub_id = '$hero_sub_id', hero_sub_en = '$hero_sub_en' 
                        WHERE id = 1";
                if (mysqli_query($conn, $sql)) {
                    $msg_success = "Narasi Utama HERO sukses diperbarui!";
                }
            } elseif ($tab == 'about') {
                $about_judul_id     = mysqli_real_escape_string($conn, $_POST['about_judul_id']);
                $about_judul_en     = mysqli_real_escape_string($conn, $_POST['about_judul_en']);
                $about_deskripsi_id = mysqli_real_escape_string($conn, $_POST['about_deskripsi_id']);
                $about_deskripsi_en = mysqli_real_escape_string($conn, $_POST['about_deskripsi_en']);

                $sql = "UPDATE konten_homepage SET 
                        about_judul_id = '$about_judul_id', about_judul_en = '$about_judul_en', 
                        about_deskripsi_id = '$about_deskripsi_id', about_deskripsi_en = '$about_deskripsi_en' 
                        WHERE id = 1";
                if (mysqli_query($conn, $sql)) {
                    $msg_success = "Kisah Sejarah ABOUT sukses diperbarui!";
                }
            } elseif ($tab == 'founder') {
                $founder_nama   = mysqli_real_escape_string($conn, $_POST['founder_nama']);
                $founder_bio_id = mysqli_real_escape_string($conn, $_POST['founder_bio_id']);
                $founder_bio_en = mysqli_real_escape_string($conn, $_POST['founder_bio_en']);

                $sql = "UPDATE konten_homepage SET 
                        founder_nama = '$founder_nama', 
                        founder_bio_id = '$founder_bio_id', founder_bio_en = '$founder_bio_en' 
                        WHERE id = 1";
                if (mysqli_query($conn, $sql)) {
                    $msg_success = "Biografi FOUNDER sukses diperbarui!";
                }
            } elseif ($tab == 'history') {
                $sejarah_judul_id  = mysqli_real_escape_string($conn, $_POST['sejarah_judul_id']);
                $sejarah_judul_en  = mysqli_real_escape_string($conn, $_POST['sejarah_judul_en']);
                $sejarah_konten_id = mysqli_real_escape_string($conn, $_POST['sejarah_konten_id']);
                $sejarah_konten_en = mysqli_real_escape_string($conn, $_POST['sejarah_konten_en']);

                $sql = "UPDATE konten_homepage SET 
                        sejarah_judul_id = '$sejarah_judul_id', sejarah_judul_en = '$sejarah_judul_en', 
                        sejarah_konten_id = '$sejarah_konten_id', sejarah_konten_en = '$sejarah_konten_en' 
                        WHERE id = 1";
                if (mysqli_query($conn, $sql)) {
                    $msg_success = "Linimasa Cerita HISTORY sukses diperbarui!";
                }
            } elseif ($tab == 'quotes') {
                $kutipan_id = mysqli_real_escape_string($conn, $_POST['kutipan_id']);
                $kutipan_en = mysqli_real_escape_string($conn, $_POST['kutipan_en']);
                $video_url  = mysqli_real_escape_string($conn, $_POST['video_url']);

                $sql = "UPDATE konten_homepage SET 
                        kutipan_id = '$kutipan_id', kutipan_en = '$kutipan_en', video_url = '$video_url' 
                        WHERE id = 1";
                if (mysqli_query($conn, $sql)) {
                    $msg_success = "Kutipan Emas QUOTES & Video sukses diperbarui!";
                }
            }
        }
        break;

    // ---------------------------------------------------------------------
    // KATEGORI 2: TAMBAH DATA BARU (Data Berkelompok Banyak Baris)
    // ---------------------------------------------------------------------
    case 'tambah':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if ($tab == 'motto') {
                $nomor        = mysqli_real_escape_string($conn, $_POST['nomor']);
                $judul        = mysqli_real_escape_string($conn, $_POST['judul']);
                $judul_en     = mysqli_real_escape_string($conn, $_POST['judul_en']);
                $deskripsi    = mysqli_real_escape_string($conn, $_POST['deskripsi']);
                $deskripsi_en = mysqli_real_escape_string($conn, $_POST['deskripsi_en']);

                $sql = "INSERT INTO motto_utama (nomor, judul, judul_en, deskripsi, deskripsi_en, status) 
                        VALUES ('$nomor', '$judul', '$judul_en', '$deskripsi', '$deskripsi_en', 'aktif')";
                if (mysqli_query($conn, $sql)) {
                    $msg_success = "Item MOTTO baru berhasil diluncurkan!";
                }
            } elseif ($tab == 'why_us') {
                $ikon         = mysqli_real_escape_string($conn, $_POST['ikon']);
                $judul        = mysqli_real_escape_string($conn, $_POST['judul']);
                $judul_en     = mysqli_real_escape_string($conn, $_POST['judul_en']);
                $deskripsi    = mysqli_real_escape_string($conn, $_POST['deskripsi']);
                $deskripsi_en = mysqli_real_escape_string($conn, $_POST['deskripsi_en']);

                $sql = "INSERT INTO keunggulan_utama (ikon, judul, judul_en, deskripsi, deskripsi_en, status) 
                        VALUES ('$ikon', '$judul', '$judul_en', '$deskripsi', '$deskripsi_en', 'aktif')";
                if (mysqli_query($conn, $sql)) {
                    $msg_success = "Item keunggulan WHY US berhasil ditambahkan!";
                }
            } elseif ($tab == 'masterpieces') {
                $nama_produk    = mysqli_real_escape_string($conn, $_POST['nama_produk']);
                $nama_produk_en = mysqli_real_escape_string($conn, $_POST['nama_produk_en']);

                // Amankan proses unggah foto menggunakan ROOTPATH absolute
                $gambar = "";
                if (!empty($_FILES['gambar']['name'])) {
                    $nama_file_baru = time() . '_' . basename($_FILES['gambar']['name']);
                    $target_dir     = ROOTPATH . "/assets/imgs/";
                    $target_file    = $target_dir . $nama_file_baru;

                    // Pastikan folder assets/imgs/ sudah ada
                    if (!is_dir($target_dir)) {
                        mkdir($target_dir, 0755, true);
                    }

                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                        $gambar = $nama_file_baru;
                    }
                }

                $sql = "INSERT INTO produk_pilihan (nama_produk, nama_produk_en, gambar, status) 
                        VALUES ('$nama_produk', '$nama_produk_en', '$gambar', 'aktif')";
                if (mysqli_query($conn, $sql)) {
                    $msg_success = "Produk MASTERPIECES baru sukses dipajang!";
                }
            }
        }
        break;

    // ---------------------------------------------------------------------
    // KATEGORI 3: HAPUS DATA PERMANEN (Berdasarkan Parameter ID)
    // ---------------------------------------------------------------------
    case 'hapus':
        $id_hapus = isset($_REQUEST['id']) ? mysqli_real_escape_string($conn, $_REQUEST['id']) : '';

        if (!empty($id_hapus)) {
            if ($tab == 'motto') {
                $sql = "DELETE FROM motto_utama WHERE id = '$id_hapus'";
                if (mysqli_query($conn, $sql)) {
                    $msg_success = "Item Motto berhasil dihapus secara permanen!";
                }
            } elseif ($tab == 'why_us') {
                $sql = "DELETE FROM keunggulan_utama WHERE id = '$id_hapus'";
                if (mysqli_query($conn, $sql)) {
                    $msg_success = "Item keunggulan Why Us berhasil dihapus!";
                }
            } elseif ($tab == 'masterpieces') {
                // Ambil data gambar terlebih dahulu untuk dihapus dari folder lokal
                $check_img = mysqli_query($conn, "SELECT gambar FROM produk_pilihan WHERE id = '$id_hapus'");
                if ($img_row = mysqli_fetch_assoc($check_img)) {
                    $file_path = ROOTPATH . "/assets/imgs/" . $img_row['gambar'];
                    if (!empty($img_row['gambar']) && file_exists($file_path)) {
                        unlink($file_path); // Hapus file gambar asli dari server
                    }
                }

                $sql = "DELETE FROM produk_pilihan WHERE id = '$id_hapus'";
                if (mysqli_query($conn, $sql)) {
                    $msg_success = "Produk Masterpiece berhasil dimusnahkan!";
                }
            }
        } else {
            $msg_error = "ID target penghapusan tidak valid atau kosong.";
        }
        break;
}

// =========================================================================
// RESPONS NOTIFIKASI & PENGALIHAN HALAMAN KEMBALI (REDIRECT)
// =========================================================================
if (!empty($msg_success)) {
    $_SESSION['sukses'] = $msg_success;
} else if (!empty($msg_error)) {
    $_SESSION['error'] = $msg_error;
} else {
    $_SESSION['error'] = "Sistem gagal memproses atau terdeteksi kegagalan query: " . mysqli_error($conn);
}

// Mundur 1 folder keluar dari process/ menuju file content_manage.php utama
header("Location: ../content_manage.php?tab=" . $tab);
exit();
