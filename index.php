<?php
define('ROOTPATH', realpath(__DIR__));
define('BASE_URL', '');

include_once ROOTPATH . "/config/config.php";
include_once ROOTPATH . "/layouts/header.php";

$query = mysqli_query($conn, "SELECT * FROM slide_utama WHERE status = 'active'");
$slides = [];
while ($row = mysqli_fetch_assoc($query)) {
    $slides[] = $row;
}
?>


<main>
    <div class="container-main">
        <div class="main-intro">
            <video class="video-bg" muted autoplay loop playsinline>
                <source src="<?= BASE_URL ?>/assets/videos/<?= $slides[0]['video_file'] ?? 'default.mp4'; ?>" type="video/mp4">
            </video>

            <div class="intro-content">
                <img src="<?= BASE_URL ?>/assets/imgs/logo.png" alt="Logo">
                <h1 id="dynamic-title"><?= $slides[0]['judul']; ?></h1>
                <p id="dynamic-subtitle"><?= $slides[0]['subjudul']; ?></p>
            </div>
        </div>
    </div>
</main>

<section class="about-section">
    <div class="container">
        <div class="about-grid">
            <div class="about-text">
                <h3>SINCE 2006</h3>
                <h2>PERJALANAN & LAHIRNYA AURELIS</h2>
                <p>
                    Aurelis bukan sekadar brand perhiasan. Ini adalah simbol ketangguhan dan keindahan yang lahir dari pengalaman panjang. Setiap lekukan desain kami membawa cerita tentang kekuatan hati.
                </p>
                <a href="#" class="btn-primary">Pelajari Selengkapnya</a>
            </div>
            <div class="about-img">
                <img src="<?= BASE_URL ?>/assets/imgs/about-founder.jpg" alt="Founder Aurelis">
            </div>
        </div>
    </div>
</section>
<h1>testing 100</h1>
<script>
    window.dataSlides = <?php echo json_encode($slides); ?>;
</script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>

<?php include_once ROOTPATH . "/layouts/footer.php"; ?>