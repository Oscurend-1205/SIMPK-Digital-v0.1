# SIMPK Digital - Dokumentasi Use Case

## Ringkasan
Dokumen ini menjelaskan secara detail seluruh Use Case yang ada dalam sistem **Sistem Informasi Medis Penyebab Kematian Digital (SIMPK - Digital)** untuk RS Wava Husada Malang.

---

## Aktor Sistem

### 1. Petugas Rekam Medis
- **Deskripsi:** Petugas yang bertanggung jawab atas pencatatan, pengelolaan, dan penerbitan sertifikat kematian.
- **Akses:** Penuh pada modul sertifikat, arsip, dan pencarian.

### 2. Dokter
- **Deskripsi:** Tenaga medis yang dapat mengisi dan melihat sertifikat kematian.
- **Akses:** Terbatas pada pengisian form dan melihat sertifikat yang telah diterbitkan.

### 3. Administrator
- **Deskripsi:** Pengelola sistem dengan akses penuh ke pengaturan dan manajemen data master.
- **Akses:** Pengaturan sistem, manajemen dokter, notifikasi, dan reset aplikasi.

---

## Daftar Use Case

### Modul: Sistem Autentikasi

#### UC-01: Login ke Sistem
- **Aktor:** Petugas Rekam Medis, Dokter, Administrator
- **Deskripsi:** Pengguna memasukkan username dan password untuk mengakses sistem.
- **Pre-kondisi:** Pengguna memiliki kredensial yang valid.
- **Post-kondisi:** Pengguna berhasil masuk ke dashboard utama.
- **Alur Utama:**
  1. Pengguna membuka halaman login
  2. Pengguna memasukkan username dan password
  3. Sistem memvalidasi kredensial
  4. Sistem mengarahkan pengguna ke dashboard

#### UC-02: Logout dari Sistem
- **Aktor:** Petugas Rekam Medis, Dokter, Administrator
- **Deskripsi:** Pengguna keluar dari sistem dengan aman.
- **Pre-kondisi:** Pengguna sedang login.
- **Post-kondisi:** Sesi pengguna dihapus dan pengguna diarahkan ke halaman login.

---

### Modul: Manajemen Sertifikat Kematian

#### UC-03: Memilih Jenis Sertifikat
- **Aktor:** Petugas Rekam Medis
- **Deskripsi:** Memilih jenis sertifikat yang akan dibuat (Dewasa atau Bayi).
- **Pre-kondisi:** Pengguna telah login.
- **Post-kondisi:** Pengguna diarahkan ke form yang sesuai.

#### UC-04: Mengisi Form Kematian Dewasa
- **Aktor:** Petugas Rekam Medis, Dokter
- **Deskripsi:** Mengisi formulir sertifikat kematian untuk pasien dewasa/anak.
- **Pre-kondisi:** Pengguna memilih jenis sertifikat dewasa.
- **Post-kondisi:** Data tersimpan sebagai draft atau sertifikat final.
- **Data yang Diisi:**
  - Data Pasien (Nama, NIK, NRM, Tanggal Lahir, Jenis Kelamin)
  - Data Kematian (Tanggal, Jam, Tempat)
  - Penyebab Kematian (Langsung, Antara, Dasar dengan kode ICD-10)
  - Data Dokter (Nama, SIP)

#### UC-05: Mengisi Form Kematian Bayi (Perinatal)
- **Aktor:** Petugas Rekam Medis, Dokter
- **Deskripsi:** Mengisi formulir sertifikat kematian perinatal/neonatal.
- **Pre-kondisi:** Pengguna memilih jenis sertifikat bayi.
- **Post-kondisi:** Data tersimpan sebagai draft atau sertifikat final.
- **Data yang Diisi:**
  - Data Bayi (Nama, NIK, Berat, Usia Kehamilan)
  - Data Ibu (Nama, Umur, Riwayat Kehamilan)
  - Data Kematian (Tanggal, Jam, Tempat)
  - Penyebab Kematian (format perinatal)

