# SIMPK - Digital 🏥✍️
### Sistem Informasi Medis Penyebab Kematian Digital (SIMPK - Digital)

Sistem Informasi Medis Penyebab Kematian Digital (SIMPK - Digital) adalah sebuah platform berbasis web yang dirancang untuk mendigitalisasi pencatatan, pengelolaan, dan penerbitan Sertifikat Medis Penyebab Kematian (SMPK). 

---

## 📌 Latar Belakang Proyek

Proyek ini dikembangkan secara khusus berdasarkan permintaan dari seorang klien yang merupakan **mahasiswa program studi Rekam Medis** sebagai bagian dari **Tugas Akhir (Skripsi)**. 

### 🔍 Masalah & Tujuan
* **Masalah:** Proses pencatatan penyebab kematian secara manual pada formulir kertas di rumah sakit rentan terhadap kesalahan penulisan (human error), hilangnya berkas fisik, ketidakcocokan kode diagnosis ICD-10, serta lambatnya penyusunan statistik mortalitas.
* **Tujuan:** Melakukan digitalisasi sertifikat kematian di **RS Wava Husada Malang** untuk mempermudah praktisi kesehatan dan petugas Rekam Medis dalam mencatat penyebab kematian secara cepat, akurat, dan terstandarisasi, sekaligus menyediakan penyimpanan arsip digital yang aman dan mudah diakses.

---

## 🌟 Fitur Utama

Aplikasi SIMPK - Digital memiliki fitur lengkap yang disesuaikan dengan kebutuhan administrasi rumah sakit dan standar Rekam Medis:

1. **Pemilihan Form Spesifik:**
   * **Form Kematian Dewasa (Umum):** Untuk mencatat penyebab kematian pasien dewasa dan anak-anak.
   * **Form Kematian Bayi (Perinatal):** Khusus untuk mencatat penyebab kematian perinatal/neonatal dengan format yang disesuaikan dengan standar rekam medis nasional.
2. **Pengisian Interaktif & Validasi UCOD (Underlying Cause of Death):**
   * Form pengisian penyebab kematian berjenjang (Penyebab Langsung, Penyebab Antara, Penyebab Dasar).
   * Validasi data pasien untuk memastikan integritas rekam medis sebelum diterbitkan.
3. **Sistem Draft & Auto-Save:**
   * Memungkinkan penyimpanan data sementara (draf) saat pengisian formulir belum lengkap, sehingga petugas dapat melanjutkannya kembali di lain waktu tanpa kehilangan data.
4. **Cetak & Pratinjau Sertifikat Resmi:**
   * Desain dokumen yang disesuaikan dengan format resmi rumah sakit.
   * Dilengkapi fitur tanda air (watermark) dinamis dan tata letak yang siap dicetak langsung (*print-ready layout*).
5. **Manajemen Dokumen & Riwayat Sertifikat:**
   * Halaman arsip yang menyajikan seluruh sertifikat yang telah diterbitkan maupun draf yang masih berjalan.
   * Filter pencarian cepat untuk mempermudah pencarian berkas pasien.
6. **Autentikasi & Reset Aplikasi:**
   * Menu Pengaturan (Settings) yang dilindungi kata sandi.
   * Opsi *Reset Application* untuk mempermudah pembersihan data simulasi/testing secara menyeluruh sebelum sistem diserahkan atau masuk ke tahap produksi.
7. **Sistem Deployment Teroptimasi (Shared Hosting):**
   * Dilengkapi utilitas deployment kustom (`build_split_zip.ps1`, `prepare_deploy.php`, dan `unzip.php`) untuk memecah proyek menjadi beberapa berkas ZIP berukuran kecil (di bawah 10MB). Fitur ini dibuat khusus untuk mengatasi keterbatasan batas unggah berkas (upload limit) pada shared hosting gratis seperti **InfinityFree**.

---

## 🛠️ Teknologi yang Digunakan

