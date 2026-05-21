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

<style>
    .contact-intro {
        position: relative;        
    }

    .bg-contact{
        position: absolute;
        inset: 0;
        
    }

    .bg-contact img{
        position: relative;
        width: 100%;
        height: 52vh;
        object-fit: cover;
        filter: brightness(0.3);
        -webkit-filter: brightness(0.3);
    }

    .bg-contact::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(rgba(138, 157, 255, 0.2) 0%, rgba(137, 147, 255, 0.2) 100%);
        pointer-events: none;
    }

    .intro-content{
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 52vh;
        text-transform: uppercase;
        margin-top: 35px;
    }

    .intro-content h1{
        letter-spacing: 20px; 
        font-size: 50px;
    }

    .intro-content p{
        font-weight: 300; 
        letter-spacing: 2px;
    }
</style>

<section class="contact-hero">
    <div class="contact-intro">
        <div class="bg-contact">
            <img src="<?= BASE_URL ?>/assets/imgs/contact-hero.jpeg" alt="bg-contact">
        </div>

        <div class="intro-content">
            <h1>Contact</h1>
            <p>We'd Love to Hear from You</p>
        </div>
    </div>
</section>

<?php include ROOTPATH . "/layouts/footer.php" ?>