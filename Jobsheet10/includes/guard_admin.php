<?php
// includes/guard_admin.php
// Guard tambahan berbasis role — dipanggil SETELAH includes/auth.php.
// Memastikan hanya pengguna dengan role 'admin' yang bisa melanjutkan.
// Jika bukan admin, redirect ke list.php dengan flash error.

if (($_SESSION['role'] ?? '') !== 'admin') {
    $_SESSION['flash'] = [
        'type'  => 'error',
        'pesan' => 'Akses ditolak. Hanya admin yang boleh melakukan tindakan ini.',
    ];
    header('Location: list.php');
    exit;
}
