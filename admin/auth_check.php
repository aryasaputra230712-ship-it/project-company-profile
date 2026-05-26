<?php
session_start();

// Cek apakah admin sudah login atau belum
// Jika tidak ada session 'admin_logged_in', maka usir ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>