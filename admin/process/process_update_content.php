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
$img_dir   = ROOTPATH . "/assets/imgs/";
$video_dir = ROOTPATH . "/assets/videos/";

function sync_slide_tentang_about(mysqli $conn, string $judul, string $deskripsi): void
{
    $judul_esc     = mysqli_real_escape_string($conn, $judul);
    $deskripsi_esc = mysqli_real_escape_string($conn, $deskripsi);
    $row_q         = mysqli_query($conn, "SELECT id FROM slide_tentang WHERE status = 'aktif' LIMIT 1");

    if ($row = mysqli_fetch_assoc($row_q)) {
        $id = (int) $row['id'];
        mysqli_query(
            $conn,
            "UPDATE slide_tentang SET judul = '$judul_esc', deskripsi = '$deskripsi_esc' WHERE id = $id"
        );
        return;
    }

    mysqli_query(
        $conn,
        "INSERT INTO slide_tentang (tagline, judul, deskripsi, gambar, teks_tombol, link_tombol, status) 
         VALUES ('SINCE 2006', '$judul_esc', '$deskripsi_esc', 'about.webp', 'Pelajari Selengkapnya', '#', 'aktif')"
    );
}

function update_slide_tentang_gambar(mysqli $conn, string $new_img, string $img_dir, string $judul, string $deskripsi): void
{
    $new_img_esc   = mysqli_real_escape_string($conn, $new_img);
    $judul_esc     = mysqli_real_escape_string($conn, $judul);
    $deskripsi_esc = mysqli_real_escape_string($conn, $deskripsi);
    $row_q         = mysqli_query($conn, "SELECT id, gambar FROM slide_tentang WHERE status = 'aktif' LIMIT 1");

    if ($row = mysqli_fetch_assoc($row_q)) {
        $old_path = $img_dir . ($row['gambar'] ?? '');
        if (!empty($row['gambar']) && file_exists($old_path) && !in_array($row['gambar'], ['default.jpg', 'about.webp'], true)) {
            unlink($old_path);
        }
        $id = (int) $row['id'];
        mysqli_query($conn, "UPDATE slide_tentang SET gambar = '$new_img_esc' WHERE id = $id");
        return;
    }

    mysqli_query(
        $conn,
        "INSERT INTO slide_tentang (tagline, judul, deskripsi, gambar, teks_tombol, link_tombol, status) 
         VALUES ('SINCE 2006', '$judul_esc', '$deskripsi_esc', '$new_img_esc', 'Pelajari Selengkapnya', '#', 'aktif')"
    );
}

function sync_founder_utama(mysqli $conn, string $nama, string $bio_id): void
{
    $nama_esc = mysqli_real_escape_string($conn, $nama);
    $bio_esc  = mysqli_real_escape_string($conn, $bio_id);
    $row_q    = mysqli_query($conn, "SELECT id FROM founder_utama WHERE status = 'aktif' LIMIT 1");

    if ($row = mysqli_fetch_assoc($row_q)) {
        $id = (int) $row['id'];
        mysqli_query($conn, "UPDATE founder_utama SET judul = '$nama_esc', deskripsi = '$bio_esc' WHERE id = $id");
        return;
    }

    mysqli_query(
        $conn,
        "INSERT INTO founder_utama (judul, gambar, deskripsi, status) 
         VALUES ('$nama_esc', 'founder.webp', '$bio_esc', 'aktif')"
    );
}

function update_founder_gambar(mysqli $conn, string $new_img, string $img_dir, string $nama, string $bio_id): void
{
    $new_img_esc = mysqli_real_escape_string($conn, $new_img);
    $row_q       = mysqli_query($conn, "SELECT id, gambar FROM founder_utama WHERE status = 'aktif' LIMIT 1");

    if ($row = mysqli_fetch_assoc($row_q)) {
        $old_path = $img_dir . ($row['gambar'] ?? '');
        if (!empty($row['gambar']) && file_exists($old_path) && $row['gambar'] !== 'founder.webp') {
            unlink($old_path);
        }
        $id = (int) $row['id'];
        mysqli_query($conn, "UPDATE founder_utama SET gambar = '$new_img_esc' WHERE id = $id");
        return;
    }

    sync_founder_utama($conn, $nama, $bio_id);
    mysqli_query($conn, "UPDATE founder_utama SET gambar = '$new_img_esc' WHERE status = 'aktif' LIMIT 1");
}

