<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id        = $_POST['id'] ?? null;
$judul     = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun     = $_POST['tahun'] ?? null;
$isbn      = trim($_POST['isbn'] ?? ''); // Boleh kosong/opsional
$stok      = $_POST['stok'] ?? null;
$kategori  = $_POST['kategori'] ?? '';

// Validasi hanya mengecek field wajib (ISBN tidak disyaratkan wajib)
if (!$id || empty($judul) || empty($pengarang)) {
    $_SESSION['flash'] = ['type' => 'danger', 'pesan' => 'Data tidak lengkap. Harap isi bidang yang wajib.'];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

try {
    // Kueri UPDATE data buku berdasarkan ID
    $stmt = $pdo->prepare(
        "UPDATE buku 
         SET judul = :judul, 
             pengarang = :pengarang, 
             tahun = :tahun, 
             isbn = :isbn, 
             stok = :stok, 
             kategori = :kategori 
         WHERE id = :id"
    );

    $stmt->execute([
        'judul'     => $judul,
        'pengarang' => $pengarang,
        'tahun'     => (int) $tahun,
        'isbn'      => $isbn, // Jika kosong akan tersimpan sebagai string kosong ('')
        'stok'      => (int) $stok,
        'kategori'  => $kategori,
        'id'        => $id,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil diperbarui.'];
    header('Location: list.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['flash'] = ['type' => 'danger', 'pesan' => 'Gagal memperbarui data: ' . $e->getMessage()];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}