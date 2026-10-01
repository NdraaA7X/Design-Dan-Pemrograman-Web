<?php
$page_title = 'Daftar Buku';
require __DIR__ . '/../includes/koneksi.php';
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// ===== Pagination & Pencarian Server =====
$perPage = 5;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    // 1. Hitung total baris yang cocok dengan Judul ATAU Pengarang
    $hitung = $pdo->prepare("SELECT COUNT(*) FROM buku WHERE judul ILIKE :kw OR pengarang ILIKE :kw");
    $hitung->execute(['kw' => '%' . $keyword . '%']);
    $totalRows = $hitung->fetchColumn();

    // 2. Ambil data buku yang cocok dengan Judul ATAU Pengarang + Pagination
    $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul ILIKE :kw OR pengarang ILIKE :kw ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue('kw', '%' . $keyword . '%');
} else {
    // Jika tidak ada kata kunci pencarian, tampilkan semua buku
    $totalRows = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM buku ORDER BY id DESC LIMIT :limit OFFSET :offset");
}

$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPages = max(1, (int) ceil($totalRows / $perPage));
?>

<section>
    <h2>Daftar Buku</h2>

    <?php if ($flash): ?>
    <p class="flash flash-<?php echo $flash['type']; ?>">
        <?php echo $flash['pesan']; ?>
    </p>
    <?php endif; ?>

    <div class="search-box">
        <form method="get" action="list.php">
            <span>
                <label for="search-input">Cari Judul Buku</label><br>
                <input type="text" id="search-input" name="q"
                       value="<?php echo $keyword; ?>"
                       placeholder="Ketik judul buku...">
            </span>
            <button type="submit">Cari</button>
        </form>
        <p id="filter-count"></p>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Tahun</th>
                    <th>Stok</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftarBuku)): ?>
                <tr>
                    <td colspan="6">
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
                    <td>
                        <a href="edit.php?id=<?php echo $buku['id']; ?>" class="btn-edit">Edit</a>
                        <form class="form-hapus" method="post" action="hapus.php">
                        <input type="hidden" name="id" value="<?php echo $buku['id']; ?>">
                        <button type="submit" class="btn-hapus">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Navigasi Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="list.php?page=<?php echo $i; ?><?php echo $keyword !== '' ? '&q=' . urlencode($keyword) : ''; ?>"
           class="<?php echo $i === $page ? 'active' : ''; ?>">
            <?php echo $i; ?>
        </a>
        <?php endfor; ?>
    </nav>
    <?php endif; ?>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