#### UC-06: Menyimpan Draft Sertifikat
- **Aktor:** Petugas Rekam Medis, Dokter
- **Deskripsi:** Menyimpan data sementara sebelum sertifikat selesai.
- **Pre-kondisi:** Formulir sedang diisi.
- **Post-kondisi:** Data tersimpan dengan status "Draft".
- **Fitur:** Auto-save untuk mencegah kehilangan data.

#### UC-07: Melanjutkan/Edit Draft
- **Aktor:** Petugas Rekam Medis, Dokter
- **Deskripsi:** Mengedit draft yang tersimpan untuk dilanjutkan.
- **Pre-kondisi:** Ada draft yang tersimpan.
- **Post-kondisi:** Draft dapat diedit dan diselesaikan.

#### UC-08: Menyelesaikan Sertifikat Final
- **Aktor:** Petugas Rekam Medis, Dokter
- **Deskripsi:** Menyelesaikan draft menjadi sertifikat final yang siap dicetak.
- **Pre-kondisi:** Draft telah lengkap dan valid.
- **Post-kondisi:** Status sertifikat berubah menjadi "Final/Saved".

#### UC-09: Memvalidasi Data Pasien (NIK/NRM)
- **Aktor:** Petugas Rekam Medis, Dokter
- **Deskripsi:** Validasi untuk mencegah duplikasi data pasien.
- **Pre-kondisi:** Data pasien diinput.
- **Post-kondisi:** Sistem memberikan notifikasi jika data duplikat ditemukan.

#### UC-10: Validasi UCOD (Underlying Cause of Death)
- **Aktor:** Petugas Rekam Medis, Dokter
- **Deskripsi:** Validasi berjenjang penyebab kematian sesuai standar ICD-10.
- **Pre-kondisi:** Penyebab kematian diinput.
- **Post-kondisi:** Data valid dan sesuai format standar.
- **Struktur Validasi:**
  - Penyebab Langsung (Immediate Cause)
  - Penyebab Antara (Intermediate Cause)
  - Penyebab Dasar (Underlying Cause)

#### UC-11: Menghapus Draft/Sertifikat
- **Aktor:** Petugas Rekam Medis
- **Deskripsi:** Menghapus draft atau sertifikat yang tidak diperlukan.
- **Pre-kondisi:** Draft/sertifikat ada dalam sistem.
- **Post-kondisi:** Data dihapus dari database.

---

### Modul: Manajemen Dokumen & Arsip

#### UC-12: Melihat Daftar Sertifikat
- **Aktor:** Petugas Rekam Medis, Dokter, Administrator
- **Deskripsi:** Melihat seluruh sertifikat yang telah diterbitkan.
- **Pre-kondisi:** Pengguna telah login.
- **Post-kondisi:** Daftar sertifikat ditampilkan dalam tabel.

#### UC-13: Melihat Daftar Draft
- **Aktor:** Petugas Rekam Medis, Dokter, Administrator
- **Deskripsi:** Melihat seluruh draft yang belum selesai.
- **Pre-kondisi:** Pengguna telah login.
- **Post-kondisi:** Daftar draft ditampilkan dalam tabel.

#### UC-14: Pencarian Sertifikat
- **Aktor:** Petugas Rekam Medis, Dokter
- **Deskripsi:** Mencari sertifikat berdasarkan kriteria tertentu.
- **Pre-kondisi:** Ada data sertifikat dalam sistem.
- **Post-kondisi:** Hasil pencarian ditampilkan.
- **Kriteria Pencarian:** Nama pasien, NIK, NRM, Tanggal kematian.

#### UC-15: Filter Sertifikat
- **Aktor:** Petugas Rekam Medis, Dokter
- **Deskripsi:** Memfilter sertifikat berdasarkan periode atau status.
- **Pre-kondisi:** Ada data sertifikat dalam sistem.
- **Post-kondisi:** Sertifikat yang sesuai filter ditampilkan.

