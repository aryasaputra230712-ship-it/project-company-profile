<?php
// Perbaikan path untuk hosting
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT']);
define('BASE_URL', '');

$page_css = "index";

// Memastikan file config terpanggil dengan benar
include_once ROOTPATH . "/config/config.php";
include_once ROOTPATH . "/layouts/header.php";

$query = mysqli_query($conn, "SELECT * FROM slide_utama WHERE status = 'active'");
$slides = [];
while ($row = mysqli_fetch_assoc($query)) {
    $slides[] = $row;
}

include_once ROOTPATH . "modules/main.php";

?>
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
                <img src="<?= BASE_URL ?>/assets/imgs/<?= $slides[0]["logo"] ?>" alt="Founder Aurelis">
            </div>
        </div>
    </div>
</section>
<h1>testing 100</h1>
<h1>TESTTTTTTTT</h1>
<script>
    window.dataSlides = <?php echo json_encode($slides); ?>;
</script>
<script src="../../assets/js/main.js"></script>

<?php include_once ROOTPATH . "/layouts/footer.php"; ?>