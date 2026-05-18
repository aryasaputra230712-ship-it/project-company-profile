<?php
define('BASE_URL', '');
define('ROOTPATH', __DIR__);

include_once ROOTPATH . "/config/config.php";

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

<script>
    window.dataSlides = <?php echo json_encode($slides); ?>;
</script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>

<?php include_once ROOTPATH . "/layouts/footer.php"; ?>