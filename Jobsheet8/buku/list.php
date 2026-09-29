<?php
$page_title = 'Daftar Buku';
require __DIR__ . '/../includes/koneksi.php';
include __DIR__ . '/../includes/header.php';

$flash      = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul ILIKE :keyword ORDER BY id DESC");
    $stmt->execute(['keyword' => '%' . $keyword . '%']);
    $daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $daftarBuku = $pdo->query("SELECT * FROM buku ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<section>
    <h2>Daftar Buku</h2>

    <?php if ($flash): ?>
    <p class="flash flash-<?php echo $flash['type']; ?>">
        <?php echo $flash['pesan']; ?>
    </p>
    <?php endif; ?>

    <form method="get" action="">
        <div class="search-box">
            <label for="q">Cari Judul Buku</label>
            <input type="text" id="q" name="q"
                   value="<?php echo htmlspecialchars($keyword); ?>"
                   placeholder="Ketik judul buku...">
            <button type="submit">Cari</button>
            <?php if ($keyword !== ''): ?>
            <a href="list.php">Reset</a>
            <?php endif; ?>
            <p id="filter-count"></p>
        </div>
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                    <th>Kategori</th>
                    <th>Tanggal Ditambahkan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftarBuku)): ?>
                <tr>
                    <td colspan="7">
                        <?php if ($keyword !== ''): ?>
                            Tidak ada buku dengan judul mengandung "<?php echo htmlspecialchars($keyword); ?>".
                        <?php else: ?>
                            Belum ada data buku. Silakan tambah lewat menu "Tambah Buku".
                        <?php endif; ?>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($daftarBuku as $buku): ?>
                <tr>
                    <td><?php echo htmlspecialchars($buku['judul']); ?></td>
                    <td><?php echo htmlspecialchars($buku['pengarang']); ?></td>
                    <td><?php echo htmlspecialchars($buku['tahun']); ?></td>
                    <td><?php echo htmlspecialchars($buku['stok']); ?></td>
                    <td><?php echo htmlspecialchars($buku['kategori']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($buku['tanggal_ditambahkan'])); ?></td>
                    <td>
                        <button type="button">Detail</button>
                        <button type="button">Edit</button>
                        <button type="button" class="btn-hapus">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>