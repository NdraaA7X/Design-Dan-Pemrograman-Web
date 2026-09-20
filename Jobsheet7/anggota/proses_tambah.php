<?php
session_start();

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

// Cek No. Anggota duplikat
if ($no_anggota !== '' && isset($_SESSION['anggota'])) {
    foreach ($_SESSION['anggota'] as $a) {
        if ($a['no_anggota'] === $no_anggota) {
            $errors[] = 'No. Anggota sudah terdaftar.';
            break;
        }
    }
}

// ===== Kalau ada error: simpan flash & redirect kembali =====
if (!empty($errors)) {
    $_SESSION['flash'] = [
        'type'  => 'error',
        'pesan' => implode(' ', $errors),
    ];
    header('Location: tambah.php');
    exit;
}

// ===== Kalau valid: simpan ke $_SESSION & redirect ke daftar =====
if (!isset($_SESSION['anggota'])) {
    $_SESSION['anggota'] = [];
}

$_SESSION['anggota'][] = [
    'no_anggota' => $no_anggota,
    'nama'       => $nama,
    'alamat'     => $alamat,
    'no_hp'      => $no_hp,
];

$_SESSION['flash'] = [
    'type'  => 'success',
    'pesan' => 'Anggota berhasil ditambahkan.',
];

header('Location: list.php');
exit;