# Panduan Arsitektur & Struktur Proyek (SIMPK - Digital)

Dokumen ini berisi analisis struktur, tampilan (frontend), dan logika (backend) dari aplikasi **Sistem Informasi Medis Penyebab Kematian Digital (SIMPK - Digital)**. Tujuannya adalah untuk menjaga konsistensi konteks saat AI (atau pengembang) melakukan *generate* kode baru, *debugging*, atau penambahan fitur di ruang lingkup proyek ini.

---

## 1. Teknologi (Tech Stack)

- **Backend**: Laravel v13.x (PHP ^8.3).
- **Database / ORM**: Eloquent ORM (menggunakan model standar Laravel). Data spesifik form seringkali disimpan di dalam kolom bertipe JSON (`data` pada tabel `certificates`).
- **Frontend Assets**: Vite + Tailwind CSS v4.
- **Templating**: Blade Templating Engine (`.blade.php`).
- **Styling UI**: Menggabungkan Tailwind CSS (seringkali di-_load_ via CDN di view untuk *rapid development*) bersamaan dengan custom CSS root variables (seperti `--brand`, `--ink`, `--bg-app`) yang disematkan secara *inline* atau internal `<style>`.
- **Iconography**: [Phosphor Icons](https://phosphoricons.com/).

---

## 2. Struktur Direktori Utama

### Backend (Logika & Data)
- `app/Http/Controllers/`: Tempat logika utama aplikasi.
  - `DashboardController.php`: Menangani data analitik dan tabel beranda (`/`).
  - `CertificateController.php`: Menangani *Core Logic* (CRUD) pembuatan draft, validasi data ganda, penyimpanan final, hapus, dan integrasi data `Patient` & `Doctor`.
  - `SettingsController.php` & `NotificationController.php`: Menangani pengaturan aplikasi dan pengumuman.
- `app/Models/`: Model database.
  - Entitas Utama: `Certificate`, `Patient`, `Doctor`, `ActivityLog`.
  - Tabel `certificates` memiliki relasi `BelongsTo` ke `Patient` dan `Doctor`.
- `routes/web.php`: Pusat definisi _routing_. Semua rute view dan rute API internal (digunakan oleh AJAX frontend) didefinisikan di sini.

### Frontend (Tampilan)
- `resources/views/`: Berisi semua halaman Blade aplikasi.
  - `dashboard.blade.php`: Halaman beranda utama.
  - `simpk/`: Halaman *listing* utama (contoh: `certificates.blade.php`, `drafts.blade.php`, `pilih-sertifikat.blade.php`).
  - `form/`: Halaman formulir input data (contoh: `kematian-dewasa.blade.php`, `kematian-bayi.blade.php`).
  - `output/`: Halaman untuk *preview* atau cetak hasil (contoh: `output-dewasa.blade.php`).
  - `partials/`: Potongan tampilan yang sering di- *reuse* (seperti `sidebar.blade.php`, `watermark.blade.php`).

---

## 3. Logika & Alur Aplikasi (Flow)

### A. Pengelolaan Sertifikat (Create & Update)
1. **User Membuka Form**: Rute `/form` -> pilih jenis (Dewasa/Bayi) -> diarahkan ke view di `resources/views/form/`.
2. **AJAX Submission**: Form disubmit menggunakan JavaScript (Fetch API/AJAX) menuju rute internal API di `web.php` (contoh: `POST /api/drafts/save`).
3. **Logika Kontroler (`CertificateController`)**:
   - **Validasi Redundansi**: Memeriksa apakah NIK / NRM sudah ada di draft/sertifikat yang lain untuk mencegah duplikasi.
   - **Resolusi Relasi**: Secara otomatis mencari atau membuat baris data untuk tabel `Patient` (berdasarkan NRM/NIK) dan `Doctor` (berdasarkan SIP).
   - **Penyimpanan JSON**: Seluruh isian detail klinis (seperti penyebab kematian ICD, nama ibu, dll) dibungkus lalu disimpan di kolom `data` (JSON array/object) milik model `Certificate`.
   - **Status Flow**: Status sertifikat mengikuti siklus `Draft` -> `Saved` -> `Printed`.

### B. Respon API / AJAX
Setiap permintaan AJAX selalu merespon dengan format JSON standar aplikasi ini:
```json
{
  "success": true_or_false,
  "message": "Pesan balasan operasi",
  "id": "ID dari model (jika ada)"
}
```

---

## 4. Konvensi Kode & Aturan Konsistensi (Rules for AI)

Bila AI atau developer membuat kode baru untuk proyek ini, WAJIB mematuhi panduan berikut:

1. **Konsistensi Tampilan (UI/UX)**:
   - Gunakan skema warna yang sudah ada di CSS variables (contoh: `var(--brand)` untuk warna utama, `var(--bg-cell)` untuk latar belakang baris tabel).
   - Selalu gunakan [Phosphor Icons](https://phosphoricons.com/) untuk ikon UI (melalui class `<i class="ph-bold ph-*">`).
   - Komponen UI seperti Notifikasi, Modal, atau Tabel harus mempertahankan gaya (*style*) minimalis yang sama seperti pada `dashboard.blade.php` atau `certificates.blade.php`.

2. **Konsistensi Backend & Database**:
   - Jika membuat modul form medis baru, **jangan** menambahkan kolom secara *hardcode* di tabel utama jika sifatnya dinamis. Gunakan kolom `data` berbasis JSON di tabel `certificates` untuk menyimpan entri spesifik guna mempertahankan skalabilitas.
   - Jika mengubah status transaksi atau aksi krusial, wajb mencatat ke sistem log menggunakan `ActivityLog::log('Nama Aksi', 'Deskripsi...')` (lihat contoh di `CertificateController.php`).
   - Jangan lupa menangani *Error Response* secara spesifik (HTTP 422 untuk error validasi atau HTTP 404 jika tidak ditemukan).

3. **Struktur File & Routing**:
   - Komponen UI yang berulang (contoh: komponen tabel atau modal) letakkan di `resources/views/partials/`.
   - Gunakan rute `routes/web.php` untuk API yang dikonsumsi frontend internal dan bedakan dengan rute views menggunakan format komentar pemisah.

Dokumen `build.md` ini berlaku sebagai _Source of Truth_ (SOT) tentang pola arsitektur proyek SIMPK - Digital.
