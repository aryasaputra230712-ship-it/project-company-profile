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

    // Ambil data dengan LIMIT (MENGGUNAKAN LEFT JOIN)12
    $query_items = "SELECT g.*, s.tipe_spesifikasi_id, s.tipe_spesifikasi_en, s.warna_id, s.warna_en, s.berat 
                FROM galeri_utama g 
                LEFT JOIN spesifikasi_produk s ON g.id = s.id_galeri 
                ORDER BY g.id DESC LIMIT $start, $limit";
    $items = mysqli_query($conn, $query_items);
} elseif ($tab == 'gambar') {
    // 🎨 Kita set limit 12 agar grid gambar terlihat penuh dan rapi di layar
    $limit = 12;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($page > 1) ? ($page * $limit) - $limit : 0;

    // 📊 Hitung total gambar detail untuk sistem Paginasi
    $total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM gambar_detail_produk");
    $total_data = mysqli_fetch_assoc($total_query)['total'];
    $total_pages = ceil($total_data / $limit);

    // 🔗 TEKNIK JOIN: Mengambil gambar dari 'gambar_detail_produk' 
    // SEKALIGUS mencocokkan 'nama_produk' dari tabel 'galeri_utama'
    $query_detail = "SELECT g.id, g.gambar, u.nama_produk 
                     FROM gambar_detail_produk g 
                     JOIN galeri_utama u ON g.id_galeri = u.id 
                     ORDER BY g.id DESC LIMIT $start, $limit";
    $items_gambar = mysqli_query($conn, $query_detail);

    // 📦 Ambil daftar produk (ID & Nama) untuk pilihan di Dropdown Form Upload
    $produk_list = mysqli_query($conn, "SELECT id, nama_produk FROM galeri_utama ORDER BY id DESC");
}

$kategori_list = mysqli_query($conn, "SELECT * FROM kategori_galeri ORDER BY nama_kategori ASC");

