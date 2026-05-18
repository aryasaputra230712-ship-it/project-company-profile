<?php

define('ROOTPATH', __DIR__);

include_once ROOTPATH . "/config/config.php";

$page_css = "main"; // jika styling khusus main.php di main.css

$query = mysqli_query($conn, "SELECT * FROM slide_utama WHERE status = 'active'");
$slides = [];
while ($row = mysqli_fetch_assoc($query)) {
    $slides[] = $row;
}

include_once ROOTPATH . "/modules/main.php";
include_once ROOTPATH . "/layouts/header.php";
?>

<script>
    window.dataSlides = <?php echo json_encode($slides); ?>;
</script>
<script src="<?php echo isset($base_url) ? $base_url : ''; ?>/assets/js/main.js"></script>

<?php include_once ROOTPATH . "/layouts/footer.php"; ?>