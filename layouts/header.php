<?php
// 1. Path internal untuk include file PHP
if (!defined('ROOTPATH')) {
    define('ROOTPATH', __DIR__);
}

// 2. Deteksi Protokol
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    ? "https" : "http";

// 3. Base URL Pintar
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_url = $protocol . "://" . $host . ($script_name == '/' ? '' : $script_name);

if (file_exists(ROOTPATH . "/config/config.php")) {
    include_once ROOTPATH . "/config/config.php";
}
?>
<!DOCTYPE html>
<html lang="id" class="w-full min-h-full overflow-x-hidden">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : "Aurelis Jewelry | Official Store"; ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<<<<<<< HEAD
    <?php if (isset($page_css)) : ?>
        <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/<?php echo trim($page_css, '/'); ?>.css">
    <?php endif; ?>

=======
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'aurelis-gold': '#f7c66b',
                        'aurelis-dark': '#050816',
                        'aurelis-blue': '#070b1e',
                        'aurelis-light': '#e6e9ff',
                        'aurelis-hover': '#9bb7ff',
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                        'playfair': ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>

    <style>
        /* Base Styling untuk Body sesuai CSS lamamu */
        body {
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at top, rgba(255, 255, 255, 0.06), transparent 35%),
                linear-gradient(180deg, #070b1e 0%, #02050f 100%);
            color: #f7f7f7;
            min-height: 100vh;
        }

        /* Glassmorphism Header */
        .glass-header {
            background: rgba(5, 8, 22, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
>>>>>>> d1887bf0d9e45470b1a62d1756963cad573e90e8
</head>

<body class="w-full">
    <header class="fixed top-0 left-0 w-full z-[200] glass-header transition-all duration-300">
        <nav class="max-w-[1180px] mx-auto px-6 md:px-8 py-4 flex justify-between items-center gap-4">

            <div class="flex-shrink-0">
                <a href="<?php echo $base_url; ?>/index.php">
                    <img src="<?php echo $base_url; ?>/assets/imgs/logo_gold.png" alt="Logo" class="h-10 md:h-12 w-auto object-contain">
                </a>
            </div>

<<<<<<< HEAD
            <ul class="nav-links">
                <li><a href="<?php echo $base_url; ?>/index.php">HOME</a></li>
                <li><a href="<?php echo $base_url; ?>/gallery.php">GALLERY</a></li>
                <li><a href="<?php echo $base_url; ?>/about-us.php">ABOUT US</a></li>
                <li><a href="<?php echo $base_url; ?>/workshop.php">WORKSHOP</a></li>
                <li><a href="<?php echo $base_url; ?>/contact.php">CONTACT</a></li>
=======
            <ul class="hidden md:flex items-center gap-8 list-none">
                <li><a href="<?php echo $base_url; ?>/index.php" class="text-aurelis-light text-[0.95rem] font-medium tracking-wide hover:text-aurelis-hover transition-colors duration-300">Home</a></li>
                <li><a href="#" class="text-aurelis-light text-[0.95rem] font-medium tracking-wide hover:text-aurelis-hover transition-colors duration-300">Shop</a></li>
                <li><a href="#" class="text-aurelis-light text-[0.95rem] font-medium tracking-wide hover:text-aurelis-hover transition-colors duration-300">About Us</a></li>
                <li><a href="#" class="text-aurelis-light text-[0.95rem] font-medium tracking-wide hover:text-aurelis-hover transition-colors duration-300">Contact</a></li>
>>>>>>> d1887bf0d9e45470b1a62d1756963cad573e90e8
            </ul>

            <div class="flex items-center gap-5">
                <a href="<?php echo $base_url; ?>/auth/auth.php" class="text-aurelis-light text-xl hover:text-aurelis-hover transition-colors duration-300">
                    <i class="fa-regular fa-user"></i>
                </a>
                <a href="#" class="text-aurelis-light text-xl hover:text-aurelis-hover transition-colors duration-300 relative">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>

                <button class="md:hidden text-aurelis-light text-2xl focus:outline-none">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </nav>
    </header>

    <div class="h-16 md:h-20"></div>