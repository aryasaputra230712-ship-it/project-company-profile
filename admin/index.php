<?php
// 1. Hubungkan ke konfigurasi database
// Asumsi: file ini berada di dalam folder 'admin', jadi kita naik 1 tingkat untuk ke folder 'config'
include_once "../config/config.php";

// 2. Ambil data dinamis menggunakan SQL COUNT
// Hitung total item di galeri
$q_galeri = mysqli_query($conn, "SELECT COUNT(*) as total FROM galeri_utama");
$r_galeri = mysqli_fetch_assoc($q_galeri);
$total_galeri = $r_galeri['total'];

// Hitung total kategori unik yang aktif di galeri
$q_kategori = mysqli_query($conn, "SELECT COUNT(DISTINCT kategori) as total FROM galeri_utama WHERE status = 'aktif'");
$r_kategori = mysqli_fetch_assoc($q_kategori);
$total_kategori = $r_kategori['total'];

// Hitung keunggulan utama yang aktif
$q_keunggulan = mysqli_query($conn, "SELECT COUNT(*) as total FROM keunggulan_utama WHERE status = 'aktif'");
$r_keunggulan = mysqli_fetch_assoc($q_keunggulan);
$total_keunggulan = $r_keunggulan['total'];

// Hitung slide tentang yang aktif
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
        }

        .glass {
            background: rgba(10, 15, 38, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-active {
            background: linear-gradient(to right, #f7c66b, #bfa37e);
            color: #050816;
        }
    </style>
</head>

<body class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 border-r border-white/5 flex flex-col fixed h-full bg-[#050816] z-50">
        <div class="p-6">
            <img src="../assets/imgs/logo_gold.png" alt="Logo" class="h-8 mb-10 mx-auto md:mx-0 object-contain">
            <nav class="space-y-2">
                <a href="#" class="sidebar-active flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition">
                    <i class="fa-solid fa-table-columns"></i> Dashboard
                </a>
                <a href="gallery_manage.php" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-aurelis-gold rounded-xl transition">
                    <i class="fa-solid fa-images"></i> Kelola Galeri
                </a>
                <a href="keunggulan_manage.php" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-aurelis-gold rounded-xl transition">
                    <i class="fa-solid fa-star"></i> Kelola Keunggulan
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-aurelis-gold rounded-xl transition">
                    <i class="fa-solid fa-gear"></i> Pengaturan
                </a>
            </nav>
        </div>

        <div class="mt-auto p-6 border-t border-white/5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-aurelis-gold flex items-center justify-center text-aurelis-dark font-bold">A</div>
                <div>
                    <p class="text-sm font-bold">Arya Saputra</p>
                    <p class="text-[10px] text-gray-500">Super Admin</p>
                </div>
                <a href="../index.php" class="ml-auto text-gray-500 hover:text-white"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 ml-64 p-8">
        <!-- HEADER -->
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-2xl font-bold">Aurelis Control Panel</h1>
                <p class="text-gray-500 text-sm">Data statistik sinkron langsung dengan database online</p>
            </div>
            <a href="../index.php" target="_blank" class="text-xs text-aurelis-gold border border-aurelis-gold/30 px-4 py-2 rounded-full hover:bg-aurelis-gold hover:text-aurelis-dark transition">
                Lihat Live Site <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i>
            </a>
        </header>

        <!-- STATS CARDS (Warna Sudah Diharmonisasikan) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">

            <!-- Card 1: Total Galeri -->
            <div class="glass p-6 rounded-2xl relative overflow-hidden group hover:border-aurelis-gold/30 transition duration-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-aurelis-gold/10 rounded-lg text-aurelis-gold"><i class="fa-solid fa-images text-lg"></i></div>
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                </div>
                <!-- Mengambil data asli database -->
                <h3 class="text-3xl font-bold text-white"><?= $total_galeri; ?></h3>
                <p class="text-gray-400 text-xs mt-1">Total Foto Galeri</p>
            </div>

            <!-- Card 2: Kategori Aktif -->
            <div class="glass p-6 rounded-2xl relative overflow-hidden group hover:border-aurelis-gold/30 transition duration-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-aurelis-gold/10 rounded-lg text-aurelis-gold"><i class="fa-solid fa-tags text-lg"></i></div>
                </div>
                <!-- Mengambil data asli database -->
                <h3 class="text-3xl font-bold text-white"><?= $total_kategori; ?></h3>
                <p class="text-gray-400 text-xs mt-1">Kategori Produk Aktif</p>
            </div>

            <!-- Card 3: Keunggulan Utama -->
            <div class="glass p-6 rounded-2xl relative overflow-hidden group hover:border-aurelis-gold/30 transition duration-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-aurelis-gold/10 rounded-lg text-aurelis-gold"><i class="fa-solid fa-star text-lg"></i></div>
                </div>
                <!-- Mengambil data asli database -->
                <h3 class="text-3xl font-bold text-white"><?= $total_keunggulan; ?></h3>
                <p class="text-gray-400 text-xs mt-1">Keunggulan Diaktifkan</p>
            </div>

            <!-- Card 4: Slide Konten -->
            <div class="glass p-6 rounded-2xl relative overflow-hidden group hover:border-aurelis-gold/30 transition duration-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-aurelis-gold/10 rounded-lg text-aurelis-gold"><i class="fa-solid fa-sliders text-lg"></i></div>
                </div>
                <!-- Mengambil data asli database -->
                <h3 class="text-3xl font-bold text-white"><?= $total_slide; ?></h3>
                <p class="text-gray-400 text-xs mt-1">Slide 'About' Aktif</p>
            </div>
        </div>

        <!-- SEKSI MANAGEMEN UTAMA -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <h2 class="text-xs font-bold tracking-[0.2em] text-aurelis-gold uppercase">Sistem Navigasi Cepat</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="gallery_manage.php" class="flex items-center gap-4 p-5 glass rounded-2xl hover:bg-white/5 hover:border-aurelis-gold/20 transition text-left group">
                        <div class="p-4 bg-white/5 rounded-xl group-hover:text-aurelis-gold transition"><i class="fa-solid fa-plus"></i></div>
                        <div>
                            <p class="font-bold text-sm">Update Galeri Karya</p>
                            <p class="text-[10px] text-gray-500 mt-1">Unggah foto perhiasan emas terbaru</p>
                        </div>
                    </a>
                    <a href="keunggulan_manage.php" class="flex items-center gap-4 p-5 glass rounded-2xl hover:bg-white/5 hover:border-aurelis-gold/20 transition text-left group">
                        <div class="p-4 bg-white/5 rounded-xl group-hover:text-aurelis-gold transition"><i class="fa-solid fa-pen-to-square"></i></div>
                        <div>
                            <p class="font-bold text-sm">Modifikasi Keunggulan</p>
                            <p class="text-[10px] text-gray-500 mt-1">Sesuaikan 5 pilar brand Aurelis</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="space-y-6">
                <h2 class="text-xs font-bold tracking-[0.2em] text-gray-500 uppercase lg:text-right">Status Keamanan Server</h2>
                <div class="glass p-6 rounded-2xl space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]"></span>
                        <p class="text-sm font-semibold text-white">Database Connected</p>
                    </div>
                    <div class="text-xs text-gray-400 space-y-2 pt-2 border-t border-white/5">
                        <p class="flex justify-between"><span>Host:</span> <span class="text-gray-300 font-mono">Local/Hosting</span></p>
                        <p class="flex justify-between"><span>DB Name:</span> <span class="text-gray-300 font-mono">vibewebs_db</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xs font-bold tracking-[0.2em] text-aurelis-gold uppercase">Produk Terbaru</h2>
                <a href="gallery_manage.php" class="text-xs text-gray-400 hover:text-aurelis-gold flex items-center gap-1 transition">
                    Lihat Semua <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <!-- Grid Layout Responsif sesuai contoh foto -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <?php
                if (mysqli_num_rows($q_produk) > 0) {
                    while ($row = mysqli_fetch_assoc($q_produk)) {
                ?>
                        <!-- Card Produk -->
                        <div class="glass rounded-2xl overflow-hidden group hover:border-aurelis-gold/20 transition duration-300 flex flex-col">
                            <!-- Wadah Foto -->
                            <div class="h-48 overflow-hidden bg-gray-900 relative">
                                <img src="../assets/imgs/gallery/<?= $row['gambar']; ?>" alt="<?= $row['nama_produk']; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>

                            <!-- Detail Produk -->
                            <div class="p-5 flex-1 flex flex-col justify-between bg-[#0b1021]/40">
                                <div>
                                    <div class="flex justify-between items-start gap-2">
                                        <h4 class="font-semibold text-sm text-white line-clamp-1"><?= $row['nama_produk']; ?></h4>
                                        <!-- Logika Ikon Bintang dari gambar contoh -->
                                        <?php if ($row['is_featured'] == 1): ?>
                                            <i class="fa-solid fa-star text-xs text-aurelis-gold mt-1"></i>
                                        <?php endif; ?>
                                    </div>
                                    <!-- Format Rupiah otomatis dari database -->
                                    <p class="text-xs text-aurelis-gold font-mono mt-1">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
                                </div>

                                <!-- Tag Kategori -->
                                <div class="mt-4">
                                    <span class="text-[10px] bg-white/5 border border-white/10 text-gray-400 px-3 py-1 rounded-full capitalize">
                                        <?= $row['kategori']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    // Jika database masih kosong
                    echo '<div class="col-span-4 p-8 glass text-center rounded-2xl text-gray-500 text-sm">Belum ada data produk.</div>';
                }
                ?>
            </div>
        </div>
    </main>
</body>

</html>