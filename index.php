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
$page_css = "index";

// 1. LOGIK DATABASE
$query = mysqli_query($conn, "SELECT * FROM slide_utama WHERE status = 'active'");
$slides = [];
while ($row = mysqli_fetch_assoc($query)) {
    $slides[] = $row;
}

if (empty($slides)) {
    die("Error: Tidak ada data slide.");
}

// 2. HEADER (Tetap di-include agar navigasi konsisten)


include ROOTPATH . "/layouts/header.php";
?>

<main>
    <div class="container-main">
        <div class="main-intro">
            <div class="video-bg-container">
                <video class="video-bg" muted autoplay loop playsinline>
                    <source src="<?= BASE_URL ?>/assets/videos/<?= htmlspecialchars($slides[0]['video_file']); ?>" type="video/mp4">
                </video>
            </div>

            <div class="intro-content">
                <img src="<?= BASE_URL ?>/assets/imgs/logo.png" alt="Logo">
                <h1 id="dynamic-title"><?= htmlspecialchars($slides[0]['judul']); ?></h1>
                <p id="dynamic-subtitle"><?= htmlspecialchars($slides[0]['subjudul']); ?></p>
            </div>
        </div>
    </div>
</main>


<section class="about-section">
    <div class="container-about">
        <div class="about-grid">
            <div class="about-text">
                <h3>SINCE 2006</h3>
                <h2>PERJALANAN & LAHIRNYA AURELIS</h2>
                <p>
                    Aurelis bukan sekadar brand perhiasan. Ini adalah simbol ketangguhan dan keindahan yang lahir dari pengalaman panjang. Setiap lekukan desain kami membawa cerita tentang kekuatan hati.
                </p>
                <div class="btn-primary">
                    <a href="#">Pelajari Selengkapnya</a>
                </div>
            </div>
            <div class="about-img">
                <img src="<?= BASE_URL ?>/assets/imgs/about.png" alt="About">
            </div>
        </div>
    </div>
</section>

<section class="founder-section">
    <div class="container-founder">
        <div class="founder-grid">

            <div class="founder-img">
                <img src="<?= BASE_URL ?>/assets/imgs/founder.png" alt="Founder Aurelis">
            </div>
            <div class="founder-text">
                <h1>THE FOUNDER</h1>
                <div class="founder-p">
                    <p>Lahir di Banyuwangi dari keluarga petani sederhana, keterbatasan justru menjadi fondasi ketangguhan Astutik.</p>
                    <p>Belajar langsung dari mentor internasional asal Jepang, Taku Kitayama, membentuk disiplin dan standar kualitas global pada setiap karyanya.</p>
                    <p>Kini berdomisili di Bali, beliau mengembangkan Aurelis sebagai simbol perhiasan yang merepresentasikan karakter kuat seorang perempuan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    window.dataSlides = <?php echo json_encode($slides); ?>;
</script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>

<?php include_once ROOTPATH . "/layouts/footer.php"; ?>