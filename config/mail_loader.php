<?php
/**
 * Memuat konfigurasi SMTP (urutan prioritas):
 * 1. config/mail.php
 * 2. $mail_smtp di config/config.php (bagian hosting)
 * 3. Variabel lingkungan SMTP_USER / SMTP_PASSWORD
 */

function normalize_mail_config(array $cfg): ?array
{
    $user = trim((string) ($cfg['smtp_user'] ?? ''));
    $pass = trim((string) ($cfg['smtp_password'] ?? ''));

    if ($user === '' || $pass === '') {
        return null;
    }

    if (stripos($user, 'email-anda') !== false || stripos($pass, 'xxxx') !== false) {
        return null;
    }

    $from = trim((string) ($cfg['from_email'] ?? ''));
    if ($from === '') {
        $from = $user;
    }

    return [
        'smtp_host'     => $cfg['smtp_host'] ?? 'smtp.gmail.com',
        'smtp_port'     => (int) ($cfg['smtp_port'] ?? 587),
        'smtp_secure'   => strtolower($cfg['smtp_secure'] ?? 'tls'),
        'smtp_user'     => $user,
        'smtp_password' => $pass,
        'from_email'    => $from,
        'from_name'     => $cfg['from_name'] ?? 'Aurelis Profile Website',
        'notify_email'  => trim((string) ($cfg['notify_email'] ?? '')),
    ];
}

function resolve_project_root(): string
{
    if (defined('ROOTPATH') && is_dir(ROOTPATH . '/config')) {
        return ROOTPATH;
    }

    $candidates = [
        dirname(__DIR__), // config/mail_loader.php
        dirname(dirname(__DIR__)) . '', // admin/process (jika loader dipanggil dari sana tanpa ROOTPATH)
    ];

    foreach ($candidates as $path) {
        $real = realpath($path);
        if ($real && is_file($real . '/config/config.php')) {
            return $real;
        }
    }

    return defined('ROOTPATH') ? ROOTPATH : dirname(__DIR__);
}

function load_mail_config(?string $rootPath = null): ?array
{
    $root = $rootPath ? realpath($rootPath) ?: $rootPath : resolve_project_root();

    $paths = [
        $root . '/config/mail.php',
        dirname(__DIR__) . '/mail.php',
    ];

    foreach ($paths as $file) {
        if (!is_readable($file)) {
            continue;
        }
        $cfg = require $file;
        if (is_array($cfg)) {
            $normalized = normalize_mail_config($cfg);
            if ($normalized !== null) {
                return $normalized;
            }
        }
    }

    if (isset($GLOBALS['mail_smtp']) && is_array($GLOBALS['mail_smtp'])) {
        $normalized = normalize_mail_config($GLOBALS['mail_smtp']);
        if ($normalized !== null) {
            return $normalized;
        }
    }

    $user = trim((string) (getenv('SMTP_USER') ?: getenv('MAIL_USERNAME') ?: ''));
    $pass = trim((string) (getenv('SMTP_PASSWORD') ?: getenv('MAIL_PASSWORD') ?: ''));
    if ($user !== '' && $pass !== '') {
        return normalize_mail_config([
            'smtp_host'     => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
            'smtp_port'     => (int) (getenv('SMTP_PORT') ?: 587),
            'smtp_secure'   => getenv('SMTP_SECURE') ?: 'tls',
            'smtp_user'     => $user,
            'smtp_password' => $pass,
            'from_email'    => getenv('MAIL_FROM') ?: $user,
            'from_name'     => getenv('MAIL_FROM_NAME') ?: 'Aurelis Profile Website',
            'notify_email'  => getenv('MAIL_NOTIFY') ?: '',
        ]);
    }

    return null;
}

function resolve_notify_email(array $mailCfg, mysqli $conn): string
{
    $notify = $mailCfg['notify_email'] ?? '';
    if ($notify !== '' && filter_var($notify, FILTER_VALIDATE_EMAIL)) {
        return $notify;
    }

    $q = mysqli_query($conn, 'SELECT email FROM pengaturan LIMIT 1');
    if ($q && ($row = mysqli_fetch_assoc($q))) {
        $dbEmail = trim($row['email'] ?? '');
        if ($dbEmail !== '' && filter_var($dbEmail, FILTER_VALIDATE_EMAIL)) {
            return $dbEmail;
        }
    }

    return $mailCfg['from_email'];
}
