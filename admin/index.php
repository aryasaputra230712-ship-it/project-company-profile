<?php
// 1. TAMBAHKAN KEAMANAN (WAJIB DI BARIS PALING ATAS)
include "auth_check.php";

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__));
}

include_once ROOTPATH . "/config/config.php";

// Logika Base URL
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_path = str_replace('/admin', '', $script_name);
$base_url = $protocol . "://" . $host . ($base_path == '/' ? '' : $base_path);

if (!defined('BASE_URL')) {
    define('BASE_URL', $base_url);
}

// 2. Ambil data dinamis
$q_galeri = mysqli_query($conn, "SELECT COUNT(*) as total FROM galeri_utama");
$r_galeri = mysqli_fetch_assoc($q_galeri);
$total_galeri = $r_galeri['total'];

$q_kategori = mysqli_query($conn, "SELECT COUNT(DISTINCT kategori) as total FROM galeri_utama WHERE status = 'aktif'");
$r_kategori = mysqli_fetch_assoc($q_kategori);
$total_kategori = $r_kategori['total'];

$q_keunggulan = mysqli_query($conn, "SELECT COUNT(*) as total FROM keunggulan_utama WHERE status = 'aktif'");
$r_keunggulan = mysqli_fetch_assoc($q_keunggulan);
$total_keunggulan = $r_keunggulan['total'];

$q_slide = mysqli_query($conn, "SELECT COUNT(*) as total FROM slide_tentang WHERE status = 'aktif'");
$r_slide = mysqli_fetch_assoc($q_slide);
$total_slide = $r_slide['total'];