function sync_sejarah_utama(mysqli $conn, string $judul, string $cerita1): void
{
    $judul_esc   = mysqli_real_escape_string($conn, $judul);
    $cerita_esc  = mysqli_real_escape_string($conn, $cerita1);
    $row_q       = mysqli_query($conn, "SELECT id FROM sejarah_utama WHERE status = 'aktif' LIMIT 1");

    if ($row = mysqli_fetch_assoc($row_q)) {
        $id = (int) $row['id'];
        mysqli_query($conn, "UPDATE sejarah_utama SET judul = '$judul_esc', cerita_1 = '$cerita_esc' WHERE id = $id");
        return;
    }

    mysqli_query(
        $conn,
        "INSERT INTO sejarah_utama (tagline, judul, cerita_1, cerita_2, gambar, status) 
         VALUES ('SINCE 2006', '$judul_esc', '$cerita_esc', '', 'history.webp', 'aktif')"
    );
}

function update_sejarah_gambar(mysqli $conn, string $new_img, string $img_dir, string $judul, string $cerita1): void
{
    $new_img_esc = mysqli_real_escape_string($conn, $new_img);
    $row_q       = mysqli_query($conn, "SELECT id, gambar FROM sejarah_utama WHERE status = 'aktif' LIMIT 1");

    if ($row = mysqli_fetch_assoc($row_q)) {
        $old_path = $img_dir . ($row['gambar'] ?? '');
        if (!empty($row['gambar']) && file_exists($old_path) && $row['gambar'] !== 'history.webp') {
            unlink($old_path);
        }
        $id = (int) $row['id'];
        mysqli_query($conn, "UPDATE sejarah_utama SET gambar = '$new_img_esc' WHERE id = $id");
        return;
    }

    sync_sejarah_utama($conn, $judul, $cerita1);
    mysqli_query($conn, "UPDATE sejarah_utama SET gambar = '$new_img_esc' WHERE status = 'aktif' LIMIT 1");
}

