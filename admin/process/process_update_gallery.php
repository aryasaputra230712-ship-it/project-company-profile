<?php
include "../auth_check.php";

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__, 2));
}
include_once ROOTPATH . "/config/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tab = isset($_POST['tab_name']) ? mysqli_real_escape_string($conn, $_POST['tab_name']) : 'hero';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $tab !== 'hero') {
    header("Location: ../gallery_manage.php?tab=hero");
    exit();
}

$judul_id    = mysqli_real_escape_string($conn, $_POST['gallery_judul_id'] ?? '');
$judul_en    = mysqli_real_escape_string($conn, $_POST['gallery_judul_en'] ?? '');
$subjudul_id = mysqli_real_escape_string($conn, $_POST['gallery_sub_id'] ?? '');
$subjudul_en = mysqli_real_escape_string($conn, $_POST['gallery_sub_en'] ?? '');

$q_old = mysqli_query($conn, "SELECT gambar FROM header_galeri WHERE id = 1 LIMIT 1");
$old   = mysqli_fetch_assoc($q_old);
$gambar = $old['gambar'] ?? 'gallery-hero.webp';
$img_dir = ROOTPATH . "/assets/imgs/";

if (!empty($_FILES['gallery_image']['name']) && $_FILES['gallery_image']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['gallery_image']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
        $new_name = 'hero_galeri_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['gallery_image']['tmp_name'], $img_dir . $new_name)) {
            $old_path = $img_dir . $gambar;
            if ($gambar && file_exists($old_path) && $gambar !== 'gallery-hero.webp') {
                unlink($old_path);
            }
            $gambar = $new_name;
        }
    }
}

$gambar_esc = mysqli_real_escape_string($conn, $gambar);
$sql = "UPDATE header_galeri SET 
        judul_id = '$judul_id', 
        judul_en = '$judul_en', 
        subjudul_id = '$subjudul_id', 
        subjudul_en = '$subjudul_en', 
        gambar = '$gambar_esc' 
        WHERE id = 1";

if (mysqli_query($conn, $sql)) {
    $_SESSION['sukses'] = "Header hero galeri berhasil diperbarui!";
} else {
    $_SESSION['error'] = "Gagal menyimpan: " . mysqli_error($conn);
}

header("Location: ../gallery_manage.php?tab=hero");
exit();
