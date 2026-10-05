<?php
// Halaman terkunci — wajib login
require __DIR__ . '/../includes/auth.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hanya menerima method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

// Verifikasi token CSRF
require __DIR__ . '/../includes/csrf.php';
csrf_verify();

require __DIR__ . '/../includes/koneksi.php';

$id = $_POST['id'] ?? null;

if ($id && is_numeric($id)) {
    $stmt = $pdo->prepare("DELETE FROM buku WHERE id = :id");
    $stmt->execute(['id' => (int) $id]);
    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil dihapus.'];
} else {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'ID tidak valid.'];
}

header('Location: list.php');
exit;
