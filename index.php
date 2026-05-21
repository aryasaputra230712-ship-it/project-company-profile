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

// LOGIK DATABASE
$query = mysqli_query($conn, "SELECT * FROM slide_utama WHERE status = 'active'");
$slides = [];
while ($row = mysqli_fetch_assoc($query)) {
    $slides[] = $row;
}

if (empty($slides)) {
    die("Error: Tidak ada data slide.");
}

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
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap');

    .font-serif-aurelis {
        font-family: 'Playfair Display', serif;
    }
</style>

<main class="relative w-full min-h-screen overflow-hidden">
    <div class="absolute inset-0 w-full h-full overflow-hidden">
        <video class="w-full h-full object-cover brightness-90" muted autoplay loop playsinline>
            <source src="https://aurelis.yuda-aditya.cloud/jew.mp4" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-gradient-to-r from-[rgba(4,7,22,0.35)] to-[rgba(2,3,13,0.85)] pointer-events-none"></div>
    </div>

    <div class="relative z-10 flex flex-col items-center justify-center text-center px-6 min-h-screen gap-6">
        <img src="<?= BASE_URL ?>/assets/imgs/logo.png"
            class="wow animate__animated animate__bounceIn w-[100px] md:w-[130px] drop-shadow-2xl">

        <h1 id="dynamic-title"
            class="animate__animated animate__fadeInUp text-white text-[2rem] md:text-[3.5rem] lg:text-[4.2rem] font-bold leading-[1.05] tracking-tight max-w-[900px] font-serif-aurelis">
            <?= htmlspecialchars($slides[0]['judul']); ?>
        </h1>

        <p id="dynamic-subtitle"
            class="animate__animated animate__fadeInUp text-[#e6e9ff] text-[1rem] md:text-[1.2rem] max-w-[720px] opacity-90">
            <?= htmlspecialchars($slides[0]['subjudul']); ?>
        </p>
    </div>
</main>

<section class="py-24 px-6 md:px-10 bg-aurelis-navy text-[#f5f7f7]">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="text-center md:text-left order-2 md:order-1">
            <h3 class="text-aurelis-gold tracking-[0.2em] mb-4 text-sm font-semibold">SINCE 2006</h3>
            <h2 class="text-3xl md:text-5xl font-serif-aurelis leading-tight mb-6">PERJALANAN & LAHIRNYA AURELIS</h2>
            <p class="text-gray-300 leading-relaxed mb-8 max-w-xl mx-auto md:mx-0">
                Aurelis bukan sekadar brand perhiasan. Ini adalah simbol ketangguhan dan keindahan yang lahir dari pengalaman panjang. Setiap lekukan desain kami membawa cerita tentang kekuatan hati.
            </p>
            <a href="#" class="inline-block bg-aurelis-gold text-aurelis-dark px-8 py-3 rounded-full font-bold uppercase text-xs tracking-widest hover:bg-[#ffdb99] transition-all transform hover:-translate-y-1 shadow-lg hover:shadow-aurelis-gold/40">
                Pelajari Selengkapnya
            </a>
        </div>
        <div class="order-1 md:order-2">
            <img src="<?= BASE_URL ?>/assets/imgs/about.png" alt="About" class="w-full rounded-2xl shadow-2xl">
        </div>
    </div>
</section>

<section class="flex flex-col md:flex-row bg-aurelis-blue min-h-[600px] overflow-hidden shadow-2xl">
    <div class="w-full md:w-2/5 group overflow-hidden">
        <img src="<?= BASE_URL ?>/assets/imgs/founder.png" alt="Founder" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
    </div>
    <div class="w-full md:w-3/5 p-12 md:p-24 flex flex-col justify-center items-center text-center">
        <h2 class="text-white text-3xl md:text-5xl font-serif-aurelis tracking-widest uppercase mb-4">THE FOUNDER</h2>
        <div class="w-20 h-1 bg-aurelis-gold mb-10"></div>
        <div class="space-y-6 text-[#e6e9ff] max-w-xl opacity-90 leading-relaxed">
            <p>Lahir di Banyuwangi dari keluarga petani sederhana, keterbatasan justru menjadi fondasi ketangguhan Astutik.</p>
            <p>Belajar langsung dari mentor internasional asal Jepang, Taku Kitayama, membentuk disiplin dan standar kualitas global pada setiap karyanya.</p>
            <p>Kini berdomisili di Bali, beliau mengembangkan Aurelis sebagai simbol perhiasan yang merepresentasikan karakter kuat seorang perempuan.</p>
        </div>
    </div>
