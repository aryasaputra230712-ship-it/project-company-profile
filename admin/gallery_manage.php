<?php
include "auth_check.php";

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__));
}

// Logika Base URL
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_path = str_replace('/admin', '', $script_name);
$base_url = $protocol . "://" . $host . ($base_path == '/' ? '' : $base_path);

if (!defined('BASE_URL')) {
    define('BASE_URL', $base_url);
}

include_once ROOTPATH . "/config/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'hero-section';

// Logika Paginasi (Mencegah Lag Akibat Gambar Terlalu Banyak)
$limit = 8;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM galeri_utama");
$total_data  = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);

$header = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM header_galeri WHERE id = 1 LIMIT 1"));
$res_gallery = mysqli_query($conn, "SELECT * FROM galeri_utama ORDER BY id DESC LIMIT $offset, $limit");

include "layouts/sidebar.php";
?>

<main class="flex-1 md:ml-64 p-4 md:p-12 transition-all duration-300 w-full">

    <div class="md:hidden flex items-center justify-between mb-8">
        <button id="open-sidebar" class="text-aurelis-gold p-2 bg-white/5 rounded-xl">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>
        <h2 class="font-serif-lux text-lg text-aurelis-gold tracking-widest uppercase">Aurelis</h2>
    </div>

    <header class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-xl md:text-3xl font-serif-lux text-white mb-1 tracking-wide">Website Gallery Manager</h1>
            <p class="text-gray-500 text-[10px] md:text-xs tracking-wide">Kelola banner hero utama dan katalog koleksi perhiasan [Aurelis Jewelry].</p>
        </div>
        <a href="../gallery.php" target="_blank" class="text-[10px] uppercase tracking-widest text-aurelis-gold border border-aurelis-gold/30 px-6 py-2 rounded-full hover:bg-aurelis-gold hover:text-aurelis-dark transition font-bold">
            Lihat Live Site <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i>
        </a>
    </header>

    <?php if (isset($_SESSION['sukses'])): ?>
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl flex items-center gap-3 text-xs tracking-wider uppercase">
            <i class="fa-solid fa-circle-check text-sm"></i> <?= $_SESSION['sukses'];
                                                                unset($_SESSION['sukses']); ?>
        </div>
    <?php endif; ?>

    <div class="flex gap-4 border-b border-white/10 mb-8 pb-px text-xs uppercase font-bold tracking-widest">
        <button onclick="switchTab('hero-section')" id="btn-hero-section" class="tab-btn pb-3 px-2 <?= $tab == 'hero-section' ? 'border-b-2 border-aurelis-gold text-white' : 'text-gray-500' ?>">⚙️ HERO BANNER</button>
        <button onclick="switchTab('items-section')" id="btn-items-section" class="tab-btn pb-3 px-2 <?= $tab == 'items-section' ? 'border-b-2 border-aurelis-gold text-white' : 'text-gray-500' ?>">💎 MANAJEMEN PERHIASAN</button>
    </div>

    <div id="hero-section" class="tab-content <?= $tab == 'hero-section' ? 'block' : 'hidden' ?>">
        <div class="bg-aurelis-panel border border-white/5 p-6 md:p-8 rounded-[1.5rem] md:rounded-[2.5rem] shadow-2xl">
            <div class="flex items-center gap-3 mb-6 border-b border-white/5 pb-4">
                <i class="fa-solid fa-wand-magic-sparkles text-aurelis-gold text-sm"></i>
                <h3 class="text-xs md:text-sm font-bold uppercase tracking-wider text-white">Konfigurasi Hero Galeri</h3>
            </div>

            <form action="process/process_gallery.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="action" value="update_header">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Judul Utama Hero</label>
                        <input type="text" name="judul" value="<?= htmlspecialchars($header['judul'] ?? '') ?>" required class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Sub-Judul Hero</label>
                        <input type="text" name="subjudul" value="<?= htmlspecialchars($header['subjudul'] ?? '') ?>" required class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                    </div>
                </div>
                <div>
                    <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Background Hero Image</label>
                    <div class="flex flex-col md:flex-row gap-6 items-start md:items-center bg-aurelis-input p-4 rounded-xl border border-white/5">
                        <img src="../assets/imgs/<?= htmlspecialchars($header['gambar'] ?? 'default.jpg') ?>" class="w-40 h-24 object-cover rounded-lg shadow border border-white/10" alt="Current Hero">
                        <div class="flex-1 w-full">
                            <input type="file" name="gambar" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gradient-to-r file:from-aurelis-gold file:to-[#bfa37e] file:text-aurelis-dark hover:file:opacity-90 cursor-pointer">
                        </div>
                    </div>
                </div>
                <button type="submit" class="bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark font-bold py-3.5 px-8 rounded-xl uppercase text-[10px] tracking-widest hover:brightness-110 transition">Update Konten Hero</button>
            </form>
        </div>
    </div>

    <div id="items-section" class="tab-content <?= $tab == 'items-section' ? 'block' : 'hidden' ?>">
        <div id="form-perhiasan-card" class="bg-aurelis-panel border border-white/5 p-6 md:p-8 rounded-[1.5rem] md:rounded-[2.5rem] shadow-2xl mb-10">
            <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
                <div class="flex items-center gap-3">
                    <i id="form-icon" class="fa-solid fa-circle-plus text-aurelis-gold text-sm"></i>
                    <h3 id="form-title" class="text-xs md:text-sm font-bold uppercase tracking-wider text-white">Tambah Koleksi Perhiasan Baru</h3>
                </div>
                <button id="btn-batal-edit" onclick="resetFormToTambah()" class="hidden text-xs text-red-400 hover:underline uppercase tracking-wider">✖ Batal Edit</button>
            </div>

            <form id="form-crud-produk" action="process/process_gallery.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="action" id="form-action-field" value="tambah_item">
                <input type="hidden" name="id_item" id="form-id-field" value="">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Nama Produk (Bahasa Indonesia)</label>
                        <input type="text" name="nama_produk" id="input-nama" required placeholder="Contoh: Cincin Berlian Bunga" class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none">
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-aurelis-gold tracking-widest uppercase mb-2 block">Product Name (English)</label>
                        <input type="text" name="nama_produk_en" id="input-nama-en" required placeholder="Contoh: Diamond Floral Ring" class="w-full bg-aurelis-input border border-aurelis-gold/20 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Harga (Angka Saja)</label>
                        <input type="number" name="harga" id="input-harga" required placeholder="Contoh: 5000000" class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none font-mono">
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Kategori</label>
                        <select name="kategori" id="input-kategori" required class="w-full bg-aurelis-input border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-aurelis-gold/50 outline-none cursor-pointer">
                            <option value="">-- Pilih Kategori --</option>
                            <?php
                            $q_cat = mysqli_query($conn, "SELECT * FROM kategori_galeri ORDER BY nama_kategori ASC");
                            while ($cat = mysqli_fetch_assoc($q_cat)): ?>
                                <option value="<?= $cat['slug'] ?>"><?= $cat['nama_kategori'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-[9px] font-bold text-gray-500 tracking-widest uppercase mb-2 block">Foto / Gambar Perhiasan</label>
                    <div class="flex flex-col md:flex-row gap-6 items-start md:items-center bg-aurelis-input p-4 rounded-xl border border-white/5">
                        <div id="preview-box" class="hidden shrink-0">
                            <img id="img-edit-preview" src="" class="w-24 h-24 object-cover rounded-lg border border-white/10 shadow">
                        </div>
                        <div class="flex-1 w-full">
                            <input type="file" name="gambar" id="input-gambar" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gradient-to-r file:from-aurelis-gold file:to-[#bfa37e] file:text-aurelis-dark hover:file:opacity-90 cursor-pointer">
                        </div>
                    </div>
                </div>

                <button type="submit" id="btn-submit-form" class="bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark font-bold py-3.5 px-8 rounded-xl uppercase text-[10px] tracking-widest shadow-xl hover:brightness-110 transition">Simpan Koleksi Perhiasan</button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php if (mysqli_num_rows($res_gallery) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($res_gallery)): ?>
                    <div class="bg-aurelis-panel border border-white/5 rounded-[1.5rem] overflow-hidden group shadow-md hover:border-aurelis-gold/20 transition-all flex flex-col justify-between">
                        <div class="relative aspect-square overflow-hidden bg-gray-900 border-b border-white/5">
                            <img src="../assets/imgs/<?= htmlspecialchars($row['gambar']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                        </div>
                        <div class="p-5 text-center">
                            <h5 class="text-xs font-bold text-white uppercase truncate"><?= htmlspecialchars($row['nama_produk']) ?></h5>
                            <p class="text-[10px] text-gray-500 italic truncate mt-0.5">EN: <?= htmlspecialchars($row['nama_produk_en'] ?? '-') ?></p>
                            <p class="text-xs text-aurelis-gold font-mono mt-2 mb-4">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>

                            <div class="grid grid-cols-2 gap-2 border-t border-white/5 pt-4">
                                <button type="button" onclick="prepareEditItem('<?= $row['id'] ?>', '<?= htmlspecialchars($row['nama_produk'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['nama_produk_en'] ?? '', ENT_QUOTES) ?>', '<?= $row['harga'] ?>', '<?= $row['kategori'] ?>', '<?= $row['gambar'] ?>')" class="bg-white/5 text-gray-300 hover:bg-aurelis-gold hover:text-aurelis-dark py-2 rounded-lg text-[9px] font-bold uppercase tracking-wider transition-all">⚙️ Edit</button>
                                <a href="process/process_gallery.php?action=hapus&id=<?= $row['id'] ?>" onclick="return confirm('Hapus permanen?')" class="bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white py-2 rounded-lg text-[9px] font-bold uppercase tracking-wider transition-all flex items-center justify-center">🗑️ Hapus</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="mt-12 flex justify-center items-center gap-2 font-mono text-xs">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="gallery_manage.php?tab=items-section&page=<?= $i ?>" class="w-9 h-9 flex items-center justify-center rounded-lg transition font-bold <?= $page == $i ? 'bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark shadow-md' : 'bg-white/5 text-gray-400 hover:text-white' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.replace('block', 'hidden'));
        document.getElementById(tabId).classList.replace('hidden', 'block');
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('border-b-2', 'border-aurelis-gold', 'text-white'));
        document.getElementById('btn-' + tabId).classList.add('border-b-2', 'border-aurelis-gold', 'text-white');
    }

    // Tangkap nama EN dan masukkan ke form edit
    function prepareEditItem(id, nama, nama_en, harga, kategori, gambar) {
        document.getElementById('form-perhiasan-card').scrollIntoView({
            behavior: 'smooth'
        });
        document.getElementById('form-title').innerText = "Edit Detail Perhiasan Eksklusif";
        document.getElementById('form-action-field').value = "edit_item";
        document.getElementById('form-id-field').value = id;
        document.getElementById('btn-batal-edit').classList.remove('hidden');
        document.getElementById('btn-submit-form').innerText = "Simpan Perubahan Perhiasan";

        document.getElementById('input-nama').value = nama;
        document.getElementById('input-nama-en').value = nama_en;
        document.getElementById('input-harga').value = harga;
        document.getElementById('input-kategori').value = kategori;
        document.getElementById('input-gambar').required = false;

        document.getElementById('preview-box').classList.remove('hidden');
        document.getElementById('img-edit-preview').src = "../assets/imgs/" + gambar;
    }

    function resetFormToTambah() {
        document.getElementById('form-title').innerText = "Tambah Koleksi Perhiasan Baru";
        document.getElementById('form-action-field').value = "tambah_item";
        document.getElementById('form-id-field').value = "";
        document.getElementById('btn-batal-edit').classList.add('hidden');
        document.getElementById('btn-submit-form').innerText = "Simpan Koleksi Perhiasan";
        document.getElementById('form-crud-produk').reset();
        document.getElementById('input-gambar').required = true;
        document.getElementById('preview-box').classList.add('hidden');
    }
</script>

<?php include "layouts/footer.php"; ?>