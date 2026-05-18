<?php
// 1. Path internal untuk include file PHP
if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT']);
}

// 2. Deteksi Protokol (HTTP/HTTPS)
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    ? "https" : "http";

// 3. Base URL yang dinamis (Otomatis vibewebs.web.id)
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'];

// Pastikan file config ada sebelum di-include
if (file_exists(ROOTPATH . "/config/config.php")) {
    include_once ROOTPATH . "/config/config.php";
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : "Aurelis Jewelry | Official Store"; ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/global.css">

    <?php if (isset($page_css)) : ?>
        <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/<?php echo trim($page_css, '/'); ?>.css">
    <?php endif; ?>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="nav-logo">
                <a href="<?php echo $base_url; ?>/index.php">
                    <img src="<?php echo $base_url; ?>/assets/imgs/logo_blue.png" alt="Logo">
                </a>
            </div>

            <ul class="nav-links">
                <li><a href="<?php echo $base_url; ?>/index.php">Home</a></li>
                <li><a href="#">Shop</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact</a></li>
            </ul>

            <div class="nav-icons">
                <a href="<?php echo $base_url; ?>/auth/auth.php"><i class="fa-regular fa-user"></i></a>
                <a href="#"><i class="fa-solid fa-cart-shopping"></i></a>
            </div>
        </nav>
    </header>