$q_produk = mysqli_query($conn, "SELECT * FROM galeri_utama ORDER BY id DESC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aurelis Admin | Management Panel</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'aurelis-gold': '#f7c66b',
                        'aurelis-dark': '#050816',
                        'aurelis-card': 'rgba(10, 15, 38, 0.6)',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #050816;
            color: #f7f7f7;
            font-family: 'Poppins', sans-serif;
            /* Font Utama UI */
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
            /* Font Khusus Judul Mewah */
        }

        .glass {
            background: rgba(10, 15, 38, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="flex min-h-screen">

    <?php include "layouts/sidebar.php"; ?>

    <main class="flex-1 md:ml-64 p-6 md:p-8 transition-all">

        <div class="md:hidden flex items-center justify-between mb-8">
            <button id="open-sidebar" class="text-aurelis-gold p-2 border border-aurelis-gold/20 rounded-lg">
                <i class="fa-solid fa-bars-staggered text-xl"></i>
            </button>
            <img src="<?= BASE_URL ?>/assets/imgs/logo_gold.png" class="h-6">
        </div>

        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-serif tracking-widest uppercase text-white">Aurelis Control Panel</h1>
                <p class="text-gray-500 text-xs mt-1">Data statistik sinkron langsung dengan database</p>
            </div>
            <a href="../index.php" target="_blank" class="text-[10px] uppercase tracking-widest text-aurelis-gold border border-aurelis-gold/30 px-6 py-2 rounded-full hover:bg-aurelis-gold hover:text-aurelis-dark transition font-bold">
                Lihat Live Site <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i>
            </a>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-10">
            <div class="glass p-6 rounded-[2rem] relative overflow-hidden group hover:border-aurelis-gold/30 transition duration-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-aurelis-gold/10 rounded-lg text-aurelis-gold"><i class="fa-solid fa-images text-lg"></i></div>
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                </div>
                <h3 class="text-3xl font-bold text-white"><?= $total_galeri; ?></h3>
                <p class="text-gray-400 text-[10px] uppercase tracking-widest mt-1">Total Foto Galeri</p>
            </div>

            <div class="glass p-6 rounded-[2rem] relative group hover:border-aurelis-gold/30 transition duration-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-aurelis-gold/10 rounded-lg text-aurelis-gold"><i class="fa-solid fa-tags text-lg"></i></div>
                </div>
                <h3 class="text-3xl font-bold text-white"><?= $total_kategori; ?></h3>
                <p class="text-gray-400 text-[10px] uppercase tracking-widest mt-1">Kategori Produk Aktif</p>
            </div>

            <div class="glass p-6 rounded-[2rem] relative group hover:border-aurelis-gold/30 transition duration-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-aurelis-gold/10 rounded-lg text-aurelis-gold"><i class="fa-solid fa-star text-lg"></i></div>
                </div>
                <h3 class="text-3xl font-bold text-white"><?= $total_keunggulan; ?></h3>
                <p class="text-gray-400 text-[10px] uppercase tracking-widest mt-1">Keunggulan Diaktifkan</p>
            </div>

            <div class="glass p-6 rounded-[2rem] relative group hover:border-aurelis-gold/30 transition duration-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-aurelis-gold/10 rounded-lg text-aurelis-gold"><i class="fa-solid fa-sliders text-lg"></i></div>
                </div>
                <h3 class="text-3xl font-bold text-white"><?= $total_slide; ?></h3>
                <p class="text-gray-400 text-[10px] uppercase tracking-widest mt-1">Slide 'About' Aktif</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <h2 class="text-[10px] font-bold tracking-[0.3em] text-aurelis-gold uppercase border-l-2 border-aurelis-gold pl-3">Sistem Navigasi Cepat</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="gallery_manage.php" class="flex items-center gap-4 p-5 glass rounded-[1.5rem] hover:bg-white/5 hover:border-aurelis-gold/20 transition group">
                        <div class="p-4 bg-white/5 rounded-xl group-hover:text-aurelis-gold transition"><i class="fa-solid fa-plus"></i></div>
                        <div>
                            <p class="font-bold text-sm">Update Galeri Karya</p>
                            <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-tighter">Unggah perhiasan terbaru</p>
                        </div>
                    </a>
                    <a href="content_manage.php" class="flex items-center gap-4 p-5 glass rounded-[1.5rem] hover:bg-white/5 hover:border-aurelis-gold/20 transition group">
                        <div class="p-4 bg-white/5 rounded-xl group-hover:text-aurelis-gold transition"><i class="fa-solid fa-pen-to-square"></i></div>
                        <div>
                            <p class="font-bold text-sm">Kelola Konten</p>
                            <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-tighter">Sesuaikan narasi brand</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="space-y-6">
                <h2 class="text-[10px] font-bold tracking-[0.3em] text-gray-500 uppercase md:text-right border-r-2 border-gray-700 pr-3">Status Keamanan</h2>
                <div class="glass p-6 rounded-[1.5rem] space-y-4 shadow-xl">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]"></span>
                        <p class="text-xs font-semibold text-white uppercase tracking-widest">Database Connected</p>
                    </div>
                    <div class="text-[10px] text-gray-500 space-y-2 pt-4 border-t border-white/5">
                        <p class="flex justify-between"><span>HOST:</span> <span class="text-gray-300 font-mono">LOCAL/SERVER</span></p>
                        <p class="flex justify-between"><span>DB:</span> <span class="text-gray-300 font-mono">VIBEWEBS_DB</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-10 mt-16">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-[10px] font-bold tracking-[0.3em] text-aurelis-gold uppercase border-l-2 border-aurelis-gold pl-3">Koleksi Terbaru</h2>
                <a href="gallery_manage.php" class="text-[9px] font-bold uppercase tracking-widest text-gray-500 hover:text-aurelis-gold flex items-center gap-2 transition">
                    Lihat Katalog <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
                if (mysqli_num_rows($q_produk) > 0) {
                    while ($row = mysqli_fetch_assoc($q_produk)) {
                ?>
                        <div class="glass rounded-[1.5rem] overflow-hidden group hover:border-aurelis-gold/20 transition duration-300 flex flex-col shadow-2xl">
                            <div class="h-52 overflow-hidden bg-gray-900 relative">
                                <img src="<?= BASE_URL ?>/assets/imgs/<?= $row['gambar']; ?>" alt="<?= $row['nama_produk']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col justify-between bg-[#0b1021]/20">
                                <div>
                                    <h4 class="font-serif text-white tracking-wider text-sm line-clamp-1"><?= $row['nama_produk']; ?></h4>
                                    <p class="text-[11px] text-aurelis-gold font-bold mt-2 tracking-widest">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-[9px] bg-white/5 border border-white/10 text-gray-500 px-3 py-1 rounded-full uppercase tracking-tighter font-bold">
                                        <?= $row['kategori']; ?>
                                    </span>
                                    <i class="fa-solid fa-gem text-[10px] text-aurelis-gold/30"></i>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<div class="col-span-4 p-12 glass text-center rounded-[2rem] text-gray-500 text-xs tracking-widest uppercase">Katalog perhiasan masih kosong.</div>';
                }
                ?>
            </div>
        </div>
    </main>

    <script>
        const sidebar = document.getElementById('sidebar-main');
        const overlay = document.getElementById('sidebar-overlay');
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden', !sidebar.classList.contains('-translate-x-full'));
        }

        if (openBtn) openBtn.addEventListener('click', toggleSidebar);
        if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        if (overlay) overlay.addEventListener('click', toggleSidebar);
    </script>
</body>

</html>