#### UC-16: Pratinjau Sertifikat Dewasa
- **Aktor:** Petugas Rekam Medis, Dokter
- **Deskripsi:** Melihat pratinjau sertifikat dewasa sebelum dicetak.
- **Pre-kondisi:** Sertifikat dewasa telah selesai.
- **Post-kondisi:** Tampilan sertifikat dengan watermark ditampilkan.

#### UC-17: Pratinjau Sertifikat Bayi
- **Aktor:** Petugas Rekam Medis, Dokter
- **Deskripsi:** Melihat pratinjau sertifikat bayi sebelum dicetak.
- **Pre-kondisi:** Sertifikat bayi telah selesai.
- **Post-kondisi:** Tampilan sertifikat dengan watermark ditampilkan.

#### UC-18: Mencetak Sertifikat
- **Aktor:** Petugas Rekam Medis, Dokter
- **Deskripsi:** Mencetak sertifikat dalam format resmi rumah sakit.
- **Pre-kondisi:** Pratinjau sertifikat ditampilkan.
- **Post-kondisi:** Sertifikat dicetak dalam format print-ready.

---

### Modul: Manajemen Data Dokter

#### UC-19: Mencari Data Dokter
- **Aktor:** Petugas Rekam Medis, Dokter
- **Deskripsi:** Mencari data dokter berdasarkan nama atau SIP.
- **Pre-kondisi:** Database dokter tersedia.
- **Post-kondisi:** Data dokter yang sesuai ditampilkan.

#### UC-20: Menambah Data Dokter Baru
- **Aktor:** Administrator
- **Deskripsi:** Menambahkan data dokter baru ke dalam sistem.
- **Pre-kondisi:** Administrator telah login dan mengakses pengaturan.
- **Post-kondisi:** Data dokter baru tersimpan dalam database.

#### UC-21: Menghapus Data Dokter
- **Aktor:** Administrator
- **Deskripsi:** Menghapus data dokter dari sistem.
- **Pre-kondisi:** Data dokter ada dalam sistem.
- **Post-kondisi:** Data dokter dihapus dari database.

---

### Modul: Pengaturan & Administrasi

#### UC-22: Mengakses Menu Pengaturan
- **Aktor:** Administrator
- **Deskripsi:** Mengakses halaman pengaturan sistem.
- **Pre-kondisi:** Administrator telah login.
- **Post-kondisi:** Halaman pengaturan ditampilkan.

#### UC-23: Autentikasi Pengaturan (Password)
- **Aktor:** Administrator
- **Deskripsi:** Memasukkan password untuk mengakses fitur pengaturan sensitif.
- **Pre-kondisi:** Administrator mencoba mengakses fitur sensitif.
- **Post-kondisi:** Akses diberikan jika password benar.

#### UC-24: Reset Aplikasi
- **Aktor:** Administrator
- **Deskripsi:** Membersihkan seluruh data simulasi/testing.
- **Pre-kondisi:** Administrator telah terautentikasi.
- **Post-kondisi:** Database di-reset ke kondisi awal.
- **Catatan:** Digunakan sebelum deployment ke produksi.

#### UC-25: Melihat Log Aktivitas
- **Aktor:** Administrator
- **Deskripsi:** Melihat riwayat aktivitas pengguna dalam sistem.
- **Pre-kondisi:** Administrator telah login.
- **Post-kondisi:** Log aktivitas ditampilkan.

---

### Modul: Manajemen Notifikasi

#### UC-26: Melihat Pengumuman/Notifikasi
- **Aktor:** Petugas Rekam Medis, Dokter, Administrator
- **Deskripsi:** Melihat pengumuman atau notifikasi sistem.
- **Pre-kondisi:** Pengguna telah login.
- **Post-kondisi:** Daftar notifikasi ditampilkan.

