<?php
// Guard clause: di-include di baris paling atas setiap halaman yang
// membutuhkan login (sebelum header.php mengeluarkan output apa pun),
// agar header('Location: ...') masih bisa dipanggil.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Latihan Tambahan 2: Auto-login via cookie "Ingat Saya" (Remember Me)
if (!isset($_SESSION['user_id']) && !empty($_COOKIE['remember_token'])) {
    try {
        // Coba koneksi ke DB tanpa mematikan guard jika DB gagal
        $host = "localhost";
        $port = "5432";
        $db   = "simpus_mini";
        $user = "postgres";
        $pass = "1986";
        $pdoAuth = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
        $pdoAuth->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmtAuth = $pdoAuth->prepare(
            "SELECT u.id, u.nama, u.role FROM remember_tokens rt
             JOIN users u ON u.id = rt.user_id
             WHERE rt.token = :token AND rt.expires_at > NOW()"
        );
        $stmtAuth->execute(['token' => $_COOKIE['remember_token']]);
        $userAuth = $stmtAuth->fetch(PDO::FETCH_ASSOC);

        if ($userAuth) {
            $_SESSION['user_id'] = $userAuth['id'];
            $_SESSION['nama']    = $userAuth['nama'];
            $_SESSION['role']    = $userAuth['role'];
        }
    } catch (Exception $e) {
        // Jika database mati, abaikan error DB dan biarkan alur guard bekerja normal
    }
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
