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
$page_css = "gallery";

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
    .gallery-intro {
        position: relative;
    }

    .bg-gallery{
        position: absolute;
        inset: 0;
        
    }

    .bg-gallery img{
        position: relative;
        width: 100%;
        height: 60vh;
        object-fit: cover;
        filter: brightness(0.4);
        -webkit-filter: brightness(0.4);
    }

    .intro-content{
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 60vh;
        text-transform: uppercase;
    }

    .intro-content h1{
        letter-spacing: 20px; 
        font-size: 50px;
        margin-top: 50px;
    }

    .intro-content p{
        font-weight: 300; 
        letter-spacing: 2px;
    }

    .gallery-collec{
        margin: 50px auto;
        margin-bottom: 75px;
        max-width: 73rem;
    }



    /* =========================
   FILTER MENU
========================= */

.filter-menu{
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-bottom: 120px;
}

.filter-btn{
    background: none;
    border: none;
    letter-spacing: 2px;
    cursor: pointer;
    position: relative;
    color: #fff;
    transition: all .2s;
}

.filter-btn.active::after{
    content: "";
    position: absolute;
    left: 0;
    bottom: -10px;
    width: 100%;
    height: 2px;
    background: orange;
}

.filter-btn:hover{
    color: #c78835;
}

/* =========================
   GALLERY
========================= */

.gallery{
    margin: 0 30px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
}

.item{
    overflow: hidden;
    border-radius: 7px;
    transition: 0.3s;
}

.item img{
    width: 100%;
    height: 381px;
    object-fit: cover;
    display: block;
    transition: 0.3s;
}

.item:hover img{
    transform: scale(1.03);
}

/* =========================
   RESPONSIVE
========================= */

@media(max-width: 1024px){

     .item img{
        height: 100%;
    }

    .gallery{
        grid-template-columns: repeat(3, 1fr);
    }

}

@media(max-width: 768px){

    .item img{
        height: 100%;
    }

    .gallery{
        grid-template-columns: repeat(2, 1fr);
    }

}

@media(max-width: 500px){

    .item img{
        height: 100%;
    }

    .gallery{
        grid-template-columns: repeat(2, 1fr);
    }

}
</style>


<section class="gallery-hero">
    <div class="gallery-intro">
        <div class="bg-gallery">
            <img src="<?= BASE_URL ?>/assets/imgs/gallery-hero.jpg" alt="bg-gallery">
        </div>

        <div class="intro-content">
            <h1>Gallery</h1>
            <p>Timeless Jewelry Collection</p>
        </div>
    </div>
</section>

<section class="gallery-collec">
    
    <!-- FILTER MENU -->
    <div class="filter-menu">

        <button class="filter-btn active" data-filter="all">
            All
        </button>

        <button class="filter-btn" data-filter="ring">
            Rings
        </button>

        <button class="filter-btn" data-filter="necklace">
            Necklace
        </button>

        <button class="filter-btn" data-filter="earring">
            Earrings
        </button>

    </div>

    <!-- GALLERY -->
    <div class="gallery">

        
        <div class="item ring">
            <a href="<?= BASE_URL ?>/assets/imgs/product1.jpeg" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product1.jpeg" alt="product1">
            </a>
        </div>

        <div class="item necklace">
            <a href="<?= BASE_URL ?>/assets/imgs/product2.jpeg" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product2.jpeg" alt="product2">
            </a>
        </div>

        
        <div class="item earring">
            <a href="<?= BASE_URL ?>/assets/imgs/product3.jpeg" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product3.jpeg" alt="product3">
            </a>
        </div>

        <div class="item ring">
            <a href="<?= BASE_URL ?>/assets/imgs/product4.jpeg" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product4.jpeg" alt="product4">
            </a>
        </div>

        
        <div class="item ring">
            <a href="<?= BASE_URL ?>/assets/imgs/product1.jpeg" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product1.jpeg" alt="product1">
            </a>
        </div>

        <div class="item earring">
            <a href="<?= BASE_URL ?>/assets/imgs/product3.jpeg" target="_blank">
                <img src="<?= BASE_URL ?>/assets/imgs/product3.jpeg" alt="product3">
            </a>
        </div>

    </div>

</section>

<script>

/* =========================
   FILTER FUNCTION
========================= */

const buttons = document.querySelectorAll('.filter-btn');
const items = document.querySelectorAll('.item');

buttons.forEach(button => {

    button.addEventListener('click', () => {

        // ACTIVE BUTTON
        buttons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        // FILTER
        const filter = button.dataset.filter;

        items.forEach(item => {

            if(filter === 'all'){
                item.style.display = 'block';
            }
            else{

                if(item.classList.contains(filter)){
                    item.style.display = 'block';
                }
                else{
                    item.style.display = 'none';
                }

            }

        });

    });

});

</script>

<?php include ROOTPATH . "/layouts/footer.php" ?>