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
$page_css = "contact";

// 1. LOGIK DATABASE
// $query = mysqli_query($conn, "SELECT * FROM slide_utama WHERE status = 'active'");
// $slides = [];
// while ($row = mysqli_fetch_assoc($query)) {
//     $slides[] = $row;
// }

// if (empty($slides)) {
//     die("Error: Tidak ada data slide.");
// }

// 2. HEADER (Tetap di-include agar navigasi konsisten)


include ROOTPATH . "/layouts/header.php";
?>

<section style="height: 100vh;">
    <h1>fdsfdsfdsfsd</h1>
</section>

<?php include ROOTPATH . "/layouts/footer.php" ?>