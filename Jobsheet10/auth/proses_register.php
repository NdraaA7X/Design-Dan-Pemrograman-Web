<?php
// Session HARUS distart paling awal, sebelum apapun
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pastikan request berasal dari POST (bukan akses langsung via URL)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

// Koneksi database
require __DIR__ . '/../includes/koneksi.php';

$nama     = trim($_POST['nama']     ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password']      ?? '';

// ===== Validasi server-side =====
$errors = [];

if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}
if ($username === '') {
    $errors[] = "Username wajib diisi.";
}
if (strlen($password) < 6) {
    $errors[] = "Password minimal 6 karakter.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    $_SESSION['old']   = ['nama' => $nama, 'username' => $username];
    header('Location: register.php');
    exit;
}

// ===== Cek username duplikat =====
$cek = $pdo->prepare("SELECT id FROM users WHERE username = :username");
$cek->execute(['username' => $username]);
if ($cek->fetch()) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Username sudah digunakan.'];
    $_SESSION['old']   = ['nama' => $nama, 'username' => $username];
    header('Location: register.php');
    exit;
}

// ===== Simpan ke database dengan password ter-hash =====
$stmt = $pdo->prepare(
    "INSERT INTO users (nama, username, password, role)
     VALUES (:nama, :username, :password, 'petugas')"
);
$stmt->execute([
    'nama'     => $nama,
    'username' => $username,
    'password' => password_hash($password, PASSWORD_DEFAULT),
]);

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Akun berhasil dibuat. Silakan login.'];
header('Location: login.php');
exit;
