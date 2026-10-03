<?php
// Jobsheet 10: header.php dengan navbar dinamis berdasarkan status login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Latihan Tambahan 2: Auto-login via cookie "Ingat Saya" (Remember Me)
if (!isset($_SESSION['user_id']) && !empty($_COOKIE['remember_token'])) {
    try {
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
        // Abaikan jika DB belum siap
    }
}

$sudahLogin = isset($_SESSION['user_id']);

$__jobsheetRoot = dirname(__DIR__);
$__scriptDir    = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel          = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__jobsheetRoot))), '/');
$base           = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMPUS-Mini<?php echo isset($page_title) ? ' | ' . $page_title : ''; ?></title>
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
</head>
<body>
<header>
    <h1>SIMPUS-Mini</h1>
    <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Menu">&#9776;</button>
    <nav>
        <ul>
            <li><a href="<?php echo $base; ?>index.php">Beranda</a></li>
            <li><a href="<?php echo $base; ?>buku/list.php">Daftar Buku</a></li>
            <?php if ($sudahLogin): ?>
            <li><a href="<?php echo $base; ?>buku/tambah.php">Tambah Buku</a></li>
            <li><a href="<?php echo $base; ?>anggota/list.php">Daftar Anggota</a></li>
            <li><a href="<?php echo $base; ?>anggota/tambah.php">Tambah Anggota</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="auth-status">
        <?php if ($sudahLogin): ?>
            <span><?php echo htmlspecialchars($_SESSION['nama']); ?></span>
            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
            <span class="role-badge role-admin">Admin</span>
            <?php else: ?>
            <span class="role-badge role-petugas">Petugas</span>
            <?php endif; ?>
            <a href="<?php echo $base; ?>auth/logout.php">Logout</a>
        <?php else: ?>
            <a href="<?php echo $base; ?>auth/login.php">Login</a>
        <?php endif; ?>
    </div>
</header>
<main>
