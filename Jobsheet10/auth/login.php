<?php
// Jika sudah login, langsung redirect ke Beranda
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$page_title = "Login";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>
    <h2>Login Petugas</h2>

    <?php if ($flash): ?>
    <p class="flash flash-<?php echo $flash['type']; ?>">
        <?php echo htmlspecialchars($flash['pesan']); ?>
    </p>
    <?php endif; ?>

    <form id="form-tambah" method="post" action="proses_login.php" novalidate>
        <p>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autocomplete="username">
        </p>
        <p>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
        </p>
        <p>
            <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: normal; cursor: pointer;">
                <input type="checkbox" name="ingat_saya" value="1" style="width: auto;">
                Ingat Saya (30 hari)
            </label>
        </p>
        <p>
            <button type="submit">Masuk</button>
        </p>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
