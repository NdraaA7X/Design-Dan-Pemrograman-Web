<?php
$page_title = 'Edit Anggota';
require __DIR__ . '/../includes/koneksi.php';
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM anggota WHERE id = :id");
$stmt->execute(['id' => $id]);
$anggota = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$anggota) {
    header('Location: list.php');
    exit;
}
?>

<section>
    <h2>Edit Anggota</h2>

    <?php if ($flash): ?>
    <p class="flash flash-<?php echo $flash['type']; ?>">
        <?php echo $flash['pesan']; ?>
    </p>
    <?php endif; ?>

    <form id="form-tambah" method="post" action="proses_edit.php" novalidate>
        <input type="hidden" name="id" value="<?php echo $anggota['id']; ?>">

        <p>
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama"
                   value="<?php echo htmlspecialchars($anggota['nama']); ?>" required>
        </p>
        <p>
            <label for="no_anggota">No. Anggota</label>
            <input type="text" id="no_anggota" name="no_anggota"
                   value="<?php echo htmlspecialchars($anggota['no_anggota']); ?>" required>
        </p>
        <p>
            <label for="alamat">Alamat</label>
            <input type="text" id="alamat" name="alamat"
                   value="<?php echo htmlspecialchars($anggota['alamat'] ?? ''); ?>">
        </p>
        <p>
            <label for="no_hp">No. HP</label>
            <input type="text" id="no_hp" name="no_hp"
                   value="<?php echo htmlspecialchars($anggota['no_hp'] ?? ''); ?>">
        </p>
        <p>
            <button type="submit">Simpan Perubahan</button>
            <a href="list.php" style="margin-left:1rem;">Batal</a>
        </p>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
