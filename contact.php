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

include ROOTPATH . "/layouts/header.php";
?>

<section class="contact-hero">
    <div class="relative">
        <div class="absolute inset-0">
            <img class="relative w-full h-[45vh] object-cover brightness-50" src="<?= BASE_URL ?>/assets/imgs/contact-hero.jpeg" alt="bg-contact">
        </div>

        <div class="relative flex flex-col items-center justify-center gap-5 min-h-[45vh] uppercase">
            <h1 class="tracking-[10px] text-4xl text-center md:text-5xl md:tracking-[15px] lg:text-5xl lg:tracking-[20px]">Contact</h1>
            <p class="tracking-widest text-sm font-light">We'd Love to Hear from You</p>
        </div>
    </div>
</section>


<section>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 text-center max-w-6xl m-auto p-[50px] uppercase gap-10 mb-10">
        <div>
            <span><i class="fa-solid fa-phone mb-3 text-xl"></i></span>
            <p class="mb-3 text-sm font-light tracking-[2px]">Phone</p>
            <p class="text-sm font-light">+62 686-8965-9776</p>
        </div>

        <div>
            <span><i class="fa-solid fa-envelope mb-3 text-xl"></i></span>
            <p class="mb-3 text-sm font-light tracking-[2px]">Email</p>
            <p class="text-sm font-light">Ohwy123@gmail.com</p>
        </div>

        <div>
            <span><i class="fa-solid fa-location-dot mb-3 text-xl"></i></span>
            <p class="mb-3 text-sm font-light tracking-[2px]">Location</p>
            <p class="text-sm font-light">Denpasar, Bali, Indonesia</p>
        </div>
    </div>

    <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 max-w-6xl m-auto gap-12 mb-[80px] px-6">
        <form action="contact.php" method="post">
            <h2 class="pb-7 tracking-[2px] uppercase text-2xl">Send Us a Message</h2>

            <table class="text-sm w-full">
                <tr>
                    <td class="pb-5"><input class="w-full border-[3px] text-black rounded-sm border-gray-500 px-4 py-3 focus:outline-none focus:border-aurelis-gold" type="text" placeholder="Your Name"></td>
                </tr>

                <tr>
                    <td class="pb-5"><input class="w-full border-[3px] text-black rounded-sm border-gray-500 px-4 py-3 focus:outline-none focus:border-aurelis-gold" type="text" placeholder="Your Email"></td>
                </tr>

                <tr>
                    <td class="pb-5"><input class="w-full border-[3px] text-black rounded-sm border-gray-500 px-4 py-3 focus:outline-none focus:border-aurelis-gold" type="text" placeholder="Subject"></td>
                </tr>

                <tr>
                    <td><textarea class="w-full border-[3px] text-black rounded-sm border-gray-500 px-4 py-3 focus:outline-none focus:border-aurelis-gold max-h-[250px] h-[150px]" placeholder="Message"></textarea></td>
                </tr>
            </table>

            <button class="bg-none bg-blue-500 rounded-sm tracking-[2px] px-6 py-3 uppercase mt-6 transition-all duration-200 hover:bg-blue-600" type="submit">Send Message</button>
        </form>

        <div class="flex items-center relative">
            <img class="w-full h-[430px] object-cover" src="<?= BASE_URL ?>/assets/imgs/contact-image.png" alt="image-contact">

            <div class="absolute bg-white opacity-90 text-gray-500 uppercase max-w-[350px] p-6 pb-7 m-5 bottom-8">
                <p class="mb-2 text-md">VISIT OUR STUDIO</p>
                <p class="text-sm">Experience the elegance of Aurelis jewelry in person. Our studio welcomes you for private consultations and bespoke jewelry creations.</p>
            </div>
        </div>
    </div>
</section>

<section>
    <iframe src="https://www.google.com/maps/embed?origin=mfe&pb=!1m2!2m1!1sBali" class="w-full h-[400px]" frameborder="0" alt="map"></iframe>
</section>

<section class="flex flex-col items-center justify-center uppercase my-[70px]">
    <p class="tracking-[5px] text-md mb-7">Follow Us</p>
    <div class="flex gap-7 text-2xl">
        <a href="#"><i class="fa-brands fa-instagram transition-all duration-200 hover:text-aurelis-gold"></i></a>
        <a href="#"><i class="fa-brands fa-facebook transition-all duration-200 hover:text-aurelis-gold"></i></a>
        <a href="#"><i class="fa-brands fa-tiktok transition-all duration-200 hover:text-aurelis-gold"></i></a>
    </div>

</section>

<?php include ROOTPATH . "/layouts/footer.php" ?>