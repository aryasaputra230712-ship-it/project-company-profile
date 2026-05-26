<?php
// 1. TAMBAHKAN KEAMANAN (WAJIB DI BARIS PALING ATAS)
include "auth_check.php";

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__));
}

// 1. Logika Base URL
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_path = str_replace('/admin', '', $script_name);
$base_url = $protocol . "://" . $host . ($base_path == '/' ? '' : $base_path);
if (!defined('BASE_URL')) define('BASE_URL', $base_url);

include_once ROOTPATH . "/config/config.php";

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'hero';

// 3. Ambil Data (Sesuai tab)
if ($tab == 'hero') {
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT s.*, v.jalur_video FROM slide_utama s JOIN video_utama v ON s.video_id = v.id LIMIT 1"));
} elseif ($tab == 'about') {
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM slide_tentang LIMIT 1"));
} elseif ($tab == 'founder') {
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM founder_utama LIMIT 1"));
} elseif ($tab == 'history') {
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM sejarah_utama LIMIT 1"));
} elseif ($tab == 'quotes') {
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kutipan_utama LIMIT 1"));
} elseif ($tab == 'motto') {
    $items = mysqli_query($conn, "SELECT * FROM motto_utama ORDER BY nomor ASC");
} elseif ($tab == 'why_us') {
    $items = mysqli_query($conn, "SELECT * FROM keunggulan_utama ORDER BY id ASC");
} elseif ($tab == 'masterpieces') {
    $items = mysqli_query($conn, "SELECT * FROM produk_pilihan ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aurelis Admin | Content Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'aurelis-gold': '#f7c66b',
                        'aurelis-dark': '#050816',
                        'aurelis-panel': '#0c0e17',
                        'aurelis-input': '#161925'
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
            overflow-x: hidden;
        }

        .active-tab {
            background: #1e1a1d;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #f7c66b !important;
        }

        .font-serif-lux {
            font-family: 'Playfair Display', serif;
        }

        /* Penting: Smooth scroll untuk tab */
        .tab-container {
            scroll-behavior: smooth;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .tab-container::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="flex min-h-screen">

    <?php include "layouts/sidebar.php"; ?>

    <main class="flex-1 md:ml-64 p-4 md:p-12 transition-all duration-300 w-full">

        <div class="flex items-center justify-between md:hidden mb-6">
            <button id="open-sidebar" class="text-aurelis-gold p-2 bg-white/5 rounded-xl">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
            <h2 class="font-serif-lux text-lg text-aurelis-gold tracking-widest uppercase">Aurelis</h2>
        </div>

        <header class="mb-8">
            <h1 class="text-xl md:text-3xl font-serif-lux text-white mb-1 tracking-wide">Website Content Manager</h1>
            <p class="text-gray-500 text-[10px] md:text-xs tracking-wide">Update narasi eksklusif [Aurelis Jewelry].</p>
        </header>

        <div id="tab-container" class="tab-container flex overflow-x-auto gap-2 p-1 bg-white/5 rounded-xl mb-8 border border-white/5">
            <?php
            $tabs_list = ['hero' => 'wand-magic-sparkles', 'about' => 'circle-info', 'founder' => 'user-tie', 'history' => 'book-open', 'motto' => 'quote-left', 'quotes' => 'comment-dots', 'why_us' => 'crown', 'masterpieces' => 'gem'];
            foreach ($tabs_list as $t => $icon): ?>
                <a href="?tab=<?= $t ?>"
                    id="<?= ($tab == $t) ? 'active-tab-link' : '' ?>"
                    class="whitespace-nowrap px-4 py-2.5 rounded-lg text-[10px] font-bold tracking-widest transition flex items-center gap-2 <?= $tab == $t ? 'active-tab' : 'text-gray-500 hover:text-white' ?>">
                    <i class="fa-solid fa-<?= $icon ?>"></i> <?= strtoupper(str_replace('_', ' ', $t)) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="bg-aurelis-panel border border-white/5 rounded-[1.5rem] md:rounded-[2.5rem] p-5 md:p-10 shadow-2xl">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-aurelis-gold/10 border border-aurelis-gold/20 rounded-xl flex items-center justify-center text-aurelis-gold">
                        <i class="fa-solid fa-<?= $tabs_list[$tab] ?>"></i>
                    </div>
                    <h2 class="text-xs md:text-base text-white font-bold uppercase tracking-widest">Konfigurasi <?= str_replace('_', ' ', $tab) ?></h2>
                </div>

                <?php if (in_array($tab, ['motto', 'why_us', 'masterpieces'])): ?>
                    <button class="bg-white/5 border border-white/10 px-3 py-2 rounded-lg text-[9px] font-bold text-aurelis-gold hover:bg-aurelis-gold hover:text-aurelis-dark transition uppercase">
                        + Tambah Item
                    </button>
                <?php endif; ?>
            </div>

            <form action="proses_update_konten.php" method="POST" class="space-y-6">
                <input type="hidden" name="tab_name" value="<?= $tab ?>">

                <?php if (in_array($tab, ['hero', 'about', 'founder', 'history'])): ?>
                    <div class="space-y-5">
                        <div>
                            <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Judul Utama</label>
                            <input type="text" name="judul" value="<?= $data['judul'] ?>" class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Isi Deskripsi</label>
                            <textarea name="deskripsi" rows="5" class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none leading-relaxed">
                                <?php
                                if ($tab == 'hero') echo $data['subjudul'];
                                elseif ($tab == 'history') echo $data['cerita_1'];
                                elseif ($tab == 'founder') echo $data['deskripsi'];
                                else echo $data['deskripsi'];
                                ?></textarea>
                        </div>
                    </div>

                <?php elseif ($tab == 'quotes'): ?>
                    <div class="space-y-6">
                        <textarea name="isi_kutipan" rows="3" class="w-full bg-aurelis-input border border-white/5 rounded-2xl px-5 py-4 text-sm md:text-lg font-serif-lux italic text-aurelis-gold focus:border-aurelis-gold/50 outline-none"><?= $data['isi_kutipan'] ?></textarea>
                        <input type="text" name="sumber" value="<?= $data['sumber'] ?>" class="w-full bg-aurelis-input border border-white/5 rounded-xl px-5 py-3 text-xs text-white tracking-widest text-center uppercase">
                    </div>

                <?php elseif ($tab == 'motto' || $tab == 'why_us'): ?>
                    <div class="grid grid-cols-1 gap-3">
                        <?php while ($row = mysqli_fetch_assoc($items)): ?>
                            <div class="bg-white/[0.03] border border-white/5 p-4 rounded-xl flex gap-4 group hover:border-aurelis-gold/20 transition">
                                <div class="text-xl font-serif-lux italic text-aurelis-gold/40 shrink-0">
                                    <?= isset($row['nomor']) ? $row['nomor'] : '<i class="fa-solid ' . $row['ikon'] . '"></i>' ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-white text-[11px] mb-0.5 uppercase tracking-wider truncate"><?= $row['judul'] ?></h4>
                                    <p class="text-[9px] text-gray-500 line-clamp-1 mb-3"><?= $row['deskripsi'] ?></p>
                                    <div class="flex gap-2">
                                        <button type="button" class="text-[7px] font-bold bg-white/5 px-3 py-1.5 rounded-md hover:bg-aurelis-gold hover:text-aurelis-dark uppercase">Edit</button>
                                        <button type="button" class="text-[7px] font-bold bg-red-500/10 text-red-400 px-3 py-1.5 rounded-md hover:bg-red-500 hover:text-white uppercase">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>

                <?php if (in_array($tab, ['hero', 'about', 'founder', 'history', 'quotes'])): ?>
                    <div class="mt-8">
                        <button type="submit" class="w-full bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark font-bold py-4 rounded-xl shadow-xl hover:brightness-110 transition uppercase tracking-widest text-[10px]">
                            <i class="fa-solid fa-floppy-disk mr-2"></i> Update Konten
                        </button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </main>

    <script>
        // 1. Sidebar Toggle
        const sidebar = document.getElementById('sidebar-main');
        const overlay = document.getElementById('sidebar-overlay');
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        if (openBtn) openBtn.addEventListener('click', toggleSidebar);
        if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        if (overlay) overlay.addEventListener('click', toggleSidebar);

        // 2. FIX: Auto-scroll Tab ke yang aktif
        window.addEventListener('DOMContentLoaded', () => {
            const activeTab = document.getElementById('active-tab-link');
            const container = document.getElementById('tab-container');
            if (activeTab && container) {
                // Memberi waktu sedikit agar render selesai
                setTimeout(() => {
                    container.scrollTo({
                        left: activeTab.offsetLeft - (container.offsetWidth / 2) + (activeTab.offsetWidth / 2),
                        behavior: 'smooth'
                    });
                }, 100);
            }
        });
    </script>
</body>

</html>