function gallery_edit_payload(array $data): string
{
    return htmlspecialchars(
        json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE),
        ENT_QUOTES,
        'UTF-8'
    );
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

        <div class="flex gap-3 p-1 bg-white/5 rounded-xl mb-8 border border-white/5 w-fit">
            <a href="?tab=hero" class="px-5 py-2.5 rounded-lg text-[10px] font-bold tracking-widest transition flex items-center gap-2 <?= $tab == 'hero' ? 'active-tab' : 'text-gray-500 hover:text-white' ?>">
                <i class="fa-solid fa-wand-magic-sparkles"></i> HERO BANNER
            </a>
            <a href="?tab=perhiasan" class="px-5 py-2.5 rounded-lg text-[10px] font-bold tracking-widest transition flex items-center gap-2 <?= $tab == 'perhiasan' ? 'active-tab' : 'text-gray-500 hover:text-white' ?>">
                <i class="fa-solid fa-gem"></i> MANAJEMEN PERHIASAN
            </a>
            <a href="?tab=gambar" class="px-5 py-2.5 rounded-lg text-[10px] font-bold tracking-widest transition flex items-center gap-2 <?= $tab == 'gambar' ? 'active-tab' : 'text-gray-500 hover:text-white' ?>">
                <i class="fa-solid fa-image"></i> GAMBAR DETAIL PRODUK
            </a>
            <a href="?tab=kategori" class="px-5 py-2.5 rounded-lg text-[10px] font-bold tracking-widest transition flex items-center gap-2 <?= $tab == 'kategori' ? 'active-tab' : 'text-gray-500 hover:text-white' ?>">
                <i class="fa-solid fa-tags"></i> KATEGORI
            </a>
        </div>

        <div class="bg-aurelis-panel border border-white/5 rounded-[1.5rem] md:rounded-[2.5rem] p-5 md:p-10 shadow-2xl">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-aurelis-gold/10 border border-aurelis-gold/20 rounded-xl flex items-center justify-center text-aurelis-gold">
                        <i class="fa-solid <?= $tab == 'hero' ? 'fa-wand-magic-sparkles' : 'fa-gem' ?>"></i>
                    </div>
                    <h2 class="text-xs md:text-base text-white font-bold uppercase tracking-widest">
                        Konfigurasi <?= $tab == 'hero' ? 'Hero Galeri' : ($tab == 'perhiasan' ? 'Koleksi Perhiasan' : ($tab == 'kategori' ? 'Kategori Produk' : 'Gambar Detail')) ?>

                    </h2>
                </div>

                <?php if ($tab == 'perhiasan'): ?>
                    <button type="button" onclick="toggleModal('modal-add')" class="bg-white/5 border border-white/10 px-3 py-2 rounded-lg text-[9px] font-bold text-aurelis-gold hover:bg-aurelis-gold hover:text-aurelis-dark transition uppercase tracking-wider">
                        + Tambah Perhiasan
                    </button>
                <?php elseif ($tab == 'kategori'): ?>
                    <button type="button" onclick="toggleModal('modal-add-kategori')" class="bg-white/5 border border-white/10 px-3 py-2 rounded-lg text-[9px] font-bold text-aurelis-gold hover:bg-aurelis-gold hover:text-aurelis-dark transition uppercase tracking-wider">
                        + Tambah Kategori
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
                                    <button type="button"
                                        class="btn-edit-jewelry flex-1 text-center text-[8px] font-bold bg-white/5 py-2.5 rounded-lg hover:bg-aurelis-gold hover:text-aurelis-dark uppercase transition duration-300"
                                        data-edit="<?= gallery_edit_payload([
                                                        'id' => $row['id'],
                                                        'nama_produk' => $row['nama_produk'],
                                                        'nama_produk_en' => $row['nama_produk_en'] ?? '',
                                                        'harga' => $row['harga'] ?? 0,
                                                        'kategori' => $row['kategori'] ?? '',
                                                        'deskripsi_id' => $row['deskripsi_id'] ?? '',
                                                        'deskripsi_en' => $row['deskripsi_en'] ?? '',
                                                        'tipe_spesifikasi_id' => $row['tipe_spesifikasi_id'] ?? '',
                                                        'tipe_spesifikasi_en' => $row['tipe_spesifikasi_en'] ?? '',
                                                        'warna_id' => $row['warna_id'] ?? '',
                                                        'warna_en' => $row['warna_en'] ?? '',
                                                        'berat' => $row['berat'] ?? ''
                                                    ]) ?>">Edit</button>
                                    <a href="process/process_gallery.php?action=hapus&id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus perhiasan ini?')" class="text-center text-[8px] font-bold bg-red-500/10 text-red-400 px-3 py-2.5 rounded-lg hover:bg-red-500 hover:text-white uppercase transition duration-300">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </a>
                                </div>

                                <div class="border-t border-white/10 pt-4 mt-4">
                                    <h4 class="text-[10px] text-aurelis-gold font-bold uppercase tracking-widest mb-2">Spesifikasi Produk</h4>
                                    <div class="grid grid-cols-2 gap-2 text-[9px] text-gray-400">
                                        <div>
                                            <span class="block font-bold text-gray-500 mb-0.5">Tipe:</span>
                                            <span class="text-white"><?= htmlspecialchars($row['tipe_spesifikasi_id'] ?? '-') ?></span>
                                        </div>
                                        <div>
                                            <span class="block font-bold text-gray-500 mb-0.5">Warna:</span>
                                            <span class="text-white"><?= htmlspecialchars($row['warna_id'] ?? '-') ?></span>
                                        </div>
                                        <div class="col-span-2">
                                            <span class="block font-bold text-gray-500 mb-0.5">Berat:</span>
                                            <span class="text-white"><?= htmlspecialchars($row['berat'] ?? '-') ?></span>
                                        </div>
                                    </div>
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
            <?php elseif ($tab == 'gambar'): ?>

                <div class="bg-white/[0.02] border border-white/5 p-6 rounded-2xl mb-8 shadow-inner">
                    <h3 class="text-sm font-bold text-aurelis-gold tracking-widest uppercase mb-4 border-b border-white/5 pb-2">
                        <i class="fa-solid fa-images mr-2"></i> Tambah Gambar Detail
                    </h3>

                    <form action="process/process_gambar_detail.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                        <input type="hidden" name="action" value="tambah_gambar">

                        <div class="md:col-span-1">
                            <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-2 block">1. Pilih Milik Produk Apa?</label>
                            <select name="id_galeri" required class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                                <option value="" disabled selected>-- Pilih Perhiasan --</option>
                                <?php
                                if (isset($produk_list)) {
                                    mysqli_data_seek($produk_list, 0);
                                    while ($prod = mysqli_fetch_assoc($produk_list)):
                                ?>
                                        <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nama_produk']) ?></option>
                                <?php
                                    endwhile;
                                }
                                ?>
                            </select>
                        </div>

                        <div class="md:col-span-1">
                            <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-2 block">2. Pilih Gambar (Bisa Multiple)</label>
                            <input type="file" name="gambar_detail[]" accept="image/*" multiple required class="w-full text-xs text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-white/5 file:text-aurelis-gold hover:file:bg-white/10 cursor-pointer border border-white/5 rounded-xl bg-aurelis-input">
                        </div>

                        <div class="md:col-span-1">
                            <button type="submit" class="w-full bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark font-bold py-3 rounded-xl uppercase tracking-widest text-[10px] hover:brightness-110 transition shadow-lg">
                                <i class="fa-solid fa-cloud-arrow-up mr-1"></i> Upload Gambar
                            </button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <?php if (isset($items_gambar) && mysqli_num_rows($items_gambar) > 0): ?>
                        <?php while ($row_img = mysqli_fetch_assoc($items_gambar)): ?>
                            <div class="bg-white/[0.02] border border-white/5 p-2 rounded-xl group relative overflow-hidden flex flex-col justify-between h-full">

                                <div class="aspect-square rounded-lg overflow-hidden bg-gray-900 mb-2 relative">
                                    <img src="<?= BASE_URL ?>/assets/imgs/<?= htmlspecialchars($row_img['gambar']) ?>" alt="Detail" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">

                                    <div class="absolute bottom-0 left-0 right-0 bg-black/80 backdrop-blur-md p-2 translate-y-full group-hover:translate-y-0 transition duration-300">
                                        <p class="text-[9px] text-aurelis-gold text-center font-bold tracking-wider truncate">
                                            <?= htmlspecialchars($row_img['nama_produk']) ?>
                                        </p>
                                    </div>
                                </div>

                                <a href="process/process_gambar_detail.php?action=hapus&id=<?= $row_img['id'] ?>" onclick="return confirm('Yakin ingin menghapus gambar detail ini?')" class="block text-center text-[9px] font-bold bg-red-500/10 text-red-400 py-2 rounded-lg hover:bg-red-500 hover:text-white uppercase transition duration-300 mt-auto">
                                    <i class="fa-regular fa-trash-can mr-1"></i> Hapus
                                </a>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-span-full py-12 text-center bg-white/[0.01] border border-dashed border-white/5 rounded-2xl">
                            <div class="text-gray-600 mb-2 text-3xl"><i class="fa-regular fa-image"></i></div>
                            <p class="text-xs text-gray-500 italic">Belum ada gambar detail yang diunggah untuk produk manapun.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (isset($total_pages) && $total_pages > 1): ?>
                    <div class="mt-8 flex justify-center items-center gap-2">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?tab=gambar&page=<?= $i ?>" class="w-8 h-8 flex items-center justify-center text-xs font-bold rounded-lg border transition <?= $page == $i ? 'bg-aurelis-gold text-aurelis-dark border-aurelis-gold' : 'bg-white/5 border-white/5 text-gray-400 hover:text-white hover:border-white/20' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            <?php elseif ($tab == 'kategori'): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php
                    mysqli_data_seek($kategori_list, 0); // Reset pointer
                    if (mysqli_num_rows($kategori_list) > 0):
                        while ($kat = mysqli_fetch_assoc($kategori_list)):
                    ?>
                            <div class="bg-white/[0.02] border border-white/5 p-5 rounded-2xl flex items-center justify-between group hover:border-aurelis-gold/20 transition duration-300">
                                <div>
                                    <h4 class="font-bold text-white text-sm uppercase tracking-wider mb-1"><?= htmlspecialchars($kat['nama_kategori']) ?></h4>
                                    <span class="text-[9px] text-gray-500 font-mono bg-black/50 px-2 py-1 rounded">Slug: /<?= htmlspecialchars($kat['slug']) ?></span>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" onclick="openEditKategori(<?= htmlspecialchars(json_encode($kat)) ?>)" class="text-[10px] font-bold text-aurelis-gold bg-aurelis-gold/10 px-3 py-2 rounded-lg hover:bg-aurelis-gold hover:text-aurelis-dark uppercase transition">
                                        Edit
                                    </button>
                                    <a href="process/process_gallery.php?action=hapus_kategori&id=<?= $kat['id'] ?>" onclick="return confirm('Yakin ingin menghapus kategori ini?')" class="text-[10px] font-bold text-red-400 bg-red-500/10 px-3 py-2 rounded-lg hover:bg-red-500 hover:text-white uppercase transition">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <div class="col-span-full py-8 text-center border border-dashed border-white/5 rounded-2xl">
                            <p class="text-xs text-gray-500 italic">Belum ada kategori terdaftar.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <div id="modal-add" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-aurelis-panel border border-white/10 p-6 rounded-2xl w-full max-w-lg shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6 pb-2 border-b border-white/5">
                <h3 class="font-serif-lux text-lg text-white tracking-wide">Tambah Koleksi Perhiasan</h3>
                <button type="button" onclick="toggleModal('modal-add')" class="text-gray-500 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form action="process/process_gallery.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="tambah_item">
                <div>
                    <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-1 block">Nama Perhiasan (ID) 🇮🇩</label>
                    <input type="text" name="nama_produk" required class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                </div>
                <div>
                    <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-1 block">Jewelry Name (EN) 🇺🇸</label>
                    <input type="text" name="nama_produk_en" required class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-1 block">Harga (Rp)</label>
                        <input type="number" name="harga" min="0" required placeholder="5000000" class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-1 block">Kategori</label>
                        <select name="kategori" required class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                            <?php mysqli_data_seek($kategori_list, 0);
                            while ($kat = mysqli_fetch_assoc($kategori_list)): ?>
                                <option value="<?= htmlspecialchars($kat['slug']) ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="border-t border-white/5 pt-4 mt-4">
                    <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-1 block">Deskripsi (ID) 🇮🇩</label>
                    <textarea name="deskripsi_id" rows="2" class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none"></textarea>
                </div>
                <div class="mt-2">
                    <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-1 block">Description (EN) 🇺🇸</label>
                    <textarea name="deskripsi_en" rows="2" class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none"></textarea>
                </div>

                <div class="border-t border-white/5 pt-4 mt-4">
                    <h4 class="text-[9px] font-bold text-aurelis-gold uppercase tracking-widest mb-3">Spesifikasi Produk</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Tipe (ID / EN)</label>
                            <input type="text" name="tipe_spesifikasi_id" placeholder="ID" class="w-full bg-aurelis-input border border-white/5 rounded-lg px-3 py-2 text-sm text-white mb-2">
                            <input type="text" name="tipe_spesifikasi_en" placeholder="EN" class="w-full bg-aurelis-input border border-white/5 rounded-lg px-3 py-2 text-sm text-white">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Warna (ID / EN)</label>
                            <input type="text" name="warna_id" placeholder="ID" class="w-full bg-aurelis-input border border-white/5 rounded-lg px-3 py-2 text-sm text-white mb-2">
                            <input type="text" name="warna_en" placeholder="EN" class="w-full bg-aurelis-input border border-white/5 rounded-lg px-3 py-2 text-sm text-white">
                        </div>
                        <div class="col-span-2">
                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Berat (gr)</label>
                            <input type="text" name="berat" placeholder="Contoh: 5.5gr" class="w-full bg-aurelis-input border border-white/5 rounded-lg px-3 py-2 text-sm text-white">
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/5 pt-4 mt-4">
                    <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-1 block">Foto Produk Perhiasan</label>
                    <input type="file" name="gambar" accept="image/*" required class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-white/5 file:text-aurelis-gold hover:file:bg-white/10 cursor-pointer">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark font-bold py-3 rounded-xl uppercase tracking-widest text-[10px] mt-4">
                    Simpan Produk Baru
                </button>
            </form>
        </div>
    </div>

    <div id="modal-add-kategori" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-aurelis-panel border border-white/10 p-6 rounded-2xl w-full max-w-sm shadow-2xl relative">
            <div class="flex items-center justify-between mb-6 pb-2 border-b border-white/5">
                <h3 class="font-serif-lux text-lg text-white tracking-wide">Tambah Kategori</h3>
                <button type="button" onclick="toggleModal('modal-add-kategori')" class="text-gray-500 hover:text-white bg-aurelis-input/50 px-3 py-1 rounded-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="process/process_gallery.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="tambah_kategori">
                <div>
                    <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-1 block">Nama Kategori</label>
                    <input type="text" name="nama_kategori" required placeholder="Contoh: Bracelets" class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark font-bold py-3 rounded-xl uppercase tracking-widest text-[10px]">Simpan Kategori</button>
            </form>
        </div>
    </div>

    <div id="modal-edit-kategori" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-aurelis-panel border border-white/10 p-6 rounded-2xl w-full max-w-sm shadow-2xl relative">
            <div class="flex items-center justify-between mb-6 pb-2 border-b border-white/5">
                <h3 class="font-serif-lux text-lg text-white tracking-wide">Edit Kategori</h3>
                <button type="button" onclick="toggleModal('modal-edit-kategori')" class="text-gray-500 hover:text-white bg-aurelis-input/50 px-3 py-1 rounded-lg"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="process/process_gallery.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="edit_kategori">
                <input type="hidden" name="id_kategori" id="edit-kat-id">
                <div>
                    <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-1 block">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="edit-kat-nama" required class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark font-bold py-3 rounded-xl uppercase tracking-widest text-[10px]">Update Kategori</button>
            </form>
        </div>
    </div>

    <div id="modal-edit" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-aurelis-panel border border-white/10 p-6 rounded-2xl w-full max-w-lg shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6 pb-2 border-b border-white/5">
                <h3 class="font-serif-lux text-lg text-white tracking-wide">Edit Koleksi Perhiasan</h3>
                <button type="button" onclick="toggleModal('modal-edit')" class="text-gray-500 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form action="process/process_gallery.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="edit_item">
                <input type="hidden" name="id_item" id="edit-id">

                <div>
                    <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-1 block">Nama Perhiasan (ID) 🇮🇩</label>
                    <input type="text" name="nama_produk" id="edit-nama" required class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                </div>
                <div>
                    <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-1 block">Jewelry Name (EN) 🇺🇸</label>
                    <input type="text" name="nama_produk_en" id="edit-nama-en" required class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-1 block">Harga (Rp)</label>
                        <input type="number" name="harga" id="edit-harga" min="0" required class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-1 block">Kategori</label>
                        <select name="kategori" id="edit-kategori" required class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                            <?php mysqli_data_seek($kategori_list, 0);
                            while ($kat = mysqli_fetch_assoc($kategori_list)): ?>
                                <option value="<?= htmlspecialchars($kat['slug']) ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="border-t border-white/5 pt-4 mt-4">
                    <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-1 block">Deskripsi (ID) 🇮🇩</label>
                    <textarea name="deskripsi_id" id="edit-deskripsi-id" rows="2" class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none"></textarea>
                </div>
                <div class="mt-2">
                    <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-1 block">Description (EN) 🇺🇸</label>
                    <textarea name="deskripsi_en" id="edit-deskripsi-en" rows="2" class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-2.5 text-sm text-white focus:border-aurelis-gold/50 outline-none"></textarea>
                </div>

                <div class="border-t border-white/5 pt-4 mt-4">
                    <h4 class="text-[9px] font-bold text-aurelis-gold uppercase tracking-widest mb-3">Spesifikasi Produk</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Tipe (ID / EN)</label>
                            <input type="text" name="tipe_spesifikasi_id" id="edit-tipe-id" placeholder="ID" class="w-full bg-aurelis-input border border-white/5 rounded-lg px-3 py-2 text-sm text-white mb-2">
                            <input type="text" name="tipe_spesifikasi_en" id="edit-tipe-en" placeholder="EN" class="w-full bg-aurelis-input border border-white/5 rounded-lg px-3 py-2 text-sm text-white">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Warna (ID / EN)</label>
                            <input type="text" name="warna_id" id="edit-warna-id" placeholder="ID" class="w-full bg-aurelis-input border border-white/5 rounded-lg px-3 py-2 text-sm text-white mb-2">
                            <input type="text" name="warna_en" id="edit-warna-en" placeholder="EN" class="w-full bg-aurelis-input border border-white/5 rounded-lg px-3 py-2 text-sm text-white">
                        </div>
                        <div class="col-span-2">
                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Berat (gr)</label>
                            <input type="text" name="berat" id="edit-berat" placeholder="Contoh: 5.5gr" class="w-full bg-aurelis-input border border-white/5 rounded-lg px-3 py-2 text-sm text-white">
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/5 pt-4 mt-4">
                    <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-1 block">Ganti Foto (opsional)</label>
                    <input type="file" name="gambar" accept="image/*" class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-white/5 file:text-aurelis-gold hover:file:bg-white/10 cursor-pointer">
                    <p class="text-[9px] text-gray-500 italic mt-1">Biarkan kosong jika tidak ingin mengganti gambar.</p>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark font-bold py-3 rounded-xl uppercase tracking-widest text-[10px] mt-4">
                    Update Perubahan
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.toggle('hidden');
        }

        function showModal(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.remove('hidden');
        }

        function setFieldValue(id, value) {
            const el = document.getElementById(id);
            if (el) el.value = value ?? '';
        }

        function setKategoriValue(slug) {
            const select = document.getElementById('edit-kategori');
            if (!select) return;
            const normalized = (slug || '').toLowerCase();
            let matched = false;
            for (const option of select.options) {
                if (option.value.toLowerCase() === normalized) {
                    option.selected = true;
                    matched = true;
                    break;
                }
            }
            if (!matched && select.options.length > 0) {
                select.selectedIndex = 0;
            }
        }
        //test

        function openEditJewelry(data) {
            // Data Dasar
            setFieldValue('edit-id', data.id);
            setFieldValue('edit-nama', data.nama_produk);
            setFieldValue('edit-nama-en', data.nama_produk_en);
            setFieldValue('edit-harga', data.harga);

            // Data Deskripsi
            setFieldValue('edit-deskripsi-id', data.deskripsi_id);
            setFieldValue('edit-deskripsi-en', data.deskripsi_en);

            // Data Spesifikasi
            setFieldValue('edit-tipe-id', data.tipe_spesifikasi_id);
            setFieldValue('edit-tipe-en', data.tipe_spesifikasi_en);
            setFieldValue('edit-warna-id', data.warna_id);
            setFieldValue('edit-warna-en', data.warna_en);
            setFieldValue('edit-berat', data.berat);

            setKategoriValue(data.kategori);
            showModal('modal-edit');
        }

        document.querySelectorAll('.btn-edit-jewelry').forEach((btn) => {
            btn.addEventListener('click', () => {
                try {
                    const data = JSON.parse(btn.dataset.edit || '{}');
                    openEditJewelry(data);
                } catch (err) {
                    console.error('Gagal membuka form edit:', err);
                    alert('Gagal membuka form edit. Muat ulang halaman lalu coba lagi.');
                }
            });
        });

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

        function openEditKategori(data) {
            document.getElementById('edit-kat-id').value = data.id;
            document.getElementById('edit-kat-nama').value = data.nama_kategori;
            showModal('modal-edit-kategori');
        }
    </script>
</body>

</html>