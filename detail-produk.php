<?php

if (!defined('ROOTPATH')) {
    define('ROOTPATH', __DIR__);
}
include_once ROOTPATH . "/config/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================
// 1. DETEKSI ATURAN BAHASA AKTIF (BILINGUAL)
// ==========================================
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id';

// 2. Logika Base URL (Supaya gambar & asset tidak pecah)
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_url = $protocol . "://" . $host . ($script_name == '/' ? '' : $script_name);

if (!defined('BASE_URL')) define('BASE_URL', $base_url);

include ROOTPATH . "/layouts/header.php";

$id_galeri = intval($_GET['id']);
$row1 = mysqli_query($conn, "SELECT * FROM galeri_utama WHERE id = $id_galeri");
$result = mysqli_fetch_assoc($row1);

// Tarik nomor WhatsApp dari tabel pengaturan
$query_nomor = mysqli_query($conn, "SELECT whatsapp FROM pengaturan LIMIT 1");
$no_wa = mysqli_fetch_assoc($query_nomor);

?>

<style>
    .owl-nav {
        width: 100%;
        top: 0px;
        position: absolute;
        display: flex;
        justify-content: space-between;
        font-size: 18px;
        pointer-events: none;
    }

    .owl-nav button {
        pointer-events: auto;
        
    }

    .owl-nav button span {
        font-size: 20px;
        
    }
</style>

<section class="py-10 px-6">
    <header class="grid grid-cols-1 md:grid-cols-2 max-w-7xl gap-8">
        <div class="flex flex-col relative">
            <div class="overflow-hidden mx-auto">
                <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($result['gambar']) ?>" alt="product" class="md:w-[400px] md:h-[400px] w-full object-cover overflow-hidden transition duration-1000 hover:scale-[1.5]">
            </div>

            <div class="scroll-product owl-carousel owl-theme mt-5 m-auto max-w-[400px]">
                <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($result['gambar']) ?>" alt="test" class="w-[50px] h-[60px] object-cover">
                <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($result['gambar']) ?>" alt="test" class="w-[50px] h-[60px] object-cover">
                <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($result['gambar']) ?>" alt="test" class="w-[50px] h-[60px] object-cover">
                <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($result['gambar']) ?>" alt="test" class="w-[50px] h-[60px] object-cover">
                <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($result['gambar']) ?>" alt="test" class="w-[50px] h-[60px] object-cover">
                <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($result['gambar']) ?>" alt="test" class="w-[50px] h-[60px] object-cover">
            </div>
        </div>


        <div>
            <div class="mb-12">
                <h2 class="text-3xl mb-3"><?= htmlspecialchars($result['nama_produk']) ?></h2>
                <p class="text-3xl mb-3">Rp <?= number_format($result['harga'], 0, ',', '.') ?></p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas itaque labore eum temporibus enim. Doloremque cum delectus minus cupiditate vitae, beatae ducimus soluta voluptatibus expedita voluptatum totam est sed consequuntur.</p>


                <h3 class="mt-8 mb-2 text-lg">Spesifikasi Produk:</h3>
                <table cellpadding="10" width="100%" class="bg-white text-black text-sm">

                    <tr class="bg-neutral-50">
                        <td class="font-bold">Type</td>
                        <td>Perak</td>
                    </tr>

                    <tr class="bg-neutral-200">
                        <td class="font-bold">Berat</td>
                        <td>250gr</td>
                    </tr>

                    <tr class="bg-neutral-50">
                        <td class="font-bold">Type</td>
                        <td>Perak</td>
                    </tr>
                </table>
            </div>


            <a href="https://wa.me/<?= htmlspecialchars($no_wa['whatsapp'] ?? '') ?>" class="bg-aurelis-gold hover:bg-[#ffdb99] transition duration-300 px-4 py-2 text-black">Pesan Sekarang</a>
        </div>
    </header>
</section>

<section class="px-6 py-12 relative">
    <h2 align="center" class="text-2xl mb-8 font-light tracking-wider">Yang lainnya untukmu</h2>
    
    <div class="relative max-w-7xl mx-auto">
        <div class="more-product owl-carousel owl-theme">
            <?php
            $query_list = mysqli_query($conn, "SELECT * FROM galeri_utama WHERE id != $id_galeri LIMIT 8");
            while ($row2 = mysqli_fetch_assoc($query_list)) { ?>
                <div class="item">
                    <a href="detail-produk.php?id=<?= $row2['id'] ?>" class="block overflow-hidden rounded-md group">
                        <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($row2['gambar']) ?>" alt="Koleksi" class="w-full h-[350px] object-cover transition duration-500 group-hover:scale-105 group-hover:brightness-75">
                    </a>
                </div>
            <?php }; ?>
        </div>
        <div class="flex absolute top-1/2 -translate-y-1/2 w-full justify-between pointer-events-none px-2 left-0 right-0 z-10">
            <div class="customPrevBtnMore pointer-events-auto hover:cursor-pointer bg-white border border-gray-200 text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow-md hover:bg-aurelis-gold transition duration-300">
                &#10094;
            </div>
            <div class="customNextBtnMore pointer-events-auto hover:cursor-pointer bg-white border border-gray-200 text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow-md hover:bg-aurelis-gold transition duration-300">
                &#10095;
            </div>
        </div>
    </div>
</section>


<script>
    $(document).ready(function() {
        $(".more-product").owlCarousel({
            margin: 20,
            loop: true,
            responsiveClass: true,
            nav: false,
            dots: false,
            responsive: {
                0: {
                    items: 2
                },

                564: {
                    items: 3
                },

                768: {
                    items: 4
                },
                1024: {
                    items: 5
                }
            }
        });

        var owl1 = $('.more-product');
        owl1.owlCarousel();
        // Go to the next item
        $('.customNextBtnMore').click(function() {
            owl1.trigger('next.owl.carousel');
        })
        // Go to the previous item
        $('.customPrevBtnMore').click(function() {
            // With optional speed parameter
            // Parameters has to be in square bracket '[]'
            owl1.trigger('prev.owl.carousel');
        })


        //product-scroll by id
        $(".scroll-product").owlCarousel({
            margin: 15,
            loop: false,
            responsiveClass: true,
            nav: true,
            dots: false,
            responsive: {
                0: {
                    items: 2
                },

                564: {
                    items: 3
                },

                768: {
                    items: 4
                },
                1024: {
                    items: 5
                }
            }
        });
    });
</script>

<?php include ROOTPATH . "/layouts/footer.php"; ?>