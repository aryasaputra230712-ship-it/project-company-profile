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

require_once ROOTPATH . '/config/mail_loader.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../contact.php');
    exit();
}

if (!empty($_POST['website_url'])) {
    exit('Akses ditolak.');
}

$nama   = trim($_POST['name'] ?? '');
$email  = trim($_POST['email'] ?? '');
$subjek = trim($_POST['subject'] ?? '');
$pesan  = trim($_POST['message'] ?? '');

if ($nama === '' || $email === '' || $subjek === '' || $pesan === '') {
    $_SESSION['error'] = 'Semua kolom form wajib diisi.';
    header('Location: ../../contact.php');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Format email tidak valid.';
    header('Location: ../../contact.php');
    exit();
}

$stmt = $conn->prepare('INSERT INTO pesan_masuk (nama, email, subjek, pesan, tanggal) VALUES (?, ?, ?, ?, NOW())');
$stmt->bind_param('ssss', $nama, $email, $subjek, $pesan);

if (!$stmt->execute()) {
    error_log('contact insert failed: ' . $stmt->error);
    $_SESSION['error'] = 'Gagal menyimpan pesan. Silakan coba lagi.';
    header('Location: ../../contact.php');
    exit();
}

$mailCfg = load_mail_config(ROOTPATH);

if ($mailCfg === null) {
    $_SESSION['error'] = 'Pesan tersimpan, tetapi email belum dikonfigurasi di server. '
        . 'Isi $mail_smtp di config/config.php (bagian hosting) atau buat file config/mail.php.';
    header('Location: ../../contact.php');
    exit();
}

$notifyEmail = resolve_notify_email($mailCfg, $conn);

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 0;
    $mail->CharSet   = 'UTF-8';
    $mail->Timeout   = 30;

    $mail->isSMTP();
    $mail->Host       = $mailCfg['smtp_host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $mailCfg['smtp_user'];
    $mail->Password   = $mailCfg['smtp_password'];
    $mail->Port       = $mailCfg['smtp_port'];

    $secure = $mailCfg['smtp_secure'];
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

    $mail->setFrom($mailCfg['from_email'], $mailCfg['from_name']);
    $mail->addAddress($notifyEmail);
    $mail->addReplyTo($email, $nama);

    $mail->isHTML(true);
    $mail->Subject = 'Pesan Baru dari Website: ' . $subjek;
    $mail->Body    = '
        <h3>Pesan baru dari form kontak</h3>
        <p><b>Nama:</b> ' . htmlspecialchars($nama) . '</p>
        <p><b>Email:</b> ' . htmlspecialchars($email) . '</p>
        <p><b>Subjek:</b> ' . htmlspecialchars($subjek) . '</p>
        <p><b>Pesan:</b><br>' . nl2br(htmlspecialchars($pesan)) . '</p>
    ';
    $mail->AltBody = "Nama: $nama\nEmail: $email\nSubjek: $subjek\n\n$pesan";

    $mail->send();
    $_SESSION['sukses'] = 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.';
} catch (Exception $e) {
    error_log('PHPMailer contact: ' . $mail->ErrorInfo);
    $_SESSION['error'] = 'Pesan tersimpan, tetapi notifikasi email gagal terkirim. '
        . 'Periksa App Password Gmail atau coba lagi nanti.';
}

header('Location: ../../contact.php');
exit();
