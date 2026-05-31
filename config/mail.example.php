<?php
/**
 * SETUP LOKAL & HOSTING
 * ---------------------
 * 1. Salin file ini menjadi mail.php (di folder config/).
 * 2. Isi smtp_user, smtp_password (Gmail App Password), from_email.
 * 3. Di komputer Anda mail.php sudah ada — UPLOAD manual ke hosting (cPanel File Manager / FTP).
 *    File mail.php TIDAK ikut Git (.gitignore), jadi deploy Git saja tidak cukup.
 *
 * Alternatif di hosting: set variabel lingkungan SMTP_USER, SMTP_PASSWORD (lihat mail_loader.php).
 */
return [
    'smtp_host'     => 'smtp.gmail.com',
    'smtp_port'     => 587,
    'smtp_secure'   => 'tls', // tls (port 587) atau ssl (port 465)
    'smtp_user'     => 'email-anda@gmail.com',
    'smtp_password' => 'xxxx xxxx xxxx xxxx', // App Password 16 karakter dari Google
    'from_email'    => 'email-anda@gmail.com',
    'from_name'     => 'Aurelis Profile Website',
    // Kosongkan agar memakai email dari tabel pengaturan di database
    'notify_email'  => '',
];
