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

    <?php if (isset($page_css)) : ?>
        <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/<?php echo trim($page_css, '/'); ?>.css">
    <?php endif; ?>

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
                        'aurelis-krem': '#FDFBF7',
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
</head>

<body class="w-full min-h-full bg-aurelis-dark">

    <!-- 1. HEADER UTAMA (Hanya membungkus Navigasi Atas) -->
    <header class="fixed top-0 left-0 w-full z-[999] glass-header transition-all duration-300">
        <nav class="max-w-[1180px] mx-auto px-6 py-4 flex justify-between items-center">

            <div class="flex-shrink-0">
                <a href="<?php echo $base_url; ?>/index.php">
                    <img src="<?php echo $base_url; ?>/assets/imgs/logo_gold.png" alt="Logo" class="h-9 md:h-12 w-auto object-contain">
                </a>
            </div>

            <ul class="hidden md:flex items-center gap-8 list-none">
                <li><a href="<?= $base_url ?>" class="text-aurelis-krem hover:text-aurelis-gold transition">Home</a></li>
                <li><a href="<?= $base_url ?>/gallery" class="text-aurelis-krem hover:text-aurelis-gold transition">Gallery</a></li>
                <li><a href="<?= $base_url ?>/workshop" class="text-aurelis-krem hover:text-aurelis-gold transition">Workshop</a></li>
                <li><a href="<?= $base_url ?>/contact" class="text-aurelis-krem hover:text-aurelis-gold transition">Contact</a></li>
            </ul>

            <div class="flex items-center gap-4 md:gap-5">
                <!-- Language Switcher Desktop -->
                <div class="hidden md:flex items-center gap-3">
                    <button class="flex items-center gap-2 text-sm font-semibold text-aurelis-gold transition group">
                        <img src="https://flagcdn.com/w20/id.png" alt="ID" class="w-4 h-auto rounded-sm opacity-80 group-hover:opacity-100"> ID
                    </button>
                    <div class="w-[1px] h-4 bg-white/10 self-center"></div>
                    <button class="flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-aurelis-krem transition group">
                        <img src="https://flagcdn.com/w20/us.png" alt="EN" class="w-4 h-auto rounded-sm opacity-50 group-hover:opacity-80"> EN
                    </button>
                </div>

                <div id="nav-icons" class="flex items-center gap-5 transition-opacity duration-300">
                    <a href="#" class="text-aurelis-krem text-lg hover:text-aurelis-gold"><i class="fa-regular fa-user"></i></a>
                    <a href="#" class="text-aurelis-krem text-lg hover:text-aurelis-gold"><i class="fa-solid fa-cart-shopping"></i></a>
                </div>

                <!-- Tombol Hamburger (Diberi padding p-2 agar area sentuh di HP lebih luas) -->
                <button id="hamburger-btn" class="md:hidden text-aurelis-krem text-2xl focus:outline-none p-2 relative z-[1000]">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </nav>
    </header> <!-- Penutup Header di sini agar tidak mengurung mobile menu -->


    <!-- 2. MOBILE MENU (Sekarang berdiri mandiri di luar elemen Glass) -->
    <div id="mobile-menu" class="fixed inset-0 bg-aurelis-dark z-[9999] flex flex-col transition-all duration-500 transform -translate-y-full opacity-0 pointer-events-none">

        <div class="flex justify-between items-center px-6 py-4 border-b border-white/10">
            <img src="<?php echo $base_url; ?>/assets/imgs/logo_gold.png" alt="Logo" class="h-8 w-auto object-contain">
            <button id="close-menu" class="text-aurelis-krem text-2xl focus:outline-none p-2">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

            <div class="flex-1 flex flex-col items-center justify-start pt-12 bg-[#050816] ">
                <ul class="flex flex-col items-center w-full list-none ">
                    <li class="w-full border-b border-gray-50 ">
                        <a href="index.php" class="mobile-link  block py-6 text-aurelis-krem text-lg font-playfair tracking-[0.3em] text-center hover:bg-gray-50 transition">HOME</a>
                    </li>
                    <li class="w-full border-b border-gray-50">
                        <a href="gallery.php" class="mobile-link block py-6 text-aurelis-krem text-lg font-playfair tracking-[0.3em] text-center hover:bg-gray-50 transition">GALLERY</a>
                    </li>
                    <li class="w-full border-b border-gray-50">
                        <a href="workshop.php" class="mobile-link block py-6 text-aurelis-krem text-lg font-playfair tracking-[0.3em] text-center hover:bg-gray-50 transition">WORKSHOP</a>
                    </li>
                    <li class="w-full border-b border-gray-50">
                        <a href="contact.php" class="mobile-link block py-6 text-aurelis-krem text-lg font-playfair tracking-[0.3em] text-center hover:bg-gray-50 transition">CONTACT</a>
                    </li>
                </ul>

            <div class="mt-16 text-center w-full">
                <span class="text-[0.6rem] text-gray-500 tracking-[0.5em] font-bold uppercase mb-6 block">SELECT LANGUAGE</span>
                <div class="flex justify-center gap-6">
                    <button class="flex items-center gap-3 text-sm font-semibold text-aurelis-gold transition group">
                        <img src="https://flagcdn.com/w20/id.png" alt="ID" class="w-4 h-auto rounded-sm opacity-80 group-hover:opacity-100"> ID
                    </button>
                    <div class="w-[1px] h-4 bg-white/10 self-center"></div>
                    <button class="flex items-center gap-3 text-sm font-semibold text-gray-500 hover:text-aurelis-krem transition group">
                        <img src="https://flagcdn.com/w20/us.png" alt="EN" class="w-4 h-auto rounded-sm opacity-50 group-hover:opacity-80"> EN
                    </button>
                </div>
            </div>

            <div class="mt-12 flex gap-8">
                <a href="#" class="text-white/40 hover:text-aurelis-gold transition text-lg"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="text-white/40 hover:text-aurelis-gold transition text-lg"><i class="fa-brands fa-whatsapp"></i></a>
                <a href="#" class="text-white/40 hover:text-aurelis-gold transition text-lg"><i class="fa-regular fa-envelope"></i></a>
            </div>
        </div>
    </div>

    <!-- 3. SPACER HEIGHT -->
    <div class="h-16 md:h-20"></div>

    <!-- 4. LOGIKA JAVASCRIPT -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const hamburgerBtn = document.getElementById("hamburger-btn");
            const closeBtn = document.getElementById("close-menu");
            const mobileMenu = document.getElementById("mobile-menu");
            const navIcons = document.getElementById("nav-icons");
            const mobileLinks = document.querySelectorAll(".mobile-link");

            // Fungsi Buka Menu
            if (hamburgerBtn) {
                // Menggunakan 'click' tapi ditambahkan padding area sentuh
                hamburgerBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    if (mobileMenu) {
                        mobileMenu.classList.remove("-translate-y-full", "opacity-0", "pointer-events-none");
                        mobileMenu.classList.add("translate-y-0", "opacity-100", "pointer-events-auto");
                    }
                    if (navIcons) navIcons.classList.add("opacity-0");
                    document.body.style.overflow = "hidden";
                });
            }

            // Fungsi Tutup Menu
            const closeMenuAction = (e) => {
                if (e) e.preventDefault();
                if (mobileMenu) {
                    mobileMenu.classList.remove("translate-y-0", "opacity-100", "pointer-events-auto");
                    mobileMenu.classList.add("-translate-y-full", "opacity-0", "pointer-events-none");
                }
                if (navIcons) navIcons.classList.remove("opacity-0");
                document.body.style.overflow = "";
            };

            if (closeBtn) {
                closeBtn.addEventListener("click", closeMenuAction);
            }

            mobileLinks.forEach((link) => {
                link.addEventListener("click", closeMenuAction);
            });
        });
    </script>