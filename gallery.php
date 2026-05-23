<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', __DIR__);
}

// 2. Buat Base URL Otomatis (Bisa mendeteksi folder /company_profile/ secara mandiri)
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_url = $protocol . "://" . $host . ($script_name == '/' ? '' : $script_name);

// Definisikan konstanta agar bisa dipakai di seluruh file
define('BASE_URL', $base_url);

include_once ROOTPATH . "/config/config.php";

include ROOTPATH . "/layouts/header.php";
?>

<section class="gallery-hero">
    <div class="relative">
        <div class="absolute inset-0">
            <img class="relative w-full h-full object-cover brightness-50" src="<?= BASE_URL ?>/assets/imgs/gallery-hero.jpg" alt="bg-gallery">
        </div>

        <div class="relative flex flex-col items-center justify-center gap-6 min-h-[50vh] uppercase">
            <h1 class="tracking-[10px] text-4xl text-center md:text-5xl md:tracking-[15px] lg:text-5xl lg:tracking-[20px]">Gallery</h1>
            <p class="tracking-widest font-light text-sm">Timeless Jewelry Collection</p>
        </div>
    </div>
</section>

<section class="mt-12 mb-20">

    <!-- FILTER MENU -->
    <div class="flex justify-center gap-10 mb-20 text-sm">

        <button
            class="filter-btn border-b-2 border-orange-500 text-white tracking-[2px] pb-2"
            data-filter="all">
            All
        </button>

        <button
            class="filter-btn text-white tracking-[2px] pb-2 hover:text-orange-400"
            data-filter="rings">
            Rings
        </button>

        <button
            class="filter-btn text-white tracking-[2px] pb-2 hover:text-orange-400"
            data-filter="necklace">
            Necklace
        </button>

        <button
            class="filter-btn text-white tracking-[2px] pb-2 hover:text-orange-400"
            data-filter="earrings">
            Earrings
        </button>

    </div>

    <!-- GALLERY -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-7 mx-auto px-6 max-w-5xl">

        <div class="item rings overflow-hidden w-full h-[400px]">
            <a href="<?= BASE_URL ?>/assets/imgs/product1.jpeg" alt="product1" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product1.jpeg" class="w-full h-[270px] object-cover hover:scale-110 transition duration-300">
            </a>

            <!-- PRODUCT INFO -->
            <div class="mt-4 text-center">

                <h3 class="text-white text-lg tracking-wide">
                    Diamond Ring
                </h3>

                <p class="text-orange-400 text-sm mt-1 mb-3">
                    Rp 4.700.000
                </p>

                <a class="flex justify-center items-center bg-orange-600 p-2 rounded-sm hover:bg-orange-700 transition-all duration-300 m-auto w-40" href="#">Pesan Sekarang</a>

            </div>
        </div>

        <div class="item necklace overflow-hidden">
            <a href="<?= BASE_URL ?>/assets/imgs/product2.jpeg" alt="product2" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product2.jpeg" class="w-full h-[270px] object-cover hover:scale-110 transition duration-300">
            </a>

            <!-- PRODUCT INFO -->
            <div class="mt-4 text-center">

                <h3 class="text-white text-lg tracking-wide">
                    Emerald Necklace
                </h3>

                <p class="text-orange-400 text-sm mt-1 mb-3">
                    Rp 7.500.000
                </p>

                <a class="flex justify-center items-center bg-orange-600 p-2 rounded-sm hover:bg-orange-700 transition-all duration-300 m-auto w-40" href="#">Pesan Sekarang</a>

            </div>
        </div>

        <div class="item earrings overflow-hidden">
            <a href="<?= BASE_URL ?>/assets/imgs/product3.jpeg" alt="product3" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product3.jpeg" class="w-full h-[270px] object-cover hover:scale-110 transition duration-300">
            </a>

            <!-- PRODUCT INFO -->
            <div class="mt-4 text-center">

                <h3 class="text-white text-lg tracking-wide">
                    Luxury Earrings
                </h3>

                <p class="text-orange-400 text-sm mt-1 mb-3">
                    Rp 1.200.000
                </p>

                <a class="flex justify-center items-center bg-orange-600 p-2 rounded-sm hover:bg-orange-700 transition-all duration-300 m-auto w-40" href="#">Pesan Sekarang</a>

            </div>
        </div>

        <div class="item rings overflow-hidden">
            <a href="<?= BASE_URL ?>/assets/imgs/product4.jpeg" alt="product4" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product4.jpeg" class="w-full h-[270px] object-cover hover:scale-110 transition duration-300">
            </a>

            <!-- PRODUCT INFO -->
            <div class="mt-4 text-center">

                <h3 class="text-white text-lg tracking-wide">
                    Ruby Ring
                </h3>

                <p class="text-orange-400 text-sm mt-1 mb-3">
                    Rp 2.500.000
                </p>

                <a class="flex justify-center items-center bg-orange-600 p-2 rounded-sm hover:bg-orange-700 transition-all duration-300 m-auto w-40" href="#">Pesan Sekarang</a>

            </div>
        </div>

        <div class="item rings overflow-hidden">
            <a href="<?= BASE_URL ?>/assets/imgs/product1.jpeg" alt="product1" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product1.jpeg" class="w-full h-[270px] object-cover hover:scale-110 transition duration-300">
            </a>

            <!-- PRODUCT INFO -->
            <div class="mt-4 text-center">

                <h3 class="text-white text-lg tracking-wide">
                    Diamond Ring
                </h3>

                <p class="text-orange-400 text-sm mt-1 mb-3">
                    Rp 4.700.000
                </p>

                <a class="flex justify-center items-center bg-orange-600 p-2 rounded-sm hover:bg-orange-700 transition-all duration-300 m-auto w-40" href="#">Pesan Sekarang</a>

            </div>
        </div>

        <div class="item earrings overflow-hidden">
            <a href="<?= BASE_URL ?>/assets/imgs/product3.jpeg" alt="product3" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product3.jpeg" class="w-full h-[270px] object-cover hover:scale-110 transition duration-300">
            </a>

            <!-- PRODUCT INFO -->
            <div class="mt-4 text-center">

                <h3 class="text-white text-lg tracking-wide">
                    Luxury Earrings
                </h3>

                <p class="text-orange-400 text-sm mt-1 mb-3">
                    Rp 1.200.000
                </p>

                <a class="flex justify-center items-center bg-orange-600 p-2 rounded-sm hover:bg-orange-700 transition-all duration-300 m-auto w-40" href="#">Pesan Sekarang</a>

            </div>
        </div>

    </div>

</section>

<script src="<?= BASE_URL ?>/assets/js/gallery.js"></script>

<?php include ROOTPATH . "/layouts/footer.php" ?>