<?php
// Halaman terkunci — wajib login
require __DIR__ . '/../includes/auth.php';
// Latihan Tambahan: hanya role 'admin' yang boleh menghapus buku
require __DIR__ . '/../includes/guard_admin.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';

$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM buku WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil dihapus.'];
} else {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'ID tidak valid.'];
}

header('Location: list.php');
exit;
