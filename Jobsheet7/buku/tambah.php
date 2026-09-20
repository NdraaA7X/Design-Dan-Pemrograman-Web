<?php
$page_title = 'Tambah Buku';
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>
    <h2>Tambah Buku</h2>

    <?php if ($flash): ?>
    <p class="flash flash-<?php echo $flash['type']; ?>">
        <?php echo $flash['pesan']; ?>
    </p>
    <?php endif; ?>

    <form id="form-tambah" method="post" action="proses_tambah.php" novalidate>
        <p>
            <label for="judul">Judul</label>
            <input type="text" id="judul" name="judul" required>
        </p>
        <p>
            <label for="pengarang">Pengarang</label>
            <input type="text" id="pengarang" name="pengarang" required>
        </p>
        <p>
            <label for="tahun">Tahun Terbit</label>
            <input type="number" id="tahun" name="tahun" min="1900" max="2026" required>
        </p>
        <p>
            <label for="isbn">ISBN</label>
            <input type="text" id="isbn" name="isbn">
        </p>
        <p>
            <label for="stok">Stok</label>
            <input type="number" id="stok" name="stok" min="0" required>
        </p>
        <p>
            <label for="kategori">Kategori</label>
            <select id="kategori" name="kategori">
                <option value="Sejarah">Sejarah</option>
                <option value="Biografi">Biografi</option>
                <option value="Referensi">Referensi</option>
                <option value="Fiksi">Fiksi</option>
                <option value="Non-Fiksi">Non-Fiksi</option>
            </select>
        </p>
        <p>
            <button type="submit">Simpan</button>
        </p>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>