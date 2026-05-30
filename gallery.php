<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', __DIR__);
}
include_once ROOTPATH . "/config/config.php";

// 1. Logika Base URL (Supaya gambar & asset tidak pecah)
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_url = $protocol . "://" . $host . ($script_name == '/' ? '' : $script_name);

if (!defined('BASE_URL')) define('BASE_URL', $base_url);

// 2. Ambil data produk dari database
$sql_gallery = "SELECT * FROM galeri_utama WHERE status = 'aktif' ORDER BY id DESC";
$res_gallery = mysqli_query($conn, $sql_gallery);

$query_header = mysqli_query($conn, "SELECT * FROM header_galeri LIMIT 1");
$header = mysqli_fetch_assoc($query_header);

$query_nomor = mysqli_query($conn, "SELECT whatsapp FROM pengaturan LIMIT 1");
$no_wa = mysqli_fetch_assoc($query_nomor);

include ROOTPATH . "/layouts/header.php";
?>

<section class="gallery-hero relative">
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover brightness-50"
            src="<?= BASE_URL ?>/assets/imgs/<?= $header['gambar'] ?>"
            alt="bg-gallery">
    </div>
    <div class="relative flex flex-col items-center justify-center gap-4 min-h-[50vh] uppercase text-white">
        <h1 class="tracking-[10px] text-4xl text-center md:text-5xl md:tracking-[20px] font-serif">
            <?= $header['judul'] ?>
        </h1>
        <p class="tracking-[2px] text-orange-200 font-light text-xs md:tracking-[5px]">
            <?= $header['subjudul'] ?>
        </p>
    </div>
</section>

<section class="mt-12 mb-20">

    <div id="tab-container" class="flex justify-center flex-wrap gap-6 md:gap-10 mb-16 text-xs md:text-sm uppercase font-bold tracking-widest overflow-x-auto no-scrollbar">
        <button class="filter-btn border-b-2 border-orange-500 text-white pb-2 whitespace-nowrap" data-filter="all">All</button>

        <?php
        // Ambil daftar kategori dari tabel baru
        $q_kategori = mysqli_query($conn, "SELECT * FROM kategori_galeri ORDER BY nama_kategori ASC");
        while ($cat = mysqli_fetch_assoc($q_kategori)):
        ?>
            <button class="filter-btn text-gray-400 pb-2 hover:text-white transition whitespace-nowrap"
                data-filter="<?= $cat['slug'] ?>">
                <?= $cat['nama_kategori'] ?>
            </button>
        <?php endwhile; ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 mx-auto px-6 max-w-7xl">

        <?php
        if (mysqli_num_rows($res_gallery) > 0):
            while ($row = mysqli_fetch_assoc($res_gallery)):
        ?>
                <div class="item <?= strtolower($row['kategori']) ?> overflow-hidden w-full group">
                    <div class="relative overflow-hidden h-[300px]">
                        <a href="<?= BASE_URL ?>/assets/imgs/<?= $row['gambar'] ?>" target="_blank">
                            <img src="<?= BASE_URL ?>/assets/imgs/<?= $row['gambar'] ?>"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out"
                                alt="<?= $row['nama_produk'] ?>">
                        </a>
                    </div>

                    <div class="mt-5 text-center px-2">
                        <h3 class="text-white text-sm md:text-base font-bold tracking-widest uppercase mb-1">
                            <?= $row['nama_produk'] ?>
                        </h3>
                        <p class="text-orange-400 text-sm font-mono mb-4">
                            Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                        </p>

                        <a class="flex justify-center items-center bg-orange-600 p-2 rounded-sm hover:bg-orange-700 transition-all duration-300 m-auto w-40"
                            href="https://wa.me/<?= $no_wa ?>?text=Halo%20Aurelis%20Jewelry,%20saya%20tertarik%20dengan%20produk%20<?= urlencode($row['nama_produk']) ?>.">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
        <?php
            endwhile;
        else:
            echo '<div class="col-span-full text-center text-gray-500 py-20 italic tracking-widest">Belum ada koleksi perhiasan untuk ditampilkan.</div>';
        endif;
        ?>

    </div>
</section>

<script src="<?= BASE_URL ?>/assets/js/gallery.js"></script>

<?php include ROOTPATH . "/layouts/footer.php" ?>