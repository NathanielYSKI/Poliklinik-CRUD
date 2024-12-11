# Panduan Instalasi dan Import Database

## Persiapan
Pastikan Anda telah menginstal aplikasi server seperti XAMPP atau lainnya yang mendukung PHP, MySQL, dan PHPMyAdmin.

## Langkah-langkah Import SQL
1. **Akses PHPMyAdmin**:
   - Buka browser dan masuk ke PHPMyAdmin melalui URL:
     ```
     http://localhost/phpmyadmin
     ```

2. **Buat Database Baru**:
   - Klik tab **Database** di bagian atas.
   - Masukkan nama database, `poliklinik`.
   - Klik tombol **Create**.

3. **Import File SQL**:
   - Pilih database yang baru dibuat dari daftar di sidebar kiri.
   - Klik tab **Import** di bagian atas.
   - Pada bagian **File to Import**, klik **Choose File** dan pilih file SQL Anda (misalnya: `database.sql`).
   - Klik tombol **Go**.

4. **Verifikasi Import**:
   - Setelah proses import selesai, pastikan pesan berikut muncul:
     ```
     Import has been successfully finished.
     ```
   - Klik nama database di sidebar untuk memastikan tabel-tabel berhasil diimpor.

## Konfigurasi Database di Aplikasi
Pastikan konfigurasi aplikasi Anda mencantumkan detail berikut untuk koneksi database:
- **Host**: `localhost`
- **Database Name**: `poliklinik`
- **Username**: `root`
- **Password**: ``

## Catatan
- Jika terjadi kesalahan saat proses import, periksa kembali file SQL untuk memastikan tidak ada error.
- Pastikan versi MySQL kompatibel dengan file SQL yang Anda gunakan.

## Bantuan
Jika Anda mengalami kendala, silakan hubungi tim pengembang atau cek dokumentasi terkait.

**Login Admin**
Username    : Nathan
Password    : rahasia

---
