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

// ==========================================
// 3. LOGIKA FITUR HALAMAN (PAGINATION USER)
// ==========================================
$limit = 12; // Menampilkan 12 produk per halaman agar simetris (grid 4 kolom)
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$start = ($page - 1) * $limit;

// Ambil jumlah total seluruh produk perhiasan yang aktif
$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM galeri_utama WHERE status = 'aktif'");
$total_data  = mysqli_fetch_assoc($total_query)['total'] ?? 0;
$total_pages = ceil($total_data / $limit);

// Tarik data produk terbatas sesuai halaman aktif (LIMIT & OFFSET)
$sql_gallery = "SELECT * FROM galeri_utama WHERE status = 'aktif' ORDER BY id DESC LIMIT $start, $limit";
$res_gallery = mysqli_query($conn, $sql_gallery);

// Tarik data konfigurasi banner hero galeri dari tabel header_galeri
$query_header = mysqli_query($conn, "SELECT * FROM header_galeri LIMIT 1");
$header = mysqli_fetch_assoc($query_header);

// Tarik nomor WhatsApp dari tabel pengaturan
$query_nomor = mysqli_query($conn, "SELECT whatsapp FROM pengaturan LIMIT 1");
$no_wa = mysqli_fetch_assoc($query_nomor);

include ROOTPATH . "/layouts/header.php";

// Set isi teks banner hero berdasarkan bahasa pilihan user
$hero_title = ($lang == 'en' && !empty($header['judul_en'])) ? $header['judul_en'] : $header['judul_id'];
$hero_sub   = ($lang == 'en' && !empty($header['subjudul_en'])) ? $header['subjudul_en'] : $header['subjudul_id'];
?>

<section class="gallery-hero relative">
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover brightness-50"
            src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($header['gambar'] ?? 'gallery-hero.webp') ?>"
            alt="bg-gallery">
    </div>
    <div class="relative flex flex-col items-center justify-center gap-4 min-h-[50vh] uppercase text-white">
        <h1 class="tracking-[10px] text-4xl text-center md:text-5xl md:tracking-[20px] font-serif">
            <?= htmlspecialchars($hero_title) ?>
        </h1>
        <p class="tracking-[5px] font-light text-xs text-orange-200">
            <?= htmlspecialchars($hero_sub) ?>
        </p>
    </div>
</section>

<section class="mt-12 mb-20">

    <div id="tab-container" class="flex justify-center flex-wrap gap-6 md:gap-10 mb-16 text-xs md:text-sm uppercase font-bold tracking-widest overflow-x-auto no-scrollbar">
        <button class="filter-btn border-b-2 border-orange-500 text-white pb-2 whitespace-nowrap" data-filter="all">All</button>

        <?php
        // Mengambil daftar nama kelompok kategori perhiasan
        $q_kategori = mysqli_query($conn, "SELECT * FROM kategori_galeri ORDER BY nama_kategori ASC");
        while ($cat = mysqli_fetch_assoc($q_kategori)):
        ?>
            <button class="filter-btn text-gray-400 pb-2 hover:text-white transition whitespace-nowrap"
                data-filter="<?= htmlspecialchars($cat['slug']) ?>">
                <?= htmlspecialchars($cat['nama_kategori']) ?>
            </button>
        <?php endwhile; ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 mx-auto px-6 max-w-7xl">

        <?php
        if (mysqli_num_rows($res_gallery) > 0):
            while ($row = mysqli_fetch_assoc($res_gallery)):
                // Set isi nama produk berdasarkan bahasa pilihan user
                $product_name = ($lang == 'en' && !empty($row['nama_produk_en'])) ? $row['nama_produk_en'] : $row['nama_produk'];

                // Kalimat pemesanan WhatsApp otomatis (Bilingual template)
                $wa_text = ($lang == 'en')
                    ? "Hello Aurelis Jewelry, I am interested in purchasing the product: " . $product_name . "."
                    : "Halo Aurelis Jewelry, saya tertarik dengan produk " . $product_name . ".";
        ?>
                <div class="item <?= strtolower($row['kategori']) ?> overflow-hidden w-full group">
                    <div class="relative overflow-hidden h-[300px]">
                        <a href="detail-produk.php?id=<?= $row['id'] ?>">
                            <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($row['gambar']) ?>"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out"
                                alt="<?= htmlspecialchars($product_name) ?>">
                        </a>
                    </div>

                    <div class="mt-5 text-center px-2">
                        <h3 class="text-white text-sm md:text-base font-bold tracking-widest uppercase mb-1 line-clamp-1">
                            <?= htmlspecialchars($product_name) ?>
                        </h3>
                        <p class="text-orange-400 text-sm font-mono mb-4">
                            Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                        </p>

                        <a class="flex justify-center items-center bg-orange-600 p-2 rounded-sm hover:bg-orange-700 text-white font-bold tracking-wide text-xs transition-all duration-300 m-auto w-40"
                            href="https://wa.me/<?= htmlspecialchars($no_wa['whatsapp'] ?? '') ?>?text=<?= urlencode($wa_text) ?>">
                            <?= ($lang == 'en') ? 'Order Now' : 'Pesan Sekarang' ?>
                        </a>
                    </div>
                </div>
        <?php
            endwhile;
        else:
            echo '<div class="col-span-full text-center text-gray-500 py-20 italic tracking-widest">';
            echo ($lang == 'en') ? 'No jewelry collections found.' : 'Belum ada koleksi perhiasan untuk ditampilkan.';
            echo '</div>';
        endif;
        ?>

    </div>

    <?php if ($total_pages > 1): ?>
        <div class="mt-16 flex justify-center items-center gap-2 font-mono text-xs">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>"
                    class="w-9 h-9 flex items-center justify-center rounded-md transition font-bold border <?= $page == $i ? 'bg-orange-600 text-white border-orange-600 shadow-md shadow-orange-600/20' : 'bg-transparent border-white/10 text-gray-400 hover:text-white hover:border-white/30' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

</section>

<script src="<?= BASE_URL ?>/assets/js/gallery.js"></script>

<?php include ROOTPATH . "/layouts/footer.php" ?>