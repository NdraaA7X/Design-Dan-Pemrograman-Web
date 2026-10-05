# Security Checklist & Audit Keamanan — SIMPUS-Mini

Dokumen ini mendokumentasikan hasil audit keamanan menyeluruh terhadap aplikasi SIMPUS-Mini pada **Jobsheet 11 — Keamanan Web Dasar**, mencakup analisis 5 kerentanan utama, perbandingan kode sebelum vs sesudah perbaikan, serta bukti pengujian empiris.

---

## 🛡️ Matriks Audit Keamanan

| # | Kerentanan | Ditemukan di File | Status Sebelum Audit | Perbaikan / Status Sesudah | Bukti Pengujian |
|---|---|---|---|---|---|
| **1** | **SQL Injection** | `auth/proses_login.php`<br>`buku/proses_*.php`<br>`buku/hapus.php`<br>`anggota/proses_*.php`<br>`anggota/hapus.php`<br>`buku/list.php`, `anggota/list.php` | Sudah menggunakan *Prepared Statement* (`$pdo->prepare()` + parameter binding) sejak Jobsheet 08. | **Sudah Aman (Konfirmasi Audit)**.<br>Tidak ada satupun query SQL yang menyatukan input pengguna via konkatenasi string. | Input `' OR '1'='1` pada field username login menghasilkan *"Username atau password salah"* (bukan bypass autentikasi). |
| **2** | **Cross-Site Scripting (XSS)** | `buku/list.php`<br>`buku/edit.php`<br>`anggota/list.php`<br>`anggota/edit.php`<br>`includes/header.php`<br>`auth/register.php` | Output data dari database dan `$_GET` dicetak langsung via `echo` tanpa escaping entitas HTML (`echo $buku['judul']`). | **Diperbaiki di Jobsheet 11**.<br>Dibuat helper `includes/helpers.php` berisi fungsi `e($value)` yang membungkus `htmlspecialchars($val, ENT_QUOTES, 'UTF-8')`. Seluruh output dinamis dibungkus `e()`. | Input judul buku `<script>alert(1)</script>` tampil harfiah sebagai teks di tabel tanpa memunculkan alert pop-up JavaScript. |
| **3** | **Cross-Site Request Forgery (CSRF)** | Seluruh form `POST`:<br>`auth/login.php`<br>`auth/register.php`<br>`buku/tambah.php`<br>`buku/edit.php`<br>`buku/list.php` (hapus)<br>`anggota/tambah.php`<br>`anggota/edit.php`<br>`anggota/list.php` (hapus) | Form hanya mengandalkan method `POST` dan otorisasi sesi. Situs eksternal dapat memicu aksi state-changing tanpa sepengetahuan user. | **Diperbaiki di Jobsheet 11**.<br>Dibuat helper `includes/csrf.php` (`csrf_token()`, `csrf_field()`, `csrf_verify()`). Token acak 32-byte kriptografis disimpan di session dan divalidasi via `hash_equals()` pada setiap request POST. | Mengirim POST via `curl` tanpa token CSRF ke `buku/proses_tambah.php` ditolak server dengan respon **HTTP 403 Forbidden**. |
| **4** | **Validasi & Sanitasi Input** | `buku/proses_*.php`<br>`anggota/proses_*.php`<br>`buku/edit.php`<br>`anggota/edit.php` | Validasi server-side sudah ada sejak Jobsheet 07 (`is_numeric`, `empty`, `strlen`), namun parameter ID belum di-casting secara eksplisit. | **Diperbaiki & Diperkuat di Jobsheet 11**.<br>Ditambahkan type-casting integer eksplisit `(int) $id` pada hidden input form edit dan query proses edit/hapus. | Mengirim string non-numerik pada parameter ID ditolak atau di-cast ke angka 0 aman tanpa memicu crash database. |
| **5** | **Session Fixation** | `auth/proses_login.php` | ID sesi pengunjung dipertahankan sebelum dan sesudah proses login, membuka celah session hijacking jika ID sesi telah diketahui penyerang. | **Diperbaiki di Jobsheet 11**.<br>Menambahkan `session_regenerate_id(true)` tepat setelah `password_verify()` berhasil sebelum mengisi data `$_SESSION`. | ID sesi PHP berganti ke nilai acak baru setelah login berhasil, dan file sesi lama di server langsung dimusnahkan. |

---

## 🔍 Detail Implementasi & Desain Keamanan

### 1. Desain Fungsi `e()` (XSS Prevention)
```php
// includes/helpers.php
function e($value)
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}
```
* **`ENT_QUOTES`**: Meng-escape tanda petik ganda (`"`) dan tanda petik tunggal (`'`). Krusial untuk atribut HTML seperti `value="<?php echo e($val); ?>"`.
* **`UTF-8`**: Mencegah serangan multi-byte encoding bypass.
* **`(string) ($value ?? '')`**: Mencegah fatal error di PHP 8.1+ jika parameter bernilai `null`.

### 2. Desain Token CSRF
```php
// includes/csrf.php
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_verify() {
    $token = $_POST['csrf_token'] ?? '';
    if ($token === '' || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('Permintaan ditolak: token CSRF tidak valid atau kedaluwarsa.');
    }
}
```
* **`random_bytes(32)`**: Sumber keacakan CSPRNG (Cryptographically Secure Pseudo-Random Number Generator).
* **`hash_equals()`**: Membandingkan string dengan waktu konstan (*constant-time comparison*) untuk mencegah *timing attack*.

### 3. Urutan Eksekusi Guard (Catatan Implementasi Kritis)
Pada seluruh file pemroses aksi (`proses_tambah.php`, `proses_edit.php`, `hapus.php`):
```php
// 1. Guard Autentikasi terlebih dahulu
require __DIR__ . '/../includes/auth.php';

// 2. Guard CSRF kedua
require __DIR__ . '/../includes/csrf.php';
csrf_verify();

// 3. Koneksi Database terakhir
require __DIR__ . '/../includes/koneksi.php';
```
> **Alasan Urutan**:
> Pengunjung yang belum login langsung dialihkan (HTTP 302) ke halaman login sebelum memeriksa token CSRF atau menyentuh database. Hal ini menghemat sumber daya server dan mencegah kebocoran informasi token kepada pihak yang tidak terautentikasi.
