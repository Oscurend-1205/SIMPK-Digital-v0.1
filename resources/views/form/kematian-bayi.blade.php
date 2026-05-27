<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Form Kematian Bayi - RS Wava Husada</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/RS-Wava-Husada.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        borderRadius: {
          'none': '0', 'sm': '0.0625rem', DEFAULT: '0.125rem',
          'md': '0.125rem', 'lg': '0.25rem', 'xl': '0.375rem',
          '2xl': '0.5rem', '3xl': '0.75rem', 'full': '9999px',
        }
      }
    }
  }
</script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<script src="https://unpkg.com/@phosphor-icons/web"></script>
<style>
  body { font-family: 'Inter', sans-serif; }
  .custom-scrollbar::-webkit-scrollbar { width: 6px; }
  .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
  .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
  .form-input {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
    font-size: 0.8125rem;
    padding: 0.3rem 0.5rem;
    background: #fff;
    color: #1e293b;
  }
  .form-input:focus { outline: 2px solid #1da1a6; outline-offset: 1px; }
  .form-input[readonly], .form-input:disabled { background: #f8fafc; color: #64748b; cursor: not-allowed; }
  .section-header {
    background: #1da1a6;
    color: white;
    padding: 0.4rem 0.75rem;
    font-weight: 700;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    border-radius: 0.375rem 0.375rem 0 0;
  }
  .section-body {
    border: 1px solid #e2e8f0;
    border-top: none;
    padding: 1rem;
    border-radius: 0 0 0.375rem 0.375rem;
    background: #fff;
  }
  .sub-header {
    font-size: 0.75rem;
    font-weight: 700;
    color: #1e3a5f;
    padding-bottom: 0.4rem;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 0.75rem;
  }
  .field-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: 0.2rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
  }
  .checkbox-label, .radio-label {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8125rem;
    color: #374151;
    cursor: pointer;
  }
  .inner-box {
    border: 1px solid #e2e8f0;
    border-radius: 0.375rem;
    padding: 0.75rem;
    background: #f8fafc;
  }
  .penyebab-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.4rem;
  }
  .penyebab-label {
    font-size: 0.8125rem;
    font-weight: 700;
    color: #374151;
    min-width: 1.2rem;
    flex-shrink: 0;
  }
  .penyebab-input {
    flex: 1;
    min-width: 0;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
    font-size: 0.8125rem;
    padding: 0.3rem 0.5rem;
  }
  .table-header {
    font-size: 0.7rem;
    font-weight: 700;
    color: #1e3a5f;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #cbd5e1;
    margin-bottom: 0.5rem;
  }
  .divider-line {
    border: none;
    border-top: 2px solid #1da1a6;
    margin: 0.75rem 0;
    opacity: 0.3;
  }
</style>
</head>
<body class="bg-gray-100 min-h-screen">
@include('partials.watermark')

