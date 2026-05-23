<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__));
}
include_once ROOTPATH . "/config/config.php";

include 'layouts/header.php';
?>

<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Hero Slider Management</h2>
        <p class="text-sm text-gray-500">Atur teks dan gambar yang muncul di halaman utama website.</p>
    </div>
    <button class="bg-[#050816] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-900 transition shadow-lg">
        <i class="fa-solid fa-plus mr-2"></i> Tambah Slide
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-xs uppercase tracking-wider font-semibold text-gray-500">Judul</th>
                <th class="px-6 py-4 text-xs uppercase tracking-wider font-semibold text-gray-500">Subjudul</th>
                <th class="px-6 py-4 text-xs uppercase tracking-wider font-semibold text-gray-500">Status</th>
                <th class="px-6 py-4 text-xs uppercase tracking-wider font-semibold text-gray-500 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php
            $query = mysqli_query($conn, "SELECT * FROM slide_utama");
            while ($row = mysqli_fetch_assoc($query)):
            ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900"><?= $row['judul'] ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-500 max-w-xs truncate"><?= $row['subjudul'] ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($row['status'] == 'active'): ?>
                            <span class="px-3 py-1 text-[10px] font-bold uppercase bg-green-100 text-green-700 rounded-full">Active</span>
                        <?php else: ?>
                            <span class="px-3 py-1 text-[10px] font-bold uppercase bg-gray-100 text-gray-500 rounded-full">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <a href="edit_slide.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:text-blue-900 text-sm font-semibold">Edit</a>
                        <button class="text-red-400 hover:text-red-600 text-sm font-semibold">Hapus</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'layouts/footer.php'; ?>