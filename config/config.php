<?php

if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(__DIR__));
}

// Mengecek apakah server dijalankan secara lokal (localhost)
if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1') {

    // Jika dijalankan di komputer lokal, gunakan konfigurasi lokal
    $host = "localhost";        // Alamat server database lokal
    $user = "root";             // Username MySQL lokal
    $pass = ""; // Password MySQL lokal
    $db = "vibewebs_db"; // Nama database lokal

} else {
    // Jika dijalankan di jaringan (bukan localhost), gunakan konfigurasi server
    $host = "localhost"; // Biasanya hosting menggunakan localhost untuk koneksi DB internal
    $user = "vibewebs_id_rsa"; // User yang kamu buat di cPanel
    $pass = "Aryasaputra23"; // Password yang kamu reset tadi
    $db   = "vibewebs_db"; // Nama database di cPanel

    /**
     * SMTP Gmail untuk form Contact (hosting).
     * Isi di bawah ini ATAU upload config/mail.php (salin dari mail.example.php).
     * App Password: https://myaccount.google.com/apppasswords
     */
    $mail_smtp = [
        'smtp_host'     => 'mail.vibewebs.web.id',
        'smtp_port'     => 587,
        'smtp_secure'   => 'tls',
        'smtp_user'     => 'info@vibewebs.web.id', // Gmail Anda
        'smtp_password' => 'Vib3DGodW3bs', // App Password 16 karakter (Google → App passwords)
        'from_email'    => 'info@vibewebs.web.id', // biasanya sama dengan smtp_user
        'from_name'     => 'Aurelis Profile Website',
        'notify_email'  => 'vibewebss@gmail.com', // kosong = pakai email di tabel pengaturan
    ];
}

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    // Di server online, sebaiknya jangan pakai die() agar tidak muncul 500 tanpa pesan
    error_log("Koneksi gagal: " . mysqli_connect_error());
    die("Maaf, terjadi gangguan pada sistem.");
}
