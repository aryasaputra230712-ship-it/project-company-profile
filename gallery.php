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
$page_css = "gallery";

// 1. LOGIK DATABASE
// $query = mysqli_query($conn, "SELECT * FROM slide_utama WHERE status = 'active'");
// $slides = [];
// while ($row = mysqli_fetch_assoc($query)) {
//     $slides[] = $row;
// }

// if (empty($slides)) {
//     die("Error: Tidak ada data slide.");
// }

// 2. HEADER (Tetap di-include agar navigasi konsisten)
include ROOTPATH . "/layouts/header.php";
?>


<section class="gallery-hero">
    <div class="gallery-intro">
        <div class="bg-gallery">
            <img src="<?= BASE_URL ?>/assets/imgs/gallery-hero.jpg" alt="bg-gallery">
        </div>

        <div class="intro-content">
            <h1>Gallery</h1>
            <p>Timeless Jewelry Collection</p>
        </div>
    </div>
</section>

<section style="margin: 30px 100px; margin-left: 130px;">
    
    <nav class="item" style="display: flex; justify-content: center; align-items: center; gap: 30px;">
        <button class="menu" data-slide="0">All</button>
        <button class="menu" data-slide="1">Rings</button>
        <button class="menu" data-slide="2">Necklace</button>
        <button class="menu" data-slide="3">Earrings</button>
    </nav>

    <!-- Owl Carousel -->
    <div class="owl-carousel owl-theme">
        <div class="item" style="display: grid;

    
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));

    gap: 25px;">
            <a href="<?= BASE_URL ?>/assets/imgs/product1.jpeg" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product1.jpeg" alt="product1" style="width: 260px; height: 400px; object-fit: cover;">
            </a>

            <a href="<?= BASE_URL ?>/assets/imgs/product2.jpeg" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product2.jpeg" alt="product2" style="width: 260px; height: 400px; object-fit: cover;">
            </a>

            <a href="<?= BASE_URL ?>/assets/imgs/product3.jpeg" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product3.jpeg" alt="product3" style="width: 260px; height: 400px; object-fit: cover;">
            </a>

            <a href="<?= BASE_URL ?>/assets/imgs/product4.jpeg" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product4.jpeg" alt="product4" style="width: 260px; height: 400px; object-fit: cover;">
            </a>

            <a href="<?= BASE_URL ?>/assets/imgs/product4.jpeg" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product4.jpeg" alt="product4" style="width: 260px; height: 400px; object-fit: cover;">
            </a>

            <a href="<?= BASE_URL ?>/assets/imgs/product4.jpeg" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product4.jpeg" alt="product4" style="width: 260px; height: 400px; object-fit: cover;">
            </a>
        </div>

        <div class="item">
            RINGS CONTENT
        </div>

        <div class="item">
            NECKLC CONTENT
        </div>

        <div class="item">
            EARR CONTENT
        </div>
    </div>

</section>

<script>
    var owl = $('.owl-carousel');

    owl.owlCarousel({
        items: 1,
        mouseDrag: false,
        touchDrag: false,
        pullDrag: false,
        smartSpeed: 0,
        nav: false,
        dots: false
    });

    // klik menu navbar
    $('.menu').click(function () {

        // ambil nomor slide dari data-slide
        let slideIndex = $(this).data('slide');

        // pindah carousel
        owl.trigger('to.owl.carousel', [slideIndex, 0]);
    });
</script>

<?php include ROOTPATH . "/layouts/footer.php" ?>