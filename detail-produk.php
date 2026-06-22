<?php
// ============================================
// 1. SETUP KONFIGURASI & DATABASE
// ============================================
if (!defined('ROOTPATH')) {
    define('ROOTPATH', __DIR__);
}
include_once ROOTPATH . "/config/config.php";

// ============================================
// 2. SESSION & DETEKSI BAHASA (BILINGUAL)
// ============================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Tangkap dulu jika ada klik perubahan bahasa dari URL
if (isset($_GET['lang']) && in_array($_GET['lang'], ['id', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// 2. Baru masukkan ke variabel $lang
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id';

// ============================================
// 3. SETUP BASE URL UNTUK ASSET
// ============================================
if (!defined('BASE_URL')) {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $base_url = $protocol . "://" . $host . ($script_name == '/' ? '' : $script_name);
    define('BASE_URL', $base_url);
}


// ============================================
// 4. VALIDASI & AMBIL DATA PRODUK UTAMA
// ============================================
// 🔐 Keamanan: Validasi ID dengan intval() dan gunakan prepared statement
$id_galeri = intval($_GET['id'] ?? 0);

if ($id_galeri <= 0) {
    header("Location: gallery.php");
    exit("❌ ID produk tidak valid!");
}

// 🛡️ PREPARED STATEMENT untuk mencegah SQL Injection
$stmt = $conn->prepare("SELECT * FROM galeri_utama WHERE id = ?");
$stmt->bind_param("i", $id_galeri);
$stmt->execute();
$result_query = $stmt->get_result();
$result = $result_query->fetch_assoc();

// ⚠️ Cek jika produk tidak ditemukan
if (!$result) {
    header("Location: gallery.php");
    exit("❌ Produk tidak ditemukan!");
}

// ============================================
// 5. AMBIL DATA SPESIFIKASI PRODUK
// ============================================
$stmt_spek = $conn->prepare("SELECT * FROM spesifikasi_produk WHERE id_galeri = ?");
$stmt_spek->bind_param("i", $id_galeri);
$stmt_spek->execute();
$spesifikasi = $stmt_spek->get_result()->fetch_assoc();

// ============================================
// 6. AMBIL DATA GALLERY DETAIL (4 GAMBAR)
// ============================================
// 📸 Coba ambil dari tabel galeri_detail, jika tidak ada gunakan gambar utama
$gallery_images = [];

// Tambahkan gambar utama sebagai elemen pertama di array
if (!empty($result['gambar'])) {
    $gallery_images[] = ['gambar' => $result['gambar']];
}

// Ambil gambar tambahan dari database
$stmt_gallery = $conn->prepare("SELECT gambar FROM gambar_detail_produk WHERE id_galeri = ? ORDER BY id ASC LIMIT 4");
$stmt_gallery->bind_param("i", $id_galeri);
$stmt_gallery->execute();
$gallery_res = $stmt_gallery->get_result();

// Gabungkan ke dalam array yang sama
while ($img = $gallery_res->fetch_assoc()) {
    $gallery_images[] = $img;
}

// ============================================
// 7. AMBIL NOMOR WHATSAPP DARI PENGATURAN
// ============================================
$stmt_wa = $conn->prepare("SELECT whatsapp FROM pengaturan LIMIT 1");
$stmt_wa->execute();
$no_wa = $stmt_wa->get_result()->fetch_assoc();
$whatsapp_number = $no_wa['whatsapp'] ?? '0';

// 🔄 Escape untuk keamanan XSS
$product_name = htmlspecialchars($result['nama_produk'], ENT_QUOTES, 'UTF-8');
$product_image = htmlspecialchars($result['gambar'], ENT_QUOTES, 'UTF-8');
$product_price = number_format($result['harga'], 0, ',', '.');

// ============================================
// 8. LOGIKA BAHASA (BILINGUAL DISPLAY)
// ============================================

// 1. Tentukan Variabel Data Berdasarkan Bahasa
// Menggunakan deskripsi_id atau deskripsi_en dari tabel galeri_utama
$product_name = ($lang == 'en' && !empty($result['nama_produk_en'])) ? $result['nama_produk_en'] : $result['nama_produk'];
$product_desc = ($lang == 'en' && !empty($result['deskripsi_en'])) ? $result['deskripsi_en'] : ($result['deskripsi_id'] ?? $result['deskripsi'] ?? 'Tidak ada deskripsi');
// 2. Tentukan Teks Statis (Label Tabel dll) Berdasarkan Bahasa
$label_spesifikasi = ($lang == 'en') ? "Product Specifications:" : "Spesifikasi Produk:";
$label_tipe        = ($lang == 'en') ? "Type" : "Tipe";
$label_warna       = ($lang == 'en') ? "Color" : "Warna";
$label_berat       = ($lang == 'en') ? "Weight" : "Berat";
$label_wa_btn      = ($lang == 'en') ? "💬 Order via WhatsApp" : "💬 Pesan via WhatsApp";
$label_lainnya     = ($lang == 'en') ? "✨ Other Collections For You" : "✨ Koleksi Lainnya Untukmu";

// Siapkan gambar dan harga (tidak terpengaruh bahasa)
$product_image = htmlspecialchars($result['gambar'], ENT_QUOTES, 'UTF-8');
$product_price = number_format($result['harga'], 0, ',', '.');

// Siapkan pesan WhatsApp
$wa_message = ($lang == 'en')
    ? "Hello Aurelis Jewelry, I'm interested in purchasing: " . $product_name
    : "Halo Aurelis Jewelry, saya tertarik dengan produk: " . $product_name;
$wa_message_encoded = urlencode($wa_message);

// Bersihkan output untuk keamanan
$display_name = htmlspecialchars($product_name, ENT_QUOTES, 'UTF-8');

include ROOTPATH . "/layouts/header.php";
?>


<!-- ============================================
     SECTION: DETAIL PRODUK UTAMA (2 KOLOM)
     ============================================ -->
<section class="py-10 px-6">
    <div class="grid grid-cols-1 md:grid-cols-2 max-w-7xl gap-8 mx-auto">

        <div class="flex flex-col">
            <div class="overflow-hidden mx-auto rounded-lg shadow-lg">
                <img id="main-image"
                    src="<?= BASE_URL ?>/assets/imgs/<?= $product_image ?>"
                    alt="<?= $display_name ?>"
                    class="md:w-[350px] md:h-[350px] w-full h-auto object-cover overflow-hidden transition duration-1000 hover:scale-[1.5] cursor-zoom-in">
            </div>

            <div class="flex gap-4 justify-center w-full mt-6 flex-wrap">
                <?php foreach ($gallery_images as $index => $img):
                    $img_src = htmlspecialchars($img['gambar'], ENT_QUOTES, 'UTF-8');
                    $is_active = ($index === 0) ? 'border-2 border-aurelis-gold' : 'border border-gray-300';
                ?>
                    <img class="w-[80px] h-[80px] object-cover cursor-pointer rounded transition <?= $is_active ?> hover:opacity-75"
                        src="<?= BASE_URL ?>/assets/imgs/<?= $img_src ?>"
                        alt="Thumbnail <?= $index + 1 ?>"
                        onclick="changeMainImage(this.src)">
                <?php endforeach; ?>
            </div>
        </div>

        <div>
            <div class="mb-8">
                <h1 class="text-4xl font-serif mb-4 text-white tracking-wide">
                    <?= $display_name ?>
                </h1>
                <p class="text-4xl font-bold text-aurelis-gold mb-6">
                    Rp <?= $product_price ?>
                </p>

                <p class="text-gray-400 leading-relaxed mb-6">
                    <?= htmlspecialchars($product_desc, ENT_QUOTES, 'UTF-8') ?>
                </p>
            </div>

            <?php if ($spesifikasi):
                // Logika Bahasa Spesifikasi (Dalam blok if untuk memastikan data spesifikasi ada)
                $tipe_display  = ($lang == 'en' && !empty($spesifikasi['tipe_spesifikasi_en'])) ? $spesifikasi['tipe_spesifikasi_en'] : ($spesifikasi['tipe_spesifikasi_id'] ?? 'N/A');
                $warna_display = ($lang == 'en' && !empty($spesifikasi['warna_en'])) ? $spesifikasi['warna_en'] : ($spesifikasi['warna_id'] ?? 'N/A');
            ?>
                <div class="mb-8">
                    <h3 class="text-lg font-serif mb-4 text-white"><?= $label_spesifikasi ?></h3>

                    <div class="overflow-hidden rounded-lg border border-gray-300">
                        <table class="w-full text-sm">
                            <tr class="bg-gray-100 border-b border-gray-300">
                                <td class="font-bold text-gray-900 px-6 py-4 w-1/3"><?= $label_tipe ?></td>
                                <td class="text-gray-700 px-6 py-4">
                                    <?= htmlspecialchars($tipe_display, ENT_QUOTES, 'UTF-8') ?>
                                </td>
                            </tr>
                            <tr class="bg-white border-b border-gray-300">
                                <td class="font-bold text-gray-900 px-6 py-4"><?= $label_warna ?></td>
                                <td class="text-gray-700 px-6 py-4">
                                    <?= htmlspecialchars($warna_display, ENT_QUOTES, 'UTF-8') ?>
                                </td>
                            </tr>
                            <tr class="bg-gray-100 hover:bg-gray-200 transition">
                                <td class="font-bold text-gray-900 px-6 py-4"><?= $label_berat ?></td>
                                <td class="text-gray-700 px-6 py-4">
                                    <?= htmlspecialchars($spesifikasi['berat'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <a href="https://wa.me/<?= htmlspecialchars($whatsapp_number, ENT_QUOTES, 'UTF-8') ?>?text=<?= $wa_message_encoded ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-block bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 transition duration-300 px-8 py-3 text-white font-bold tracking-wider rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105">
                <?= $label_wa_btn ?>
            </a>
        </div>
    </div>
</section>
<!-- ============================================
     SECTION: PRODUK LAINNYA (CAROUSEL)
     ============================================ -->
<section class="px-6 py-16 relative bg-gray-900/30">
    <h2 class="text-center text-3xl mb-12 font-serif tracking-wider text-white">
        Koleksi Lainnya Untukmu
    </h2>

    <div class="relative max-w-7xl mx-auto">
        <!-- 🎠 OWL CAROUSEL -->
        <div class="more-product owl-carousel owl-theme">
            <?php
            // 📦 Ambil 8 produk lain (selain produk saat ini)
            $stmt_other = $conn->prepare("SELECT id, nama_produk, gambar FROM galeri_utama WHERE id != ? AND status = 'aktif' ORDER BY id DESC LIMIT 8");
            $stmt_other->bind_param("i", $id_galeri);
            $stmt_other->execute();
            $query_list = $stmt_other->get_result();

            while ($row2 = $query_list->fetch_assoc()) {
                $other_name = htmlspecialchars($row2['nama_produk'], ENT_QUOTES, 'UTF-8');
                $other_img = htmlspecialchars($row2['gambar'], ENT_QUOTES, 'UTF-8');
                $other_id = intval($row2['id']);
            ?>
                <div class="item">
                    <a href="detail-produk.php?id=<?= $other_id ?>" class="block overflow-hidden rounded-xl group shadow-lg">
                        <img src="<?= BASE_URL ?>/assets/imgs/<?= $other_img ?>"
                            alt="<?= $other_name ?>"
                            class="w-full h-[280px] object-cover transition duration-500 group-hover:scale-110 group-hover:brightness-75">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition duration-300"></div>
                    </a>
                </div>
            <?php
            }
            ?>
        </div>

        <!-- ◀️ ▶️ TOMBOL NAVIGASI CAROUSEL -->
        <div class="flex absolute top-1/2 -translate-y-1/2 w-full justify-between pointer-events-none px-2 left-0 right-0 z-10">
            <button class="customPrevBtnMore pointer-events-auto hover:cursor-pointer bg-white/90 border border-gray-200 text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow-md hover:bg-aurelis-gold hover:text-white transition duration-300 font-bold text-lg">
                &#10094;
            </button>
            <button class="customNextBtnMore pointer-events-auto hover:cursor-pointer bg-white/90 border border-gray-200 text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow-md hover:bg-aurelis-gold hover:text-white transition duration-300 font-bold text-lg">
                &#10095;
            </button>
        </div>
    </div>
</section>

<!-- 🎯 JAVASCRIPT: MAIN IMAGE CHANGE & OWL CAROUSEL -->
<script>
    // ✨ Fungsi untuk mengganti gambar utama saat thumbnail diklik
    function changeMainImage(src) {
        const mainImage = document.getElementById('main-image');
        if (mainImage) {
            mainImage.style.opacity = '0.5';
            mainImage.src = src;
            setTimeout(() => {
                mainImage.style.opacity = '1';
            }, 200);
        }
    }

    // 🎠 Inisialisasi OWL Carousel
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

        var owl = $('.more-product');

        // ▶️ Tombol Next
        $('.customNextBtnMore').click(function() {
            owl.trigger('next.owl.carousel');
        });

        // ◀️ Tombol Previous
        $('.customPrevBtnMore').click(function() {
            owl.trigger('prev.owl.carousel');
        });
    });
</script>

<?php include ROOTPATH . "/layouts/footer.php"; ?>