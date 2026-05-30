<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', __DIR__);
}

include_once ROOTPATH . "/config/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$flash_sukses = $_SESSION['sukses'] ?? null;
$flash_error  = $_SESSION['error'] ?? null;
unset($_SESSION['sukses'], $_SESSION['error']);

// 1. Logika Base URL
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_url = $protocol . "://" . $host . ($script_name == '/' ? '' : $script_name);

if (!defined('BASE_URL')) define('BASE_URL', $base_url);

// 2. AMBIL DATA PENGATURAN DARI DATABASE
$query_set = mysqli_query($conn, "SELECT * FROM pengaturan LIMIT 1");
$set = mysqli_fetch_assoc($query_set);

// 3. Siapkan variabel Map (Alamat di-encode agar bisa dibaca URL)
$map_address = urlencode($set['alamat']);

include ROOTPATH . "/layouts/header.php";
?>

<section class="contact-hero">
    <div class="relative">
        <div class="absolute inset-0">
            <img class="relative w-full h-[45vh] object-cover brightness-50" src="<?= BASE_URL ?>/assets/imgs/contact-hero.webp" alt="bg-contact">
        </div>

        <div class="relative flex flex-col items-center justify-center gap-5 min-h-[45vh] uppercase">
            <h1 class="tracking-[10px] text-4xl text-center md:text-5xl md:tracking-[15px] lg:text-5xl lg:tracking-[20px] font-serif text-white">Contact</h1>
            <p class="tracking-widest text-sm font-light text-gray-200">We'd Love to Hear from You</p>
        </div>
    </div>
</section>

<section>

    <?php if ($flash_sukses): ?>
        <div class="max-w-6xl mx-auto px-6 pt-8">
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 rounded-xl text-sm">
                <i class="fa-solid fa-circle-check mr-2"></i><?= htmlspecialchars($flash_sukses) ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($flash_error): ?>
        <div class="max-w-6xl mx-auto px-6 pt-8">
            <div class="p-4 bg-red-500/10 border border-red-500/30 text-red-300 rounded-xl text-sm">
                <i class="fa-solid fa-circle-exclamation mr-2"></i><?= htmlspecialchars($flash_error) ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 text-center max-w-6xl m-auto p-[50px] uppercase gap-10 mb-10 text-white">
        <div>
            <span><i class="fa-solid fa-phone mb-3 text-xl text-aurelis-gold"></i></span>
            <p class="mb-3 text-sm font-light tracking-[2px] text-gray-400">WhatsApp</p>
            <p class="text-sm font-light tracking-widest">+<?= $set['whatsapp'] ?></p>
        </div>

        <div>
            <span><i class="fa-solid fa-envelope mb-3 text-xl text-aurelis-gold"></i></span>
            <p class="mb-3 text-sm font-light tracking-[2px] text-gray-400">Email</p>
            <p class="text-sm font-light tracking-widest lowercase"><?= $set['email'] ?></p>
        </div>

        <div>
            <span><i class="fa-solid fa-location-dot mb-3 text-xl text-aurelis-gold"></i></span>
            <p class="mb-3 text-sm font-light tracking-[2px] text-gray-400">Location</p>
            <p class="text-sm font-light tracking-widest"><?= $set['alamat'] ?></p>
        </div>
    </div>


    <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 max-w-6xl m-auto gap-12 mb-[80px] px-6">
        <form action="admin/process/process_contact.php" method="post">
            <h2 class="pb-7 tracking-[2px] uppercase text-2xl font-serif text-white">Send Us a Message</h2>

            <table class="text-sm w-full">
                <tr>
                    <td class="pb-5"><input class="w-full border-b-[1px] bg-transparent text-white border-gray-700 px-4 py-3 focus:outline-none focus:border-aurelis-gold transition-all" type="text" name="name" placeholder="Your Name">
                    </td>
                </tr>
                <tr>
                    <td class="pb-5">
                        <input class="w-full border-b-[1px] bg-transparent text-white border-gray-700 px-4 py-3 focus:outline-none focus:border-aurelis-gold transition-all" type="email" name="email" placeholder="Your Email">
                    </td>
                </tr>
                <tr>
                    <td class="pb-5"><input class="w-full border-b-[1px] bg-transparent text-white border-gray-700 px-4 py-3 focus:outline-none focus:border-aurelis-gold transition-all" type="text" name="subject" placeholder="Subject"></td>
                </tr>
                <tr>
                    <td>
                        <textarea class="w-full border-b-[1px] bg-transparent text-white border-gray-700 px-4 py-3 focus:outline-none focus:border-aurelis-gold max-h-[250px] h-[150px]" placeholder="Message" name="message"></textarea>
                    </td>
                </tr>
            </table>

            <button class="bg-aurelis-gold text-aurelis-dark font-bold tracking-[2px] px-10 py-4 rounded-full uppercase mt-8 transition-all duration-300 hover:scale-105" type="submit">Send Message</button>
        </form>

        <div class="flex items-center relative group">
            <img class="w-full h-[430px] object-cover " src="<?= BASE_URL ?>/assets/imgs/contact-image.webp" alt="image-contact">

            <div class="absolute bg-[#050816]/90 border border-white/5 text-white uppercase max-w-[350px] p-8 m-5 bottom-8 backdrop-blur-md">
                <p class="mb-3 text-lg font-serif text-aurelis-gold">Visit Our Studio</p>
                <p class="text-[11px] leading-relaxed text-gray-300 normal-case">Experience the elegance of Aurelis jewelry in person. Our studio welcomes you for private consultations and bespoke jewelry creations.</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="w-full h-[450px] relative">
        <iframe
            src="https://www.google.com/maps?q=<?= $map_address ?>&output=embed"
            class="w-full h-full"
            frameborder="0"
            allowfullscreen
            loading="lazy"
            alt="map">
        </iframe>
    </div>
</section>

<section class="flex flex-col items-center justify-center uppercase my-[100px]">
    <p class="tracking-[5px] text-xs font-bold mb-8 text-gray-500">Follow Our Journey</p>
    <div class="flex gap-10 text-3xl">
        <a href="https://instagram.com/<?= $set['instagram'] ?>" target="_blank" class="transition-all duration-300 hover:text-aurelis-gold hover:-translate-y-2">
            <i class="fa-brands fa-instagram"></i>
        </a>
        <a href="#" class="transition-all duration-300 hover:text-aurelis-gold hover:-translate-y-2">
            <i class="fa-brands fa-facebook"></i>
        </a>
        <a href="#" class="transition-all duration-300 hover:text-aurelis-gold hover:-translate-y-2">
            <i class="fa-brands fa-tiktok"></i>
        </a>
    </div>
</section>

<?php include ROOTPATH . "/layouts/footer.php" ?>