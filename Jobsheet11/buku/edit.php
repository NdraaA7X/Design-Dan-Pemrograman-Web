<?php
// Halaman terkunci — wajib login
require __DIR__ . '/../includes/auth.php';
$page_title = 'Edit Buku';
require __DIR__ . '/../includes/koneksi.php';
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = :id");
$stmt->execute(['id' => $id]);
$buku = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$buku) {
    header('Location: list.php');
    exit;
}
?>

<section>
    <h2>Edit Buku</h2>

    <?php if ($flash): ?>
    <p class="flash flash-<?php echo e($flash['type']); ?>">
        <?php echo e($flash['pesan']); ?>
    </p>
    <?php endif; ?>

    <form id="form-tambah" method="post" action="proses_edit.php" novalidate>
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id" value="<?php echo (int) $buku['id']; ?>">

        <p>
            <label for="judul">Judul Buku</label>
            <input type="text" id="judul" name="judul" required
                   value="<?php echo e($buku['judul']); ?>">
        </p>
        <p>
            <label for="pengarang">Pengarang</label>
            <input type="text" id="pengarang" name="pengarang" required
                   value="<?php echo e($buku['pengarang']); ?>">
        </p>
        <p>
            <label for="tahun">Tahun Terbit</label>
            <input type="number" id="tahun" name="tahun" min="1900" max="2026" required
                   value="<?php echo (int) $buku['tahun']; ?>">
        </p>
        <p>
            <label for="isbn">ISBN (Opsional)</label>
            <input type="text" id="isbn" name="isbn"
                   value="<?php echo e($buku['isbn'] ?? ''); ?>">
        </p>
        <p>
            <label for="stok">Stok</label>
            <input type="number" id="stok" name="stok" min="0" required
                   value="<?php echo (int) $buku['stok']; ?>">
        </p>
        <p>
            <label for="kategori">Kategori</label>
            <select id="kategori" name="kategori">
                <?php
                $kategori_list = ['Sejarah','Biografi','Referensi','Fiksi','Non-Fiksi'];
                foreach ($kategori_list as $kat): ?>
                <option value="<?php echo e($kat); ?>" <?php echo $buku['kategori'] === $kat ? 'selected' : ''; ?>>
                    <?php echo e($kat); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <button type="submit">Simpan Perubahan</button>
            <a href="list.php" style="margin-left:1rem;">Batal</a>
        </p>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
