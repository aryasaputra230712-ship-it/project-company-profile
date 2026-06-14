<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Simpan pilihan bahasa ke dalam Session
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// 2. Ambil URL asal (dari mana user mengklik tombol bahasa)
$url_kembali = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';

// 3. Bersihkan URL dari parameter ?lang agar tidak terlihat di address bar
// Contoh: index.php?lang=en menjadi index.php saja
$url_bersih = strtok($url_kembali, '?');

// 4. Lempar kembali ke halaman asal dengan URL yang sudah bersih
header("Location: " . $url_bersih);
exit();
?>