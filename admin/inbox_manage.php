<?php
include "auth_check.php";

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__));
}

// Base URL Logic
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

// ==========================================
// PAGINATION LOGIC
// ==========================================
$limit = 5;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$total_data  = 0;
$total_pages = 0;
$res_inbox   = false;

$check_table = mysqli_query($conn, "SHOW TABLES LIKE 'pesan_masuk'");
if (mysqli_num_rows($check_table) > 0) {
    $total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pesan_masuk");
    $total_data  = mysqli_fetch_assoc($total_query)['total'] ?? 0;
    $total_pages = ceil($total_data / $limit);

    $res_inbox = mysqli_query($conn, "SELECT * FROM pesan_masuk ORDER BY id DESC LIMIT $offset, $limit");
}

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
            <h1 class="text-xl md:text-3xl font-serif-lux text-white mb-1 tracking-wide">Customer Messages</h1>
            <p class="text-gray-500 text-[10px] md:text-xs tracking-wide">Manage incoming inquiries, feedback, suggestions, and collaboration requests from Aurelis Jewelry clients.</p>
        </div>
        <div class="bg-white/5 border border-white/5 rounded-xl px-4 py-2 text-xs font-mono text-gray-400">
            Total: <span class="text-aurelis-gold font-bold"><?= $total_data; ?></span> Messages
        </div>
    </header>

    <?php if (isset($_SESSION['sukses'])): ?>
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl flex items-center gap-3 text-xs tracking-wider uppercase font-bold">
            <i class="fa-solid fa-circle-check text-sm"></i> <?= $_SESSION['sukses'];
                                                                unset($_SESSION['sukses']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl flex items-center gap-3 text-xs tracking-wider uppercase font-bold">
            <i class="fa-solid fa-circle-xmark text-sm"></i> <?= $_SESSION['error'];
                                                                unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-aurelis-panel border border-white/5 p-6 md:p-8 rounded-[1.5rem] md:rounded-[2.5rem] shadow-2xl">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/5">
            <div class="w-8 h-8 bg-aurelis-gold/10 border border-aurelis-gold/20 rounded-lg flex items-center justify-center text-aurelis-gold">
                <i class="fa-solid fa-envelope-open text-xs"></i>
            </div>
            <h3 class="text-xs md:text-sm font-bold uppercase tracking-wider text-white">Inbox</h3>
        </div>

        <div class="space-y-4">
            <?php if ($res_inbox && mysqli_num_rows($res_inbox) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($res_inbox)):
                    $tanggal_formatted = date('d M Y | H:i', strtotime($row['tanggal']));
                ?>
                    <div class="bg-[#161925]/40 border border-white/5 p-5 rounded-2xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4 group hover:border-aurelis-gold/20 transition duration-300">
                        <div class="flex-1 min-w-0 space-y-1.5">
                            <div class="flex flex-wrap items-center gap-2">
                                <h4 class="font-bold text-white text-sm tracking-wide truncate uppercase"><?= htmlspecialchars($row['nama']) ?></h4>
                                <span class="text-[9px] bg-white/5 text-gray-500 border border-white/10 px-2.5 py-0.5 rounded-full font-mono"><?= $tanggal_formatted; ?></span>
                            </div>
                            <p class="text-xs text-aurelis-gold font-mono truncate"><?= htmlspecialchars($row['email']) ?></p>
                            <p class="text-xs text-gray-400 line-clamp-1 leading-relaxed pt-1"><strong class="text-gray-500">Subject:</strong> <?= htmlspecialchars($row['subjek'] ?? 'No Subject') ?> — <?= htmlspecialchars($row['pesan']) ?></p>
                        </div>

                        <div class="flex items-center gap-2 w-full md:w-auto shrink-0 border-t border-white/5 md:border-0 pt-3 md:pt-0">
                            <button type="button"
                                onclick="openMessageModal('<?= htmlspecialchars($row['nama'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['email'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['subjek'] ?? 'No Subject', ENT_QUOTES) ?>', '<?= htmlspecialchars(json_encode($row['pesan']), ENT_QUOTES) ?>', '<?= $tanggal_formatted; ?>')"
                                class="flex-1 md:flex-none text-center text-[9px] font-bold bg-white/5 px-4 py-2.5 rounded-lg text-gray-300 hover:bg-aurelis-gold hover:text-aurelis-dark uppercase tracking-wider transition duration-300">
                                <i class="fa-solid fa-envelope-open-text mr-1"></i> Read Message
                            </button>
                            <a href="process/process_inbox.php?action=hapus&id=<?= $row['id'] ?>"
                                onclick="return confirm('Are you sure you want to permanently delete the message from <?= htmlspecialchars($row['nama']) ?>?')"
                                class="text-center text-[9px] font-bold bg-red-500/10 text-red-400 p-2.5 rounded-lg hover:bg-red-500 hover:text-white transition duration-300">
                                <i class="fa-regular fa-trash-can text-xs"></i>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center text-gray-500 py-16 bg-[#161925]/20 border border-white/5 border-dashed rounded-2xl italic text-xs tracking-wider uppercase">
                    <i class="fa-solid fa-mailbox text-2xl text-gray-600 block mb-3 not-italic"></i> Your inbox is currently empty.
                </div>
            <?php endif; ?>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="mt-10 flex justify-center items-center gap-2 font-mono text-xs border-t border-white/5 pt-6">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="inbox_manage.php?page=<?= $i ?>" class="w-9 h-9 flex items-center justify-center rounded-lg transition font-bold <?= $page == $i ? 'bg-gradient-to-r from-aurelis-gold to-[#bfa37e] text-aurelis-dark shadow-md' : 'bg-white/5 text-gray-400 hover:text-white' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<div id="message-modal" class="fixed inset-0 bg-black/80 z-50 backdrop-blur-sm hidden flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-[#0c0e17] border border-white/10 p-6 md:p-8 rounded-[1.5rem] max-w-xl w-full shadow-2xl relative space-y-4">
        <div class="flex justify-between items-start border-b border-white/5 pb-4">
            <div>
                <h3 id="modal-nama" class="text-base font-bold text-white uppercase tracking-wide">Sender Name</h3>
                <p id="modal-email" class="text-xs text-aurelis-gold font-mono mt-0.5">client@email.com</p>
            </div>
            <span id="modal-tanggal" class="text-[9px] text-gray-500 font-mono bg-white/5 px-2.5 py-1 rounded-full">Date</span>
        </div>
        <div class="space-y-1">
            <span class="text-[9px] font-bold text-gray-500 tracking-widest uppercase block">Subject:</span>
            <p id="modal-subjek" class="text-xs text-white font-semibold tracking-wide bg-white/5 p-3 rounded-lg border border-white/5">Subject Text</p>
        </div>
        <div class="space-y-1">
            <span class="text-[9px] font-bold text-gray-500 tracking-widest uppercase block">Message Content:</span>
            <div id="modal-pesan" class="text-xs text-gray-300 leading-relaxed bg-[#161925] border border-white/5 p-4 rounded-xl max-h-48 overflow-y-auto whitespace-pre-wrap">Message body...</div>
        </div>
        <div class="pt-2 flex justify-end">
            <button type="button" onclick="closeMessageModal()" class="bg-white/5 border border-white/10 hover:bg-white/10 text-white font-bold px-6 py-2.5 rounded-lg text-[10px] uppercase tracking-wider transition">Close Details</button>
        </div>
    </div>
</div>

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

    const modal = document.getElementById('message-modal');

    function openMessageModal(nama, email, subjek, pesanJson, tanggal) {
        document.getElementById('modal-nama').innerText = nama;
        document.getElementById('modal-email').innerText = email;
        document.getElementById('modal-subjek').innerText = subjek;
        document.getElementById('modal-tanggal').innerText = tanggal;
        document.getElementById('modal-pesan').innerText = JSON.parse(pesanJson);
        modal.classList.remove('hidden');
    }

    function closeMessageModal() {
        modal.classList.add('hidden');
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            closeMessageModal();
        }
    }
</script>

<?php include "layouts/footer.php"; ?>