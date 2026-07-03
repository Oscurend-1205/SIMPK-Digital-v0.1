<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Sertifikat Medis Penyebab Kematian Dewasa - RS Wava Husada</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/RS-Wava-Husada.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        borderRadius: {
          'none': '0', 'sm': '0.25rem', DEFAULT: '0.375rem',
          'md': '0.375rem', 'lg': '0.375rem', 'xl': '0.375rem',
          '2xl': '0.5rem', '3xl': '0.75rem', 'full': '9999px',
        }
      }
    }
  }
</script>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet"/>
<script src="https://unpkg.com/@phosphor-icons/web"></script>
<style>
  :root {
    --brand: #0f6e72;
    --brand-md: #1da1a6;
    --brand-lt: #e8f7f7;
    --ink: #0c1924;
    --ink-mid: #3d5166;
    --ink-lt: #7a8fa0;
    --rule: #c8d6df;
    --bg-app: #eef2f5;
    --bg-cell: #f4f8fa;
    --white: #ffffff;
    --danger: #b91c1c;
    --radius: 6px;
  }
  body { font-family: 'IBM Plex Sans', sans-serif; background: var(--bg-app); color: var(--ink); font-size: 12px; zoom: 1.1; }
  html::-webkit-scrollbar, body::-webkit-scrollbar, .custom-scrollbar::-webkit-scrollbar { width: 5px; }
  html::-webkit-scrollbar-track, body::-webkit-scrollbar-track, .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
  html::-webkit-scrollbar-thumb, body::-webkit-scrollbar-thumb, .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

  .form-shell { max-width: 1080px; width: 100%; margin: 0 auto; }
  .form-input {
    width: 100%;
    border: 1px solid var(--rule) !important;
    border-radius: var(--radius) !important;
    font-size: 11px !important;
    line-height: 1.2 !important;
    padding: 3px 8px !important;
    height: 26px !important;
    background: var(--white) !important;
    color: var(--ink) !important;
    font-family: inherit !important;
    transition: border-color .15s, box-shadow .15s;
  }
  textarea.form-input { height: auto !important; min-height: 50px; }
  .form-input:focus { outline: none !important; border-color: var(--brand-md) !important; box-shadow: 0 0 0 2px rgba(29,161,166,.12) !important; }
  .form-input[readonly], .form-input:disabled { background: var(--bg-cell) !important; color: var(--ink-lt) !important; cursor: not-allowed !important; }
  .form-input.err { border-color: var(--danger) !important; }

  .section-card { background: var(--white); border: 1px solid var(--rule); border-radius: var(--radius); overflow: hidden; margin-bottom: 6px; }
  .section-header {
    display: flex; align-items: center; gap: 7px;
    background: linear-gradient(135deg, var(--brand) 0%, var(--brand-md) 100%);
    color: white;
    padding: 6px 12px;
    font-weight: 700;
    font-size: 10px;
    letter-spacing: .06em;
    text-transform: uppercase;
  }
  .section-header .sec-letter {
    width: 18px; height: 18px; border-radius: 4px;
    background: rgba(255,255,255,.18);
    display: flex; align-items: center; justify-content: center;
    font-size: 9px; font-weight: 800; flex-shrink: 0;
  }
  .section-body { padding: 8px 12px; }
  .section-hint { font-size: 10px; color: var(--ink-lt); margin-bottom: 6px; line-height: 1.45; }

  .field-label {
    display: block;
    font-size: 9px;
    font-weight: 700;
    color: var(--ink-mid);
    margin-bottom: 3px;
    text-transform: uppercase;
    letter-spacing: .06em;
  }
  .field-label .req { color: var(--danger); }

  .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 6px 10px; }
  .form-grid-2 { grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); }
  .form-grid-4 { grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); }
  .span-2 { grid-column: span 2; }
  .span-3 { grid-column: span 3; }
  .span-full { grid-column: 1 / -1; }

  .checkbox-label, .radio-label {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    color: var(--ink);
    cursor: pointer;
    white-space: nowrap;
  }
  .checkbox-label input, .radio-label input { width: 13px; height: 13px; accent-color: var(--brand); flex-shrink: 0; }
  .choice-row { display: flex; flex-wrap: wrap; gap: 10px 14px; align-items: center; min-height: 28px; }
  .choice-col { display: flex; flex-direction: column; gap: 6px; }

  .input-group { display: flex; align-items: stretch; }
  .input-group .form-input { border-radius: var(--radius) 0 0 var(--radius); }
  .input-group .input-addon {
    display: flex; align-items: center; padding: 0 8px;
    background: var(--bg-cell); border: 1px solid var(--rule); border-left: none;
    border-radius: 0 var(--radius) var(--radius) 0; font-size: 10px; color: var(--ink-lt); white-space: nowrap;
  }
  .datetime-row { display: flex; align-items: center; gap: 6px; }
  .datetime-row .form-input { flex: 1; min-width: 0; }
  .datetime-sep { font-size: 10px; color: var(--ink-lt); flex-shrink: 0; }

  .inner-box {
    border: 1px solid var(--rule);
    border-radius: var(--radius);
    padding: 8px 10px;
    background: var(--bg-cell);
  }

  .cause-table { width: 100%; min-width: 420px; border-collapse: collapse; font-size: 11px; }
  .cause-table th {
    text-align: left; font-size: 9px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; color: var(--ink-mid); padding: 6px 8px;
    background: var(--bg-cell); border: 1px solid var(--rule);
  }
  .cause-table td { padding: 5px 8px; border: 1px solid var(--rule); vertical-align: middle; }
  .cause-table .group-row td {
    background: var(--brand-lt); font-size: 9px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; color: var(--brand); padding: 4px 8px;
  }
  .cause-table .row-label { font-weight: 600; color: var(--ink-mid); white-space: nowrap; width: 1%; padding-right: 4px; }
  .cause-table .interval-col { width: 130px; }
  .cause-table input.form-input { padding: 2px 6px !important; font-size: 10.5px !important; height: 24px !important; border-radius: var(--radius) !important; }

  .icd-block { display: flex; flex-wrap: wrap; gap: 12px 20px; margin-top: 10px; padding-top: 10px; border-top: 1px dashed var(--rule); }
  .icd-field { display: flex; align-items: center; gap: 8px; flex: 1; min-width: 210px; }
  .icd-field label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--ink-mid); white-space: nowrap; }
  .icd-field input {
    flex: 1; max-width: 120px; font-family: 'IBM Plex Mono', monospace;
    font-size: 11px; letter-spacing: .12em; text-align: center; text-transform: uppercase;
  }

  .verify-grid { display: grid; grid-template-columns: 1fr 200px; gap: 12px; align-items: end; }
  .sig-box {
    border: 1px dashed var(--rule); border-radius: var(--radius); background: var(--white);
    min-height: 88px; display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 4px; padding: 10px; text-align: center; cursor: pointer; transition: background .15s;
  }
  .sig-box:hover { background: var(--bg-cell); }
  .sig-box i { font-size: 20px; color: var(--ink-lt); }
  .sig-box span { font-size: 9px; color: var(--ink-lt); line-height: 1.4; max-width: 160px; }

  .page-pad { padding-left: clamp(0.625rem, 2.5vw, 1.75rem); padding-right: clamp(0.625rem, 2.5vw, 1.75rem); }

  .top-bar, .meta-bar, .action-bar-wrap {
    background: var(--white); border: 1px solid var(--rule); border-radius: var(--radius);
  }
  .page-title { font-size: 14px; font-weight: 800; color: var(--ink); line-height: 1.3; }
  .page-sub { font-size: 10px; color: var(--ink-lt); margin-top: 2px; }

  @media (max-width: 640px) {
    .form-grid, .form-grid-2, .form-grid-4 { grid-template-columns: 1fr; }
    .span-2, .span-3, .span-full { grid-column: auto; }
    .verify-grid { grid-template-columns: 1fr; }
    .cause-table .interval-col { width: 90px; }
  }
  @media (min-width: 641px) and (max-width: 900px) {
    .form-grid, .form-grid-2, .form-grid-4 { grid-template-columns: repeat(2, 1fr); }
    .span-3 { grid-column: span 2; }
  }
