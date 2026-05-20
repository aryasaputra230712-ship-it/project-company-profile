<?php
// 1. Path internal untuk include file PHP
if (!defined('ROOTPATH')) {
    define('ROOTPATH', __DIR__);
}

// 2. Deteksi Protokol(Jangan di otak atik)
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    ? "https" : "http";

// 3. Base URL Pintar
// Ini akan menghasilkan "vibewebs.web.id" di hosting
// Dan "localhost/company_profile" di laptop kamu
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_url = $protocol . "://" . $host . ($script_name == '/' ? '' : $script_name);

// Pastikan file config ter-include dengan benar
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css"
        integrity="sha512-UTNP5BXLIptsaj5WdKFrkFov94lDx+eBvbKyoe1YAfjeRPC+gT5kyZ10kOHCfNZqEui1sxmqvodNUx3KbuYI/A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
        integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
        integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>

<body>
    <header>
        <nav class="navbar">
            <div class="nav-logo">
                <a href="<?php echo $base_url; ?>/index.php">
                    <img src="<?php echo $base_url; ?>/assets/imgs/logo_gold.png" alt="Logo">
                </a>
            </div>

            <ul class="nav-links">
                <li><a href="<?php echo $base_url; ?>/index.php">HOME</a></li>
                <li><a href="<?php echo $base_url; ?>/gallery.php">GALLERY</a></li>
                <li><a href="<?php echo $base_url; ?>/about-us.php">ABOUT US</a></li>
                <li><a href="<?php echo $base_url; ?>/workshop.php">WORKSHOP</a></li>
                <li><a href="<?php echo $base_url; ?>/contact.php">CONTACT</a></li>
            </ul>

            <div class="nav-icons">
                <a href="<?php echo $base_url; ?>/auth/auth.php"><i class="fa-regular fa-user"></i></a>
                <a href="#"><i class="fa-solid fa-cart-shopping"></i></a>
            </div>
        </nav>
    </header>