* **Backend Framework:** [Laravel 13.x](https://laravel.com)
* **Frontend Tools:** [Vite](https://vitejs.dev), [Tailwind CSS v4](https://tailwindcss.com) (Untuk tampilan premium, responsif, dan modern)
* **Admin Panel & Interaktivitas:** [Filament](https://filamentphp.com)
* **Database:** SQLite (Default lokal) / MySQL (Produksi)
* **Deployment Scripting:** PowerShell & PHP Custom Extraction Utility

---

## 💻 Panduan Instalasi Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan SIMPK - Digital di komputer lokal Anda:

### 1. Prasyarat Sistem
Pastikan perangkat Anda sudah terinstal:
* PHP `>= 8.3`
* Composer
* Node.js & NPM
* Web Server (seperti Laragon, XAMPP, atau Laravel Herd)

### 2. Langkah Setup Cepat
Proyek ini telah dikonfigurasi dengan script setup otomatis. Jalankan perintah berikut di terminal/PowerShell Anda:

```bash
# Clone atau masuk ke direktori proyek
cd "Sistem Informasi Medis Penyebab Kematian Digital (SIMPK - Digital)"

# Jalankan skrip setup otomatis
composer run setup
```
> Skrip di atas secara otomatis akan menginstal dependensi Composer & NPM, menyalin file `.env.example` ke `.env`, membuat kunci enkripsi aplikasi (`key:generate`), menjalankan migrasi database, dan membangun aset frontend.

### 3. Menjalankan Aplikasi
Setelah proses setup selesai, Anda dapat menjalankan server lokal dengan perintah:

```bash
composer run dev
```
> Perintah ini akan menjalankan server Laravel (`php artisan serve`) sekaligus server build frontend Vite (`npm run dev`) secara bersamaan. Aplikasi Anda akan dapat diakses melalui browser pada alamat default `http://127.0.0.1:8000`.

---

## 🚀 Alur Deployment ke Shared Hosting (seperti InfinityFree)

Karena adanya limitasi ukuran file unggahan pada beberapa shared hosting (misalnya maksimal 10MB per file), proyek ini memiliki utilitas khusus untuk mempermudah proses deploy:

1. **Pembuatan Split Zip:**
   Jalankan perintah PowerShell berikut di komputer lokal Anda:
   ```powershell
   ./build_split_zip.ps1
   ```
   Skrip ini akan membagi seluruh kode program Laravel, aset, dan folder `vendor` menjadi beberapa berkas ZIP berukuran di bawah 10MB:
   * `simpk-core.zip` (Kode aplikasi utama)
   * `simpk-vendor-framework.zip` (Pustaka framework utama)
   * `simpk-vendor-filament.zip` (Aset & pustaka panel admin Filament)
   * `simpk-vendor-others.zip` (Pustaka pendukung lainnya)

2. **Proses Unggah & Ekstraksi:**
   * Unggah semua berkas `.zip` tersebut beserta file `unzip.php` ke folder root hosting Anda (misal `htdocs` or `public_html`).
   * Akses alamat `domain-anda.com/unzip.php` melalui browser.
   * Gunakan panel kontrol yang tersedia pada `unzip.php` untuk mengekstrak seluruh arsip secara berurutan, mengonfigurasi file `.env`, serta menjalankan perintah artisan (`migrate`, `config:cache`, dll.) secara langsung dari browser tanpa akses SSH.

---

## 📂 Struktur Folder Penting

* `/app` - Berisi logika utama aplikasi (Controller, Model, dan Middleware).
  * `Http/Controllers/CertificateController.php` - Mengatur pembuatan, penyimpanan draf, pengeditan, dan pengunduhan sertifikat.
* `/database` - File migrasi database SQLite/MySQL dan data seeder.
* `/resources/views` - File template Blade untuk antarmuka pengguna.
  * `form/kematian-dewasa.blade.php` - Formulir sertifikat kematian umum/dewasa.
  * `form/kematian-bayi.blade.php` - Formulir sertifikat kematian bayi/perinatal.
  * `partials/watermark.blade.php` - Elemen watermark RS Wava Husada untuk pratinjau sertifikat.
* `unzip.php` - Alat bantu ekstraksi dan administrasi di shared hosting.
* `build_split_zip.ps1` - Script otomatis untuk mempersiapkan file siap deploy.

---

## 📄 Lisensi
Proyek ini dibuat untuk tujuan akademik dan kepatuhan administratif Rekam Medis di **RS Wava Husada Malang**. Seluruh hak cipta dan lisensi disesuaikan dengan ketentuan lisensi akademik dan institusi terkait.
