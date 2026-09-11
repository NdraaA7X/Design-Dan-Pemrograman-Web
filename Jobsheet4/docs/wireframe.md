+------------------------------------------------------------------+
| SIMPUS-Mini Beranda | Daftar Buku | Daftar Anggota | Login |
|------------------------------------------------------------------|
| |
| [ Registrasi Anggota Baru ] |
| Isi data di bawah untuk mendaftar sebagai anggota. |
| |
| Nama : [______________________] |
| Alamat : [______________________] |
| No. HP : [______________________] |  
| Email : [______________________] |
| Kata Sandi : [______________________] |
| Ulangi Sandi : [______________________] |
| |
| [ Daftar Sekarang ] |
| |  
| Sudah jadi anggota? [ Login di sini ] |
+------------------------------------------------------------------+

[Petugas Login] -> [Dashboard] -> [Menu "Anggota" > "Cek Tunggakan"]
-> [Sistem Filter: status = "Lewat Jatuh Tempo"]
-> [Tampilkan Daftar Anggota + Jumlah Hari Terlambat]
-> [Pilih Satu Anggota] -> [Lihat Detail Transaksi Terlambat]
-> [Kirim Peringatan / Hubungi Anggota] -> [Selesai]

Edge case yang mungkin terjadi: Petugas mendaftarkan anggota baru
dengan Nomor Identitas yang ternyata sudah pernah terdaftar (duplikat).

Aturan:

- Sebelum data disimpan, sistem mengecek apakah Nomor Identitas
  sudah ada di database anggota;
- Jika ditemukan duplikat, proses registrasi ditolak dan sistem
  menampilkan pesan bahwa nomor tersebut sudah terdaftar.
