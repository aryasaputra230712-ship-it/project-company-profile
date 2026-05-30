<?php
// 1. TAMBAHKAN KEAMANAN (WAJIB DI BARIS PALING ATAS)
include "auth_check.php";

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__));
}

// 2. Logika Base URL
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

// Menentukan tab aktif (default: hero)
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'hero';

// 3. Ambil Data Konten Hero Galeri dari Tabel 'header_galeri'
$gallery_query = mysqli_query($conn, "SELECT * FROM header_galeri WHERE id = 1 LIMIT 1");
$gallery_data  = mysqli_fetch_assoc($gallery_query);

// 4. Logika Pagination untuk Tab Perhiasan di Admin
if ($tab == 'perhiasan') {
    $limit = 8; // Jumlah item per halaman di admin
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($page > 1) ? ($page * $limit) - $limit : 0;

    // Hitung total data
    $total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM galeri_utama");
    $total_data = mysqli_fetch_assoc($total_query)['total'];
    $total_pages = ceil($total_data / $limit);

    // Ambil data dengan LIMIT
    $items = mysqli_query($conn, "SELECT * FROM galeri_utama ORDER BY id DESC LIMIT $start, $limit");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aurelis Admin | Gallery Manager</title>
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

        <header class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-xl md:text-3xl font-serif-lux text-white mb-1 tracking-wide">Website Gallery Manager</h1>
                <p class="text-gray-500 text-[10px] md:text-xs tracking-wide">Kelola banner hero utama dan katalog koleksi perhiasan [Aurelis Jewelry].</p>
            </div>
            <div>
                <a href="<?= BASE_URL ?>/gallery.php" target="_blank" class="inline-flex items-center gap-2 bg-white/5 border border-white/10 px-4 py-2.5 rounded-xl text-[10px] font-bold text-gray-400 hover:text-white hover:bg-white/10 transition uppercase tracking-widest">
                    Lihat Live Site <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                </a>
            </div>
        </header>

        <?php if (isset($_SESSION['sukses'])): ?>
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl flex items-center gap-3 text-xs tracking-wider uppercase">
                <i class="fa-solid fa-circle-check text-sm"></i> <?= $_SESSION['sukses'];
                                                                    unset($_SESSION['sukses']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl flex items-center gap-3 text-xs tracking-wider uppercase">
                <i class="fa-solid fa-circle-exclamation text-sm"></i> <?= $_SESSION['error'];
                                                                        unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="flex gap-2 p-1 bg-white/5 rounded-xl mb-8 border border-white/5 w-fit">
            <a href="?tab=hero" class="px-5 py-2.5 rounded-lg text-[10px] font-bold tracking-widest transition flex items-center gap-2 <?= $tab == 'hero' ? 'active-tab' : 'text-gray-500 hover:text-white' ?>">
                <i class="fa-solid fa-wand-magic-sparkles"></i> HERO BANNER
            </a>
            <a href="?tab=perhiasan" class="px-5 py-2.5 rounded-lg text-[10px] font-bold tracking-widest transition flex items-center gap-2 <?= $tab == 'perhiasan' ? 'active-tab' : 'text-gray-500 hover:text-white' ?>">
                <i class="fa-solid fa-gem"></i> MANAJEMEN PERHIASAN
            </a>
        </div>

        <div class="bg-aurelis-panel border border-white/5 rounded-[1.5rem] md:rounded-[2.5rem] p-5 md:p-10 shadow-2xl">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-aurelis-gold/10 border border-aurelis-gold/20 rounded-xl flex items-center justify-center text-aurelis-gold">
                        <i class="fa-solid <?= $tab == 'hero' ? 'fa-wand-magic-sparkles' : 'fa-gem' ?>"></i>
                    </div>
                    <h2 class="text-xs md:text-base text-white font-bold uppercase tracking-widest">
                        Konfigurasi <?= $tab == 'hero' ? 'Hero Galeri' : 'Koleksi Perhiasan' ?>
                    </h2>
                </div>

                <?php if ($tab == 'perhiasan'): ?>
                    <button type="button" onclick="toggleModal('modal-add')" class="bg-white/5 border border-white/10 px-3 py-2 rounded-lg text-[9px] font-bold text-aurelis-gold hover:bg-aurelis-gold hover:text-aurelis-dark transition uppercase tracking-wider">
                        + Tambah Perhiasan
                    </button>
                <?php endif; ?>
            </div>

            <?php if ($tab == 'hero'): ?>
                <form action="process/process_update_gallery.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="tab_name" value="<?= $tab ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Judul Utama Hero (ID) 🇮🇩</label>
                            <input type="text" name="gallery_judul_id" value="<?= htmlspecialchars($gallery_data['judul_id'] ?? '') ?>" placeholder="Contoh: GALERI" class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-2 block">Hero Main Title (EN) 🇺🇸</label>
                            <input type="text" name="gallery_judul_en" value="<?= htmlspecialchars($gallery_data['judul_en'] ?? '') ?>" placeholder="e.g., GALLERY" class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Sub-Judul Hero (ID) 🇮🇩</label>
                            <input type="text" name="gallery_sub_id" value="<?= htmlspecialchars($gallery_data['subjudul_id'] ?? '') ?>" placeholder="Masukkan sub-judul dalam Bahasa Indonesia..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-2 block">Hero Sub-title (EN) 🇺🇸</label>
                            <input type="text" name="gallery_sub_en" value="<?= htmlspecialchars($gallery_data['subjudul_en'] ?? '') ?>" placeholder="Enter sub-title text in English..." class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                        </div>
                        <div class="md:col-span-2 border-t border-white/5 pt-6 flex flex-col md:flex-row gap-6 items-center bg-white/[0.02] p-4 rounded-xl border border-white/5">
                            <div class="mx-auto md:mx-0 shrink-0">
                                <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($gallery_data['gambar'] ?? 'default-gallery.jpg') ?>" class="w-32 h-20 object-cover rounded-xl border border-white/10 shadow-lg" alt="Current Gallery Hero">
                            </div>
                            <div class="flex-1 w-full">
                                <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Background Hero Image</label>
                                <input type="file" name="gallery_image" accept="image/*" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gradient-to-r file:from-aurelis-gold file:to-[#bfa37e] file:text-aurelis-dark hover:file:opacity-90 cursor-pointer">
                            </div>
                        </div>
                    </div>
                    <div class="mt-8">
                        <button type="submit" class="w-full bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark font-bold py-4 rounded-xl shadow-xl hover:brightness-110 transition uppercase tracking-widest text-[10px]">
                            <i class="fa-solid fa-floppy-disk mr-2"></i> Update Konten Hero
                        </button>
                    </div>
                </form>

            <?php elseif ($tab == 'perhiasan'): ?>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <?php if (mysqli_num_rows($items) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($items)): ?>
                            <div class="bg-white/[0.02] border border-white/5 p-4 rounded-2xl group hover:border-aurelis-gold/20 transition flex flex-col justify-between duration-300">
                                <div>
                                    <div class="aspect-square rounded-xl overflow-hidden bg-gray-900 mb-4 border border-white/5">
                                        <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($row['gambar']) ?>" alt="Koleksi Perhiasan" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    </div>
                                    <div class="space-y-1 px-1">
                                        <h4 class="font-bold text-white text-xs uppercase tracking-wide truncate">
                                            <span class="text-gray-500 mr-1 text-[9px]">🇮🇩</span><?= htmlspecialchars($row['nama_produk']) ?>
                                        </h4>
                                        <h5 class="text-aurelis-gold text-[10px] tracking-wide truncate italic flex items-center">
                                            <span class="text-gray-600 mr-1 text-[9px] not-italic">🇺🇸</span><?= htmlspecialchars($row['nama_produk_en'] ?? 'No translation yet') ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-4 pt-3 border-t border-white/5">
                                    <button type="button" class="flex-1 text-center text-[8px] font-bold bg-white/5 py-2.5 rounded-lg hover:bg-aurelis-gold hover:text-aurelis-dark uppercase transition duration-300">Edit</button>
                                    <a href="process/process_delete_jewelry.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus perhiasan ini?')" class="text-center text-[8px] font-bold bg-red-500/10 text-red-400 px-3 py-2.5 rounded-lg hover:bg-red-500 hover:text-white uppercase transition duration-300">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-span-full py-12 text-center bg-white/[0.01] border border-dashed border-white/5 rounded-2xl">
                            <p class="text-xs text-gray-500 italic">Belum ada item perhiasan di dalam katalog galeri.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                    <div class="mt-12 flex justify-center items-center gap-2">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?tab=perhiasan&page=<?= $i ?>" class="w-8 h-8 flex items-center justify-center text-xs font-bold rounded-lg border transition <?= $page == $i ? 'bg-aurelis-gold text-aurelis-dark border-aurelis-gold' : 'bg-white/5 border-white/5 text-gray-400 hover:text-white hover:border-white/20' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>

    <div id="modal-add" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-aurelis-panel border border-white/10 p-6 rounded-2xl w-full max-w-lg shadow-2xl">
            <div class="flex items-center justify-between mb-6 pb-2 border-b border-white/5">
                <h3 class="font-serif-lux text-lg text-white tracking-wide">Tambah Koleksi Perhiasan</h3>
                <button onclick="toggleModal('modal-add')" class="text-gray-500 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form action="process/process_add_jewelry.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-1 block">Nama Perhiasan (ID) 🇮🇩</label>
                    <input type="text" name="nama_produk" required class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                </div>
                <div>
                    <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-1 block">Jewelry Name (EN) 🇺🇸</label>
                    <input type="text" name="nama_produk_en" required class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                </div>
                <div>
                    <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-1 block">Foto Produk Perhiasan</label>
                    <input type="file" name="gambar" accept="image/*" required class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-white/5 file:text-aurelis-gold hover:file:bg-white/10 cursor-pointer">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark font-bold py-3 rounded-xl uppercase tracking-widest text-[10px] mt-4">
                    Simpan Produk Baru
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('hidden');
        }
    </script>
</body>

</html>