</style>
</head>
<body class="min-h-screen">
@include('partials.watermark')

<main id="main-container" data-draft-id="{{ $certificate->id ?? '' }}" data-certificate="{{ json_encode($certificate ?? null) }}" class="min-h-screen page-pad py-5 relative">

  <div class="form-shell space-y-2.5">

    <!-- Top Navigation -->
    <div class="top-bar flex justify-between items-center px-3 py-2">
      <button type="button" onclick="handleBack()" class="flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-bold text-slate-600 hover:bg-slate-50 rounded transition-all group border border-slate-200">
        <i class="ph ph-arrow-left text-sm transition-transform group-hover:-translate-x-0.5"></i>
        Kembali
      </button>
      <div id="form-status-indicator" class="flex items-center gap-2">
        <span class="text-[9px] font-bold uppercase tracking-wider text-gray-400">Status</span>
        <div id="status-badge" class="flex items-center gap-1.5 bg-amber-50 text-amber-700 px-2 py-0.5 rounded border border-amber-200">
          <div class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></div>
          <span id="status-text" class="text-[9px] font-bold uppercase">Menulis Draft...</span>
        </div>
      </div>
    </div>

    <!-- Page Header -->
    <div class="flex flex-wrap justify-between items-start gap-3">
      <div>
        <span class="text-[9px] font-bold text-gray-400 tracking-wider uppercase">Formulir Elektronik</span>
        <h2 class="page-title mt-0.5">Sertifikat Medis Penyebab Kematian Dewasa</h2>
        <p class="page-sub">RS Wava Husada — Standar Pelaporan Penyebab Kematian (Adult)</p>
      </div>
    </div>

    <!-- Meta bar -->
    <div class="meta-bar flex flex-wrap justify-between items-center gap-2 px-3 py-2">
      <div class="flex items-center text-[10px] text-teal-800 font-medium">
        <i class="ph-bold ph-info mr-1.5 text-sm"></i>
        Formulir SKD — Sertifikat Medis Penyebab Kematian Dewasa
      </div>
      <div class="flex items-center gap-2">
        <span class="text-[10px] font-semibold text-slate-600">No. Sertifikat</span>
        <input class="form-input w-36 text-center" id="no_sertifikat" type="text" value="SKD-2024/00001"/>
        <input type="hidden" id="nama_pembuat"/>
        <input type="hidden" id="jabatan"/>
        <input type="hidden" id="unit"/>
        <input type="hidden" id="dpjp"/>
      </div>
    </div>

    <!-- ===== FORM SECTIONS ===== -->
    <div class="space-y-3">

      <!-- A. IDENTITAS JENAZAH -->
      <section class="section-card">
        <div class="section-header"><span class="sec-letter">A</span> Identitas Jenazah</div>
        <div class="section-body">
          <div class="form-grid">
            <div>
              <label class="field-label">Nomor Rekam Medis</label>
              <input id="nrm" class="form-input" type="text" placeholder="Ketik atau pilih"/>
            </div>
            <div>
              <label class="field-label">NIK</label>
              <input id="nik" class="form-input" type="text" placeholder="Ketik NIK"/>
            </div>
            <div class="span-2">
              <label class="field-label">Nama Lengkap</label>
              <input id="nama_lengkap" class="form-input" type="text" placeholder="Ketik nama lengkap"/>
            </div>
            <div>
              <label class="field-label">Jenis Kelamin</label>
              <div class="choice-row">
                <label class="radio-label"><input type="radio" name="gender" value="Laki-laki"/> Laki-laki</label>
                <label class="radio-label"><input type="radio" name="gender" value="Perempuan"/> Perempuan</label>
              </div>
            </div>
            <div>
              <label class="field-label">Agama</label>
              <select id="agama" class="form-input">
                <option value="">Pilih agama</option>
                <option>Islam</option><option>Kristen</option><option>Katolik</option>
                <option>Hindu</option><option>Budha</option><option>Konghucu</option>
              </select>
            </div>
            <div>
              <label class="field-label">Tanggal Lahir</label>
              <input id="tanggal_lahir" class="form-input" type="date"/>
            </div>
            <div class="span-2">
              <label class="field-label">Alamat Tempat Tinggal</label>
              <input id="alamat" class="form-input" type="text" placeholder="Ketik alamat lengkap"/>
            </div>
            <div>
              <label class="field-label">Provinsi</label>
              <div class="relative">
                <input id="provinsi" class="form-input" type="text" placeholder="Ketik provinsi" autocomplete="off"/>
                <div id="dropdown-provinsi" class="absolute z-50 w-full bg-white border border-slate-200 shadow-lg rounded mt-1 hidden max-h-40 overflow-y-auto"></div>
              </div>
            </div>
            <div>
              <label class="field-label">Kab/Kota</label>
              <div class="relative">
                <input id="kab_kota" class="form-input" type="text" placeholder="Ketik kab/kota" autocomplete="off"/>
                <div id="dropdown-kab_kota" class="absolute z-50 w-full bg-white border border-slate-200 shadow-lg rounded mt-1 hidden max-h-40 overflow-y-auto"></div>
              </div>
            </div>
            <div>
              <label class="field-label">Kecamatan</label>
              <div class="relative">
                <input id="kecamatan" class="form-input" type="text" placeholder="Ketik kecamatan" autocomplete="off"/>
                <div id="dropdown-kecamatan" class="absolute z-50 w-full bg-white border border-slate-200 shadow-lg rounded mt-1 hidden max-h-40 overflow-y-auto"></div>
              </div>
            </div>
            <div>
              <label class="field-label">Kelurahan</label>
              <div class="relative">
                <input id="kelurahan" class="form-input" type="text" placeholder="Ketik kelurahan" autocomplete="off"/>
                <div id="dropdown-kelurahan" class="absolute z-50 w-full bg-white border border-slate-200 shadow-lg rounded mt-1 hidden max-h-40 overflow-y-auto"></div>
              </div>
            </div>
            <div class="span-2">
              <label class="field-label">Status Kependudukan</label>
              <div class="choice-row">
                <label class="checkbox-label"><input type="checkbox" id="status_penduduk_tetap" class="status-kp-cb"/> Penduduk Tetap</label>
                <label class="checkbox-label"><input type="checkbox" id="status_penduduk_bukan" class="status-kp-cb"/> Bukan Penduduk</label>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- B. PERNYATAAN MENINGGAL -->
      <section class="section-card">
        <div class="section-header"><span class="sec-letter">B</span> Pernyataan Meninggal</div>
        <div class="section-body">
          <p class="section-hint font-bold border-b border-slate-200 pb-1 text-slate-600 mb-3">Yang Bersangkutan Dinyatakan Telah Meninggal Dunia Pada:</p>
          <div class="form-grid mb-4">
            <div>
              <label class="field-label">Hari</label>
              <select id="hari_kematian" class="form-input">
                <option>Pilih hari</option>
                <option>Senin</option><option>Selasa</option><option>Rabu</option>
                <option>Kamis</option><option>Jumat</option><option>Sabtu</option><option>Minggu</option>
              </select>
            </div>
            <div>
              <label class="field-label">Tanggal</label>
              <input id="tanggal_kematian" class="form-input" type="date"/>
            </div>
            <div>
              <label class="field-label">Jam (WIB)</label>
              <input id="jam_kematian" class="form-input" type="time"/>
            </div>
          </div>
          <p class="section-hint font-bold border-b border-slate-200 pb-1 text-slate-600 mb-3">Umur Saat Meninggal</p>
          <div class="form-grid">
            <div>
              <label class="field-label">Umur (Tahun)</label>
              <div class="input-group">
                <input id="umur_tahun" class="form-input" type="number" min="0" placeholder="0"/>
                <span class="input-addon">tahun</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- C. KONDISI KHUSUS PEREMPUAN -->
      <section class="section-card">
        <div class="section-header"><span class="sec-letter">C</span> Kondisi Khusus (Perempuan)</div>
        <div class="section-body">
          <p class="section-hint mb-3">Bila yang Meninggal Wanita Umur 10–59 Tahun, Almarhumah dalam Keadaan:</p>
          <div class="choice-row">
            <label class="checkbox-label"><input type="checkbox" id="kondisi_hamil"/> Hamil</label>
            <label class="checkbox-label"><input type="checkbox" id="kondisi_bersalin"/> Bersalin</label>
            <label class="checkbox-label"><input type="checkbox" id="kondisi_nifas"/> Nifas (s/d 42 hari)</label>
            <label class="checkbox-label"><input type="checkbox" id="kondisi_lainnya"/> Lainnya</label>
          </div>
        </div>
      </section>

      <!-- D. LAMA DIRAWAT & TEMPAT MENINGGAL -->
      <section class="section-card">
        <div class="section-header"><span class="sec-letter">D</span> Lama Dirawat &amp; Tempat Meninggal</div>
        <div class="section-body">
          <div class="form-grid form-grid-2">
            <div>
              <p class="section-hint font-bold border-b border-slate-200 pb-1 text-slate-600 mb-3">Lama Dirawat di Rumah Sakit</p>
              <div class="form-grid" style="grid-template-columns: repeat(3, 1fr);">
                <div>
                  <label class="field-label">a. Jam (&lt;48 jam)</label>
                  <input id="lama_rawat_jam" class="form-input" type="number" min="0" placeholder="Ketik jam"/>
                </div>
                <div>
                  <label class="field-label">b. Hari</label>
                  <input id="lama_rawat_hari" class="form-input" type="number" min="0" placeholder="0"/>
                </div>
                <div>
                  <label class="field-label">c. DOA</label>
                  <div class="choice-row" style="min-height:26px">
                    <label class="radio-label"><input type="radio" name="doa" value="Ya"/> Ya</label>
                    <label class="radio-label"><input type="radio" name="doa" value="Tidak" checked/> Tidak</label>
                  </div>
                </div>
              </div>
            </div>
            <div>
              <p class="section-hint font-bold border-b border-slate-200 pb-1 text-slate-600 mb-3">Tempat Meninggal</p>
              <div class="form-grid" style="grid-template-columns: repeat(2, 1fr);">
                <div class="choice-col">
                  <label class="checkbox-label"><input type="checkbox" id="tempat_meninggal_rs" class="tempat-cb"/> Rumah Sakit</label>
                  <label class="checkbox-label"><input type="checkbox" id="tempat_meninggal_rb" class="tempat-cb"/> Rumah Bersalin</label>
                </div>
                <div class="choice-col">
                  <label class="checkbox-label"><input type="checkbox" id="tempat_meninggal_puskesmas" class="tempat-cb"/> Puskesmas</label>
                  <label class="checkbox-label"><input type="checkbox" id="tempat_meninggal_rumah" class="tempat-cb"/> Rumah Tinggal</label>
                </div>
                <div class="span-2 flex items-center gap-2 mt-1">
                  <label class="checkbox-label whitespace-nowrap"><input type="checkbox" id="tempat_meninggal_lainnya" class="tempat-cb"/> Lainnya</label>
                  <input id="tempat_lainnya_ket" class="form-input flex-1" type="text" placeholder="Termasuk meninggal di perjalanan"/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- E. DASAR DIAGNOSA -->
      <section class="section-card">
        <div class="section-header"><span class="sec-letter">E</span> Dasar Diagnosa</div>
        <div class="section-body">
          <p class="section-hint mb-3">Dapat dicentang lebih dari satu.</p>
          <div class="choice-row">
            <label class="checkbox-label"><input type="checkbox" id="dasar_diagnosa_rm"/> Rekam Medis</label>
            <label class="checkbox-label"><input type="checkbox" id="dasar_diagnosa_luar"/> Pemeriksaan Luar Jenazah</label>
            <label class="checkbox-label"><input type="checkbox" id="dasar_diagnosa_forensik"/> Autopsi Forensik</label>
            <label class="checkbox-label"><input type="checkbox" id="dasar_diagnosa_medis"/> Autopsi Medis</label>
            <label class="checkbox-label"><input type="checkbox" id="dasar_diagnosa_verbal"/> Autopsi Verbal</label>
            <label class="checkbox-label"><input type="checkbox" id="dasar_diagnosa_lainnya"/> Surat Keterangan Lainnya</label>
          </div>
        </div>
      </section>

      <!-- F. KELOMPOK PENYEBAB KEMATIAN -->
      <section class="section-card">
        <div class="section-header"><span class="sec-letter">F</span> Kelompok Penyebab Kematian</div>
        <div class="section-body">
          <p class="section-hint mb-3">Penyakit/Gangguan (Pilih Salah Satu)</p>
          <div class="form-grid" style="grid-template-columns: repeat(3, 1fr);">
            <div class="choice-col">
              <label class="radio-label"><input type="radio" name="kelompok" value="Penyakit khusus"/> Penyakit Khusus</label>
              <label class="radio-label"><input type="radio" name="kelompok" value="Penyakit menular"/> Penyakit Menular</label>
              <label class="radio-label"><input type="radio" name="kelompok" value="Penyakit tidak menular"/> Penyakit Tidak Menular</label>
            </div>
            <div class="choice-col">
              <label class="radio-label"><input type="radio" name="kelompok" value="Cedera kecelakaan lalu lintas"/> Cedera Kecelakaan Lalu Lintas</label>
              <label class="radio-label"><input type="radio" name="kelompok" value="Gangguan maternal"/> Gangguan Maternal</label>
              <label class="radio-label"><input type="radio" name="kelompok" value="Gejala tanda kondisi lainnya"/> Gejala, Tanda dan Kondisi Lainnya</label>
            </div>
            <div class="choice-col">
              <label class="radio-label"><input type="radio" name="kelompok" value="Cedera kecelakaan kerja"/> Cedera Kecelakaan Kerja</label>
              <label class="radio-label"><input type="radio" name="kelompok" value="Cedera lainnya"/> Cedera Lainnya</label>
            </div>
          </div>
        </div>
      </section>

      <!-- G. PENYEBAB KEMATIAN -->
      <section class="section-card">
        <div class="section-header"><span class="sec-letter">G</span> Penyebab Kematian</div>
        <div class="section-body">
          <div class="inner-box" style="padding:0;overflow-x:auto;background:var(--white)">
            <table class="cause-table">
              <thead>
                <tr>
                  <th>I. Penyebab Kematian (a-d)</th>
                  <th style="width:25%">II. Kondisi Lain (Berkontribusi)</th>
                  <th style="width:20%">III. Selang Waktu</th>
                  <th style="width:15%">IV. ICD Code</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="flex items-start gap-1.5">
                      <span class="row-label">a.</span>
                      <div style="flex:1;min-width:0">
                        <div style="font-size:10px;font-weight:600;color:var(--ink-mid);margin-bottom:3px">Penyebab Langsung</div>
                        <input id="penyebab_a" class="form-input" type="text" placeholder="Penyebab langsung kematian"/>
                      </div>
                    </div>
                  </td>
                  <td rowspan="4" style="vertical-align:top">
                    <textarea id="penyakit_lain" class="form-input" style="height:100% !important; min-height:120px;" placeholder="Tuliskan penyakit/kondisi lain yang berkontribusi namun tidak berhubungan dengan (a-d)"></textarea>
                  </td>
                  <td><input id="selang_waktu_a" class="form-input" type="text" placeholder="cth: 2 hari"/></td>
                  <td><input id="icd_a" class="form-input" type="text" placeholder="ICD a"/></td>
                </tr>
                <tr>
                  <td>
                    <div class="flex items-start gap-1.5">
                      <span class="row-label">b.</span>
                      <div style="flex:1;min-width:0">
                        <div style="font-size:10px;font-weight:600;color:var(--ink-mid);margin-bottom:3px">Penyebab Antara</div>
                        <input id="penyebab_b" class="form-input" type="text" placeholder="Penyebab antara"/>
                      </div>
                    </div>
                  </td>
                  <td><input id="selang_waktu_b" class="form-input" type="text" placeholder="cth: 3 bulan"/></td>
                  <td><input id="icd_b" class="form-input" type="text" placeholder="ICD b"/></td>
                </tr>
                <tr>
                  <td>
                    <div class="flex items-start gap-1.5">
                      <span class="row-label">c.</span>
                      <div style="flex:1;min-width:0">
                        <div style="font-size:10px;font-weight:600;color:var(--ink-mid);margin-bottom:3px">Penyebab Antara</div>
                        <input id="penyebab_c" class="form-input" type="text" placeholder="Penyebab antara"/>
                      </div>
                    </div>
                  </td>
                  <td><input id="selang_waktu_c" class="form-input" type="text" placeholder="cth: 1 tahun"/></td>
                  <td><input id="icd_c" class="form-input" type="text" placeholder="ICD c"/></td>
                </tr>
                <tr>
                  <td>
                    <div class="flex items-start gap-1.5">
                      <span class="row-label">d.</span>
                      <div style="flex:1;min-width:0">
                        <div style="font-size:10px;font-weight:600;color:var(--ink-mid);margin-bottom:3px">Penyebab Dasar</div>
                        <input id="penyebab_d" class="form-input" type="text" placeholder="Penyebab dasar kematian"/>
                      </div>
                    </div>
                  </td>
                  <td><input id="selang_waktu_d" class="form-input" type="text" placeholder="cth: 5 tahun"/></td>
                  <td><input id="icd_d" class="form-input" type="text" placeholder="ICD d"/></td>
                </tr>
              </tbody>
            </table>
            <div class="icd-block" style="padding:10px 12px;margin-top:0;border-top:1px solid var(--rule)">
              <div class="icd-field" style="justify-content: flex-end;">
                <label>ICD Penyerta (Kolom II)</label>
                <input id="icd_penyerta" class="form-input" type="text" placeholder="XXX.XX" maxlength="7"/>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- H. VERIFIKASI DOKTER -->
      <section class="section-card">
        <div class="section-header"><span class="sec-letter">H</span> Verifikasi Dokter</div>
        <div class="section-body">
          <div class="form-grid mb-4">
            <div class="span-2">
              <label class="field-label">FUCoD (Final/Underlying Cause of Death)</label>
              <input id="fucod" class="form-input" type="text" placeholder="Tuliskan FUCoD..."/>
            </div>
            <div>
              <label class="field-label">ICD Code FUCoD</label>
              <input id="icd_fucod" class="form-input" type="text" placeholder="Kode ICD FUCoD"/>
            </div>
          </div>
          <div class="form-grid mb-6">
            <div>
              <label class="field-label">Nama Dokter</label>
              <input id="nama_dokter" class="form-input" type="text" placeholder="Nama lengkap dokter"/>
            </div>
            <div>
              <label class="field-label">SIP</label>
              <input id="nomor_sip" class="form-input" type="text" placeholder="Nomor SIP"/>
            </div>
            <div>
              <label class="field-label">Tanggal Penetapan</label>
              <input id="tanggal_ttd" class="form-input" type="date"/>
            </div>
          </div>
          
          <div class="border-t border-slate-200 pt-4">
            <div class="form-grid" style="grid-template-columns: repeat(3, 1fr);">
              <!-- Dokter -->
              <div class="flex flex-col items-center text-center">
                <p class="field-label w-full text-left mb-2">Tanda Tangan Dokter</p>
                <div class="sig-box w-full">
                  <i class="ph ph-qr-code"></i>
                  <span>Elektronik / QR Verifikasi</span>
                </div>
              </div>
              <!-- Penerima -->
              <div class="flex flex-col gap-3">
                <div>
                  <label class="field-label">Nama Terang Penerima</label>
                  <input id="nama_terang_penerima" class="form-input" type="text" placeholder="Nama Terang..."/>
                </div>
                <div>
                  <label class="field-label">Hubungan dengan Jenazah</label>
                  <select id="hubungan_jenazah" class="form-input">
                    <option value="">Pilih hubungan</option>
                    <option>Suami/Istri</option><option>Anak</option><option>Orang Tua</option>
                    <option>Saudara Kandung</option><option>Lainnya</option>
                  </select>
                </div>
              </div>
              <!-- Tanda Tangan Penerima -->
              <div class="flex flex-col items-center text-center">
                <p class="field-label w-full text-left mb-2">Tanda Tangan Penerima</p>
                <div class="sig-box w-full">
                  <i class="ph ph-pencil-simple"></i>
                  <span>Klik untuk tanda tangan</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

    </div>

    <!-- Action Footer -->
    <div class="action-bar-wrap flex flex-wrap items-center justify-end gap-2 px-3 py-2.5" id="form-actions">
      <button type="button" id="btn-draft" onclick="saveDraft()" class="px-4 py-1.5 border border-gray-300 bg-white text-gray-800 rounded font-bold text-[11px] hover:bg-gray-50 transition-colors">
        Simpan Draft
      </button>
      <button type="button" id="btn-final" onclick="submitFinal()" class="px-4 py-1.5 bg-teal-700 text-white rounded font-bold text-[11px] hover:bg-teal-800 transition-colors">
        Simpan Final
      </button>
      <button type="button" id="btn-print" onclick="goToOutput()" class="hidden px-4 py-1.5 bg-emerald-600 text-white rounded font-bold text-[11px] hover:bg-emerald-700 transition-colors">
        <i class="ph-bold ph-printer mr-1"></i> Cetak / Preview
      </button>
      <a href="/certificates" id="btn-back" class="hidden px-4 py-1.5 bg-gray-100 text-gray-700 rounded font-bold text-[11px] hover:bg-gray-200 transition-colors">
        Kembali ke Arsip
      </a>
    </div>

    <div class="pb-4 text-center text-[10px] text-gray-400 font-medium">
      &copy; 2026 RS Wava Husada. All rights reserved.
    </div>

  </div>

  <!-- Toast Container -->
  <div id="toast-container" class="fixed bottom-8 right-8 z-[110] flex flex-col gap-3"></div>

  <!-- BEGIN: Modern Confirmation Modal -->
  <div id="confirmation-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 text-left">
      <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity duration-300 opacity-0" id="modal-backdrop"></div>
      <div class="relative bg-white/90 backdrop-blur-xl border border-white/20 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="modal-content">
          <div class="p-8 text-center">
              <div id="modal-icon-container" class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-6">
                  <i id="modal-icon" class="ph-bold ph-warning-circle text-3xl"></i>
              </div>
              <h3 id="modal-title" class="text-xl font-bold text-slate-900 mb-2"></h3>
              <p id="modal-description" class="text-slate-600 mb-8"></p>
              <div id="modal-actions" class="flex flex-col gap-3">
              </div>
          </div>
      </div>
  </div>

  <div class="mt-8 pb-8 text-center text-xs text-gray-500 font-medium">
    © 2026 RS Wava Husada. All rights reserved.
  </div>
