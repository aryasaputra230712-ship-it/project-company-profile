<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(dirname(__DIR__)));
}

// 1. MEMANGGIL KONEKSI DATABASE
include_once ROOTPATH . "/config/config.php";

// 2. PANGGIL LIBRARY PHPMAILER
require ROOTPATH . '/libs/PHPMailer/src/Exception.php';
require ROOTPATH . '/libs/PHPMailer/src/PHPMailer.php';
require ROOTPATH . '/libs/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pastikan data dikirim lewat method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Tangkap data dari form contact dan amankan dari SQL Injection
    $nama    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $subjek  = mysqli_real_escape_string($conn, $_POST['subject']);
    $pesan   = mysqli_real_escape_string($conn, $_POST['message']);

    // Validasi input kosong (Mencegah pengunjung mengirimkan data kosong)
    if (empty($nama) || empty($email) || empty($subjek) || empty($pesan)) {
        $_SESSION['error'] = "Semua kolom form wajib diisi!";
        header("Location: ../../contact.php");
        exit();
    }

    // Nama tabel disesuaikan ke 'pesan_masuk' & kolom disamakan dengan phpMyAdmin
    $sql = "INSERT INTO pesan_masuk (nama, email, subjek, pesan, tanggal) 
            VALUES ('$nama', '$email', '$subjek', '$pesan', NOW())";
    mysqli_query($conn, $sql);

    // Langkah Kedua: Konfigurasi Kirim Email Menggunakan PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Mengubah ke 0 untuk mematikan tampilan log putih di layar browser pengunjung
        $mail->SMTPDebug = 0;

        // Pengaturan Server SMTP Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'aryasaputra230712@gmail.com';
        $mail->Password   = 'fjfq gddm pjje syxr';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Opsi Bypass SSL Certificate untuk Localhost (XAMPP / Laragon)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Pengaturan Penerima & Pengirim
        $mail->setFrom('aryasaputra230712@gmail.com', 'Aurelis Profile Website');
        $mail->addAddress('aryasaputra230712@gmail.com');
        $mail->addReplyTo($email, $nama);

        // Konten Email (Format HTML)
        $mail->isHTML(true);
        $mail->Subject = "Pesan Baru dari Website: " . $subjek;

        // Mengembalikan desain template email tabel yang rapi dan profesional
        $mail->Body    = "
            <h3>Ada Pesan Baru Masuk dari Pengunjung Website!</h3>
            <table border='0' cellpadding='5' cellspacing='0'>
                <tr><td><b>Nama Pengirim</b></td><td>: $nama</td></tr>
                <tr><td><b>Email Pengirim</b></td><td>: $email</td></tr>
                <tr><td><b>Subjek</b></td><td>: $subjek</td></tr>
                <tr><td><b>Isi Pesan</b></td><td>: <br>$pesan</td></tr>
            </table>
            <br>
            <hr>
            <small>Email ini dikirim otomatis oleh sistem CMS Aurelis Company Profile.</small>
        ";

        // Eksekusi kirim email
        $mail->send();

        // Menyimpan status sukses ke dalam Session
        $_SESSION['sukses'] = "Pesan Anda berhasil dikirim! Admin akan segera menghubungi Anda.";
        header("Location: ../../contact.php");
        exit();
    } catch (Exception $e) {
        // Menyimpan status eror ke dalam Session jika SMTP gagal jabat tangan
        $_SESSION['error'] = "Pesan tersimpan di sistem, namun gagal mengirim notifikasi email. Error: {$mail->ErrorInfo}";
        header("Location: ../../contact.php");
        exit();
    }
} else {
    header("Location: ../../contact.php");
    exit();
}
