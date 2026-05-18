<?php
// Perbaikan path untuk hosting
define('ROOTPATH', __DIR__);
define('BASE_URL', '');

$page_css = "index";

// Memastikan file config terpanggil dengan benar
include_once ROOTPATH . "/config/config.php";


$query = mysqli_query($conn, "SELECT * FROM slide_utama WHERE status = 'active'");
$slides = [];
while ($row = mysqli_fetch_assoc($query)) {
    $slides[] = $row;
}
include_once ROOTPATH . "/layouts/header.php";
require ROOTPATH . "/modules/main.php";


?>
<script>
    window.dataSlides = <?php echo json_encode($slides); ?>;
</script>
<script src="../../assets/js/main.js"></script>

<?php include_once ROOTPATH . "/layouts/footer.php"; ?>