</main>

<script src="{{ asset('js/wilayah-autocomplete.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  let isFormDirty = false;
  let isFinalSubmitted = false;
  const formInputs = document.querySelectorAll('input, select, textarea');
  const STORAGE_KEY = 'draft_kematian_dewasa';

  const mainContainer = document.getElementById('main-container');
  let draftId = mainContainer.getAttribute('data-draft-id') ? parseInt(mainContainer.getAttribute('data-draft-id')) : null;
  const initialCertificate = JSON.parse(mainContainer.getAttribute('data-certificate') || 'null');

  // --- Local Storage / Database Load Logic ---
  function getFormData() {
    const formData = {};
    formInputs.forEach(input => {
      if (input.type === 'checkbox' || input.type === 'radio') {
        if (input.id) formData[input.id] = input.checked;
        else if (input.name) {
          if (input.checked) formData[input.name] = input.value;
        } else if (input.classList.contains('status-kp-cb') || input.classList.contains('tempat-cb')) {
          // Fallback for class-based single selection checkboxes without id/name
          const classList = Array.from(input.classList).join(' ');
          const label = input.closest('label')?.textContent.trim();
          if (input.checked && label) {
             formData[`class_${classList}_${label}`] = true;
          }
        }
      } else if (input.id) {
        formData[input.id] = input.value;
      }
    });
    return formData;
  }

  function saveToLocalStorage() {
    if (isFinalSubmitted) return;
    const formData = getFormData();
    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(formData));
  }

  function restoreFromData(formData) {
    if (!formData) return false;
    let hasData = false;
    formInputs.forEach(input => {
      if (input.type === 'checkbox' || input.type === 'radio') {
        if (input.id && formData[input.id] !== undefined) {
          input.checked = formData[input.id];
          hasData = true;
        } else if (input.name && formData[input.name] !== undefined) {
          if (input.value === formData[input.name]) {
            input.checked = true;
            hasData = true;
          } else if (input.type === 'radio') {
            input.checked = false;
          }
        } else if (input.classList.contains('status-kp-cb') || input.classList.contains('tempat-cb')) {
          const classList = Array.from(input.classList).join(' ');
          const label = input.closest('label')?.textContent.trim();
          if (label && formData[`class_${classList}_${label}`]) {
            input.checked = true;
            hasData = true;
          }
        }
      } else if (input.id && formData[input.id] !== undefined) {
        input.value = formData[input.id];
        hasData = true;
      }
    });
    return hasData;
  }

  function restoreFromLocalStorage() {
    const savedData = sessionStorage.getItem(STORAGE_KEY);
    if (savedData) {
      try {
        const formData = JSON.parse(savedData);
        if (restoreFromData(formData)) {
          isFormDirty = true;
          updateStatus('Sedang Diedit', 'amber');
        }
      } catch (e) {
        console.error('Failed to parse form draft from session storage', e);
      }
    }
  }

  function clearLocalStorage() {
    sessionStorage.removeItem(STORAGE_KEY);
  }

  // Restore on load
  if (initialCertificate) {
    if (initialCertificate.data) {
      restoreFromData(initialCertificate.data);
    }
    if (initialCertificate.nomor_sertifikat) {
      const noSertifInput = document.getElementById('no_sertifikat');
      if (noSertifInput) noSertifInput.value = initialCertificate.nomor_sertifikat;
    }
    if (initialCertificate.status === 'Draft') {
      updateStatus('Draft Tersimpan', 'blue');
    } else if (initialCertificate.status === 'Saved' || initialCertificate.status === 'Printed') {
      updateStatus('Final Submitted', 'emerald');
      isFinalSubmitted = true;
      formInputs.forEach(input => { input.disabled = true; });
      
      // Toggle buttons
      document.getElementById('btn-draft')?.classList.add('hidden');
      document.getElementById('btn-final')?.classList.add('hidden');
      document.getElementById('btn-print')?.classList.remove('hidden');
      document.getElementById('btn-back')?.classList.remove('hidden');
    }
  } else {
    restoreFromLocalStorage();
  }

  // --- End Local Storage / Database Load Logic ---

  formInputs.forEach(input => {
    input.addEventListener('change', () => { 
      if (!isFinalSubmitted) { 
        isFormDirty = true; 
        updateStatus('Sedang Diedit', 'amber'); 
        saveToLocalStorage();
      } 
    });
    if (input.tagName === 'INPUT' || input.tagName === 'TEXTAREA') {
      input.addEventListener('input', () => { 
        if (!isFinalSubmitted) { 
          isFormDirty = true; 
          updateStatus('Sedang Diedit', 'amber'); 
          saveToLocalStorage();
        } 
      });
    }
  });

  function updateStatus(label, color) {
    const badge = document.getElementById('status-badge');
    const text = badge.querySelector('span');
    const dot = badge.querySelector('div');
    text.innerText = label;
    const map = {
      emerald: ['bg-emerald-50 text-emerald-700 px-3 py-1 rounded border border-emerald-200', 'w-2 h-2 bg-emerald-500 rounded-full'],
      blue: ['bg-blue-50 text-blue-700 px-3 py-1 rounded border border-blue-200', 'w-2 h-2 bg-blue-500 rounded-full animate-pulse'],
      amber: ['bg-amber-50 text-amber-700 px-3 py-1 rounded border border-amber-200', 'w-2 h-2 bg-amber-500 rounded-full animate-pulse'],
    };
    badge.className = 'flex items-center gap-2 ' + (map[color]?.[0] || map.amber[0]);
    dot.className = map[color]?.[1] || map.amber[1];
  }

  window.showToast = function(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    const bg = type === 'success' ? 'bg-emerald-600' : 'bg-red-600';
    const icon = type === 'success' ? 'ph-check-circle' : 'ph-x-circle';
    toast.className = `${bg} text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 transform translate-y-10 opacity-0 transition-all duration-300`;
    toast.innerHTML = `<i class="ph-bold ${icon} text-lg"></i><span class="text-sm font-bold">${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => toast.classList.remove('translate-y-10', 'opacity-0'), 10);
    setTimeout(() => { toast.classList.add('translate-y-10', 'opacity-0'); setTimeout(() => toast.remove(), 300); }, 3000);
  };

  window.openDynamicModal = function(iconType, title, desc, actionsHtml) {
      const modal = document.getElementById('confirmation-modal');
      const iconContainer = document.getElementById('modal-icon-container');
      const icon = document.getElementById('modal-icon');
      
      document.getElementById('modal-title').innerText = title;
      document.getElementById('modal-description').innerText = desc;
      document.getElementById('modal-actions').innerHTML = actionsHtml;

      if (iconType === 'warning') {
          iconContainer.className = "w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-6";
          icon.className = "ph-bold ph-warning-circle text-3xl";
      } else if (iconType === 'question') {
          iconContainer.className = "w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6";
          icon.className = "ph-bold ph-question text-3xl";
      } else if (iconType === 'danger') {
          iconContainer.className = "w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6";
          icon.className = "ph-bold ph-trash text-3xl";
      }

      modal.classList.remove('hidden');
      modal.classList.add('flex');
      setTimeout(() => {
          document.getElementById('modal-backdrop').classList.remove('opacity-0');
          document.getElementById('modal-content').classList.remove('scale-95', 'opacity-0');
      }, 10);
  };

  window.saveDraftToServer = function(status = 'Draft', isAutoSave = false) {
    const formData = getFormData();
    const noSertifikat = document.getElementById('no_sertifikat')?.value;

    return fetch('/api/drafts/save', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        id: draftId,
        jenis: 'Dewasa',
        data: formData,
        status: status,
        no_sertifikat: noSertifikat
      })
    })
    .then(response => {
      if (!response.ok) {
        return response.json().then(err => { throw err; });
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        draftId = data.id;
        isFormDirty = false;
        
        // Update browser history to edit route if currently on create route
        if (!window.location.pathname.includes('/edit/')) {
          const editUrl = `/form/edit/${data.id}`;
          window.history.replaceState({ path: editUrl }, '', editUrl);
        }
        
        if (status === 'Saved') {
          updateStatus('Final Submitted', 'emerald');
          isFinalSubmitted = true;
          formInputs.forEach(input => { input.disabled = true; });
          
          // Toggle buttons
          document.getElementById('btn-draft')?.classList.add('hidden');
          document.getElementById('btn-final')?.classList.add('hidden');
          document.getElementById('btn-print')?.classList.remove('hidden');
          document.getElementById('btn-back')?.classList.remove('hidden');
          
          clearLocalStorage();
          showToast('Formulir berhasil disimpan secara final', 'success');
          
          if (data.redirect) {
            setTimeout(() => {
              window.location.href = data.redirect;
            }, 1500);
          }
        } else {
          updateStatus('Draft Tersimpan', 'blue');
          showToast(isAutoSave ? 'Draft berhasil disimpan otomatis' : 'Draft berhasil disimpan', 'success');
        }
        return true;
      } else {
        showToast(data.message || 'Gagal menyimpan draft', 'error');
        return false;
      }
    })
    .catch(error => {
      console.error('Error saving draft:', error);
      showToast(error.message || 'Terjadi kesalahan saat menyimpan', 'error');
      return false;
    });
  };

  window.saveDraft = function() {
    return saveDraftToServer('Draft');
  };

  window.submitFinal = function() {
    // Validasi: minimal poin Identitas Jenazah harus terisi
    const identitasFields = ['nrm', 'nama_lengkap', 'tanggal_lahir', 'alamat'];
    const emptyIdentitas = identitasFields.filter(id => {
      const el = document.getElementById(id);
      return !el || el.value.trim() === '';
    });

    if (emptyIdentitas.length > 0) {
       showToast('Mohon lengkapi minimal poin "Identitas Jenazah" sebelum melakukan pengajuan final.', 'error');
       // Scroll & focus ke field kosong pertama
       const firstEmpty = document.getElementById(emptyIdentitas[0]);
       if (firstEmpty) { firstEmpty.scrollIntoView({ behavior: 'smooth', block: 'center' }); firstEmpty.focus(); }
       return;
    }

    openDynamicModal(
        'question',
        'Konfirmasi Pengajuan Surat',
        'Apakah Anda yakin ingin mengirim formulir ini secara final? Formulir tidak dapat diubah lagi setelah dikirim.',
        `
            <button onclick="confirmSubmitFinal()" class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                <i class="ph-bold ph-check"></i>
                Yakin
            </button>
            <button onclick="cancelSubmitFinal()" class="w-full py-3 bg-slate-200 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-300 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                <i class="ph-bold ph-x"></i>
                Batal
            </button>
        `
    );
  };

  window.confirmSubmitFinal = function() {
      saveDraftToServer('Saved').then(success => {
          if (success) {
              closeModal();
          }
      });
  };

  window.cancelSubmitFinal = function() {
      closeModal();
  };

  // --- Custom Navigation Interceptor ---
  let navigationTargetUrl = null;
  let isNavigatingBack = false;

  // Initialize browser history state for back button intercept
  window.history.pushState({ page: 'form' }, '', window.location.href);

  window.addEventListener('popstate', function(event) {
    if (isFormDirty && !isFinalSubmitted) {
      // Re-push state to keep user on current page while showing modal
      window.history.pushState({ page: 'form' }, '', window.location.href);
      isNavigatingBack = true;
      showExitModal();
    } else {
      // If not dirty, allow normal back navigation
      window.location.href = '/form';
    }
  });

  // Intercept link clicks to warn about unsaved changes
  document.querySelectorAll('a[href]').forEach(link => {
    link.addEventListener('click', function(e) {
      if (isFormDirty && !isFinalSubmitted && !link.hasAttribute('target')) {
        e.preventDefault();
        navigationTargetUrl = link.href;
        isNavigatingBack = false;
        showExitModal();
      }
    });
  });

  window.handleBack = function() {
    if (isFinalSubmitted) {
      window.location.href = '/form';
      return;
    }
    isNavigatingBack = true;
    showExitModal();
  };

  function showExitModal() {
    openDynamicModal(
        'warning',
        'Perubahan belum disimpan',
        'Apakah Anda ingin menyimpan perubahan sebagai draft atau membuangnya sebelum keluar?',
        `
            <button onclick="actionLanjutMengisi()" class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                <i class="ph-bold ph-pencil-simple"></i>
                Lanjut Mengisi
            </button>
            <button onclick="actionDraft()" class="w-full py-3 bg-emerald-600 text-white rounded-xl font-bold text-sm hover:bg-emerald-700 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                <i class="ph-bold ph-floppy-disk"></i>
                Simpan Draft
            </button>
            <button onclick="actionBuang()" class="w-full py-3 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                <i class="ph-bold ph-trash"></i>
                Buang Perubahan
            </button>
        `
    );
  }

  window.closeModal = function() {
    document.getElementById('modal-backdrop').classList.add('opacity-0');
    document.getElementById('modal-content').classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
      document.getElementById('confirmation-modal').classList.add('hidden');
      document.getElementById('confirmation-modal').classList.remove('flex');
    }, 300);
  }

  window.actionLanjutMengisi = function() {
    closeModal();
  }

  window.actionBuang = function() {
      openDynamicModal(
          'danger',
          'Konfirmasi Hapus Data',
          'Apakah Anda sangat yakin ingin membuang semua perubahan? Data yang belum disimpan tidak dapat dikembalikan.',
          `
              <button onclick="confirmActionBuang()" class="w-full py-3 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                  <i class="ph-bold ph-trash"></i>
                  Ya, Buang Data
              </button>
              <button onclick="showExitModal()" class="w-full py-3 bg-slate-200 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-300 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                  <i class="ph-bold ph-arrow-left"></i>
                  Kembali
              </button>
          `
      );
  }

  window.confirmActionBuang = function() {
    isFormDirty = false;
    clearLocalStorage();
    closeModal();
    console.log('Audit Log: Perubahan dibuang pada ' + new Date().toISOString());
    showToast('Data formulir telah dibuang.', 'success');
    setTimeout(() => {
      if (isNavigatingBack) {
        window.location.href = '/form';
      } else if (navigationTargetUrl) {
        window.location.href = navigationTargetUrl;
      } else {
        window.location.href = '/form';
      }
    }, 500);
  }

  window.actionDraft = function() {
    saveDraft().then(success => {
      if (success) {
        clearLocalStorage();
        closeModal();
        setTimeout(() => {
          if (isNavigatingBack) {
            window.location.href = '/form';
          } else if (navigationTargetUrl) {
            window.location.href = navigationTargetUrl;
          } else {
            window.location.href = '/form';
          }
        }, 500);
      }
    });
  }
  // --- End Navigation Interceptor ---

  window.goToOutput = function() {
    if (draftId) {
      window.location.href = `/output/dewasa/${draftId}`;
    } else {
      showToast('Sertifikat belum tersimpan', 'error');
    }
  };

  // Auto-update hari from tanggal
  const dateInput = document.getElementById('tanggal_kematian');
  const daySelect = document.getElementById('hari_kematian');
  const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
  if (dateInput && daySelect) {
    dateInput.addEventListener('change', function() {
      if (!dateInput.value) return;
      const [y,m,d] = dateInput.value.split('-').map(Number);
      const day = new Date(y, m-1, d).getDay();
      [...daySelect.options].forEach((o, i) => { if (o.text === days[day]) daySelect.selectedIndex = i; });
    });
  }

  // Single-select checkbox groups
  function enforceSingle(selector) {
    const cbs = document.querySelectorAll(selector);
    cbs.forEach(cb => cb.addEventListener('change', function() {
      if (this.checked) cbs.forEach(o => { if (o !== this) o.checked = false; });
    }));
  }
  enforceSingle('.status-kp-cb');
  enforceSingle('.tempat-cb');

  window.addEventListener('beforeunload', (e) => {
    if (isFormDirty && !isFinalSubmitted) { e.preventDefault(); e.returnValue = ''; }
  });
});
</script>
</body>
</html>