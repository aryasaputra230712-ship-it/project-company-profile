<?php
// admin/logout.php

// 1. Mulai session
session_start();

// 2. Hapus semua variabel session
$_SESSION = array();

// 3. Jika menggunakan cookies untuk session, hapus juga
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 4. Hancurkan session
session_destroy();

// 5. Arahkan kembali ke halaman login
header("Location: ../login.php"); // Sesuaikan dengan path file login kamu
exit();
