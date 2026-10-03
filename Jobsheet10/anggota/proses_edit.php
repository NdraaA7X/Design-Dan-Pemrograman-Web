<?php
// Halaman terkunci — wajib login
require __DIR__ . '/../includes/auth.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';

$id         = $_POST['id']         ?? null;
$nama       = trim($_POST['nama']       ?? '');
$no_anggota = trim($_POST['no_anggota'] ?? '');
$alamat     = trim($_POST['alamat']     ?? '');
$no_hp      = trim($_POST['no_hp']      ?? '');

if (!$id) {
    header('Location: list.php');
    exit;
}

// ===== Validasi server-side =====
$errors = [];

if ($nama === '') {
    $errors[] = 'Nama wajib diisi.';
}
if ($no_anggota === '') {
    $errors[] = 'No. Anggota wajib diisi.';
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

// ===== UPDATE ke database =====
try {
    $stmt = $pdo->prepare(
        "UPDATE anggota
         SET nama = :nama, no_anggota = :no_anggota,
             alamat = :alamat, no_hp = :no_hp
         WHERE id = :id"
    );
    $stmt->execute([
        'nama'       => $nama,
        'no_anggota' => $no_anggota,
        'alamat'     => $alamat ?: null,
        'no_hp'      => $no_hp  ?: null,
        'id'         => $id,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Data anggota berhasil diperbarui.'];
    header('Location: list.php');
    exit;

} catch (PDOException $e) {
    if (str_contains($e->getMessage(), '23505') || str_contains($e->getMessage(), 'unique')) {
        $_SESSION['flash'] = [
            'type'  => 'error',
            'pesan' => 'No. Anggota "' . htmlspecialchars($no_anggota) . '" sudah dipakai, gunakan nomor lain.',
        ];
    } else {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Terjadi kesalahan database: ' . $e->getMessage()];
    }
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}
