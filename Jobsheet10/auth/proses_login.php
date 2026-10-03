<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';

// ===== Latihan Tambahan 3: Pembatasan Percobaan Login Gagal =====
// Inisialisasi penghitung jika belum ada di session
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['login_locked_until'])) {
    $_SESSION['login_locked_until'] = 0;
}

$MAX_ATTEMPTS  = 5;        // batas maksimal percobaan
$LOCK_SECONDS  = 300;      // dikunci selama 5 menit (300 detik)

// Cek apakah sedang dalam masa kunci
if (time() < $_SESSION['login_locked_until']) {
    $sisaDetik = $_SESSION['login_locked_until'] - time();
    $sisaMenit = ceil($sisaDetik / 60);
    $_SESSION['flash'] = [
        'type'  => 'error',
        'pesan' => "Akun sementara dikunci karena terlalu banyak percobaan gagal. "
                 . "Coba lagi dalam {$sisaMenit} menit.",
    ];
    header('Location: login.php');
    exit;
}

// Cek apakah sudah mencapai batas percobaan (tapi belum dikunci — set kunci)
if ($_SESSION['login_attempts'] >= $MAX_ATTEMPTS) {
    $_SESSION['login_locked_until'] = time() + $LOCK_SECONDS;
    $_SESSION['login_attempts']     = 0; // reset counter setelah dikunci
    $_SESSION['flash'] = [
        'type'  => 'error',
        'pesan' => "Terlalu banyak percobaan login gagal. Akun dikunci selama 5 menit.",
    ];
    header('Location: login.php');
    exit;
}

// ===== Proses Login Normal =====
$username = trim($_POST['username'] ?? '');
$password = $_POST['password']      ?? '';

// Cari user berdasarkan username
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifikasi password dengan password_verify()
if ($user && password_verify($password, $user['password'])) {
    // Login berhasil — reset penghitung dan simpan identitas ke session
    $_SESSION['login_attempts']     = 0;
    $_SESSION['login_locked_until'] = 0;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama']    = $user['nama'];
    $_SESSION['role']    = $user['role'];

    // Latihan Tambahan 2: Fitur "Ingat Saya" (Remember Me)
    if (!empty($_POST['ingat_saya'])) {
        try {
            $token = bin2hex(random_bytes(32)); // 64 karakter token kriptografis
            $expires = date('Y-m-d H:i:s', strtotime('+30 days'));

            $stmtToken = $pdo->prepare(
                "INSERT INTO remember_tokens (user_id, token, expires_at)
                 VALUES (:uid, :token, :exp)"
            );
            $stmtToken->execute([
                'uid'   => $user['id'],
                'token' => $token,
                'exp'   => $expires,
            ]);

            // Set cookie 30 hari, path root (/), HttpOnly true untuk mencegah XSS
            setcookie('remember_token', $token, time() + (30 * 24 * 3600), '/', '', false, true);
        } catch (Exception $e) {
            // Jika token gagal disimpan, login session tetap valid
        }
    }

    header('Location: ../index.php');
    exit;
}

// Login gagal — tambah penghitung, beri pesan yang sesuai
$_SESSION['login_attempts']++;
$sudahCoba = $_SESSION['login_attempts'];
$sisa      = $MAX_ATTEMPTS - $sudahCoba;

// Pesan selalu umum (tidak membedakan username/password salah — security best practice)
if ($sisa > 0 && $sisa <= 2) {
    // Peringatan dini: 1-2 percobaan tersisa
    $pesan = "Username atau password salah. Peringatan: {$sisa} percobaan tersisa sebelum akun dikunci.";
} elseif ($sisa <= 0) {
    // Langsung kunci
    $_SESSION['login_locked_until'] = time() + $LOCK_SECONDS;
    $_SESSION['login_attempts']     = 0;
    $pesan = "Terlalu banyak percobaan login gagal. Akun dikunci selama 5 menit.";
} else {
    // Percobaan normal (masih banyak sisa)
    $pesan = "Username atau password salah.";
}

$_SESSION['flash'] = ['type' => 'error', 'pesan' => $pesan];
header('Location: login.php');
exit;
