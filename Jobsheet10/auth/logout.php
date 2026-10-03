<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Latihan Tambahan 2: Hapus token dari database & hapus cookie
if (!empty($_COOKIE['remember_token'])) {
    try {
        require_once __DIR__ . '/../includes/koneksi.php';
        $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE token = :token");
        $stmt->execute(['token' => $_COOKIE['remember_token']]);
    } catch (Exception $e) {
        // Abaikan jika database bermasalah saat logout
    }
    // Hapus cookie dari browser dengan set expiry waktu lampau
    setcookie('remember_token', '', time() - 3600, '/');
}

session_destroy();
header('Location: login.php');
exit;
