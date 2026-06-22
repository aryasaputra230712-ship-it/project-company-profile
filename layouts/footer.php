<?php
// 1. Path internal untuk include file PHP
if (!defined('ROOTPATH')) {
    // PERBAIKAN: Gunakan dirname(__DIR__) agar path kembali ke root project, bukan terjebak di folder layouts
    define('ROOTPATH', dirname(__DIR__));
}

// 2. Deteksi Protokol
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    ? "https" : "http";

// 3. Base URL Pintar
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_url = $protocol . "://" . $host . ($script_name == '/' ? '' : $script_name);

if (file_exists(ROOTPATH . "/config/config.php")) {
    include_once ROOTPATH . "/config/config.php";
}

// ==========================================
// 4. LOGIKA BAHASA & URL DINAMIS
// ==========================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tangkap jika ada permintaan ganti bahasa dari URL
if (isset($_GET['lang']) && in_array($_GET['lang'], ['id', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// ==========================================
// PERBAIKAN LOGIKA QUERY DATABASE
// ==========================================
global $conn; // Pastikan koneksi database terbaca di file ini

// Berikan nilai default agar website tidak crash meskipun tabel database kosong/gagal ditarik
$set = [
    'whatsapp' => '-',
    'email' => 'info@aurelis.com',
    'alamat' => 'Denpasar, Bali, Indonesia'
];

// Jika koneksi berhasil, baru kita tarik datanya
if (isset($conn)) {
    $query_set = mysqli_query($conn, "SELECT * FROM pengaturan LIMIT 1");
    if ($query_set && mysqli_num_rows($query_set) > 0) {
        $set = mysqli_fetch_assoc($query_set);
    }
}
?>

<footer class="bg-aurelis-blue py-[60px] px-6">
    <div class="grid sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 max-w-[77rem] gap-12 m-auto uppercase">
        <div>
            <img class="w-[100px] mb-6" src="<?= BASE_URL ?>/assets/imgs/logo_gold.png" alt="brand-footer">
            <p class="text-sm font-thin">Aurelis Jewelry is dedicated to crafting timeless jewelry pieces that embody elegance, luxury, and exceptional craftsmanship.</p>
        </div>

        <div class="text-sm">
            <p class="text-2xl mb-4">CONTACT</p>
            <p class="text-sm font-light tracking-widest">Nomor: +<?= htmlspecialchars($set['whatsapp']) ?></p>
            <p class="mb-1 font-thin">Email: <?= htmlspecialchars($set['email']) ?></p>
            <p class="font-thin"><?= htmlspecialchars($set['alamat']) ?></p>
        </div>

        <div>
            <p class="text-2xl mb-5">Follow Us</p>

            <div class="flex gap-10 text-3xl">
                <?php if (!empty($set['instagram'])): ?>
                    <a href="https://instagram.com/<?= htmlspecialchars($set['instagram']) ?>" target="_blank" class="hover:text-aurelis-gold">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                <?php endif; ?>

                <?php if (!empty($set['facebook'])): ?>
                    <a href="https://facebook.com/<?= htmlspecialchars($set['facebook']) ?>" target="_blank" class="hover:text-aurelis-gold">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                <?php endif; ?>

                <?php if (!empty($set['tiktok'])): ?>
                    <a href="https://tiktok.com/@<?= htmlspecialchars($set['tiktok']) ?>" target="_blank" class="hover:text-aurelis-gold">
                        <i class="fa-brands fa-tiktok"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="text-center">
        <div class="max-w-[1240px] m-auto mt-[55px] mb-6 border border-gray-700"></div>
        <p class="text-sm font-thin">&copy; 2026 Aurelis Jewelry. All rights reserved.</p>
    </div>
</footer>

</body>

</html>