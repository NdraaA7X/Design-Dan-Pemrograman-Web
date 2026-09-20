<?php
$page_title = 'Beranda';
include __DIR__ . '/includes/header.php';
?>

<section>
    <h2>Selamat Datang di Sistem Perpustakaan Mini</h2>
    <p>Aplikasi sederhana untuk mengelola data buku dan anggota perpustakaan.</p>
</section>

<section>
    <h2>Ringkasan</h2>
    <article>
        <h3>Total Buku</h3>
        <p><?php echo count($_SESSION['buku'] ?? []); ?></p>
    </article>
    <article>
        <h3>Total Anggota</h3>
        <p><?php echo count($_SESSION['anggota'] ?? []); ?></p>
    </article>
    <article>
        <h3>Sedang Dipinjam</h3>
        <p>0</p>
    </article>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>