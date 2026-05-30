<?php
/**
 * Salin file ini menjadi mail.php dan isi kredensial Gmail App Password Anda.
 * Jangan commit mail.php ke Git (sudah ada di .gitignore).
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
