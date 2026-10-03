<?php
// Halaman terkunci — wajib login
require __DIR__ . '/../includes/auth.php';
$page_title = 'Tambah Buku';
require __DIR__ . '/../includes/koneksi.php';
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>
    <h2>Tambah Buku</h2>

    <?php if ($flash): ?>
    <p class="flash flash-<?php echo $flash['type']; ?>">
        <?php echo htmlspecialchars($flash['pesan']); ?>
    </p>
    <?php endif; ?>

    <form id="form-tambah" method="post" action="proses_tambah.php" novalidate>
        <p>
            <label for="judul">Judul</label>
            <input type="text" id="judul" name="judul" required
                   value="<?php echo htmlspecialchars($_SESSION['old']['judul'] ?? ''); ?>">
        </p>
        <p>
            <label for="pengarang">Pengarang</label>
            <input type="text" id="pengarang" name="pengarang" required
                   value="<?php echo htmlspecialchars($_SESSION['old']['pengarang'] ?? ''); ?>">
        </p>
        <p>
            <label for="tahun">Tahun Terbit</label>
            <input type="number" id="tahun" name="tahun" min="1900" max="2026" required
                   value="<?php echo htmlspecialchars($_SESSION['old']['tahun'] ?? ''); ?>">
        </p>
        <p>
            <label for="isbn">ISBN (Opsional)</label>
            <input type="text" id="isbn" name="isbn"
                   value="<?php echo htmlspecialchars($_SESSION['old']['isbn'] ?? ''); ?>">
        </p>
        <p>
            <label for="stok">Stok</label>
            <input type="number" id="stok" name="stok" min="0" required
                   value="<?php echo htmlspecialchars($_SESSION['old']['stok'] ?? ''); ?>">
        </p>
        <p>
            <label for="kategori">Kategori</label>
            <select id="kategori" name="kategori">
                <?php
                $kategori_list = ['Sejarah','Biografi','Referensi','Fiksi','Non-Fiksi'];
                $old_kat = $_SESSION['old']['kategori'] ?? '';
                foreach ($kategori_list as $kat): ?>
                <option value="<?php echo $kat; ?>" <?php echo $old_kat === $kat ? 'selected' : ''; ?>>
                    <?php echo $kat; ?>
                </option>
                <?php endforeach; ?>
            </select>
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