</section>

<section class="bg-aurelis-dark py-20 px-6 md:px-10 border-t border-white/5">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-20">
        <div class="flex-1 relative">
            <img src="<?= BASE_URL ?>/assets/imgs/history.png" alt="History" class="w-full rounded-lg shadow-[0_30px_60px_rgba(0,0,0,0.4)] relative z-10">
            <div class="absolute -top-4 -left-4 w-4/5 h-[90%] border border-white/10 hidden md:block"></div>
        </div>
        <div class="flex-[1.2] text-center lg:text-left">
            <h3 class="text-[#bfa37e] tracking-[0.4em] text-sm mb-6">SINCE 2006</h3>
            <h2 class="text-white text-3xl md:text-5xl font-serif-aurelis leading-tight mb-10 uppercase tracking-wider">PERJALANAN ASTUTIK & LAHIRNYA AURELIS</h2>
            <div class="space-y-6 text-[#e6e9ff] opacity-85 text-[1rem] leading-loose tracking-wide">
                <p>TIDAK SEMUA BRAND BESAR LAHIR DARI KEMEWAHAN. SEBAGIAN JUSTRU TUMBUH DARI KETEKUNAN, JATUH BANGUN, DAN MIMPI SEORANG PEREMPUAN YANG TIDAK PERNAH MENYERAH.</p>
                <p>ASTUTIK PERTAMA KALI MENGENAL DUNIA PERHIASAN PADA TAHUN 2006. BERAWAL DARI RASA SUKA, TUMBUHLAH PROSES BELAJAR MANDIRI; MEMAHAMI KARAKTER BATU HINGGA MENGENAL SELERA PASAR DUNIA.</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#fdfbf7] py-24 px-10 text-center">
    <h1 class="font-serif-aurelis italic text-3xl md:text-5xl uppercase tracking-widest text-aurelis-dark mb-20">"Lebih dari Sekadar Perhiasan"</h1>
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-16">
        <?php
        $motto = [
            ['01.', 'Perjalanan Hidup', 'Mewakili setiap langkah dan cerita yang membentuk pribadi perempuan.'],
            ['02.', 'Kekuatan', 'Melambangkan keberanian untuk bangkit dan berdiri lebih tegak.'],
            ['03.', 'Makna', 'Menyimpan filosofi mendalam di balik setiap lengkungan desain.'],
            ['04.', 'Makna', 'Menyimpan filosofi mendalam di balik setiap lengkungan desain.']
        ];
        foreach ($motto as $item): ?>
            <div class="group hover:-translate-y-3 transition-all duration-300">
                <h3 class="text-aurelis-gold font-serif-aurelis text-4xl mb-4"><?= $item[0] ?></h3>
                <h4 class="text-aurelis-dark font-bold tracking-[0.2em] uppercase mb-6"><?= $item[1] ?></h4>
                <p class="text-gray-600 leading-relaxed max-w-[300px] mx-auto"><?= $item[2] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="bg-aurelis-navy py-24 px-10 text-center">
    <div class="max-w-4xl mx-auto flex flex-col items-center gap-8">

        <div class="text-aurelis-gold text-5xl md:text-6xl font-serif-aurelis opacity-80">
            <i class="fa-solid fa-quote-left"></i>
        </div>

        <blockquote class="relative">
            <h2 class="font-serif-aurelis italic text-2xl md:text-4xl uppercase tracking-[0.05em] leading-relaxed text-white">
                "Aurelis lahir untuk perempuan yang berani bermimpi besar, yang percaya bahwa keindahan sejati datang dari kekuatan hati."
            </h2>
        </blockquote>

        <div class="w-32 h-[1px] bg-aurelis-gold opacity-60"></div>

        <p class="uppercase tracking-[0.3em] text-[10px] md:text-xs text-gray-500 font-medium">
            Aurelis International Vision — 2025
        </p>

    </div>
</section>

<script>
    window.dataSlides = <?php echo json_encode($slides); ?>;
</script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>

<?php include_once ROOTPATH . "/layouts/footer.php"; ?>