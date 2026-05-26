<?php
// 1. TAMBAHKAN KEAMANAN (WAJIB DI BARIS PALING ATAS)
include "auth_check.php";

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__));
}
include_once ROOTPATH . "/config/config.php";

// Logika Base URL tetap ada untuk keperluan CSS/Assets
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_path = str_replace('/admin', '', $script_name);
$base_url = $protocol . "://" . $host . ($base_path == '/' ? '' : $base_path);
if (!defined('BASE_URL')) define('BASE_URL', $base_url);

// Ambil data pengaturan untuk ditampilkan di form
$query = mysqli_query($conn, "SELECT * FROM pengaturan LIMIT 1");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Aurelis Admin | Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #050816;
            color: #f7f7f7;
            font-family: 'Poppins', sans-serif;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body class="flex min-h-screen">

    <?php include "layouts/sidebar.php"; ?>

    <main class="flex-1 md:ml-64 transition-all duration-300 w-full">
        <div class="md:hidden flex items-center justify-between p-4 bg-[#0c0e17] border-b border-white/5 sticky top-0 z-30">
            <img src="<?= BASE_URL ?>/assets/imgs/logo_gold.png" class="h-6">
            <button id="open-sidebar" class="text-aurelis-gold p-2"><i class="fa-solid fa-bars-staggered"></i></button>
        </div>

        <div class="p-6 md:p-12">
            <div class="mb-8">
                <h1 class="text-2xl font-serif tracking-widest uppercase">General Settings</h1>
            </div>

            <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 text-green-400 text-xs rounded-xl">
                    <i class="fa-solid fa-check-circle mr-2"></i> Profil perusahaan berhasil diperbarui!
                </div>
            <?php endif; ?>

            <form action="process/process_setting.php" method="POST" class="max-w-5xl space-y-6">
                <input type="hidden" name="id" value="<?= $data['id'] ?>">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-[#0c0e17] border border-white/5 p-6 rounded-[2rem] space-y-5">
                        <label class="text-[9px] font-bold text-gray-500 tracking-widest block uppercase">Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan" value="<?= $data['nama_perusahaan'] ?>" class="w-full bg-[#161925] border border-white/5 rounded-xl px-4 py-3 text-sm text-white outline-none">

                        <label class="text-[9px] font-bold text-gray-500 tracking-widest block uppercase">WhatsApp</label>
                        <input type="text" name="whatsapp" value="<?= $data['whatsapp'] ?>" class="w-full bg-[#161925] border border-white/5 rounded-xl px-4 py-3 text-sm text-white outline-none">
                    </div>

                    <div class="bg-[#0c0e17] border border-white/5 p-6 rounded-[2rem] space-y-5">
                        <label class="text-[9px] font-bold text-gray-500 tracking-widest block uppercase">Email</label>
                        <input type="email" name="email" value="<?= $data['email'] ?>" class="w-full bg-[#161925] border border-white/5 rounded-xl px-4 py-3 text-sm text-white outline-none">

                        <label class="text-[9px] font-bold text-gray-500 tracking-widest block uppercase">Instagram</label>
                        <input type="text" name="instagram" value="<?= $data['instagram'] ?>" class="w-full bg-[#161925] border border-white/5 rounded-xl px-4 py-3 text-sm text-white outline-none">
                    </div>

                    <div class="lg:col-span-2">
                        <div class="bg-[#0c0e17] border border-white/5 p-6 rounded-[2rem]">
                            <label class="text-[9px] font-bold text-gray-500 tracking-widest block uppercase">Alamat</label>
                            <textarea name="alamat" rows="3" class="w-full bg-[#161925] border border-white/5 rounded-xl px-4 py-3 text-sm text-white outline-none"><?= $data['alamat'] ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-gradient-to-r from-[#f7c66b] to-[#bfa37e] text-[#050816] font-bold px-12 py-4 rounded-xl uppercase text-[10px] tracking-[2px] shadow-xl">
                        Simpan Profil
                    </button>
                </div>
            </form>
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
        }

        if (openBtn) openBtn.addEventListener('click', toggleSidebar);
        if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        if (overlay) overlay.addEventListener('click', toggleSidebar);
    </script>
</body>

</html>