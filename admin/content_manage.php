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

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'hero';

// 3. Ambil Data dari Wadah Baru untuk Tab Tunggal Multi-Bahasa
$home_query = mysqli_query($conn, "SELECT * FROM konten_homepage WHERE id = 1 LIMIT 1");
$home_data  = mysqli_fetch_assoc($home_query);

// ➕ QUERY TAMBAHAN: Ambil data asset gambar aktif untuk penunjang kotak preview
$about_query = mysqli_query($conn, "SELECT gambar FROM slide_tentang WHERE status = 'aktif' LIMIT 1");
$about_data  = mysqli_fetch_assoc($about_query);

$founder_query = mysqli_query($conn, "SELECT gambar FROM founder_utama WHERE status = 'aktif' LIMIT 1");
$founder_data  = mysqli_fetch_assoc($founder_query);

$history_query = mysqli_query($conn, "SELECT gambar FROM sejarah_utama WHERE status = 'aktif' LIMIT 1");
$history_data  = mysqli_fetch_assoc($history_query);

// Logika pembagian query untuk data berkelompok (Multiple Rows)
if ($tab == 'motto') {
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
            background: rgba(247, 198, 107, 0.1);
            border: 1px solid rgba(247, 198, 107, 0.2);
            color: #f7c66b !important;
        }

        .font-serif-lux {
            font-family: 'Playfair Display', serif;
        }

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
            <p class="text-gray-500 text-[10px] md:text-xs tracking-wide">Update luxury brand narratives and localized profiles.</p>
        </header>

        <?php if (isset($_SESSION['sukses'])): ?>
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl flex items-center gap-3 text-xs tracking-wider uppercase">
                <i class="fa-solid fa-circle-check text-sm"></i> <?= $_SESSION['sukses'];
                                                                    unset($_SESSION['sukses']); ?>
            </div>
        <?php endif; ?>

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
                    <h2 class="text-xs md:text-base text-white font-bold uppercase tracking-widest">Configuration Panel: <?= str_replace('_', ' ', $tab) ?></h2>
                </div>

                <?php if (in_array($tab, ['motto', 'why_us', 'masterpieces'])): ?>
                    <button class="bg-white/5 border border-white/10 px-3 py-2 rounded-lg text-[9px] font-bold text-aurelis-gold hover:bg-aurelis-gold hover:text-aurelis-dark transition uppercase">
                        + Add Item
                    </button>
                <?php endif; ?>
            </div>

            <form action="process/process_update_content.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="tab_name" value="<?= $tab ?>">

                <?php if ($tab == 'hero'): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Judul Utama (ID) 🇮🇩</label>
                            <input type="text" name="hero_judul_id" value="<?= htmlspecialchars($home_data['hero_judul_id'] ?? '') ?>" placeholder="Masukkan judul utama dalam Bahasa Indonesia..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-2 block">Main Title (EN) 🇺🇸</label>
                            <input type="text" name="hero_judul_en" value="<?= htmlspecialchars($home_data['hero_judul_en'] ?? '') ?>" placeholder="Enter main title in English..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                        </div>
                        <div class="md:col-span-1">
                            <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Isi Deskripsi / Sub-judul (ID) 🇮🇩</label>
                            <textarea name="hero_sub_id" rows="5" placeholder="Masukkan narasi sub-judul dalam Bahasa Indonesia..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none leading-relaxed"><?= htmlspecialchars($home_data['hero_sub_id'] ?? '') ?></textarea>
                        </div>
                        <div class="md:col-span-1">
                            <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-2 block">Sub-headline / Description (EN) 🇺🇸</label>
                            <textarea name="hero_sub_en" rows="5" placeholder="Enter sub-headline text in English..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none leading-relaxed"><?= htmlspecialchars($home_data['hero_sub_en'] ?? '') ?></textarea>
                        </div>

                        <div class="md:col-span-2 border-t border-white/5 pt-6 flex flex-col md:flex-row gap-6 items-center bg-white/[0.02] p-4 rounded-xl border border-white/5">
                            <div class="flex-1 w-full">
                                <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Ganti Background Video (.mp4)</label>
                                <input type="file" name="video_file" accept="video/mp4" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gradient-to-r file:from-aurelis-gold file:to-[#bfa37e] file:text-aurelis-dark hover:file:opacity-90 cursor-pointer">
                                <p class="text-[11px] text-gray-500 mt-2 font-mono">Video Aktif: <?= htmlspecialchars($home_data['video_url'] ?? 'default.mp4') ?></p>
                            </div>
                        </div>
                    </div>

                <?php elseif ($tab == 'about'): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Judul About (ID) 🇮🇩</label>
                            <input type="text" name="about_judul_id" value="<?= htmlspecialchars($home_data['about_judul_id'] ?? '') ?>" placeholder="Masukkan judul tentang kami..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-2 block">About Section Title (EN) 🇺🇸</label>
                            <input type="text" name="about_judul_en" value="<?= htmlspecialchars($home_data['about_judul_en'] ?? '') ?>" placeholder="Enter about section title in English..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Isi Deskripsi About (ID) 🇮🇩</label>
                            <textarea name="about_deskripsi_id" rows="5" placeholder="Masukkan paragraf tentang kami..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none leading-relaxed"><?= htmlspecialchars($home_data['about_deskripsi_id'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-2 block">About Narrative Story (EN) 🇺sky</label>
                            <textarea name="about_deskripsi_en" rows="5" placeholder="Enter about section story in English..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none leading-relaxed"><?= htmlspecialchars($home_data['about_deskripsi_en'] ?? '') ?></textarea>
                        </div>

                        <div class="md:col-span-2 border-t border-white/5 pt-6 flex flex-col md:flex-row gap-6 items-center bg-white/[0.02] p-4 rounded-xl border border-white/5">
                            <div class="mx-auto md:mx-0 shrink-0">
                                <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($about_data['gambar'] ?? 'default.jpg') ?>" class="w-32 h-24 object-cover rounded-xl border border-white/10 shadow-lg" alt="Current About">
                            </div>
                            <div class="flex-1 w-full">
                                <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Ganti Gambar About</label>
                                <input type="file" name="about_gambar" accept="image/*" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gradient-to-r file:from-aurelis-gold file:to-[#bfa37e] file:text-aurelis-dark hover:file:opacity-90 cursor-pointer">
                            </div>
                        </div>
                    </div>

                <?php elseif ($tab == 'founder'): ?>
                    <div class="mb-4">
                        <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Nama Lengkap Founder</label>
                        <input type="text" name="founder_nama" value="<?= htmlspecialchars($home_data['founder_nama'] ?? '') ?>" placeholder="Contoh: Astutik" class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Biografi Founder (ID) 🇮🇩</label>
                            <textarea name="founder_bio_id" rows="6" placeholder="Masukkan biografi lengkap founder dalam Bahasa Indonesia..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none leading-relaxed"><?= htmlspecialchars($home_data['founder_bio_id'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-2 block">Founder Biography (EN) 🇺🇸</label>
                            <textarea name="founder_bio_en" rows="6" placeholder="Enter complete founder biography in English..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none leading-relaxed"><?= htmlspecialchars($home_data['founder_bio_en'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="border-t border-white/5 pt-6 flex flex-col md:flex-row gap-6 items-center bg-white/[0.02] p-4 rounded-xl border border-white/5 mt-6">
                        <div class="mx-auto md:mx-0 shrink-0">
                            <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($founder_data['gambar'] ?? 'default.jpg') ?>" class="w-24 h-24 object-cover rounded-xl border border-white/10 shadow-lg" alt="Current Founder">
                        </div>
                        <div class="flex-1 w-full">
                            <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Ganti Foto Potret Founder</label>
                            <input type="file" name="founder_gambar" accept="image/*" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gradient-to-r file:from-aurelis-gold file:to-[#bfa37e] file:text-aurelis-dark hover:file:opacity-90 cursor-pointer">
                            <p class="text-[10px] text-gray-500 mt-2">Disarankan menggunakan aspek rasio potret vertikal (3:4).</p>
                        </div>
                    </div>

                <?php elseif ($tab == 'history'): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Judul Sejarah (ID) 🇮🇩</label>
                            <input type="text" name="sejarah_judul_id" value="<?= htmlspecialchars($home_data['sejarah_judul_id'] ?? '') ?>" placeholder="Masukkan judul linimasa sejarah..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-2 block">History Title (EN) 🇺🇸</label>
                            <input type="text" name="sejarah_judul_en" value="<?= htmlspecialchars($home_data['sejarah_judul_en'] ?? '') ?>" placeholder="Enter historical timeline title in English..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Narasi Cerita Sejarah (ID) 🇮🇩</label>
                            <textarea name="sejarah_konten_id" rows="6" placeholder="Masukkan cerita perkembangan perjalanan bisnis dalam Bahasa Indonesia..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none leading-relaxed"><?= htmlspecialchars($home_data['sejarah_konten_id'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-2 block">Historical Narrative Story (EN) 🇺🇸</label>
                            <textarea name="sejarah_konten_en" rows="6" placeholder="Enter historical narrative storyline in English..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none leading-relaxed"><?= htmlspecialchars($home_data['sejarah_konten_en'] ?? '') ?></textarea>
                        </div>

                        <div class="md:col-span-2 border-t border-white/5 pt-6 flex flex-col md:flex-row gap-6 items-center bg-white/[0.02] p-4 rounded-xl border border-white/5">
                            <div class="mx-auto md:mx-0 shrink-0">
                                <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($history_data['gambar'] ?? 'default.jpg') ?>" class="w-32 h-24 object-cover rounded-xl border border-white/10 shadow-lg" alt="Current History">
                            </div>
                            <div class="flex-1 w-full">
                                <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Ganti Gambar Dokumentasi Sejarah</label>
                                <input type="file" name="history_gambar" accept="image/*" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gradient-to-r file:from-aurelis-gold file:to-[#bfa37e] file:text-aurelis-dark hover:file:opacity-90 cursor-pointer">
                            </div>
                        </div>
                    </div>

                <?php elseif ($tab == 'quotes'): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Kutipan Emas (ID) 🇮🇩</label>
                            <textarea name="kutipan_id" rows="4" placeholder="Masukkan kalimat kutipan inspiratif dalam Bahasa Indonesia..." class="w-full bg-aurelis-input border border-white/5 rounded-2xl px-5 py-4 text-sm font-serif-lux italic text-aurelis-gold focus:border-aurelis-gold/50 outline-none"><?= htmlspecialchars($home_data['kutipan_id'] ?? '') ?></textarea>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-2 block">Golden Philosophy Quote (EN) 🇺🇸</label>
                            <textarea name="kutipan_en" rows="4" placeholder="Enter inspirational philosophy quote statement in English..." class="w-full bg-aurelis-input border border-white/5 rounded-2xl px-5 py-4 text-sm font-serif-lux italic text-aurelis-gold focus:border-aurelis-gold/50 outline-none"><?= htmlspecialchars($home_data['kutipan_en'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Link Video Utama (YouTube Embed/Jalur Video)</label>
                        <input type="text" name="video_url" value="<?= htmlspecialchars($home_data['video_url'] ?? '') ?>" placeholder="Contoh: https://www.youtube.com/embed/..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-5 py-3 text-xs text-white tracking-wide outline-none focus:border-aurelis-gold/50">
                    </div>

                <?php elseif ($tab == 'motto' || $tab == 'why_us'): ?>
                    <div class="grid grid-cols-1 gap-4">
                        <?php if (mysqli_num_rows($items) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($items)): ?>
                                <div class="bg-white/[0.03] border border-white/5 p-5 rounded-2xl flex gap-4 group hover:border-aurelis-gold/20 transition duration-300">
                                    <div class="text-xl font-serif-lux italic text-aurelis-gold/40 shrink-0">
                                        <?= isset($row['nomor']) ? $row['nomor'] : '<i class="fa-solid ' . htmlspecialchars($row['ikon']) . '"></i>' ?>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-white text-[12px] mb-1.5 uppercase tracking-wider truncate">
                                            <span class="text-gray-500 mr-1 text-[10px]">🇮🇩</span> <?= htmlspecialchars($row['judul']) ?>
                                            <span class="text-aurelis-gold font-normal ml-2 font-mono text-[11px]">
                                                / <span class="text-gray-600 mr-0.5 text-[10px]">🇺🇸</span> <?= htmlspecialchars($row['judul_en'] ?? '[No translation available]') ?>
                                            </span>
                                        </h4>
                                        <p class="text-[10px] text-gray-400 leading-relaxed mb-4 space-y-1">
                                            <span class="block"><strong class="text-gray-600 font-mono">ID:</strong> <?= htmlspecialchars($row['deskripsi']) ?></span>
                                            <span class="block"><strong class="text-aurelis-gold/60 font-mono">EN:</strong> <?= htmlspecialchars($row['deskripsi_en'] ?? '-') ?></span>
                                        </p>
                                        <div class="flex gap-2">
                                            <button type="button" class="text-[8px] font-bold bg-white/5 px-4 py-2 rounded-lg hover:bg-aurelis-gold hover:text-aurelis-dark uppercase transition duration-300">Edit</button>
                                            <button type="button" class="text-[8px] font-bold bg-red-500/10 text-red-400 px-4 py-2 rounded-lg hover:bg-red-500 hover:text-white uppercase transition duration-300">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-xs text-gray-500 italic p-4">No records found in this section.</p>
                        <?php endif; ?>
                    </div>

                <?php elseif ($tab == 'masterpieces'): ?>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        <?php if (mysqli_num_rows($items) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($items)): ?>
                                <div class="bg-white/[0.02] border border-white/5 p-4 rounded-2xl group hover:border-aurelis-gold/20 transition flex flex-col justify-between duration-300">
                                    <div>
                                        <div class="aspect-[3/4] rounded-xl overflow-hidden bg-gray-900 mb-4 border border-white/5">
                                            <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($row['gambar']) ?>" alt="Product Collection" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                        </div>
                                        <div class="space-y-1.5 px-1">
                                            <h4 class="font-bold text-white text-xs uppercase tracking-wide truncate">
                                                <span class="text-gray-500 mr-1 text-[10px]">🇮🇩</span><?= htmlspecialchars($row['nama_produk']) ?>
                                            </h4>
                                            <h5 class="text-aurelis-gold text-[10px] tracking-wide truncate italic flex items-center">
                                                <span class="text-gray-600 mr-1 text-[9px] not-italic">🇺🇸</span><?= htmlspecialchars($row['nama_produk_en'] ?? '[No English name available]') ?>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 mt-4 pt-3 border-t border-white/5">
                                        <button type="button" class="flex-1 text-center text-[8px] font-bold bg-white/5 py-2.5 rounded-lg hover:bg-aurelis-gold hover:text-aurelis-dark uppercase transition duration-300">Edit</button>
                                        <button type="button" class="text-center text-[8px] font-bold bg-red-500/10 text-red-400 px-3 py-2.5 rounded-lg hover:bg-red-500 hover:text-white uppercase transition duration-300"><i class="fa-regular fa-trash-can"></i></button>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-span-full py-10 text-center">
                                <p class="text-xs text-gray-500 italic">No historical masterpiece products added yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (in_array($tab, ['hero', 'about', 'founder', 'history', 'quotes'])): ?>
                    <div class="mt-8">
                        <button type="submit" class="w-full bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark font-bold py-4 rounded-xl shadow-xl hover:brightness-110 transition uppercase tracking-widest text-[10px]">
                            <i class="fa-solid fa-floppy-disk mr-2"></i> Save Bilingual Content Changes
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
            if (sidebar) sidebar.classList.toggle('-translate-x-full');
            if (overlay) overlay.classList.toggle('hidden');
        }
        if (openBtn) openBtn.addEventListener('click', toggleSidebar);
        if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        if (overlay) overlay.addEventListener('click', toggleSidebar);

        // 2. Auto-scroll Tab ke yang aktif
        window.addEventListener('DOMContentLoaded', () => {
            const activeTab = document.getElementById('active-tab-link');
            const container = document.getElementById('tab-container');
            if (activeTab && container) {
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