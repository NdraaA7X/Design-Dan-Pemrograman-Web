-- Jobsheet 10 Latihan Tambahan 1: Seed akun admin
-- Jalankan file ini di psql untuk membuat akun admin pertama:
--   psql -d simpus_mini -f sql/03_admin_seed.sql
--
-- Catatan: password yang diset adalah 'admin123'
-- Hash di bawah di-generate via: password_hash('admin123', PASSWORD_DEFAULT)
-- Kalau expired atau invalid, jalankan script PHP berikut untuk re-generate hash:
--
--   <?php echo password_hash('admin123', PASSWORD_DEFAULT); ?>
--
-- Lalu update kolom password-nya manual di psql.

INSERT INTO users (nama, username, password, role)
VALUES (
    'Administrator',
    'admin',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin'
)
ON CONFLICT (username) DO UPDATE
    SET role = 'admin',
        nama = 'Administrator';

-- Verifikasi:
SELECT id, nama, username, role FROM users ORDER BY id;
