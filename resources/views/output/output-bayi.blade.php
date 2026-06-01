<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sertifikat Medis Penyebab Kematian Perinatal / Bayi - RS Wava Husada</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;600&display=swap" rel="stylesheet"/>
    <style>
        :root {
            --brand:     #00A1C5;
            --brand-mid: #1da1a6;
            --brand-lt:  #e8f7f7;
            --ink:       #0c1924;
            --ink-mid:   #3d5166;
            --ink-lt:    #7a8fa0;
            --rule:      #64748b;
            --bg-cell:   #f4f8fa;
            --white:     #ffffff;
            --accent:    #b45309;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'IBM Plex Sans', sans-serif;
            background: #dde4e8;
            margin: 0; padding: 0;
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        /* ── TOPBAR ─────────────────────────────────────── */
        .topbar {
            position: fixed; top: 0; left: 0; width: 100%;
            background: #00A1C5;
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 24px;
            z-index: 200;
            border-bottom: 3px solid rgba(0, 0, 0, 0.15);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .badge-preview {
            background: rgba(255, 255, 255, 0.2); color: #fff;
            font-size: 10px; font-weight: 700;
            letter-spacing: .05em; text-transform: uppercase;
            padding: 4px 10px; border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .topbar-title { color: #ffffff; font-size: 14px; font-weight: 600; letter-spacing: -0.01em; }
        .topbar-right { display: flex; gap: 10px; align-items: center; }
        .btn-nav {
            display: flex; align-items: center; gap: 6px;
            padding: 7px 16px; border-radius: 4px;
            font-size: 12px; font-weight: 600; text-decoration: none;
            cursor: pointer; border: none; transition: all 0.2s ease;
            color: #fff; background: rgba(255, 255, 255, 0.15);
        }
        .btn-nav:hover { background: rgba(255, 255, 255, 0.25); }
        .btn-home  { background: rgba(255, 255, 255, 0.1); color: #fff; border: 1px solid rgba(255, 255, 255, 0.2); }
        .btn-home:hover { background: rgba(255, 255, 255, 0.2); }
        .btn-edit  { background: rgba(255, 255, 255, 0.15); color: #fff; }
        .btn-print { background: #ffffff; color: #00A1C5; font-weight: 700; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .btn-print:hover { background: #f0fdfa; color: #008eb0; }
        .btn-save-active { background: #10b981; color: #fff; }
        .btn-save-active:hover { background: #059669; }

        /* ── CANVAS ─────────────────────────────────────── */
        .canvas-wrap { display: flex; justify-content: center; padding: 85px 20px 40px; }

        main {
            background: var(--white);
            width: 210mm; min-height: 297mm;
            padding: 8mm 10mm;
            display: flex; flex-direction: column; justify-content: space-between;
            box-shadow: 0 4px 32px rgba(0,0,0,.18);
            position: relative;
        }

        /* ── HEADER ─────────────────────────────────────── */
        .doc-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px; }
        .logo-block { display: flex; align-items: center; gap: 12px; }
        .logo-frame {
            width: 52px; height: 52px;
            border: 1px solid var(--rule); border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            background: var(--bg-cell); overflow: hidden; padding: 4px;
        }
        .logo-frame img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .hospital-name { font-size: 20.4px; font-weight: 800; color: #00A1C5; letter-spacing: -.02em; text-transform: uppercase; line-height: 1; margin-bottom: 4px; }
        .hospital-address { font-size: 10.2px; color: var(--ink-lt); line-height: 1.45; }
        .form-number-box {
            border: 1.5px solid var(--ink);
            padding: 5px 10px;
            font-size: 10.8px; font-weight: 700;
            font-family: 'IBM Plex Mono', monospace;
            text-align: right; line-height: 1.6;
            background: var(--bg-cell);
        }

        /* ── RULE ─────────────────────────────────────── */
        .double-rule { margin: 4px 0 6px; border: none; border-top: 3px double var(--ink); height: 4px; }

        /* ── TITLE BLOCK ──────────────────────────────── */
        .doc-title-block {
            border: 1.5px solid var(--ink);
            padding: 6px 12px; margin-bottom: 6px;
            text-align: center; background: var(--bg-cell);
        }
        .doc-title { font-size: 15.6px; font-weight: 800; text-transform: uppercase; letter-spacing: .07em; line-height: 1.2; }
        .doc-subtitle { font-size: 10.2px; color: var(--ink-lt); font-style: italic; margin-top: 1px; }

        /* ── SECTION HEADER ───────────────────────────── */
        .sec-head {
            font-size: 10.2px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em;
            background: var(--ink); color: #fff;
            padding: 4px 8px; margin-bottom: 0;
            border-radius: 2px 2px 0 0;
        }
        .sec-head-light {
            font-size: 10.2px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em;
            background: var(--bg-cell); color: var(--ink-mid);
            padding: 4px 8px; margin-bottom: 0;
            border: 1px solid var(--rule); border-bottom: none;
            border-radius: 2px 2px 0 0;
        }

        /* ── FORM TABLE ───────────────────────────────── */
        .form-table { width: 100%; border-collapse: collapse; }
        .form-table td {
            border: 1px solid var(--rule);
            padding: 4px 7px;
            font-size: 11.4px;
            vertical-align: middle;
        }
        .lbl { background: var(--bg-cell); font-weight: 600; color: var(--ink-mid); white-space: nowrap; }

        /* ── CHECKBOX ─────────────────────────────────── */
        .cb {
            display: inline-block;
            width: 13px; height: 13px;
            border: 1.5px solid var(--ink);
            vertical-align: middle;
            margin-right: 3px; position: relative;
        }
        .cb.on::after {
            content: '✓';
            position: absolute; top: -4px; left: 1px;
            font-size: 13px; font-weight: 800; color: var(--ink);
        }

        /* ── 2-col grid ───────────────────────────────── */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 6px; }

        /* ── info panel ───────────────────────────────── */
        .info-panel {
            border: 1px solid var(--rule);
            padding: 7px 9px;
            font-size: 10.8px;
            background: var(--bg-cell);
        }
        .info-panel .kv { display: flex; gap: 4px; margin-bottom: 2px; }
        .info-panel .k { font-weight: 600; color: var(--ink-mid); min-width: 70px; }

        /* ── age display ─────────────────────────────── */
        .age-display {
            border: 1px solid var(--rule);
            height: 58px;
            display: flex; align-items: center; justify-content: center;
        }
        .age-val { font-size: 26.4px; font-weight: 800; color: #00A1C5; letter-spacing: -.03em; }
        .age-unit { font-size: 9.6px; font-weight: 700; color: var(--ink-lt); text-transform: uppercase; margin-left: 4px; }

        /* ── FUCoD bar ───────────────────────────────── */
        .fucod-bar {
            display: flex; justify-content: space-between; align-items: center;
            border-left: 4px solid #00A1C5;
            background: #e6f6f9;
            padding: 6px 12px; margin-bottom: 6px;
        }
        .fucod-label { font-size: 9px; font-weight: 700; color: var(--ink-lt); text-transform: uppercase; letter-spacing: .1em; margin-bottom: 2px; }
        .fucod-val { font-size: 15.6px; font-weight: 800; color: var(--ink); text-transform: uppercase; }
        .fucod-icd { border-left: 1px solid var(--rule); padding-left: 12px; text-align: right; }
        .fucod-icd-val { font-size: 24px; font-weight: 800; color: #00A1C5; font-family: 'IBM Plex Mono', monospace; }

        /* ── SIGNATURE ────────────────────────────────── */
        .sig-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; text-align: center; margin-top: 8px; }
        .sig-role { font-size: 10.8px; font-weight: 700; text-transform: uppercase; color: var(--ink-lt); margin-bottom: 70px; letter-spacing: .05em; }
        .sig-line { border-top: 1.5px solid var(--rule); width: 160px; margin: 0 auto; }
        .sig-name { font-size: 11.4px; font-weight: 800; text-transform: uppercase; margin-top: 3px; }
        .sig-sub  { font-size: 10.2px; color: var(--ink-lt); font-style: italic; }

        /* ── FOOTER ───────────────────────────────────── */
        .doc-footer {
            margin-top: 8px; padding-top: 4px;
            border-top: 1px solid var(--rule);
            text-align: center;
            font-size: 8.4px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .2em;
            color: var(--ink-lt);
        }

        /* ── PRINT ────────────────────────────────────── */
        @media print {
            :root {
                --rule: #000000 !important;
                --ink-lt: #000000 !important;
                --ink-mid: #000000 !important;
                --bg-cell: #ffffff !important;
            }
            @page {
                size: A4 portrait;
                margin: 0;
            }
            html, body { 
                background: #fff !important; 
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .topbar { display: none !important; }
            .canvas-wrap { padding: 0 !important; margin: 0 !important; }
            main {
                width: 210mm !important; height: 296mm !important; max-height: 296mm !important;
                padding: 8mm 10mm !important; margin: 0 !important;
                box-shadow: none !important; border: none !important;
                overflow: hidden !important;
                page-break-after: avoid !important;
                page-break-inside: avoid !important;
                background: transparent !important;
                position: relative !important;
            }
            img {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                display: block !important;
            }
        }
    </style>
</head>
<body>

<nav class="topbar">
    <div class="topbar-left">
        <span class="badge-preview">Preview Mode</span>
        <span class="topbar-title">Sertifikat Medis Kematian Perinatal / Bayi (RL.4.1)</span>
    </div>
    <div class="topbar-right">
        <a href="/" class="btn-nav btn-home">
            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Beranda
        </a>
        <a href="{{ route('form.edit', $certificate->id) }}" class="btn-nav btn-edit">
            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit Data
        </a>
        <button onclick="window.print()" class="btn-nav btn-print">
            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4H7v4a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            Cetak / PDF
        </button>
        <div id="save-status-container" style="display: flex; align-items: center;">
            @if($certificate->status !== 'Saved')
            <button data-id="{{ $certificate->id }}" onclick="saveCertificate(this.getAttribute('data-id'))" class="btn-nav btn-save-active" id="save-btn">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Simpan ke Arsip
            </button>
            @else
            <span class="btn-nav" style="background: #10b981; color: #fff; cursor: default; font-weight: 700;">
                <span style="font-size: 14px; margin-right:2px;">✓</span> Tersimpan
            </span>
            @endif
        </div>
    </div>
</nav>

<div class="canvas-wrap">
<main>
    @include('partials.watermark')

    <div>
        <header class="doc-header">
            <div class="logo-block">
                <div class="logo-frame">
                    <img src="{{ asset('asset/logo-rs-wava-husada.png') }}" alt="Logo RSWH">
                </div>
                <div>
                    <div class="hospital-name">RS Wava Husada</div>
                    <div class="hospital-address">
                        Jl. Panglima Sudirman No.99A, Dilem, Kepanjen, Malang, Jawa Timur 65163<br>
                        Telp: (0341) 393000 &nbsp;|&nbsp; Email: info@wavahusada.com
                    </div>
                </div>
            </div>
            <div class="form-number-box">
                FORMULIR: RL.4.1<br>
                No: {{ $certificate->nomor_sertifikat }}
            </div>
        </header>

        <hr class="double-rule">

        <div class="doc-title-block">
            <div class="doc-title">Sertifikat Medis Penyebab Kematian Perinatal / Bayi</div>
            <div class="doc-subtitle">(Medical Certificate of Cause of Death for Perinatal / Infants)</div>
        </div>

        <div style="margin-bottom:6px">
            <div class="sec-head">1. Identitas Jenazah Bayi</div>
            <table class="form-table">
                <tbody>
                    <tr>
                        <td class="lbl" style="width:22%">No. Rekam Medis</td>
                        <td style="width:28%">{{ $certificate->data['nrm_bayi'] ?? ($certificate->data['nrm'] ?? '-') }}</td>
                        <td class="lbl" style="width:22%">No. RM Ibu</td>
                        <td>{{ $certificate->data['nrm_ibu'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Nama Bayi</td>
                        <td colspan="3" style="font-weight:700;text-transform:uppercase">
                            {{ !empty($certificate->data['nama_bayi']) ? $certificate->data['nama_bayi'] : (!empty($certificate->data['nama_lengkap']) ? $certificate->data['nama_lengkap'] : 'BY. NY. ' . ($certificate->data['nama_ibu'] ?? 'UNKNOWN')) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="lbl">Jenis Kelamin</td>
                        <td>
                            <span class="cb {{ (($certificate->data['gender_bayi'] ?? ($certificate->data['gender'] ?? '')) === 'Laki-laki') ? 'on' : '' }}"></span> Laki-laki &nbsp;
                            <span class="cb {{ (($certificate->data['gender_bayi'] ?? ($certificate->data['gender'] ?? '')) === 'Perempuan') ? 'on' : '' }}"></span> Perempuan
                        </td>
                        <td class="lbl">Agama Ibu</td>
                        <td>{{ $certificate->data['agama_ibu'] ?? ($certificate->data['agama'] ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Tanggal Lahir</td>
                        <td>
                            @php
                                $tglLahir = $certificate->data['tanggal_lahir_bayi'] ?? ($certificate->data['tanggal_lahir'] ?? null);
                            @endphp
                            {{ $tglLahir ? \Carbon\Carbon::parse($tglLahir)->translatedFormat('d F Y') : '-' }}
                        </td>
                        <td class="lbl">Waktu Lahir</td>
                        <td>{{ $certificate->data['jam_lahir_bayi'] ?? ($certificate->data['jam_lahir'] ?? '-') }} WIB</td>
                    </tr>
                    <tr>
                        <td class="lbl">Alamat Orang Tua</td>
                        <td colspan="3" style="font-size:10.2px">
                            @if(!empty($certificate->data['alamat_ibu']))
                                {{ $certificate->data['alamat_ibu'] }}
                            @else
                                {{ $certificate->data['alamat'] ?? '-' }}{{ !empty($certificate->data['kelurahan']) ? ', Kel. ' . $certificate->data['kelurahan'] : '' }}{{ !empty($certificate->data['kecamatan']) ? ', Kec. ' . $certificate->data['kecamatan'] : '' }}{{ !empty($certificate->data['kab_kota']) ? ', ' . $certificate->data['kab_kota'] : '' }}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="grid-2">
            <div>
                <div class="sec-head-light">2. Pernyataan Meninggal</div>
                @php
                    $tglKematian = $certificate->data['tanggal_meninggal_bayi'] ?? ($certificate->data['tanggal_kematian'] ?? null);
                    $hariKematian = '-';
                    if ($tglKematian) {
                        $hariKematian = \Carbon\Carbon::parse($tglKematian)->translatedFormat('l');
                    }
                @endphp
                <table class="form-table">
                    <tr>
                        <td class="lbl" style="width:45%">Hari / Tanggal</td>
                        <td>{{ $hariKematian }}, {{ $tglKematian ? \Carbon\Carbon::parse($tglKematian)->translatedFormat('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Waktu Kematian</td>
                        <td>{{ $certificate->data['jam_meninggal_bayi'] ?? ($certificate->data['jam_kematian'] ?? '-') }} WIB</td>
                    </tr>
                </table>
            </div>
            <div>
                <div class="sec-head-light">3. Umur Saat Meninggal</div>
                <div class="age-display">
                    @if(($certificate->data['lahir_mati'] ?? '') === 'Ya')
                        <span class="age-val" style="font-size: 18px;">Lahir Mati</span>
                    @elseif(isset($certificate->data['umur_hari']) && $certificate->data['umur_hari'] !== '')
                        <span class="age-val">{{ $certificate->data['umur_hari'] }}</span>
                        <span class="age-unit">Hari</span>
                    @elseif(isset($certificate->data['umur_bulan']) && $certificate->data['umur_bulan'] !== '')
                        <span class="age-val">{{ $certificate->data['umur_bulan'] }}</span>
                        <span class="age-unit">Bulan</span>
                    @elseif(isset($certificate->data['umur_tahun']) && $certificate->data['umur_tahun'] !== '')
                        <span class="age-val">{{ $certificate->data['umur_tahun'] }}</span>
                        <span class="age-unit">Tahun</span>
                    @else
                        <span class="age-val">-</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid-2">
            <div>
                <div class="sec-head-light">4. Informasi Ibu &amp; Kehamilan</div>
                <div class="info-panel" style="height:68px;display:flex;flex-direction:column;justify-content:center;gap:3px">
                    <div class="kv"><span class="k" style="min-width: 95px;">Nama Ibu</span><span>: {{ $certificate->data['nama_ibu'] ?? '-' }}</span></div>
                    <div class="kv"><span class="k" style="min-width: 95px;">Umur Ibu</span><span>: {{ $certificate->data['umur_ibu'] ?? '-' }} Tahun</span></div>
                    <div class="kv"><span class="k" style="min-width: 95px;">Usia Gestasi</span><span>: {{ $certificate->data['usia_kehamilan'] ?? ($certificate->data['usia_kehamilan_minggu'] ?? '-') }} Minggu</span></div>
                </div>
            </div>
            <div>
                <div class="sec-head-light">5. Spesifikasi Lahir &amp; Kematian</div>
                <div class="info-panel" style="height:68px;display:flex;flex-direction:column;justify-content:center;gap:3px">
                    @php
                        $meninggalSaat = $certificate->data['meninggal_saat'] ?? '';
                        
                        $umurHari = $certificate->data['umur_hari'] ?? '';
                        $umurBulan = $certificate->data['umur_bulan'] ?? '';
                        $umurTahun = $certificate->data['umur_tahun'] ?? '';
                        $lahirMati = $certificate->data['lahir_mati'] ?? '';

                        // If any age field is filled, it must be Setelah Lahir
                        if (($umurHari !== '' && floatval($umurHari) >= 0) || ($umurBulan !== '' && floatval($umurBulan) >= 0) || ($umurTahun !== '' && floatval($umurTahun) >= 0)) {
                            $meninggalSaat = 'Setelah Lahir';
                        } elseif ($lahirMati === 'Ya') {
                            // If stillbirth, default to Sebelum Lahir unless form specifically set Saat Lahir
                            if ($meninggalSaat !== 'Sebelum Lahir' && $meninggalSaat !== 'Saat Lahir') {
                                $meninggalSaat = 'Sebelum Lahir';
                            }
                        } elseif ($lahirMati === 'Tidak') {
                            $meninggalSaat = 'Setelah Lahir';
                        }

                        if (empty($meninggalSaat)) {
                            $meninggalSaat = '-';
                        }
                    @endphp
                    <div class="kv"><span class="k" style="min-width: 95px;">Berat Lahir</span><span>: {{ $certificate->data['berat_badan_lahir'] ?? ($certificate->data['berat_lahir_gram'] ?? '-') }} gram</span></div>
                    <div class="kv"><span class="k" style="min-width: 95px;">Jenis Kelahiran</span><span>: {{ $certificate->data['jenis_persalinan'] ?? '-' }}</span></div>
                    <div class="kv"><span class="k" style="min-width: 95px;">Meninggal Saat</span><span>: {{ $meninggalSaat }}</span></div>
                </div>
            </div>
        </div>

        <div class="grid-2">
            <div class="info-panel">
                <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink-lt);margin-bottom:5px">6. Dasar Diagnosis</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px 12px;font-size:10.8px">
                    <div><span class="cb {{ !empty($certificate->data['dasar_rekam_medis']) ? 'on' : '' }}"></span> Rekam Medis</div>
                    <div><span class="cb {{ !empty($certificate->data['dasar_pemeriksaan_bayi']) ? 'on' : '' }}"></span> Periksa Bayi</div>
                    <div><span class="cb {{ !empty($certificate->data['dasar_pemeriksaan_penunjang']) ? 'on' : '' }}"></span> Penunjang</div>
                    <div><span class="cb {{ !empty($certificate->data['dasar_autopsi']) ? 'on' : '' }}"></span> Autopsi</div>
                </div>
            </div>
            @php
                $babyConds = [];
                if (!empty($certificate->data['cond_prematuritas'])) $babyConds[] = 'Prematuritas';
                if (!empty($certificate->data['cond_asfiksia'])) $babyConds[] = 'Asfiksia';
                if (!empty($certificate->data['cond_infeksi'])) $babyConds[] = 'Infeksi';
                if (!empty($certificate->data['cond_kongenital'])) $babyConds[] = 'Kongenital';
                if (!empty($certificate->data['cond_sepsis'])) $babyConds[] = 'Sepsis';
                if (!empty($certificate->data['cond_bblr'])) $babyConds[] = 'BBLR';
                if (!empty($certificate->data['cond_lainnya_bayi']) && !empty($certificate->data['cond_lainnya_bayi_ket'])) {
                    $babyConds[] = $certificate->data['cond_lainnya_bayi_ket'];
                }
                $babyCondsStr = implode(', ', $babyConds) ?: '-';

                $matConds = [];
                if (!empty($certificate->data['mat_kehamilan_komplikasi'])) $matConds[] = 'Komplikasi Hamil';
                if (!empty($certificate->data['mat_persalinan_komplikasi'])) $matConds[] = 'Komplikasi Lahir';
                if (!empty($certificate->data['mat_nifas_komplikasi'])) $matConds[] = 'Komplikasi Nifas';
                if (!empty($certificate->data['mat_penyakit_ibu'])) $matConds[] = 'Penyakit Ibu';
                if (!empty($certificate->data['mat_lainnya']) && !empty($certificate->data['mat_lainnya_ket'])) {
                    $matConds[] = $certificate->data['mat_lainnya_ket'];
                }
                $matCondsStr = implode(', ', $matConds) ?: '-';
            @endphp
            <div class="info-panel" style="display:flex;flex-direction:column;justify-content:center;gap:3px;font-size:10.2px;">
                <div><span style="font-weight:600;color:var(--ink-mid);">Kondisi Bayi:</span> <span style="font-weight:500;">{{ $babyCondsStr }}</span></div>
                <div style="border-top:1px dashed var(--rule);padding-top:2px;margin-top:2px;"><span style="font-weight:600;color:var(--ink-mid);">Kondisi Ibu:</span> <span style="font-weight:500;">{{ $matCondsStr }}</span></div>
            </div>
        </div>

        <div style="margin-bottom:6px">
            <div class="sec-head-light">8. Kondisi &amp; Penyebab Kematian Perinatal (Cause of Death)</div>
            <table class="form-table">
                <thead>
                    <tr style="background:var(--bg-cell);text-align:center;font-size:10.2px;font-weight:700">
                        <td style="width:75%">Kategori Kondisi Utama / Kontributor</td>
                        <td style="width:25%">Kode ICD-10</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b style="color:var(--ink-mid);margin-right:4px">a.</b> Kondisi Utama Bayi (Main disease/condition in fetus or infant):<br><span style="padding-left:14px;font-weight:500;">{{ $certificate->data['penyebab_utama_bayi'] ?? ($certificate->data['penyebab_a'] ?? '-') }}</span></td>
                        <td style="text-align:center;font-weight:700;font-family:'IBM Plex Mono',monospace;font-size:10.8px">{{ $certificate->data['icd_penyebab_bayi'] ?? ($certificate->data['icd_a'] ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td><b style="color:var(--ink-mid);margin-right:4px">b.</b> Kondisi Lain Bayi (Other diseases/conditions in fetus or infant):<br><span style="padding-left:14px;font-weight:500;">{{ $certificate->data['penyebab_lain_bayi'] ?? ($certificate->data['penyebab_b'] ?? '-') }}</span></td>
                        <td style="text-align:center;font-weight:700;font-family:'IBM Plex Mono',monospace;font-size:10.8px">-</td>
                    </tr>
                    <tr>
                        <td><b style="color:var(--ink-mid);margin-right:4px">c.</b> Kondisi Utama Ibu mempengaruhi janin (Main maternal disease affecting fetus):<br><span style="padding-left:14px;font-weight:500;">{{ $certificate->data['penyebab_utama_ibu'] ?? ($certificate->data['penyebab_c'] ?? '-') }}</span></td>
                        <td style="text-align:center;font-weight:700;font-family:'IBM Plex Mono',monospace;font-size:10.8px">{{ $certificate->data['icd_penyebab_maternal'] ?? ($certificate->data['icd_c'] ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td><b style="color:var(--ink-mid);margin-right:4px">d.</b> Kondisi Lain Ibu mempengaruhi janin (Other maternal diseases affecting fetus):<br><span style="padding-left:14px;font-weight:500;">{{ $certificate->data['penyebab_lain_ibu'] ?? ($certificate->data['penyebab_d'] ?? '-') }}</span></td>
                        <td style="text-align:center;font-weight:700;font-family:'IBM Plex Mono',monospace;font-size:10.8px">-</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="fucod-bar">
            <div>
                <div class="fucod-label">Final Underlying Cause of Death (FUCoD)</div>
                <div class="fucod-val">{{ $certificate->data['penyebab_utama_bayi'] ?? ($certificate->data['fucod'] ?? '-') }}</div>
            </div>
            <div class="fucod-icd">
                <div class="fucod-label">Kode ICD-10</div>
                <div class="fucod-icd-val">{{ $certificate->data['icd_fucod_bayi'] ?? ($certificate->data['icd_fucod'] ?? '-') }}</div>
            </div>
        </div>
    </div>

    <div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; text-align: center; margin-bottom: 2px;">
            <div></div>
            <div style="font-size:10.2px;color:var(--ink-lt);">
                Malang, {{ isset($certificate->data['tanggal_ttd']) ? \Carbon\Carbon::parse($certificate->data['tanggal_ttd'])->translatedFormat('d F Y') : '-' }}
            </div>
        </div>
        <div class="sig-grid" style="margin-top: 0px;">
            <div>
                <div class="sig-role">Pihak Penerima / Keluarga Jenazah,</div>
                <div class="sig-line"></div>
                <div class="sig-name">{{ $certificate->data['nama_terang_penerima'] ?? '..................................' }}</div>
                <div class="sig-sub">Hubungan: {{ $certificate->data['hubungan_bayi'] ?? ($certificate->data['hubungan_jenazah'] ?? '-') }}</div>
            </div>
            <div>
                <div class="sig-role">Dokter Penanggung Jawab,</div>
                <div class="sig-line"></div>
                <div class="sig-name">{{ $certificate->data['nama_dokter'] ?? 'Dr. ..................................' }}</div>
                <div class="sig-sub">SIP. {{ $certificate->data['nomor_sip'] ?? '-' }}</div>
            </div>
        </div>

        <div class="doc-footer">
            Dokumen Resmi RS Wava Husada &nbsp;•&nbsp; Standar Kematian Perinatal RL.4.1 &nbsp;•&nbsp; Cetak Satu Halaman A4
        </div>
    </div>
</main>
</div>

<script>
async function saveCertificate(certificateId) {
    if (confirm('Simpan sertifikat ini ke arsip?\n\nSertifikat yang sudah disimpan tidak dapat diubah lagi.')) {
        try {
            const response = await fetch(`/api/certificates/${certificateId}/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            const data = await response.json();

            if (data.success) {
                const container = document.getElementById('save-status-container');
                container.innerHTML = `
                    <span class="btn-nav" style="background: #10b981; color: #fff; cursor: default; display: flex; align-items: center; gap: 6px; font-weight: 700;">
                        <span style="font-size: 14px;">✓</span> Tersimpan
                    </span>
                `;
                alert(data.message);
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan sertifikat');
        }
    }
}
</script>

</body>
</html>