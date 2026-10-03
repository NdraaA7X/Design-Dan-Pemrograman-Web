<?php
// Halaman terkunci — wajib login
require __DIR__ . '/../includes/auth.php';
// Latihan Tambahan 1: hanya role 'admin' yang boleh menghapus anggota
require __DIR__ . '/../includes/guard_admin.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';

// Hanya menerima POST — bukan GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM anggota WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil dihapus.'];
}

header('Location: list.php');
exit;
