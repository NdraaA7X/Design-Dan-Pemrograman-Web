<?php
// includes/csrf.php
// Proteksi Cross-Site Request Forgery (CSRF)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Mengambil atau membuat token CSRF baru yang unik untuk sesi saat ini.
 */
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Menghasilkan input hidden HTML berisi token CSRF untuk disisipkan ke form POST.
 */
function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Memverifikasi keabsahan token CSRF dari request POST.
 * Jika tidak valid atau tidak cocok, batalkan request dengan HTTP 403 Forbidden.
 */
function csrf_verify()
{
    $token = $_POST['csrf_token'] ?? '';
    if ($token === '' || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('Permintaan ditolak: token CSRF tidak valid atau kedaluwarsa.');
    }
}
