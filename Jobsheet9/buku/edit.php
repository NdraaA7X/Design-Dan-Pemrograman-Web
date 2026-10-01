<?php
session_start();
$page_title = "Edit Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

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

<section class="container">
    <h2>Edit Buku</h2>

    <?php if ($flash): ?>
        <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>">
            <?php echo htmlspecialchars($flash['pesan']); ?>
        </div>
    <?php endif; ?>

    <form id="form-edit" method="post" action="proses_edit.php">
        <!-- Input hidden untuk ID agar terbaca di proses_edit.php -->
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($buku['id']); ?>">

        <p>
            <label for="judul">Judul Buku</label><br>
            <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($buku['judul']); ?>" required>
        </p>

        <p>
            <label for="pengarang">Pengarang</label><br>
            <input type="text" id="pengarang" name="pengarang" value="<?php echo htmlspecialchars($buku['pengarang']); ?>" required>
        </p>

        <p>
            <label for="tahun">Tahun Terbit</label><br>
            <input type="number" id="tahun" name="tahun" value="<?php echo htmlspecialchars($buku['tahun']); ?>" required>
        </p>

        <p>
            <label for="isbn">ISBN (Opsional)</label><br>
            <!-- Atribut 'required' dihapus dari ISBN -->
            <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($buku['isbn'] ?? ''); ?>">
        </p>

        <p>
            <label for="stok">Stok</label><br>
            <input type="number" id="stok" name="stok" value="<?php echo htmlspecialchars($buku['stok']); ?>" required>
        </p>

        <p>
            <label for="kategori">Kategori</label><br>
            <select id="kategori" name="kategori" required>
                <?php 
                $kategori_list = ['fiksi' => 'Fiksi', 'non-fiksi' => 'Non-Fiksi', 'referensi' => 'Referensi'];
                foreach ($kategori_list as $value => $label): 
                ?>
                    <option value="<?php echo $value; ?>" <?php echo $buku['kategori'] === $value ? 'selected' : ''; ?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
            <a href="list.php" class="btn-secondary">Batal</a>
        </p>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>