#### UC-27: Membuat Notifikasi Baru
- **Aktor:** Administrator
- **Deskripsi:** Membuat pengumuman atau notifikasi baru.
- **Pre-kondisi:** Administrator telah login dan terautentikasi.
- **Post-kondisi:** Notifikasi baru ditampilkan ke semua pengguna.

#### UC-28: Menghapus Notifikasi
- **Aktor:** Administrator
- **Deskripsi:** Menghapus notifikasi yang tidak relevan.
- **Pre-kondisi:** Notifikasi ada dalam sistem.
- **Post-kondisi:** Notifikasi dihapus dari sistem.

---

## Matriks Akses Aktor

| Use Case | Petugas Rekam Medis | Dokter | Administrator |
|----------|---------------------|--------|---------------|
| UC-01: Login | ✓ | ✓ | ✓ |
| UC-02: Logout | ✓ | ✓ | ✓ |
| UC-03: Memilih Jenis Sertifikat | ✓ | ✗ | ✗ |
| UC-04: Mengisi Form Dewasa | ✓ | ✓ | ✗ |
| UC-05: Mengisi Form Bayi | ✓ | ✓ | ✗ |
| UC-06: Menyimpan Draft | ✓ | ✓ | ✗ |
| UC-07: Edit Draft | ✓ | ✓ | ✗ |
| UC-08: Selesaikan Sertifikat | ✓ | ✓ | ✗ |
| UC-09: Validasi Data Pasien | ✓ | ✓ | ✗ |
| UC-10: Validasi UCOD | ✓ | ✓ | ✗ |
| UC-11: Hapus Draft/Sertifikat | ✓ | ✗ | ✗ |
| UC-12: Lihat Daftar Sertifikat | ✓ | ✓ | ✓ |
| UC-13: Lihat Daftar Draft | ✓ | ✓ | ✓ |
| UC-14: Pencarian Sertifikat | ✓ | ✓ | ✗ |
| UC-15: Filter Sertifikat | ✓ | ✓ | ✗ |
| UC-16: Pratinjau Dewasa | ✓ | ✓ | ✗ |
| UC-17: Pratinjau Bayi | ✓ | ✓ | ✗ |
| UC-18: Cetak Sertifikat | ✓ | ✓ | ✗ |
| UC-19: Cari Dokter | ✓ | ✓ | ✗ |
| UC-20: Tambah Dokter | ✗ | ✗ | ✓ |
| UC-21: Hapus Dokter | ✗ | ✗ | ✓ |
| UC-22: Akses Pengaturan | ✗ | ✗ | ✓ |
| UC-23: Autentikasi Pengaturan | ✗ | ✗ | ✓ |
| UC-24: Reset Aplikasi | ✗ | ✗ | ✓ |
| UC-25: Lihat Log Aktivitas | ✗ | ✗ | ✓ |
| UC-26: Lihat Notifikasi | ✓ | ✓ | ✓ |
| UC-27: Buat Notifikasi | ✗ | ✗ | ✓ |
| UC-28: Hapus Notifikasi | ✗ | ✗ | ✓ |

---

## Catatan Penting

1. **Validasi Data:** Sistem melakukan validasi otomatis untuk mencegah duplikasi NIK/NRM dan memastikan kode ICD-10 yang valid.
2. **Auto-Save:** Fitur draft dan auto-save memastikan data tidak hilang jika pengguna keluar secara tidak sengaja.
3. **Watermark:** Sertifikat yang dicetak dilengkapi watermark RS Wava Husada Malang untuk keamanan.
4. **Audit Trail:** Semua aktivitas penting dicatat dalam log aktivitas untuk audit.
5. **Reset Aplikasi:** Fitur reset hanya untuk administrator dan digunakan sebelum deployment produksi.

---

*Dokumen ini dibuat berdasarkan analisis kode sumber dan struktur proyek SIMPK Digital v0.1*