<main id="main-container" data-draft-id="{{ $certificate->id ?? '' }}" data-certificate="{{ json_encode($certificate ?? null) }}" class="h-screen overflow-y-auto custom-scrollbar p-8 relative">

  <!-- Navigation Top -->
  <div class="mb-4 flex justify-between items-center bg-white p-4 rounded-lg shadow-sm border border-slate-200">
    <button type="button" onclick="handleBack()" class="flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-50 rounded transition-all group border border-slate-200 shadow-sm">
      <i class="ph ph-arrow-left transition-transform group-hover:-translate-x-1"></i>
      Kembali ke Pemilihan Sertifikat
    </button>
    <div class="flex items-center gap-4">
      <div id="form-status-indicator" class="flex items-center gap-2">
        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Status:</span>
        <div id="status-badge" class="flex items-center gap-2 bg-amber-50 text-amber-700 px-3 py-1 rounded border border-amber-200">
          <div class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></div>
          <span id="status-text" class="text-[10px] font-bold uppercase tracking-tight">Menulis Draft...</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Page Header -->
  <div class="mb-5 flex justify-between items-start">
    <div>
      <span class="text-xs font-bold text-gray-500 tracking-wider uppercase">Formulir Elektronik</span>
      <h2 class="text-2xl font-bold text-blue-900 mt-1 uppercase">Sertifikat Medis Penyebab Kematian Bayi & Perinatal</h2>
      <p class="text-sm text-gray-500 italic mt-0.5">RS Wava Husada — Standar Pelaporan Penyebab Kematian (Infants & Perinatal)</p>
    </div>
  </div>

  <!-- Sub-header bar -->
  <div class="mb-5 flex justify-between items-center bg-blue-50/50 p-3 rounded-lg border border-blue-100">
    <div class="flex items-center text-blue-800 text-xs font-medium">
      <i class="ph-bold ph-info mr-2"></i>
      <span>Formulir Sertifikat Medis Penyebab Kematian Bayi/Neonatal (SKB)</span>
    </div>
    <div class="flex items-center space-x-2">
      <span class="text-sm font-semibold">No. Sertifikat :</span>
      <input class="bg-white border border-gray-300 rounded text-sm px-3 py-1 w-48 text-gray-700 text-center" id="no_sertifikat" type="text" value="SKB-2024/00001"/>
    </div>
  </div>

  <!-- ===== FORM CONTAINER ===== -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">

    <!-- 1. IDENTITAS BAYI -->
    <section>
      <div class="section-header">1. Identitas Bayi</div>
      <div class="section-body space-y-4">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
          <div>
            <label class="field-label">No. Rekam Medis Bayi</label>
            <input id="nrm_bayi" class="form-input" type="text" placeholder="Ketik atau pilih"/>
          </div>
          <div>
            <label class="field-label">Nama Bayi (jika ada)</label>
            <input id="nama_bayi" class="form-input" type="text" placeholder="Ketik nama bayi"/>
          </div>
          <div>
            <label class="field-label">Jenis Kelamin</label>
            <div class="flex items-center gap-4 mt-1.5">
              <label class="radio-label"><input type="radio" name="gender_bayi" value="Laki-laki" checked/> Laki-laki</label>
              <label class="radio-label"><input type="radio" name="gender_bayi" value="Perempuan"/> Perempuan</label>
            </div>
          </div>
          <div>
            <label class="field-label">Tanggal Lahir</label>
            <input id="tanggal_lahir_bayi" class="form-input" type="date"/>
          </div>
          <div>
            <label class="field-label">Jam Lahir</label>
            <input id="jam_lahir_bayi" class="form-input" type="time" placeholder="hh:mm"/>
          </div>
          <div>
            <label class="field-label">Tanggal Meninggal</label>
            <input id="tanggal_meninggal_bayi" class="form-input" type="date"/>
          </div>
          <div>
            <label class="field-label">Jam Meninggal</label>
            <input id="jam_meninggal_bayi" class="form-input" type="time" placeholder="hh:mm"/>
          </div>
        </div>

      </div>
    </section>

    <!-- 2. UMUR SAAT MENINGGAL -->
    <section>
      <div class="section-header">2. Umur Saat Meninggal</div>
      <div class="section-body">
        <div class="inner-box">
          <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div>
              <label class="field-label">a. Hari (&lt;29 hari)</label>
              <input id="umur_hari" class="form-input" type="number" min="0" placeholder="Ketik hari"/>
            </div>
            <div>
              <label class="field-label">b. Bulan (&gt;28 hr s/d 59 bulan)</label>
              <input id="umur_bulan" class="form-input" type="number" min="0" placeholder="Ketik bulan"/>
            </div>
            <div>
              <label class="field-label">c. Tahun (≥ 5 tahun)</label>
              <input id="umur_tahun" class="form-input" type="number" min="0" placeholder="Ketik tahun"/>
            </div>
            <div>
              <label class="field-label">d. Lahir Mati</label>
              <div class="flex items-center gap-3 mt-1.5">
                <label class="radio-label"><input type="radio" name="lahir_mati" value="Ya" checked/> Ya</label>
                <label class="radio-label"><input type="radio" name="lahir_mati" value="Tidak"/> Tidak</label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 3. IDENTITAS IBU -->
    <section>
      <div class="section-header">3. Identitas Ibu</div>
      <div class="section-body space-y-3">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
          <div>
            <label class="field-label">Nama Ibu</label>
            <input id="nama_ibu" class="form-input" type="text" placeholder="Ketik nama ibu"/>
          </div>
          <div>
            <label class="field-label">NIK Ibu</label>
            <input id="nik_ibu" class="form-input" type="text" placeholder="Ketik NIK"/>
          </div>
          <div>
            <label class="field-label">Umur Ibu</label>
            <input id="umur_ibu" class="form-input" type="number" min="0" placeholder="Ketik umur"/>
          </div>
          <div>
            <label class="field-label">No. Rekam Medis Ibu</label>
            <input id="nrm_ibu" class="form-input" type="text" placeholder="Ketik atau pilih"/>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
          <div class="md:col-span-2">
            <label class="field-label">Alamat Ibu</label>
            <input id="alamat_ibu" class="form-input" type="text" placeholder="Ketik alamat ibu"/>
          </div>
          <div>
            <label class="field-label">Gravida (G)</label>
            <input id="gravida" class="form-input" type="text" placeholder="Ketik"/>
          </div>
          <div>
            <label class="field-label">Para (P)</label>
            <input id="para" class="form-input" type="text" placeholder="Ketik"/>
          </div>
          <div>
            <label class="field-label">Usia Kehamilan (minggu)</label>
            <input id="usia_kehamilan" class="form-input" type="number" min="0" placeholder="Ketik (minggu)"/>
          </div>
        </div>

      </div>
    </section>

    <!-- 4. INFORMASI PERSALINAN -->
    <section>
      <div class="section-header">4. Informasi Persalinan</div>
      <div class="section-body space-y-3">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
          <div>
            <label class="field-label">Jenis Persalinan</label>
            <select id="jenis_persalinan" class="form-input">
              <option value="">Pilih jenis</option>
              <option>Spontan</option>
              <option>Sectio Caesarea</option>
              <option>Vacuum/Forsep</option>
              <option>Lainnya</option>
            </select>
          </div>
          <div>
            <label class="field-label">Tempat Persalinan</label>
            <select id="tempat_persalinan" class="form-input">
              <option value="">Pilih tempat</option>
              <option>Rumah Sakit</option>
              <option>Puskesmas</option>
              <option>Rumah Bersalin</option>
              <option>Rumah</option>
              <option>Lainnya</option>
            </select>
          </div>
          <div>
            <label class="field-label">Penolong Persalinan</label>
            <input id="penolong_persalinan" class="form-input" type="text" placeholder="Ketik penolong"/>
          </div>
          <div>
            <label class="field-label">Kehamilan Ke-</label>
            <input id="kehamilan_ke" class="form-input" type="number" min="1" placeholder="Ketik"/>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
          <div>
            <label class="field-label">Berat Badan Lahir (gram)</label>
            <input id="berat_badan_lahir" class="form-input" type="number" min="0" placeholder="Ketik gram"/>
          </div>
          <div>
            <label class="field-label">Panjang Badan Lahir (cm)</label>
            <input id="panjang_badan_lahir" class="form-input" type="number" min="0" placeholder="Ketik cm"/>
          </div>
          <div>
            <label class="field-label">APGAR Score (1 menit / 5 menit)</label>
            <div class="flex items-center gap-1">
              <input id="apgar_1" class="form-input" type="number" min="0" max="10" placeholder="1 mnt"/>
              <span class="text-gray-400 text-sm">/</span>
              <input id="apgar_5" class="form-input" type="number" min="0" max="10" placeholder="5 mnt"/>
            </div>
          </div>
          <div>
            <label class="field-label">Kondisi Ketuban</label>
            <select id="kondisi_ketuban" class="form-input">
              <option value="">Pilih kondisi</option>
              <option>Jernih</option>
              <option>Keruh</option>
              <option>Hijau</option>
              <option>Mekonium</option>
              <option>Lainnya</option>
            </select>
          </div>
        </div>

        <div>
          <label class="field-label">Komplikasi Persalinan</label>
          <textarea id="komplikasi_persalinan" class="form-input min-h-[56px] resize-none" placeholder="Tuliskan komplikasi persalinan jika ada"></textarea>
        </div>

      </div>
    </section>

    <!-- 5. INFORMASI KEMATIAN BAYI -->
    <section>
      <div class="section-header">5. Informasi Kematian Bayi</div>
      <div class="section-body">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
          <div>
            <label class="field-label">Tempat Meninggal</label>
            <select id="tempat_meninggal_bayi" class="form-input">
              <option value="">Pilih tempat</option>
              <option>Rumah Sakit</option>
              <option>Puskesmas</option>
              <option>Rumah Bersalin</option>
              <option>Rumah</option>
              <option>Lainnya (Termasuk DOA)</option>
            </select>
          </div>
          <div>
            <label class="field-label">Lama Perawatan</label>
            <input id="lama_perawatan_bayi" class="form-input" type="text" placeholder="Ketik jam/hari"/>
          </div>
          <div>
            <label class="field-label">DOA</label>
            <div class="flex items-center gap-3 mt-1.5">
              <label class="radio-label"><input type="radio" name="doa_bayi" value="Ya"/> Ya</label>
              <label class="radio-label"><input type="radio" name="doa_bayi" value="Tidak" checked/> Tidak</label>
            </div>
          </div>
          <div>
            <label class="field-label">Resusitasi Dilakukan</label>
            <div class="flex items-center gap-3 mt-1.5">
              <label class="radio-label"><input type="radio" name="resusitasi" value="Ya"/> Ya</label>
              <label class="radio-label"><input type="radio" name="resusitasi" value="Tidak" checked/> Tidak</label>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 6. DASAR DIAGNOSA -->
    <section>
      <div class="section-header">6. Dasar Diagnosa (Dapat Lebih dari Satu)</div>
      <div class="section-body">
        <div class="flex flex-wrap gap-4">
          <label class="checkbox-label"><input type="checkbox" id="dasar_rekam_medis"/> Rekam Medis</label>
          <label class="checkbox-label"><input type="checkbox" id="dasar_pemeriksaan_bayi"/> Pemeriksaan Bayi</label>
          <label class="checkbox-label"><input type="checkbox" id="dasar_pemeriksaan_penunjang"/> Pemeriksaan Penunjang</label>
          <label class="checkbox-label"><input type="checkbox" id="dasar_autopsi"/> Autopsi</label>
          <label class="checkbox-label"><input type="checkbox" id="dasar_surat_keterangan"/> Surat Keterangan Lain</label>
        </div>
      </div>
    </section>

    <!-- 7. PENYEBAB KEMATIAN BAYI -->
    <section>
      <div class="section-header">7. Penyebab Kematian Bayi</div>
      <div class="section-body space-y-4">

        <div class="inner-box">
          <!-- Headers -->
          <div class="grid grid-cols-12 gap-x-4 mb-3">
            <div class="col-span-4 table-header">A. Penyebab dari Bayi</div>
            <div class="col-span-4 table-header">B. Penyebab dari Ibu (Maternal)</div>
            <div class="col-span-4 table-header">C. Kode ICD</div>
          </div>

          <div class="grid grid-cols-12 gap-x-4">
            <!-- Col A: Penyebab dari Bayi -->
            <div class="col-span-4 space-y-2">
              <div class="penyebab-row">
                <span class="penyebab-label">a.</span>
                <div class="flex-1">
                  <label class="field-label">Penyebab Utama Bayi</label>
                  <input id="penyebab_utama_bayi" class="penyebab-input form-input" type="text" placeholder="Ketik penyebab utama bayi"/>
                </div>
              </div>
              <div class="penyebab-row">
                <span class="penyebab-label">b.</span>
                <div class="flex-1">
                  <label class="field-label">Penyebab Lain Bayi</label>
                  <input id="penyebab_lain_bayi" class="penyebab-input form-input" type="text" placeholder="Ketik penyebab lain bayi"/>
                </div>
              </div>
            </div>

            <!-- Col B: Penyebab dari Ibu -->
            <div class="col-span-4 space-y-2">
              <div class="penyebab-row">
                <span class="penyebab-label">c.</span>
                <div class="flex-1">
                  <label class="field-label">Penyebab Utama Ibu</label>
                  <input id="penyebab_utama_ibu" class="penyebab-input form-input" type="text" placeholder="Ketik penyebab utama ibu"/>
                </div>
              </div>
              <div class="penyebab-row">
                <span class="penyebab-label">d.</span>
                <div class="flex-1">
                  <label class="field-label">Penyebab Lain Ibu</label>
                  <input id="penyebab_lain_ibu" class="penyebab-input form-input" type="text" placeholder="Ketik penyebab lain ibu"/>
                </div>
              </div>
            </div>

            <!-- Col C: Kode ICD -->
            <div class="col-span-4 space-y-2">
              <div>
                <label class="field-label">ICD Penyebab Bayi</label>
                <input id="icd_penyebab_bayi" class="form-input text-xs" type="text" placeholder="ICD penyebab bayi"/>
              </div>
              <div>
                <label class="field-label">ICD Penyebab Maternal</label>
                <input id="icd_penyebab_maternal" class="form-input text-xs" type="text" placeholder="ICD penyebab maternal"/>
              </div>
              <div>
                <label class="field-label">ICD FUCoD</label>
                <input id="icd_fucod_bayi" class="form-input text-xs" type="text" placeholder="ICD FUCoD"/>
              </div>
            </div>
          </div>
        </div>

        <hr class="divider-line"/>

        <!-- Kelaian / Kondisi Penyerta -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p class="sub-header">Kelainan/Kondisi Penyerta pada Bayi</p>
            <div class="flex flex-wrap gap-3">
              <label class="checkbox-label"><input type="checkbox" id="cond_prematuritas"/> Prematuritas</label>
              <label class="checkbox-label"><input type="checkbox" id="cond_asfiksia"/> Asfiksia</label>
              <label class="checkbox-label"><input type="checkbox" id="cond_infeksi"/> Infeksi</label>
              <label class="checkbox-label"><input type="checkbox" id="cond_kongenital"/> Kelainan Kongenital</label>
              <label class="checkbox-label"><input type="checkbox" id="cond_sepsis"/> Sepsis Neonatorum</label>
              <label class="checkbox-label"><input type="checkbox" id="cond_bblr"/> BBLR</label>
              <div class="flex items-center gap-2">
                <label class="checkbox-label"><input type="checkbox" id="cond_lainnya_bayi"/> Lainnya</label>
                <input id="cond_lainnya_bayi_ket" class="form-input w-28 text-xs" type="text" placeholder="Sebutkan"/>
              </div>
            </div>
          </div>
          <div>
            <p class="sub-header">Kondisi Maternal yang Berkaitan</p>
            <div class="flex flex-wrap gap-3">
              <label class="checkbox-label"><input type="checkbox" id="mat_kehamilan_komplikasi"/> Kehamilan dengan komplikasi</label>
              <label class="checkbox-label"><input type="checkbox" id="mat_persalinan_komplikasi"/> Persalinan dengan komplikasi</label>
              <label class="checkbox-label"><input type="checkbox" id="mat_nifas_komplikasi"/> Nifas dengan komplikasi</label>
              <label class="checkbox-label"><input type="checkbox" id="mat_penyakit_ibu"/> Penyakit Ibu</label>
              <div class="flex items-center gap-2">
                <label class="checkbox-label"><input type="checkbox" id="mat_lainnya"/> Lainnya</label>
                <input id="mat_lainnya_ket" class="form-input w-28 text-xs" type="text" placeholder="Sebutkan"/>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- 8. PENGESAHAN -->
    <section>
      <div class="section-header">8. Pengesahan</div>
      <div class="section-body">
        <div class="inner-box space-y-4">

          <!-- Dokter & Unit -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="field-label">Nama Dokter yang Menerangkan</label>
              <input id="nama_dokter" class="form-input" type="text" placeholder="Ketik nama dokter"/>
            </div>
            <div>
              <label class="field-label">SIP Dokter</label>
              <input id="nomor_sip" class="form-input" type="text" placeholder="Ketik no. SIP"/>
            </div>
            <div>
              <label class="field-label">Unit/Instalasi</label>
              <input id="unit_instalasi" class="form-input" type="text" placeholder="Ketik unit"/>
            </div>
            <div>
              <label class="field-label">Tanggal Pengisian</label>
              <input id="tanggal_ttd" class="form-input" type="date"/>
            </div>
          </div>

          <!-- Tanda Tangan -->
          <div class="border-t border-gray-200 pt-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
              <!-- Dokter TTD -->
              <div class="flex flex-col">
                <p class="text-xs font-bold text-gray-700 mb-2">Tanda Tangan Dokter</p>
                <div class="w-full h-20 border border-dashed border-gray-300 rounded bg-white flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors">
                  <span class="text-xs text-gray-400 flex items-center gap-1"><i class="ph ph-pencil-simple"></i>Klik untuk tanda tangan</span>
                </div>
              </div>
              <!-- Penerima -->
              <div class="flex flex-col">
                <p class="text-xs font-bold text-gray-700 mb-2">Pihak Penerima</p>
                <div class="space-y-2">
                  <div>
                    <label class="field-label">Nama Terang</label>
                    <input id="nama_terang_penerima" class="form-input" type="text" placeholder="Nama Terang..."/>
                  </div>
                  <div>
                    <label class="field-label">Hubungan dengan Bayi</label>
                    <select id="hubungan_bayi" class="form-input">
                      <option value="">Pilih hubungan</option>
                      <option>Orang Tua (Ayah)</option>
                      <option>Orang Tua (Ibu)</option>
                      <option>Wali</option>
                      <option>Lainnya</option>
                    </select>
                  </div>
                </div>
              </div>
              <!-- Penerima TTD -->
              <div class="flex flex-col">
                <p class="text-xs font-bold text-gray-700 mb-2">Tanda Tangan Penerima</p>
                <div class="w-full h-20 border border-dashed border-gray-300 rounded bg-white flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors">
                  <span class="text-xs text-gray-400 flex items-center gap-1"><i class="ph ph-pencil-simple"></i>Klik untuk tanda tangan</span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

  </div>

  <!-- Action Footer -->
  <div class="mt-6 flex flex-wrap items-center justify-end gap-3" id="form-actions">
    <button type="button" id="btn-draft" onclick="saveDraft()" class="px-6 py-2 border border-gray-300 bg-white text-gray-800 rounded font-bold text-sm hover:bg-gray-50 transition-colors">
      Simpan Draft
    </button>
    <button type="button" id="btn-final" onclick="submitFinal()" class="px-6 py-2 bg-blue-600 text-white rounded font-bold text-sm hover:bg-blue-700 transition-colors">
      Simpan Final
    </button>
    <button type="button" id="btn-print" onclick="goToOutput()" class="hidden px-6 py-2 bg-emerald-600 text-white rounded font-bold text-sm hover:bg-emerald-700 transition-colors">
      <i class="ph-bold ph-printer mr-1"></i> Cetak / Preview
    </button>
    <a href="/certificates" id="btn-back" class="hidden px-6 py-2 bg-gray-100 text-gray-700 rounded font-bold text-sm hover:bg-gray-200 transition-colors">
      Kembali ke Arsip
    </a>
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
              <h3 id="modal-title" class="text-xl font-bold text-slate-900 mb-2">Perubahan belum disimpan</h3>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
  let isFormDirty = false;
  let isFinalSubmitted = false;
  const formInputs = document.querySelectorAll('input, select, textarea');
  const STORAGE_KEY = 'draft_kematian_bayi';

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
        } else if (input.classList.contains('tempat-cb')) {
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
        } else if (input.classList.contains('tempat-cb')) {
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

  window.goToOutput = function() {
    if (draftId) {
      window.location.href = `/output/bayi/${draftId}`;
    }
  };
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
        jenis: 'Bayi',
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
    let filledCount = 0;
    let totalCount = 0;
    formInputs.forEach(input => {
      if(input.type !== 'checkbox' && input.type !== 'radio' && input.type !== 'hidden' && input.id !== 'no_sertifikat') {
         totalCount++;
         if(input.value.trim() !== '') filledCount++;
      }
    });

    if (filledCount < totalCount * 0.3) {
        showToast('Minimal 30% data harus terisi untuk menyimpan draf.', 'error');
        return Promise.resolve(false);
    }

    return saveDraftToServer('Draft');
  };

  window.submitFinal = function() {
    let filledCount = 0;
    let totalCount = 0;
    formInputs.forEach(input => {
      if(input.type !== 'checkbox' && input.type !== 'radio' && input.type !== 'hidden' && input.id !== 'no_sertifikat') {
         totalCount++;
         if(input.value.trim() !== '') filledCount++;
      }
    });

    if (filledCount < totalCount * 0.8) {
       showToast('Mohon lengkapi minimal 80% data sebelum melakukan pengajuan final.', 'error');
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
            <button onclick="closeModal()" class="w-full py-3 bg-slate-200 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-300 transition-all transform active:scale-95 flex items-center justify-center gap-2">
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

  window.addEventListener('beforeunload', (e) => {
    if (isFormDirty && !isFinalSubmitted) { e.preventDefault(); e.returnValue = ''; }
  });
});
</script>
</body>
</html>