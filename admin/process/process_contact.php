<?php
if (!defined('ROOTPATH')) {
    define('ROOTPATH', dirname(dirname(__DIR__)));
}

include_once ROOTPATH . '/config/config.php';

require ROOTPATH . '/libs/PHPMailer/src/Exception.php';
require ROOTPATH . '/libs/PHPMailer/src/PHPMailer.php';
require ROOTPATH . '/libs/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mailConfigPath = ROOTPATH . '/config/mail.php';
if (!is_readable($mailConfigPath)) {
    $_SESSION['error'] = 'Konfigurasi email belum disetup. Salin config/mail.example.php menjadi config/mail.php.';
    header('Location: ../../contact.php');
    exit();
}
$mailCfg = require $mailConfigPath;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../contact.php');
    exit();
}

$nama   = trim($_POST['name'] ?? '');
$email  = trim($_POST['email'] ?? '');
$subjek = trim($_POST['subject'] ?? '');
$pesan  = trim($_POST['message'] ?? '');

if ($nama === '' || $email === '' || $subjek === '' || $pesan === '') {
    $_SESSION['error'] = 'Semua kolom form wajib diisi!';
    header('Location: ../../contact.php');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Format email pengirim tidak valid.';
    header('Location: ../../contact.php');
    exit();
}

$nama_db   = mysqli_real_escape_string($conn, $nama);
$email_db  = mysqli_real_escape_string($conn, $email);
$subjek_db = mysqli_real_escape_string($conn, $subjek);
$pesan_db  = mysqli_real_escape_string($conn, $pesan);

$sql = "INSERT INTO pesan_masuk (nama, email, subjek, pesan, tanggal) 
        VALUES ('$nama_db', '$email_db', '$subjek_db', '$pesan_db', NOW())";
if (!mysqli_query($conn, $sql)) {
    error_log('contact insert failed: ' . mysqli_error($conn));
    $_SESSION['error'] = 'Gagal menyimpan pesan. Silakan coba lagi.';
    header('Location: ../../contact.php');
    exit();
}

$notifyEmail = $mailCfg['notify_email'] ?? '';
if ($notifyEmail === '') {
    $qSet = mysqli_query($conn, 'SELECT email FROM pengaturan LIMIT 1');
    if ($rowSet = mysqli_fetch_assoc($qSet)) {
        $notifyEmail = trim($rowSet['email'] ?? '');
    }
}
if ($notifyEmail === '' || !filter_var($notifyEmail, FILTER_VALIDATE_EMAIL)) {
    $notifyEmail = $mailCfg['from_email'] ?? $mailCfg['smtp_user'];
}

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug  = 0;
    $mail->CharSet    = 'UTF-8';
    $mail->Timeout    = 30;
    $mail->SMTPKeepAlive = false;

    $mail->isSMTP();
    $mail->Host       = $mailCfg['smtp_host'] ?? 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $mailCfg['smtp_user'];
    $mail->Password   = $mailCfg['smtp_password'];
    $mail->Port       = (int) ($mailCfg['smtp_port'] ?? 587);

    $secure = strtolower($mailCfg['smtp_secure'] ?? 'tls');
    if ($secure === 'ssl') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } else {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    }

    $isLocal = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true);
    if ($isLocal) {
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ];
    }

    $fromEmail = $mailCfg['from_email'] ?? $mailCfg['smtp_user'];
    $fromName  = $mailCfg['from_name'] ?? 'Aurelis Website';

    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($notifyEmail);
    $mail->addReplyTo($email, $nama);

    $mail->isHTML(true);
    $mail->Subject = 'Pesan Baru dari Website: ' . $subjek;
    $mail->Body    = '
        <h3>Ada pesan baru dari pengunjung website</h3>
        <table border="0" cellpadding="6" cellspacing="0" style="font-family:sans-serif;font-size:14px;">
            <tr><td><b>Nama</b></td><td>' . htmlspecialchars($nama) . '</td></tr>
            <tr><td><b>Email</b></td><td>' . htmlspecialchars($email) . '</td></tr>
            <tr><td><b>Subjek</b></td><td>' . htmlspecialchars($subjek) . '</td></tr>
            <tr><td valign="top"><b>Pesan</b></td><td>' . nl2br(htmlspecialchars($pesan)) . '</td></tr>
        </table>
        <p style="color:#666;font-size:12px;">Dikirim otomatis dari form kontak Aurelis.</p>
    ';
    $mail->AltBody = "Nama: $nama\nEmail: $email\nSubjek: $subjek\n\nPesan:\n$pesan";

    $mail->send();

    $_SESSION['sukses'] = 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.';
} catch (Exception $e) {
    error_log('PHPMailer contact error: ' . $mail->ErrorInfo);
    $_SESSION['error'] = 'Pesan tersimpan di sistem, tetapi notifikasi email gagal terkirim. '
        . 'Coba lagi nanti atau hubungi kami via WhatsApp.';
}

header('Location: ../../contact.php');
exit();
