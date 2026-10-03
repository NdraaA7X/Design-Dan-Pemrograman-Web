<?php
// ================================================================
// HELPER: Generate hash password untuk akun admin
// Akses: http://localhost:8000/buat_admin.php
// PENTING: Hapus file ini setelah selesai dipakai!
// ================================================================

// Jangan bisa diakses kalau sudah ada DB connection dan sudah ada admin
session_start();

$password_admin = 'admin123'; // ganti sesuai keinginan
$hash = password_hash($password_admin, PASSWORD_DEFAULT);

echo "<!DOCTYPE html><html lang='id'><head><meta charset='UTF-8'>";
echo "<title>Buat Akun Admin</title>";
echo "<style>body{font-family:monospace;padding:2rem;max-width:700px;margin:auto;}
      .box{background:#f1f5f9;padding:1rem;border-radius:6px;word-break:break-all;}
      .ok{color:green;font-weight:bold;} .warn{color:orange;} .err{color:red;}
      button{background:#1e40af;color:#fff;border:none;padding:.6rem 1.2rem;border-radius:4px;cursor:pointer;}</style>";
echo "</head><body>";
echo "<h2>Helper: Buat Akun Admin — Jobsheet 10 Latihan Tambahan</h2>";
echo "<p class='warn'>⚠️ Hapus file <code>buat_admin.php</code> ini setelah selesai dipakai!</p>";
echo "<hr>";

echo "<h3>1. Hash yang di-generate untuk password '<code>$password_admin</code>':</h3>";
echo "<div class='box'>$hash</div>";

echo "<h3>2. Buat akun admin langsung ke database:</h3>";

require_once __DIR__ . '/includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buat_admin'])) {
    $nama_admin = trim($_POST['nama'] ?? 'Administrator');
    $user_admin = trim($_POST['username'] ?? 'admin');
    $pass_admin = trim($_POST['password'] ?? 'admin123');

    if (strlen($pass_admin) < 6) {
        echo "<p class='err'>Password minimal 6 karakter!</p>";
    } else {
        $hash_baru = password_hash($pass_admin, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO users (nama, username, password, role)
                 VALUES (:nama, :username, :password, 'admin')
                 ON CONFLICT (username) DO UPDATE
                     SET role = 'admin', nama = :nama, password = :password"
            );
            $stmt->execute([
                'nama'     => $nama_admin,
                'username' => $user_admin,
                'password' => $hash_baru,
            ]);
            echo "<p class='ok'>✅ Akun admin '<code>$user_admin</code>' berhasil dibuat/diperbarui!</p>";
            echo "<p>Sekarang login dengan username: <strong>$user_admin</strong> dan password: <strong>$pass_admin</strong></p>";
            echo "<p><a href='auth/login.php'>Pergi ke halaman Login →</a></p>";
        } catch (PDOException $e) {
            echo "<p class='err'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}

// Tampilkan daftar user yang ada
$users = $pdo->query("SELECT id, nama, username, role FROM users ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
echo "<h3>3. User yang ada di database:</h3>";
if (empty($users)) {
    echo "<p>Belum ada user.</p>";
} else {
    echo "<table border='1' cellpadding='8' style='border-collapse:collapse'>";
    echo "<tr><th>ID</th><th>Nama</th><th>Username</th><th>Role</th></tr>";
    foreach ($users as $u) {
        $roleStyle = $u['role'] === 'admin' ? 'color:green;font-weight:bold' : '';
        echo "<tr><td>{$u['id']}</td><td>" . htmlspecialchars($u['nama']) . "</td>"
           . "<td>" . htmlspecialchars($u['username']) . "</td>"
           . "<td style='$roleStyle'>{$u['role']}</td></tr>";
    }
    echo "</table>";
}

echo "<h3>4. Buat / Update Akun Admin:</h3>";
echo "<form method='post'>
    <p><label>Nama: <input type='text' name='nama' value='Administrator' style='padding:.4rem;width:300px;'></label></p>
    <p><label>Username: <input type='text' name='username' value='admin' style='padding:.4rem;width:300px;'></label></p>
    <p><label>Password: <input type='text' name='password' value='admin123' style='padding:.4rem;width:300px;'></label>
    <small>(min 6 karakter)</small></p>
    <p><button type='submit' name='buat_admin' value='1'>Buat / Update Akun Admin</button></p>
</form>";

echo "<hr><p class='warn'>Hapus file <code>buat_admin.php</code> ini setelah selesai!</p>";
echo "</body></html>";
?>
