<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden transition-opacity duration-300"></div>

<aside id="sidebar-main" class="w-64 border-r border-white/5 flex flex-col fixed h-full bg-[#050816] z-50 transition-transform duration-300 transform -translate-x-full md:translate-x-0">
    <div class="p-6">
        <div class="flex items-center justify-between mb-10">
            <a href="<?= BASE_URL ?>/index.php">
                <img src="<?= BASE_URL ?>/assets/imgs/logo_gold.png" class="h-8 mx-auto md:mx-0 object-contain">
            </a>
            <button id="close-sidebar" class="md:hidden text-gray-400 hover:text-white transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <nav class="space-y-2">
            <a href="index.php"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($current_page == 'index.php') ? 'bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark font-bold shadow-lg' : 'text-gray-400 hover:text-aurelis-gold' ?>">
                <i class="fa-solid fa-chart-line"></i> Dashboard
            </a>

            <a href="content_manage.php"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($current_page == 'content_manage.php' || $current_page == 'content_management.php') ? 'bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark font-bold shadow-lg' : 'text-gray-400 hover:text-aurelis-gold' ?>">
                <i class="fa-solid fa-pen-to-square"></i> Kelola Konten
            </a>

            <a href="gallery_manage.php"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($current_page == 'gallery_manage.php') ? 'bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark font-bold shadow-lg' : 'text-gray-400 hover:text-aurelis-gold' ?>">
                <i class="fa-solid fa-images"></i> Galeri Produk
            </a>

            <a href="setting.php"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($current_page == 'setting.php') ? 'bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark font-bold shadow-lg' : 'text-gray-400 hover:text-aurelis-gold' ?>">
                <i class="fa-solid fa-gear"></i> Pengaturan Umum
            </a>
        </nav>
    </div>

    <div class="mt-auto p-6 border-t border-white/5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-aurelis-gold flex items-center justify-center text-aurelis-dark font-bold shadow-inner">A</div>
            <div>
                <p class="text-sm font-bold text-white">Arya Saputra</p>
                <p class="text-[10px] text-gray-500 italic uppercase tracking-wider text-right">Super Admin</p>
            </div>
        </div>
    </div>
    <div class="p-6 border-t border-white/5">
        <a href="process/logout.php"
            onclick="return confirm('Apakah Anda yakin ingin keluar?')"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 transition duration-300">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="text-sm font-bold uppercase tracking-widest">Logout</span>
        </a>
    </div>
</aside>