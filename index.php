<?php

define('ROOTPATH', __DIR__);

include_once ROOTPATH . "/config/config.php";

$page_css = "main"; // jika styling khusus main.php di main.css

$query = mysqli_query($conn, "SELECT * FROM slide_utama WHERE status = 'active'");
$slides = [];
while ($row = mysqli_fetch_assoc($query)) {
    $slides[] = $row;
}

include ROOTPATH . "/layouts/header.php";


?>

<main>
    <div class="container-main">
        <div class="main-intro">
            <div class="video-bg-container">
                <video class="video-bg" muted autoplay loop playsinline>
                    <source src="<?= BASE_URL ?>/assets/videos/<?= $slides[0]['video_file']; ?>" type="video/mp4">
                </video>
            </div>

            <div class="intro-content">
                <img src="<?= BASE_URL ?>/assets/imgs/logo.png" alt="Logo">
                <h1 id="dynamic-title"><?= $slides[0]['judul']; ?></h1>
                <p id="dynamic-subtitle"><?= $slides[0]['subjudul']; ?></p>
            </div>
        </div>
    </div>
</main>

<script>
    window.dataSlides = <?php echo json_encode($slides); ?>;
</script>
<script src="<?php echo isset($base_url) ? $base_url : ''; ?>/assets/js/main.js"></script>

<?php include_once ROOTPATH . "/layouts/footer.php"; ?>