function sync_kutipan_utama(mysqli $conn, string $isi_kutipan): void
{
    $isi_esc = mysqli_real_escape_string($conn, $isi_kutipan);
    $row_q   = mysqli_query($conn, "SELECT id FROM kutipan_utama WHERE status = 'aktif' LIMIT 1");

    if ($row = mysqli_fetch_assoc($row_q)) {
        $id = (int) $row['id'];
        mysqli_query($conn, "UPDATE kutipan_utama SET isi_kutipan = '$isi_esc' WHERE id = $id");
        return;
    }

    mysqli_query(
        $conn,
        "INSERT INTO kutipan_utama (isi_kutipan, sumber, status) 
         VALUES ('$isi_esc', 'Aurelis International Vision', 'aktif')"
    );
}

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

                if (!empty($_FILES['video_file']['name']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION));
                    if ($ext === 'webm') {
                        $video_name = 'hero_' . time() . '.webm';
                        if (!is_dir($video_dir)) {
                            mkdir($video_dir, 0755, true);
                        }
                        if (move_uploaded_file($_FILES['video_file']['tmp_name'], $video_dir . $video_name)) {
                            $q_old = mysqli_query($conn, "SELECT video_url FROM konten_homepage WHERE id = 1 LIMIT 1");
                            if ($old = mysqli_fetch_assoc($q_old)) {
                                $old_path = $video_dir . ($old['video_url'] ?? '');
                                if (!empty($old['video_url']) && file_exists($old_path)) {
                                    unlink($old_path);
                                }
                            }
                            mysqli_query($conn, "UPDATE konten_homepage SET video_url = '$video_name' WHERE id = 1");
                            $msg_success = "Narasi HERO dan video background berhasil diperbarui!";
                        }
                    }
                }
            } elseif ($tab == 'about') {
                $about_judul_id     = mysqli_real_escape_string($conn, $_POST['about_judul_id'] ?? '');
                $about_judul_en     = mysqli_real_escape_string($conn, $_POST['about_judul_en'] ?? '');
                $about_deskripsi_id = mysqli_real_escape_string($conn, $_POST['about_deskripsi_id'] ?? '');
                $about_deskripsi_en = mysqli_real_escape_string($conn, $_POST['about_deskripsi_en'] ?? '');

                $sql = "UPDATE konten_homepage SET 
                        about_judul_id = '$about_judul_id', about_judul_en = '$about_judul_en', 
                        about_deskripsi_id = '$about_deskripsi_id', about_deskripsi_en = '$about_deskripsi_en' 
                        WHERE id = 1";

                $about_ok = mysqli_query($conn, $sql);
                sync_slide_tentang_about($conn, $_POST['about_judul_id'] ?? '', $_POST['about_deskripsi_id'] ?? '');

                if ($about_ok) {
                    $msg_success = "Konten ABOUT berhasil diperbarui!";
                }

                if (!empty($_FILES['about_gambar']['name']) && $_FILES['about_gambar']['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['about_gambar']['name'], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                        $new_img = 'about_' . time() . '.' . $ext;
                        if (move_uploaded_file($_FILES['about_gambar']['tmp_name'], $img_dir . $new_img)) {
                            update_slide_tentang_gambar(
                                $conn,
                                $new_img,
                                $img_dir,
                                $_POST['about_judul_id'] ?? '',
                                $_POST['about_deskripsi_id'] ?? ''
                            );
                            $msg_success = "Konten dan gambar ABOUT berhasil diperbarui!";
                        } else {
                            $msg_error = "Gagal mengunggah gambar About.";
                        }
                    } else {
                        $msg_error = "Format gambar tidak didukung. Gunakan JPG, PNG, atau WEBP.";
                    }
                }
            } elseif ($tab == 'founder') {
                $founder_nama   = mysqli_real_escape_string($conn, $_POST['founder_nama'] ?? '');
                $founder_bio_id = mysqli_real_escape_string($conn, $_POST['founder_bio_id'] ?? '');
                $founder_bio_en = mysqli_real_escape_string($conn, $_POST['founder_bio_en'] ?? '');

                $sql = "UPDATE konten_homepage SET 
                        founder_nama = '$founder_nama', 
                        founder_bio_id = '$founder_bio_id', founder_bio_en = '$founder_bio_en' 
                        WHERE id = 1";
                if (mysqli_query($conn, $sql)) {
                    $msg_success = "Biografi FOUNDER sukses diperbarui!";
                }
                sync_founder_utama($conn, $_POST['founder_nama'] ?? '', $_POST['founder_bio_id'] ?? '');

                if (!empty($_FILES['founder_gambar']['name']) && $_FILES['founder_gambar']['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['founder_gambar']['name'], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                        $new_img = 'founder_' . time() . '.' . $ext;
                        if (move_uploaded_file($_FILES['founder_gambar']['tmp_name'], $img_dir . $new_img)) {
                            update_founder_gambar($conn, $new_img, $img_dir, $_POST['founder_nama'] ?? '', $_POST['founder_bio_id'] ?? '');
                            $msg_success = "Biografi dan foto FOUNDER berhasil diperbarui!";
                        }
                    }
                }
            } elseif ($tab == 'history') {
                $sejarah_judul_id  = mysqli_real_escape_string($conn, $_POST['sejarah_judul_id'] ?? '');
                $sejarah_judul_en  = mysqli_real_escape_string($conn, $_POST['sejarah_judul_en'] ?? '');
                $sejarah_konten_id = mysqli_real_escape_string($conn, $_POST['sejarah_konten_id'] ?? '');
                $sejarah_konten_en = mysqli_real_escape_string($conn, $_POST['sejarah_konten_en'] ?? '');

                $sql = "UPDATE konten_homepage SET 
                        sejarah_judul_id = '$sejarah_judul_id', sejarah_judul_en = '$sejarah_judul_en', 
                        sejarah_konten_id = '$sejarah_konten_id', sejarah_konten_en = '$sejarah_konten_en' 
                        WHERE id = 1";
                if (mysqli_query($conn, $sql)) {
                    $msg_success = "Linimasa Cerita HISTORY sukses diperbarui!";
                }
                sync_sejarah_utama($conn, $_POST['sejarah_judul_id'] ?? '', $_POST['sejarah_konten_id'] ?? '');

                if (!empty($_FILES['history_gambar']['name']) && $_FILES['history_gambar']['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['history_gambar']['name'], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                        $new_img = 'history_' . time() . '.' . $ext;
                        if (move_uploaded_file($_FILES['history_gambar']['tmp_name'], $img_dir . $new_img)) {
                            update_sejarah_gambar($conn, $new_img, $img_dir, $_POST['sejarah_judul_id'] ?? '', $_POST['sejarah_konten_id'] ?? '');
                            $msg_success = "Konten dan gambar HISTORY berhasil diperbarui!";
                        }
                    }
                }
            } elseif ($tab == 'quotes') {
                $kutipan_id = mysqli_real_escape_string($conn, $_POST['kutipan_id'] ?? '');
                $kutipan_en = mysqli_real_escape_string($conn, $_POST['kutipan_en'] ?? '');

                $sql = "UPDATE konten_homepage SET 
                        kutipan_id = '$kutipan_id', kutipan_en = '$kutipan_en' 
                        WHERE id = 1";
                if (mysqli_query($conn, $sql)) {
                    $msg_success = "Kutipan Emas QUOTES sukses diperbarui!";
                }
                sync_kutipan_utama($conn, $_POST['kutipan_id'] ?? '');
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
                $sql = "DELETE FROM motto_utama WHERE nomor = '$id_hapus'";
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
} elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST' && mysqli_errno($conn)) {
    $_SESSION['error'] = "Gagal menyimpan perubahan: " . mysqli_error($conn);
}

// Mundur 1 folder keluar dari process/ menuju file content_manage.php utama
header("Location: ../content_manage.php?tab=" . $tab);
exit();
