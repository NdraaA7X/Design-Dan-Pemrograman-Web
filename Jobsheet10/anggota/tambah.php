<?php
// Halaman terkunci — wajib login
require __DIR__ . '/../includes/auth.php';
$page_title = 'Tambah Anggota';
require __DIR__ . '/../includes/koneksi.php';
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>
    <h2>Tambah Anggota</h2>

    <?php if ($flash): ?>
    <p class="flash flash-<?php echo $flash['type']; ?>">
        <?php echo htmlspecialchars($flash['pesan']); ?>
    </p>
    <?php endif; ?>

    <form id="form-tambah" method="post" action="proses_tambah.php" novalidate>
        <p>
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" required
                   value="<?php echo htmlspecialchars($_SESSION['old']['nama'] ?? ''); ?>">
        </p>
        <p>
            <label for="no_anggota">No. Anggota</label>
            <input type="text" id="no_anggota" name="no_anggota" required
                   value="<?php echo htmlspecialchars($_SESSION['old']['no_anggota'] ?? ''); ?>">
        </p>
        <p>
            <label for="alamat">Alamat</label>
            <input type="text" id="alamat" name="alamat"
                   value="<?php echo htmlspecialchars($_SESSION['old']['alamat'] ?? ''); ?>">
        </p>
        <p>
            <label for="no_hp">No. HP</label>
            <input type="text" id="no_hp" name="no_hp"
                   value="<?php echo htmlspecialchars($_SESSION['old']['no_hp'] ?? ''); ?>">
        </p>
        <p>
            <button type="submit">Simpan</button>
        </p>
    </form>
</section>

<?php
unset($_SESSION['old']);
include __DIR__ . '/../includes/footer.php';
?>
