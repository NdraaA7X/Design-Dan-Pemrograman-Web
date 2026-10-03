<?php
// Jika sudah login, langsung redirect ke Beranda
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$page_title = "Registrasi Petugas";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>
    <h2>Registrasi Petugas</h2>
    <p>Buat akun baru untuk mengakses fitur pengelolaan perpustakaan.</p>

    <?php if ($flash): ?>
    <p class="flash flash-<?php echo $flash['type']; ?>">
        <?php echo htmlspecialchars($flash['pesan']); ?>
    </p>
    <?php endif; ?>

    <form id="form-tambah" method="post" action="proses_register.php" novalidate>
        <p>
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" required
                   value="<?php echo htmlspecialchars($_SESSION['old']['nama'] ?? ''); ?>">
        </p>
        <p>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required
                   value="<?php echo htmlspecialchars($_SESSION['old']['username'] ?? ''); ?>">
        </p>
        <p>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required minlength="6">
        </p>
        <p>
            <button type="submit">Daftar</button>
        </p>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </form>
</section>
<?php
unset($_SESSION['old']);
include __DIR__ . '/../includes/footer.php';
?>
