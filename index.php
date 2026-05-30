<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', __DIR__);
}

// Base URL Otomatis
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_url = $protocol . "://" . $host . ($script_name == '/' ? '' : $script_name);
define('BASE_URL', $base_url);

include_once ROOTPATH . "/config/config.php";

// ==========================================
// 1. SYSTEM MULTI-BAHASA (DETEKSI & SESSION)
// ==========================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$bahasa_aktif = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id';

// ==========================================
// 2. AMBIL DATA DARI WADAH BARU (HOMEPAGE)
// ==========================================
$query_home = mysqli_query($conn, "SELECT * FROM konten_homepage WHERE id = 1 LIMIT 1");
$home = mysqli_fetch_assoc($query_home);

$hero_judul     = ($bahasa_aktif == 'en' && !empty($home['hero_judul_en'])) ? $home['hero_judul_en'] : $home['hero_judul_id'];
$hero_sub       = ($bahasa_aktif == 'en' && !empty($home['hero_sub_en'])) ? $home['hero_sub_en'] : $home['hero_sub_id'];
$sejarah_judul  = ($bahasa_aktif == 'en' && !empty($home['sejarah_judul_en'])) ? $home['sejarah_judul_en'] : $home['sejarah_judul_id'];
$sejarah_konten = ($bahasa_aktif == 'en' && !empty($home['sejarah_konten_en'])) ? $home['sejarah_konten_en'] : $home['sejarah_konten_id'];
$founder_nama   = !empty($home['founder_nama']) ? $home['founder_nama'] : 'THE FOUNDER';
$founder_bio    = ($bahasa_aktif == 'en' && !empty($home['founder_bio_en'])) ? $home['founder_bio_en'] : $home['founder_bio_id'];
$kutipan        = ($bahasa_aktif == 'en' && !empty($home['kutipan_en'])) ? $home['kutipan_en'] : $home['kutipan_id'];

// ==========================================
// 3. AMBIL DATA DARI BERBAGAI TABEL
// ==========================================
$sql_video = "SELECT v.jalur_video FROM slide_utama s INNER JOIN video_utama v ON s.video_id = v.id WHERE v.status = 'aktif' LIMIT 1";
$res_video = mysqli_query($conn, $sql_video);
$video_old = mysqli_fetch_assoc($res_video);
$video_file = !empty($home['video_url']) ? $home['video_url'] : ($video_old ? $video_old['jalur_video'] : '');

$sql_about = "SELECT * FROM slide_tentang WHERE status = 'aktif' LIMIT 1";
$res_about = mysqli_query($conn, $sql_about);
$about = mysqli_fetch_assoc($res_about);

$sql_founder = "SELECT gambar FROM founder_utama WHERE status = 'aktif' LIMIT 1";
$res_founder = mysqli_query($conn, $sql_founder);
$founder_asset = mysqli_fetch_assoc($res_founder);

$sql_history = "SELECT tagline, gambar, cerita_2 FROM sejarah_utama WHERE status = 'aktif' LIMIT 1";
$res_history = mysqli_query($conn, $sql_history);
$history_asset = mysqli_fetch_assoc($res_history);

$sql_quote = "SELECT sumber FROM kutipan_utama WHERE status = 'aktif' LIMIT 1";
$res_quote = mysqli_query($conn, $sql_quote);
$quote_asset = mysqli_fetch_assoc($res_quote);

$res_motto   = mysqli_query($conn, "SELECT * FROM motto_utama WHERE status = 'aktif' ORDER BY nomor ASC");
$res_produk  = mysqli_query($conn, "SELECT * FROM produk_pilihan WHERE status = 'aktif'");
$res_why     = mysqli_query($conn, "SELECT * FROM keunggulan_utama WHERE status = 'aktif' ORDER BY id ASC");
$res_gallery = mysqli_query($conn, "SELECT * FROM galeri_utama WHERE status = 'aktif' ORDER BY id DESC");

include ROOTPATH . "/layouts/header.php";
?>

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'aurelis-gold': '#f7c66b',
                    'aurelis-dark': '#050816',
                    'aurelis-blue': '#070b1e',
                    'aurelis-navy': '#090a12',
                }
            }
        }
    }
</script>

