<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', __DIR__);
}


include ROOTPATH . "/layouts/header.php";
?>

<div class="mb-8 flex justify-between items-center">
    <div>
        <a href="gallery_manage.php" class="text-gray-400 hover:text-aurelis-gold text-sm transition mb-2 inline-block">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Galeri
        </a>
        <h1 class="text-3xl font-serif tracking-widest uppercase text-white">Edit Perhiasan</h1>
    </div>
</div>

<form action="process_edit_produk.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <div class="lg:col-span-2 space-y-8">

        <div class="glass p-8 rounded-[2rem] border border-white/5 relative overflow-hidden">
            <h2 class="text-aurelis-gold font-bold tracking-widest text-sm mb-6 border-b border-white/10 pb-3">
                <i class="fa-solid fa-file-lines mr-2"></i> INFORMASI UTAMA
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-xs text-gray-400 mb-2">Nama Produk (Indonesia) <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_produk" value="Cincin Perak Klasik" class="w-full bg-[#0a0f26] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-aurelis-gold transition">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-2">Nama Produk (English)</label>
                    <input type="text" name="nama_produk_en" value="Classic Silver Ring" class="w-full bg-[#0a0f26] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-aurelis-gold transition">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-xs text-gray-400 mb-2">Deskripsi (Indonesia)</label>
                <textarea name="deskripsi_id" rows="4" class="w-full bg-[#0a0f26] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-aurelis-gold transition">Tidak ada deskripsi</textarea>
            </div>
            <div>
                <label class="block text-xs text-gray-400 mb-2">Deskripsi (English)</label>
                <textarea name="deskripsi_en" rows="4" class="w-full bg-[#0a0f26] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-aurelis-gold transition"></textarea>
            </div>
        </div>

        <div class="glass p-8 rounded-[2rem] border border-white/5 relative overflow-hidden">
            <h2 class="text-aurelis-gold font-bold tracking-widest text-sm mb-6 border-b border-white/10 pb-3">
                <i class="fa-solid fa-list-check mr-2"></i> SPESIFIKASI PRODUK
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs text-gray-400 mb-2">Tipe (ID / EN)</label>
                    <input type="text" name="tipe_id" placeholder="Misal: Cincin" value="Kayu" class="w-full bg-[#0a0f26] border border-white/10 rounded-xl px-4 py-2 text-white mb-2">
                    <input type="text" name="tipe_en" placeholder="E.g: Ring" class="w-full bg-[#0a0f26] border border-white/10 rounded-xl px-4 py-2 text-white">
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-2">Warna (ID / EN)</label>
                    <input type="text" name="warna_id" placeholder="Misal: Emas" value="Coklat" class="w-full bg-[#0a0f26] border border-white/10 rounded-xl px-4 py-2 text-white mb-2">
                    <input type="text" name="warna_en" placeholder="E.g: Gold" class="w-full bg-[#0a0f26] border border-white/10 rounded-xl px-4 py-2 text-white">
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-2">Berat</label>
                    <input type="text" name="berat" value="300gr" class="w-full bg-[#0a0f26] border border-white/10 rounded-xl px-4 py-2 text-white">
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-8">

        <div class="glass p-6 rounded-[2rem] border border-white/5">
            <h2 class="text-aurelis-gold font-bold tracking-widest text-sm mb-4">PENGATURAN</h2>

            <div class="mb-4">
                <label class="block text-xs text-gray-400 mb-2">Harga (Rp)</label>
                <input type="number" name="harga" value="5000000" class="w-full bg-[#0a0f26] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-aurelis-gold transition">
            </div>

            <div class="mb-4">
                <label class="block text-xs text-gray-400 mb-2">Kategori</label>
                <select name="kategori" class="w-full bg-[#0a0f26] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-aurelis-gold">
                    <option value="Rings" selected>Rings</option>
                    <option value="Necklaces">Necklaces</option>
                    <option value="Earrings">Earrings</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-xs text-gray-400 mb-2">Status Produk</label>
                <select name="status" class="w-full bg-[#0a0f26] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-aurelis-gold">
                    <option value="aktif" selected>Aktif (Tampilkan)</option>
                    <option value="tidak_aktif">Draft (Sembunyikan)</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-aurelis-gold text-black font-bold py-3 rounded-xl hover:bg-yellow-400 transition transform hover:scale-105 shadow-[0_0_15px_rgba(212,175,55,0.4)]">
                <i class="fa-solid fa-floppy-disk mr-2"></i> SIMPAN PERUBAHAN
            </button>
        </div>

        <div class="glass p-6 rounded-[2rem] border border-white/5">
            <h2 class="text-aurelis-gold font-bold tracking-widest text-sm mb-4">MEDIA GAMBAR</h2>

            <div class="mb-6">
                <label class="block text-xs text-gray-400 mb-2">Gambar Utama</label>
                <div class="w-full h-40 bg-black/50 border-2 border-dashed border-white/20 rounded-xl flex flex-col items-center justify-center relative overflow-hidden group">
                    <img src="../assets/imgs/product1.webp" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-30 transition">
                    <div class="z-10 text-center">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-aurelis-gold mb-2"></i>
                        <p class="text-xs text-gray-300">Klik untuk Ganti Gambar</p>
                    </div>
                    <input type="file" name="gambar_utama" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                </div>
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-2">Galeri Tambahan (Maks 4)</label>
                <div class="grid grid-cols-2 gap-3">
                    <div class="h-20 bg-black/50 border border-dashed border-white/20 rounded-lg relative flex items-center justify-center cursor-pointer hover:border-aurelis-gold transition">
                        <i class="fa-solid fa-plus text-gray-500"></i>
                        <input type="file" name="galeri[]" class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                    <div class="h-20 bg-black/50 border border-dashed border-white/20 rounded-lg relative flex items-center justify-center cursor-pointer hover:border-aurelis-gold transition">
                        <i class="fa-solid fa-plus text-gray-500"></i>
                        <input type="file" name="galeri[]" class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                    <div class="h-20 bg-black/50 border border-dashed border-white/20 rounded-lg relative flex items-center justify-center cursor-pointer hover:border-aurelis-gold transition">
                        <i class="fa-solid fa-plus text-gray-500"></i>
                        <input type="file" name="galeri[]" class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                    <div class="h-20 bg-black/50 border border-dashed border-white/20 rounded-lg relative flex items-center justify-center cursor-pointer hover:border-aurelis-gold transition">
                        <i class="fa-solid fa-plus text-gray-500"></i>
                        <input type="file" name="galeri[]" class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>