<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$nama       = trim($_POST['nama']       ?? '');
$no_anggota = trim($_POST['no_anggota'] ?? '');
$alamat     = trim($_POST['alamat']     ?? '');
$no_hp      = trim($_POST['no_hp']      ?? '');

// ===== Validasi server-side =====
$errors = [];

if ($nama === '') {
    $errors[] = 'Nama wajib diisi.';
}

if ($no_anggota === '') {
    $errors[] = 'No. Anggota wajib diisi.';
}

if (!empty($errors)) {
    $_SESSION['flash'] = [
        'type'  => 'error',
        'pesan' => implode(' ', $errors),
    ];
    header('Location: tambah.php');
    exit;
}

// ===== INSERT ke database + tangani UNIQUE error (Latihan Opsional 1) =====
try {
    $stmt = $pdo->prepare(
        "INSERT INTO anggota (nama, no_anggota, alamat, no_hp)
         VALUES (:nama, :no_anggota, :alamat, :no_hp)
         RETURNING id"
    );

    $stmt->execute([
        'nama'       => $nama,
        'no_anggota' => $no_anggota,
        'alamat'     => $alamat ?: null,
        'no_hp'      => $no_hp  ?: null,
    ]);

    $_SESSION['flash'] = [
        'type'  => 'success',
        'pesan' => 'Anggota berhasil ditambahkan.',
    ];

    header('Location: list.php');
    exit;

} catch (PDOException $e) {
    // Tangkap error UNIQUE (kode SQLSTATE 23505)
    if (str_contains($e->getMessage(), '23505') || str_contains($e->getMessage(), 'unique')) {
        $_SESSION['flash'] = [
            'type'  => 'error',
            'pesan' => 'No. Anggota "' . htmlspecialchars($no_anggota) . '" sudah dipakai, gunakan nomor lain.',
        ];
    } else {
        $_SESSION['flash'] = [
            'type'  => 'error',
            'pesan' => 'Terjadi kesalahan database: ' . $e->getMessage(),
        ];
    }

    header('Location: tambah.php');
    exit;
}