<main class="relative w-full min-h-screen overflow-hidden">
    <div class="absolute inset-0 w-full h-full overflow-hidden">
        <video
            class="w-full h-full object-cover brightness-90"
            muted autoplay loop playsinline
            preload="metadata"
            fetchpriority="high"
            poster="<?= BASE_URL ?>/assets/imgs/hero-poster.webp">
            <source src="<?= BASE_URL ?>/assets/videos/<?= $video_file ?>" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-gradient-to-r from-[rgba(4,7,22,0.35)] to-[rgba(2,3,13,0.85)] pointer-events-none"></div>
    </div>

    <div class="relative z-10 flex flex-col items-center justify-center text-center px-6 min-h-screen gap-6">
        <img src="<?= BASE_URL ?>/assets/imgs/logo.png"
            fetchpriority="high"
            alt="Aurelis Logo"
            class="wow animate__animated animate__bounceIn w-[100px] md:w-[130px] drop-shadow-2xl">

        <h1 id="dynamic-title"
            class="animate__animated animate__fadeInUp text-white text-[2rem] md:text-[3.5rem] lg:text-[4.2rem] font-bold leading-[1.05] tracking-tight max-w-[900px] font-serif-aurelis">
            <?= htmlspecialchars($hero_judul); ?>
        </h1>

        <p id="dynamic-subtitle"
            class="animate__animated animate__fadeInUp text-[#e6e9ff] text-[1rem] md:text-[1.2rem] max-w-[720px] opacity-90">
            <?= htmlspecialchars($hero_sub); ?>
        </p>
    </div>
</main>

<section class="py-24 px-6 md:px-10 bg-aurelis-navy text-[#f5f7f7]">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="text-center md:text-left order-2 md:order-1">
            <h3 class="text-aurelis-gold tracking-[0.2em] mb-4 text-sm font-semibold">
                <?= htmlspecialchars($about['tagline'] ?? '') ?>
            </h3>
            <h2 class="text-3xl md:text-5xl font-serif-aurelis leading-tight mb-6 uppercase">
                <?= htmlspecialchars($about['judul'] ?? '') ?>
            </h2>
            <p class="text-gray-300 leading-relaxed mb-8 max-w-xl mx-auto md:mx-0">
                <?= nl2br(htmlspecialchars($about['deskripsi'] ?? '')) ?>
            </p>
            <a href="<?= $about['link_tombol'] ?? '#' ?>" class="inline-block bg-aurelis-gold text-aurelis-dark px-8 py-3 rounded-full font-bold uppercase text-xs tracking-widest hover:bg-[#ffdb99] transition-all transform hover:-translate-y-1 shadow-lg">
                <?= htmlspecialchars($about['teks_tombol'] ?? 'Learn More') ?>
            </a>
        </div>
        <div class="order-1 md:order-2">
            <img src="<?= BASE_URL ?>/assets/imgs/<?= $about['gambar'] ?? '' ?>" alt="About Aurelis" loading="lazy" class="w-full rounded-2xl shadow-2xl">
        </div>
    </div>
</section>

<section class="flex flex-col md:flex-row bg-aurelis-blue min-h-[600px] overflow-hidden shadow-2xl">
    <div class="w-full md:w-2/5 group overflow-hidden">
        <img src="<?= BASE_URL ?>/assets/imgs/<?= $founder_asset['gambar'] ?? '' ?>" alt="Founder" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
    </div>
    <div class="w-full md:w-3/5 p-12 md:p-24 flex flex-col justify-center items-center text-center">
        <h2 class="text-white text-3xl md:text-5xl font-serif-aurelis tracking-widest uppercase mb-4">
            <?= htmlspecialchars($founder_nama) ?>
        </h2>
        <div class="w-20 h-1 bg-aurelis-gold mb-10"></div>
        <div class="space-y-6 text-[#e6e9ff] max-w-xl opacity-90 leading-relaxed">
            <?= nl2br(htmlspecialchars($founder_bio)) ?>
        </div>
    </div>
</section>

<section class="bg-aurelis-dark py-20 px-6 md:px-10 border-t border-white/5">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-20">
        <div class="flex-1 relative">
            <img src="<?= BASE_URL ?>/assets/imgs/<?= $history_asset['gambar'] ?? '' ?>" alt="History" loading="lazy" class="w-full rounded-lg relative z-10">
            <div class="absolute -top-4 -left-4 w-4/5 h-[90%] border border-white/10 hidden md:block"></div>
        </div>
        <div class="flex-[1.2] text-center lg:text-left">
            <h3 class="text-[#bfa37e] tracking-[0.4em] text-sm mb-6"><?= htmlspecialchars($history_asset['tagline'] ?? '') ?></h3>
            <h2 class="text-white text-3xl md:text-5xl font-serif-aurelis leading-tight mb-10 uppercase tracking-wider">
                <?= htmlspecialchars($sejarah_judul) ?>
            </h2>
            <div class="space-y-6 text-[#e6e9ff] opacity-85 text-[1rem] leading-loose tracking-wide">
                <p><?= nl2br(htmlspecialchars($sejarah_konten)) ?></p>
                <?php if (!empty($history_asset['cerita_2'])): ?>
                    <p><?= nl2br(htmlspecialchars($history_asset['cerita_2'])) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#fdfbf7] py-24 px-10 text-center">
    <h1 class="font-serif-aurelis italic text-3xl md:text-5xl uppercase tracking-widest text-aurelis-dark mb-20">
        "Lebih dari Sekadar Perhiasan"
    </h1>
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-16">
        <?php while ($row_motto = mysqli_fetch_assoc($res_motto)): ?>
            <div class="group hover:-translate-y-3 transition-all duration-300">
                <h3 class="text-aurelis-gold font-serif-aurelis text-4xl mb-4">
                    <?= htmlspecialchars($row_motto['nomor']) ?>
                </h3>
                <h4 class="text-aurelis-dark font-bold tracking-[0.2em] uppercase mb-6">
                    <?= htmlspecialchars($row_motto['judul']) ?>
                </h4>
                <p class="text-gray-600 leading-relaxed max-w-[300px] mx-auto">
                    <?= htmlspecialchars($row_motto['deskripsi']) ?>
                </p>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<section class="bg-aurelis-navy py-24 px-10 text-center">
    <div class="max-w-4xl mx-auto flex flex-col items-center gap-8">
        <div class="text-aurelis-gold text-5xl md:text-6xl font-serif-aurelis opacity-80">
            <i class="fa-solid fa-quote-left"></i>
        </div>
        <blockquote class="relative">
            <h2 class="font-serif-aurelis italic text-2xl md:text-4xl uppercase tracking-[0.05em] leading-relaxed text-white">
                "<?= htmlspecialchars($kutipan) ?>"
            </h2>
        </blockquote>
        <div class="w-32 h-[1px] bg-aurelis-gold opacity-60"></div>
        <p class="uppercase tracking-[0.3em] text-[10px] md:text-xs text-gray-500 font-medium">
            <?= htmlspecialchars($quote_asset['sumber'] ?? '') ?>
        </p>
    </div>
</section>

<section class="bg-[#fdfbf7] py-24 px-6 md:px-10">
    <div class="text-center mb-16">
        <h2 class="font-serif-aurelis italic text-gray-500 uppercase tracking-[0.4em] text-sm md:text-base">
            Handpicked Masterpieces
        </h2>
    </div>
    <div class="max-w-7xl mx-auto grid grid-cols-2 lg:grid-cols-3 gap-8 md:gap-16">
        <?php while ($row_produk = mysqli_fetch_assoc($res_produk)): ?>
            <div class="group cursor-pointer">
                <div class="overflow-hidden mb-6 aspect-[3/4]">
                    <img src="<?= BASE_URL ?>/assets/imgs/<?= $row_produk['gambar'] ?>"
                        alt="<?= htmlspecialchars($row_produk['nama_produk']) ?>"
                        loading="lazy"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                </div>
                <h3 class="text-center font-serif-aurelis uppercase tracking-[0.2em] text-aurelis-dark text-lg">
                    <?= htmlspecialchars($row_produk['nama_produk']) ?>
                </h3>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<section class="bg-aurelis-dark py-24 px-10 text-center">
    <h2 class="font-serif-aurelis text-white text-3xl md:text-5xl uppercase tracking-[0.6em] mb-20">
        WHY AURELIS?
    </h2>
    <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-12 md:gap-8">
        <?php while ($row_why = mysqli_fetch_assoc($res_why)): ?>
            <div class="flex flex-col items-center group">
                <div class="text-aurelis-gold text-3xl mb-8 transition-transform duration-500 group-hover:scale-125">
                    <i class="fa-solid <?= htmlspecialchars($row_why['ikon']) ?>"></i>
                </div>
                <h4 class="text-white font-bold tracking-[0.3em] uppercase text-xs mb-4">
                    <?= htmlspecialchars($row_why['judul']) ?>
                </h4>
                <p class="text-gray-400 text-[10px] md:text-xs leading-relaxed italic max-w-[150px]">
                    <?= htmlspecialchars($row_why['deskripsi']) ?>
                </p>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<section class="bg-[#fdfbf7] py-24 px-6 md:px-10 border-t border-gray-100">
    <div class="text-center mb-16">
        <h2 class="font-serif-aurelis text-3xl md:text-4xl uppercase tracking-[0.5em] text-aurelis-dark">
            Gallery
        </h2>
    </div>
    <div class="max-w-7xl mx-auto grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <?php
        if (mysqli_num_rows($res_gallery) > 0):
            while ($row_gal = mysqli_fetch_assoc($res_gallery)):
        ?>
                <div class="group relative overflow-hidden aspect-square bg-gray-200 rounded-lg">
                    <img src="<?= BASE_URL ?>/assets/imgs/<?= $row_gal['gambar'] ?>"
                        alt="<?= htmlspecialchars($row_gal['judul']) ?>"
                        loading="lazy"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <p class="text-white text-[10px] tracking-widest uppercase"><?= htmlspecialchars($row_gal['judul']) ?></p>
                    </div>
                </div>
        <?php
            endwhile;
        else:
            echo "<p class='col-span-full text-center text-gray-400'>Belum ada foto di galeri.</p>";
        endif;
        ?>
    </div>
</section>

<script>
    window.dataSlides = [<?php echo json_encode($home); ?>];
</script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>

<?php include_once ROOTPATH . "/layouts/footer.php"; ?>