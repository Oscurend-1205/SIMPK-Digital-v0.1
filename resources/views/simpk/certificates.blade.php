<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Arsip Sertifikat - SIMPK RS Wava Husada</title>
<link rel="icon" type="image/png" href="{{ asset('asset/RS-Wava-Husada.png') }}">
<meta name="description" content="Arsip semua sertifikat medis penyebab kematian yang telah dibuat di RS Wava Husada"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet"/>
<script src="https://unpkg.com/@phosphor-icons/web"></script>
<style>
    :root {
        --brand:    #0f6e72;
        --brand-md: #1da1a6;
        --brand-lt: #e8f7f7;
        --ink:      #0c1924;
        --ink-mid:  #3d5166;
        --ink-lt:   #7a8fa0;
        --rule:     #c8d6df;
        --bg-app:   #eef2f5;
        --bg-cell:  #f4f8fa;
        --white:    #ffffff;
        --sidebar-w: 220px;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'IBM Plex Sans', sans-serif;
        background: var(--bg-app); color: var(--ink);
        display: flex; min-height: 100vh;
        font-size: 13px; -webkit-font-smoothing: antialiased;
    }

    /* ── MAIN ────────────────────────────────── */
    .main-wrap { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

    /* ── CONTENT ─────────────────────────────── */
    .content { padding: 20px 24px; flex: 1; }

    /* ── PAGE HEAD ────────────────────────────── */
    .page-head {
        display: flex; justify-content: space-between; align-items: flex-end;
        margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--rule);
    }
    .page-head h1 { font-size: 15px; font-weight: 800; color: var(--ink); margin-bottom: 2px; }
    .page-head p  { font-size: 11px; color: var(--ink-lt); }
    .btn-new {
        display: flex; align-items: center; gap: 6px;
        padding: 7px 14px; background: var(--brand); color: #fff;
        border-radius: 4px; font-size: 12px; font-weight: 700;
        text-decoration: none; border: none; cursor: pointer;
        transition: background .15s; white-space: nowrap;
        font-family: inherit;
    }
    .btn-new:hover { background: #0a5558; }

    /* ── TOOLBAR ──────────────────────────────── */
    .toolbar {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 12px; flex-wrap: wrap;
    }
    .search-box {
        display: flex; align-items: center; gap: 8px;
        background: var(--white); border: 1px solid var(--rule);
        border-radius: 4px; padding: 0 12px;
        height: 32px;
        flex: 1; min-width: 180px; max-width: 340px;
        transition: border-color .15s;
    }
    .search-box:focus-within { border-color: var(--brand-md); }
    .search-box i { color: var(--ink-lt); font-size: 15px; flex-shrink: 0; }
    .search-box input {
        border: none; outline: none; background: transparent;
        font-size: 12px; font-family: inherit; color: var(--ink);
        width: 100%;
    }
    .search-box input::placeholder { color: var(--ink-lt); }

    .filter-select {
        background: var(--white); border: 1px solid var(--rule);
        border-radius: 4px; padding: 0 10px;
        height: 32px;
        font-size: 11px; font-weight: 600; color: var(--ink-mid);
        font-family: inherit; cursor: pointer;
        transition: border-color .15s;
    }
    .filter-select:focus { outline: none; border-color: var(--brand-md); }

    .toolbar-stats {
        margin-left: auto;
        font-size: 11px; color: var(--ink-lt); font-weight: 600;
        display: flex; align-items: center; gap: 6px;
    }
    .toolbar-stats .count {
        font-weight: 800; color: var(--ink);
        font-family: 'IBM Plex Mono', monospace;
    }

    /* ── TABLE ───────────────────────────────── */
    .table-wrap {
        background: var(--white); border: 1px solid var(--rule);
        border-radius: 6px; overflow: hidden;
    }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: var(--bg-cell); }
    thead th {
        padding: 8px 18px;
        font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .08em; color: var(--ink-lt);
        text-align: left; border-bottom: 1px solid var(--rule);
        white-space: nowrap;
    }
    tbody tr { border-bottom: 1px solid var(--bg-cell); transition: background .1s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--bg-cell); }
    tbody td { padding: 9px 18px; font-size: 12px; color: var(--ink); vertical-align: middle; }

    .td-cert-no { font-weight: 700; font-family: 'IBM Plex Mono', monospace; font-size: 11px; color: var(--brand); }
    .td-name    { font-weight: 600; color: var(--ink); }
    .td-rm      { font-size: 10px; color: var(--ink-lt); margin-top: 1px; font-family: 'IBM Plex Mono', monospace; }
    .td-muted   { color: var(--ink-mid); }
    .td-center  { text-align: center; }

    .type-badge {
        display: inline-block; padding: 2px 8px;
        background: var(--brand-lt); color: var(--brand);
        border: 1px solid #b2e8e9; border-radius: 100px;
        font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: .04em;
    }
    .type-badge.bayi { background: #fdf2f8; color: #be185d; border-color: #f9a8d4; }

    /* Status badges */
    .badge {
        display: inline-block; padding: 2px 9px;
        border-radius: 100px; font-size: 10px; font-weight: 700;
        letter-spacing: .02em;
    }
    .badge-printed { background: #e6f9f8; color: var(--brand); border: 1px solid #b2e8e9; }
    .badge-saved   { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .badge-draft   { background: var(--bg-cell); color: var(--ink-lt); border: 1px solid var(--rule); }

    /* ── ACTIONS ─────────────────────────────── */
    .actions { display: flex; align-items: center; justify-content: flex-end; gap: 4px; }
    .action-btn {
        display: flex; align-items: center; justify-content: center;
        width: 30px; height: 30px; border-radius: 4px;
        border: none; cursor: pointer; background: transparent;
        font-size: 16px; transition: all .15s;
        text-decoration: none;
    }
    .action-btn.view  { color: var(--brand-md); }
    .action-btn.view:hover  { background: var(--brand-lt); color: var(--brand); }
    .action-btn.edit  { color: #d97706; }
    .action-btn.edit:hover  { background: #fffbeb; color: #b45309; }
    .action-btn.download { color: #2563eb; }
    .action-btn.download:hover { background: #eff6ff; color: #1d4ed8; }
    .action-btn.del   { color: var(--ink-lt); }
    .action-btn.del:hover   { background: #fef2f2; color: #dc2626; }

    /* ── TOOLTIP ─────────────────────────────── */
    .action-btn[title] { position: relative; }

    /* ── EMPTY STATE ─────────────────────────── */
    .empty-state {
        padding: 56px 24px; text-align: center;
        color: var(--ink-lt); opacity: .55;
    }
    .empty-state i { font-size: 48px; margin-bottom: 10px; display: block; }
    .empty-state p { font-size: 12px; font-weight: 500; }

    /* ── PAGINATION ──────────────────────────── */
    .pagination-wrap {
        display: flex; justify-content: space-between; align-items: center;
        padding: 12px 18px; border-top: 1px solid var(--rule);
        background: var(--bg-cell);
    }
    .pagination-info { font-size: 11px; color: var(--ink-lt); font-weight: 500; }
    .pagination-btns { display: flex; gap: 4px; }
    .page-btn {
        display: flex; align-items: center; justify-content: center;
        min-width: 30px; height: 30px; padding: 0 8px;
        border-radius: 4px; border: 1px solid var(--rule);
        background: var(--white); color: var(--ink-mid);
        font-size: 11px; font-weight: 600; cursor: pointer;
        text-decoration: none; transition: all .15s;
        font-family: inherit;
    }
    .page-btn:hover { border-color: var(--brand-md); color: var(--brand); }
    .page-btn.active { background: var(--brand); color: #fff; border-color: var(--brand); }
    .page-btn.disabled { opacity: .4; cursor: default; pointer-events: none; }

    /* ── TOAST ───────────────────────────────── */
    .toast-wrap { position: fixed; bottom: 20px; right: 20px; z-index: 200; display: flex; flex-direction: column; gap: 8px; }
    .toast {
        display: flex; align-items: center; gap: 8px;
        padding: 9px 16px; border-radius: 6px;
        font-size: 12px; font-weight: 600; color: #fff;
        box-shadow: 0 4px 16px rgba(0,0,0,.15);
        transform: translateY(12px); opacity: 0; transition: all .25s;
    }
    .toast.show { transform: translateY(0); opacity: 1; }

    /* ── DOWNLOAD MODAL ─────────────────────── */
    .modal-overlay {
        position: fixed; inset: 0;
        background: rgba(12, 25, 36, 0.5);
        backdrop-filter: blur(4px);
        z-index: 300;
        display: none; align-items: center; justify-content: center;
        animation: fadeIn .2s ease;
    }
    .modal-overlay.show { display: flex; }
    .modal-box {
        background: var(--white);
        border-radius: 8px;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
        max-width: 380px; width: 100%;
        padding: 28px 24px;
        text-align: center;
        animation: slideUp .25s ease;
    }
    .modal-icon {
        width: 52px; height: 52px;
        border-radius: 50%;
        background: var(--brand-lt);
        color: var(--brand);
        display: flex; align-items: center; justify-content: center;
        font-size: 24px;
        margin: 0 auto 14px;
    }
    .modal-title { font-size: 14px; font-weight: 800; color: var(--ink); margin-bottom: 6px; }
    .modal-desc { font-size: 11px; color: var(--ink-lt); line-height: 1.5; margin-bottom: 20px; }
    .modal-actions { display: flex; gap: 8px; justify-content: center; }
    .modal-btn {
        padding: 8px 18px; border-radius: 4px;
        font-size: 12px; font-weight: 700;
        border: none; cursor: pointer; font-family: inherit;
        transition: all .15s;
    }
    .modal-btn.primary { background: var(--brand); color: #fff; }
    .modal-btn.primary:hover { background: #0a5558; }
    .modal-btn.secondary { background: var(--bg-cell); color: var(--ink-mid); border: 1px solid var(--rule); }
    .modal-btn.secondary:hover { background: var(--rule); }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { transform: translateY(16px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    /* ── DETAIL MODAL ───────────────────────── */
    .modal-detail-overlay {
        position: fixed; inset: 0;
        background: rgba(12, 25, 36, 0.6);
        backdrop-filter: blur(5px);
        z-index: 300;
        display: none; align-items: center; justify-content: center;
        animation: fadeIn .2s ease;
        padding: 20px;
    }
    .modal-detail-overlay.show { display: flex; }
    .modal-detail-box {
        background: var(--white);
        border-radius: 8px;
        box-shadow: 0 20px 60px rgba(0,0,0,.25);
        max-width: 680px; width: 100%;
        max-height: 90vh; overflow-y: auto;
        animation: slideUp .25s ease;
        display: flex; flex-direction: column;
    }
    .modal-detail-head {
        padding: 16px 20px;
        border-bottom: 1px solid var(--rule);
        display: flex; justify-content: space-between; align-items: center;
        background: var(--bg-cell);
    }
    .modal-detail-head h3 { font-size: 14px; font-weight: 800; color: var(--ink); }
    .modal-detail-close {
        background: none; border: none; font-size: 20px; cursor: pointer; color: var(--ink-lt);
        transition: color .15s;
    }
    .modal-detail-close:hover { color: var(--ink); }
    .modal-detail-body { padding: 20px; overflow-y: auto; }
    
    .detail-section-title {
        font-size: 11px; font-weight: 800; text-transform: uppercase;
        color: var(--brand); border-bottom: 1.5px solid var(--brand-lt);
        padding-bottom: 4px; margin-bottom: 12px; margin-top: 18px;
        letter-spacing: .05em; display: flex; align-items: center; gap: 6px;
    }
    .detail-section-title:first-child { margin-top: 0; }
    
    .detail-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px 20px; }
    .detail-item { display: flex; flex-direction: column; gap: 2px; }
    .detail-label { font-size: 10px; font-weight: 700; color: var(--ink-lt); text-transform: uppercase; letter-spacing: .02em; }
    .detail-val { font-size: 12px; font-weight: 600; color: var(--ink); }
    .detail-val.mono { font-family: 'IBM Plex Mono', monospace; color: var(--brand); font-weight: 700; }
    
    .cause-row {
        display: grid; grid-template-columns: 24px 1fr 100px 80px; gap: 8px;
        padding: 6px 10px; border-bottom: 1px solid var(--bg-cell);
        align-items: center;
    }
    .cause-row.header {
        background: var(--bg-cell); font-weight: 700; font-size: 9px;
        color: var(--ink-lt); text-transform: uppercase; border-radius: 4px;
        margin-bottom: 4px;
    }
    .cause-row.fucod-row {
        background: var(--brand-lt); border: 1.5px solid #b2e8e9;
        margin-top: 8px; border-radius: 4px;
    }
    
    .modal-detail-foot {
        padding: 16px 20px;
        border-top: 1px solid var(--rule);
        display: flex; justify-content: space-between; align-items: center;
        background: var(--bg-cell);
    }
    
    .status-editor {
        display: flex; align-items: center; gap: 8px;
    }
    
    .status-select-inline {
        padding: 4px 10px; border-radius: 4px; border: 1px solid var(--rule);
        font-size: 11px; font-weight: 700; font-family: inherit;
        background: var(--white); cursor: pointer;
    }
    
    .badge-interactive {
        cursor: pointer; transition: transform .15s, opacity .15s;
    }
    .badge-interactive:hover { transform: scale(1.04); opacity: 0.9; }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { transform: translateY(16px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    /* ── RESPONSIVE ──────────────────────────── */
    @media (max-width: 768px) {
        .toolbar { flex-direction: column; align-items: stretch; }
        .search-box { max-width: 100%; }
        .toolbar-stats { margin-left: 0; }
        .page-head { flex-direction: column; gap: 10px; align-items: flex-start; }
        .detail-grid { grid-template-columns: 1fr; }
        .cause-row { grid-template-columns: 20px 1fr 60px; }
        .cause-row .interval { display: none; }
    }
</style>
</head>
<body>

@include('partials.watermark')
@include('partials.sidebar', ['activePage' => 'documents'])

<!-- MAIN -->
<div class="main-wrap">

    <div class="content">

        <!-- Page Head -->
        <div class="page-head">
            <div>
                <h1>Arsip Sertifikat Kematian</h1>
                <p>Seluruh sertifikat medis penyebab kematian yang telah direkam dalam sistem.</p>
            </div>
            <a href="/form" class="btn-new">
                <i class="ph-bold ph-plus"></i> Buat Baru
            </a>
        </div>

        <!-- Toolbar -->
        <div class="toolbar">
            <div class="search-box" id="search-box">
                <i class="ph-bold ph-magnifying-glass"></i>
                <input type="text" id="search-input" placeholder="Cari nama, no. sertifikat, NRM..." autocomplete="off" value="{{ request('search') }}"/>
            </div>

            <select class="filter-select" id="filter-status">
                <option value="all" {{ request('status') === 'all' || !request()->has('status') ? 'selected' : '' }}>Semua Status</option>
                <option value="Saved" {{ request('status') === 'Saved' ? 'selected' : '' }}>Tersimpan</option>
                <option value="Printed" {{ request('status') === 'Printed' ? 'selected' : '' }}>Tercetak</option>
                <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
            </select>

            <select class="filter-select" id="filter-type">
                <option value="all" {{ request('type') === 'all' || !request()->has('type') ? 'selected' : '' }}>Semua Jenis</option>
                <option value="Dewasa" {{ request('type') === 'Dewasa' ? 'selected' : '' }}>Dewasa</option>
                <option value="Bayi" {{ request('type') === 'Bayi' ? 'selected' : '' }}>Bayi</option>
            </select>

            <select class="filter-select" id="filter-doa">
                <option value="all" {{ request('doa') === 'all' || !request()->has('doa') ? 'selected' : '' }}>Semua Kematian</option>
                <option value="1" {{ request('doa') === '1' ? 'selected' : '' }}>Hanya DOA</option>
            </select>

            <div class="toolbar-stats">
                <i class="ph-bold ph-files" style="font-size:14px"></i>
                Total: <span class="count" id="visible-count">{{ $certificates->total() }}</span> sertifikat
            </div>
        </div>

        <!-- Table -->
        <div class="table-wrap">
            <table id="cert-table">
                <thead>
                    <tr>
                        <th>No. Sertifikat</th>
                        <th>Pasien</th>
                        <th>Jenis</th>
                        <th>Waktu Meninggal</th>
                        <th>Dokter</th>
                        <th class="td-center">Status</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="cert-body">
                    @forelse($certificates as $cert)
                    <tr data-status="{{ $cert->status }}" data-type="{{ $cert->jenis }}"
                        data-doa="{{ (isset($cert->data['doa']) && $cert->data['doa'] === 'Ya') || (isset($cert->data['doa_bayi']) && $cert->data['doa_bayi'] === 'Ya') ? '1' : '0' }}"
                        data-search="{{ strtolower($cert->nomor_sertifikat . ' ' . ($cert->patient?->nama_lengkap ?? '') . ' ' . ($cert->patient?->nrm ?? '')) }}">
                        <td class="td-cert-no">{{ $cert->nomor_sertifikat }}</td>
                        <td>
                            <div class="td-name">{{ $cert->patient?->nama_lengkap ?? '-' }}</div>
                            <div class="td-rm">RM: {{ $cert->patient?->nrm ?? '-' }}</div>
                        </td>
                        <td>
                            <span class="type-badge {{ strtolower($cert->jenis) === 'bayi' ? 'bayi' : '' }}">{{ $cert->jenis }}</span>
                        </td>
                        <td class="td-muted">
                            @if($cert->waktu_meninggal)
                                {{ $cert->waktu_meninggal->format('d M Y, H:i') }}
                            @else
                                <span style="color:var(--ink-lt)">—</span>
                            @endif
                        </td>
                        <td class="td-muted">{{ $cert->doctor?->nama_dokter ?? '-' }}</td>
                        <td class="td-center">
                            @if($cert->status === 'Printed')
                                <span class="badge badge-printed">Tercetak</span>
                            @elseif($cert->status === 'Saved')
                                <span class="badge badge-saved">Tersimpan</span>
                            @else
                                <span class="badge badge-draft">Draft</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                {{-- View Detail Modal --}}
                                <button data-action="view" data-id="{{ $cert->id }}" class="action-btn view" title="Lihat Detail">
                                    <i class="ph-bold ph-eye"></i>
                                </button>

                                {{-- Download / Print --}}
                                @if($cert->status !== 'Draft')
                                    <button data-action="download" data-id="{{ $cert->id }}" data-jenis="{{ $cert->jenis }}" data-nama="{{ $cert->patient?->nama_lengkap ?? 'Unknown' }}" class="action-btn download" title="Cetak / Save PDF">
                                        <i class="ph-bold ph-download-simple"></i>
                                    </button>
                                @endif

                                {{-- Edit --}}
                                <a href="{{ route('form.edit', $cert->id) }}" class="action-btn edit" title="Edit Data">
                                    <i class="ph-bold ph-pencil-line"></i>
                                </a>

                                {{-- Delete --}}
                                <button data-action="delete" data-id="{{ $cert->id }}" data-nama="{{ $cert->patient?->nama_lengkap ?? 'Unknown' }}" class="action-btn del" title="Hapus Sertifikat">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="empty-row">
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="ph ph-file-dashed"></i>
                                <p>Belum ada sertifikat yang direkam dalam sistem.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- No results state (hidden by default) --}}
            <div id="no-results" style="display:none">
                <div class="empty-state">
                    <i class="ph ph-magnifying-glass"></i>
                    <p>Tidak ditemukan sertifikat yang sesuai dengan pencarian Anda.</p>
                </div>
            </div>

            {{-- Pagination --}}
            @if($certificates->hasPages())
            <div class="pagination-wrap">
                <div class="pagination-info">
                    Menampilkan {{ $certificates->firstItem() }}–{{ $certificates->lastItem() }} dari {{ $certificates->total() }} data
                </div>
                <div class="pagination-btns">
                    {{-- Previous --}}
                    @if($certificates->onFirstPage())
                        <span class="page-btn disabled"><i class="ph-bold ph-caret-left" style="font-size:12px"></i></span>
                    @else
                        <a href="{{ $certificates->previousPageUrl() }}" class="page-btn"><i class="ph-bold ph-caret-left" style="font-size:12px"></i></a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach($certificates->getUrlRange(max(1, $certificates->currentPage() - 2), min($certificates->lastPage(), $certificates->currentPage() + 2)) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $certificates->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach

                    {{-- Next --}}
                    @if($certificates->hasMorePages())
                        <a href="{{ $certificates->nextPageUrl() }}" class="page-btn"><i class="ph-bold ph-caret-right" style="font-size:12px"></i></a>
                    @else
                        <span class="page-btn disabled"><i class="ph-bold ph-caret-right" style="font-size:12px"></i></span>
                    @endif
                </div>
            </div>
            @endif
        </div>

    </div><!-- /content -->

    <footer style="padding:14px 24px;text-align:center;font-size:10px;color:var(--ink-lt);border-top:1px solid var(--rule);background:var(--white);margin-top:auto">
        &copy; 2026 RS Wava Husada — Sistem Informasi Medis Penyebab Kematian Digital
    </footer>
</div>

<!-- TOAST -->
<div class="toast-wrap" id="toast-wrap"></div>

<!-- DOWNLOAD MODAL -->
<div class="modal-overlay" id="download-modal">
    <div class="modal-box">
        <div class="modal-icon">
            <i class="ph-bold ph-file-pdf"></i>
        </div>
        <div class="modal-title">Download Sertifikat</div>
        <div class="modal-desc" id="modal-desc">
            Sertifikat akan dibuka di tab baru dalam mode cetak. Pilih <strong>"Save as PDF"</strong> pada dialog cetak untuk menyimpan sebagai file PDF.
        </div>
        <div class="modal-actions">
            <button class="modal-btn secondary" onclick="closeDownloadModal()">Batal</button>
            <button class="modal-btn primary" id="modal-confirm-btn">
                <i class="ph-bold ph-download-simple" style="margin-right:4px"></i> Download
            </button>
        </div>
    </div>
</div>

<!-- DETAIL MODAL -->
<div class="modal-detail-overlay" id="detail-modal">
    <div class="modal-detail-box">
        <div class="modal-detail-head">
            <h3 id="detail-modal-title">Detail Sertifikat</h3>
            <button class="modal-detail-close" onclick="closeDetailModal()"><i class="ph-bold ph-x"></i></button>
        </div>
        <div class="modal-detail-body">
            <!-- Section 1: Identitas Sertifikat -->
            <div class="detail-section-title">
                <i class="ph-bold ph-file-text"></i> Informasi Sertifikat
            </div>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Nomor Sertifikat</span>
                    <span class="detail-val mono" id="detail-cert-no">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Jenis Sertifikat</span>
                    <span class="detail-val" id="detail-cert-type">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Waktu Meninggal</span>
                    <span class="detail-val" id="detail-cert-death-time">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Dokter Pemeriksa</span>
                    <span class="detail-val" id="detail-cert-doctor">-</span>
                </div>
            </div>

            <!-- Section 2: Identitas Jenazah -->
            <div class="detail-section-title">
                <i class="ph-bold ph-user"></i> Identitas Jenazah
            </div>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Nama Lengkap</span>
                    <span class="detail-val" id="detail-patient-name">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Nomor Rekam Medis (NRM)</span>
                    <span class="detail-val mono" id="detail-patient-nrm">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">NIK Jenazah</span>
                    <span class="detail-val" id="detail-patient-nik">-</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Jenis Kelamin</span>
                    <span class="detail-val" id="detail-patient-gender">-</span>
                </div>
                <div class="detail-item" style="grid-column: span 2;">
                    <span class="detail-label">Alamat Lengkap</span>
                    <span class="detail-val" id="detail-patient-address">-</span>
                </div>
            </div>

            <!-- Section 3: Penyebab Kematian -->
            <div class="detail-section-title">
                <i class="ph-bold ph-activity"></i> Penyebab Kematian (Cause of Death)
            </div>
            <div class="cause-table-wrap" style="border: 1px solid var(--rule); border-radius: 6px; overflow: hidden;">
                <div class="cause-row header">
                    <span>Seq</span>
                    <span>Deskripsi Peristiwa Penyakit</span>
                    <span>Interval</span>
                    <span style="text-align: right;">ICD-10</span>
                </div>
                <div class="cause-row">
                    <strong>A</strong>
                    <span id="detail-cause-a">-</span>
                    <span id="detail-interval-a">-</span>
                    <span id="detail-icd-a" class="detail-val mono" style="text-align: right;">-</span>
                </div>
                <div class="cause-row">
                    <strong>B</strong>
                    <span id="detail-cause-b">-</span>
                    <span id="detail-interval-b">-</span>
                    <span id="detail-icd-b" class="detail-val mono" style="text-align: right;">-</span>
                </div>
                <div class="cause-row">
                    <strong>C</strong>
                    <span id="detail-cause-c">-</span>
                    <span id="detail-interval-c">-</span>
                    <span id="detail-icd-c" class="detail-val mono" style="text-align: right;">-</span>
                </div>
                <div class="cause-row">
                    <strong>D</strong>
                    <span id="detail-cause-d">-</span>
                    <span id="detail-interval-d">-</span>
                    <span id="detail-icd-d" class="detail-val mono" style="text-align: right;">-</span>
                </div>
                <div class="cause-row">
                    <strong>II</strong>
                    <span id="detail-cause-other" style="font-style: italic;">-</span>
                    <span>-</span>
                    <span style="text-align: right;">-</span>
                </div>
                <div class="cause-row fucod-row">
                    <strong>FUCoD</strong>
                    <span id="detail-fucod" style="font-weight: 800; color: var(--ink);">-</span>
                    <span>-</span>
                    <span id="detail-icd-fucod" class="detail-val mono" style="text-align: right; font-size: 13px;">-</span>
                </div>
            </div>
        </div>
        <div class="modal-detail-foot">
            <div class="status-editor">
                <span class="detail-label" style="margin-bottom: 0;">Status:</span>
                <select class="status-select-inline" id="detail-status-changer">
                    <option value="Draft">Draft</option>
                    <option value="Saved">Tersimpan</option>
                    <option value="Printed">Tercetak</option>
                </select>
            </div>
            <div class="modal-actions">
                <a href="#" id="detail-edit-link" class="modal-btn secondary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                    <i class="ph-bold ph-pencil-line"></i> Edit Data
                </a>
                <button id="detail-print-btn" class="modal-btn primary" style="display: inline-flex; align-items: center; gap: 4px;">
                    <i class="ph-bold ph-printer"></i> Cetak / PDF
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // ── Search & Filter ─────────────────────────
    const searchInput = document.getElementById('search-input');
    const filterStatus = document.getElementById('filter-status');
    const filterType = document.getElementById('filter-type');
    const filterDoa = document.getElementById('filter-doa');
    const certBody = document.getElementById('cert-body');
    const visibleCount = document.getElementById('visible-count');
    const noResults = document.getElementById('no-results');

    function applyFilters() {
        const query = searchInput.value.trim();
        const statusFilter = filterStatus.value;
        const typeFilter = filterType.value;
        const doaFilter = filterDoa.value;
        
        let url = new URL(window.location.href);
        
        if (query) url.searchParams.set('search', query);
        else url.searchParams.delete('search');
        
        if (statusFilter !== 'all') url.searchParams.set('status', statusFilter);
        else url.searchParams.delete('status');
        
        if (typeFilter !== 'all') url.searchParams.set('type', typeFilter);
        else url.searchParams.delete('type');
        
        if (doaFilter !== 'all') url.searchParams.set('doa', doaFilter);
        else url.searchParams.delete('doa');
        
        // Reset to page 1 on new filter
        url.searchParams.delete('page');
        
        window.location.href = url.toString();
    }

    // Debounce function to prevent triggering applyFilters too often for search input
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Use enter key for search or debounce
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
    filterStatus.addEventListener('change', applyFilters);
    filterType.addEventListener('change', applyFilters);
    filterDoa.addEventListener('change', applyFilters);

    // ── Interactive CRUD Event Delegation ─────────
    certBody.addEventListener('click', function(e) {
        const btn = e.target.closest('button[data-action]');
        if (!btn) return;
        
        const action = btn.dataset.action;
        const id = btn.dataset.id;
        
        if (action === 'view') {
            showDetail(id);
        } else if (action === 'download') {
            downloadCert(id, btn.dataset.jenis, btn.dataset.nama);
        } else if (action === 'delete') {
            deleteCert(id, btn.dataset.nama);
        }
    });

    // ── Download (Print to PDF) ─────────────────
    let pendingDownload = null;

    function downloadCert(id, jenis, namaLengkap) {
        const modal = document.getElementById('download-modal');
        const desc = document.getElementById('modal-desc');
        const confirmBtn = document.getElementById('modal-confirm-btn');

        desc.innerHTML = `Sertifikat atas nama <strong>${namaLengkap}</strong> akan dibuka di tab baru dalam mode cetak. Pilih <strong>"Save as PDF"</strong> pada dialog cetak untuk menyimpan sebagai file PDF.`;

        const outputUrl = jenis === 'Dewasa'
            ? `/output/dewasa/${id}`
            : `/output/bayi/${id}`;

        pendingDownload = outputUrl;
        modal.classList.add('show');

        // Replace button handler
        confirmBtn.onclick = function() {
            const printWin = window.open(outputUrl, '_blank');
            if (printWin) {
                printWin.addEventListener('load', function() {
                    setTimeout(() => printWin.print(), 600);
                });
            }
            closeDownloadModal();
            showToast(`Membuka sertifikat ${namaLengkap}...`, 'success');
        };
    }

    function closeDownloadModal() {
        document.getElementById('download-modal').classList.remove('show');
        pendingDownload = null;
    }

    // Close modal on overlay click
    document.getElementById('download-modal').addEventListener('click', function(e) {
        if (e.target === this) closeDownloadModal();
    });

    // ── Detail Modal & Interactive CRUD ─────────
    let currentDetailId = null;

    function showDetail(id) {
        showToast('Memuat rincian sertifikat...', 'success');
        fetch(`/api/certificates/${id}`)
            .then(r => r.json())
            .then(res => {
                if (!res.success) {
                    showToast(res.message || 'Gagal memuat detail', 'error');
                    return;
                }
                const cert = res.certificate;
                currentDetailId = cert.id;

                // Fill values
                document.getElementById('detail-cert-no').textContent = cert.nomor_sertifikat;
                document.getElementById('detail-cert-type').textContent = cert.jenis;
                document.getElementById('detail-cert-death-time').textContent = cert.waktu_meninggal;
                document.getElementById('detail-cert-doctor').textContent = cert.doctor ? (cert.doctor.nama_dokter + ' (' + (cert.doctor.spesialisasi || 'Umum') + ')') : '-';

                document.getElementById('detail-patient-name').textContent = cert.patient ? cert.patient.nama_lengkap : '-';
                document.getElementById('detail-patient-nrm').textContent = cert.patient ? cert.patient.nrm : '-';
                document.getElementById('detail-patient-nik').textContent = cert.patient ? cert.patient.nik : '-';
                document.getElementById('detail-patient-gender').textContent = cert.patient ? cert.patient.jenis_kelamin : '-';
                document.getElementById('detail-patient-address').textContent = cert.patient ? cert.patient.alamat : '-';

                // Causes
                const data = cert.data || {};
                
                if (cert.jenis === 'Dewasa') {
                    document.getElementById('detail-cause-a').textContent = data.penyebab_langsung || '-';
                    document.getElementById('detail-interval-a').textContent = data.interval_langsung || '-';
                    document.getElementById('detail-icd-a').textContent = data.icd_langsung || '-';

                    document.getElementById('detail-cause-b').textContent = data.penyebab_antara1 || '-';
                    document.getElementById('detail-interval-b').textContent = data.interval_antara1 || '-';
                    document.getElementById('detail-icd-b').textContent = data.icd_antara1 || '-';

                    document.getElementById('detail-cause-c').textContent = data.penyebab_antara2 || '-';
                    document.getElementById('detail-interval-c').textContent = data.interval_antara2 || '-';
                    document.getElementById('detail-icd-c').textContent = data.icd_antara2 || '-';

                    document.getElementById('detail-cause-d').textContent = data.penyebab_dasar || '-';
                    document.getElementById('detail-interval-d').textContent = data.interval_dasar || '-';
                    document.getElementById('detail-icd-d').textContent = data.icd_dasar || '-';

                    document.getElementById('detail-cause-other').textContent = data.penyebab_kontributor || '-';
                    document.getElementById('detail-fucod').textContent = data.fucod_deskripsi || '-';
                    document.getElementById('detail-icd-fucod').textContent = data.fucod_code || '-';
                } else {
                    // Bayi
                    document.getElementById('detail-cause-a').textContent = data.penyebab_utama_bayi || '-';
                    document.getElementById('detail-interval-a').textContent = '-';
                    document.getElementById('detail-icd-a').textContent = data.icd_utama_bayi || '-';

                    document.getElementById('detail-cause-b').textContent = data.penyebab_lain_bayi || '-';
                    document.getElementById('detail-interval-b').textContent = '-';
                    document.getElementById('detail-icd-b').textContent = data.icd_lain_bayi || '-';

                    document.getElementById('detail-cause-c').textContent = data.penyebab_utama_ibu || '-';
                    document.getElementById('detail-interval-c').textContent = '-';
                    document.getElementById('detail-icd-c').textContent = data.icd_utama_ibu || '-';

                    document.getElementById('detail-cause-d').textContent = data.penyebab_lain_ibu || '-';
                    document.getElementById('detail-interval-d').textContent = '-';
                    document.getElementById('detail-icd-d').textContent = data.icd_lain_ibu || '-';

                    document.getElementById('detail-cause-other').textContent = data.hal_lain_bayi || '-';
                    document.getElementById('detail-fucod').textContent = data.fucod_deskripsi || '-';
                    document.getElementById('detail-icd-fucod').textContent = data.fucod_code || '-';
                }

                // Status dropdown
                document.getElementById('detail-status-changer').value = cert.status;

                // Setup edit link
                document.getElementById('detail-edit-link').href = `/form/edit/${cert.id}`;

                // Setup print button
                const printBtn = document.getElementById('detail-print-btn');
                if (cert.status === 'Draft') {
                    printBtn.disabled = true;
                    printBtn.style.opacity = '0.5';
                    printBtn.style.cursor = 'not-allowed';
                    printBtn.onclick = null;
                } else {
                    printBtn.disabled = false;
                    printBtn.style.opacity = '1';
                    printBtn.style.cursor = 'pointer';
                    printBtn.onclick = function() {
                        downloadCert(cert.id, cert.jenis, cert.patient.nama_lengkap);
                    };
                }

                // Show modal
                document.getElementById('detail-modal').classList.add('show');
            })
            .catch(() => showToast('Gagal memuat detail sertifikat', 'error'));
    }

    function closeDetailModal() {
        document.getElementById('detail-modal').classList.remove('show');
        currentDetailId = null;
    }

    // Status Changer in detail modal
    document.getElementById('detail-status-changer').addEventListener('change', function() {
        if (!currentDetailId) return;
        const newStatus = this.value;
        fetch(`/api/certificates/${currentDetailId}/status`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ status: newStatus })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                // Reload after short delay to update table view
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Gagal mengubah status', 'error');
            }
        })
        .catch(() => showToast('Terjadi kesalahan saat mengubah status', 'error'));
    });

    // Close detail modal on overlay click
    document.getElementById('detail-modal').addEventListener('click', function(e) {
        if (e.target === this) closeDetailModal();
    });

    // Close modals on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDownloadModal();
            closeDetailModal();
        }
    });

    // ── Delete Certificate (General CRUD) ───────
    function deleteCert(id, namaPasien) {
        if (!confirm(`Hapus sertifikat atas nama "${namaPasien}" secara permanen?\nTindakan ini tidak dapat dibatalkan!`)) return;
        fetch(`/api/certificates/${id}`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(data => {
            showToast(data.success ? data.message : (data.message || 'Gagal menghapus'), data.success ? 'success' : 'error');
            if (data.success) {
                closeDetailModal();
                setTimeout(() => location.reload(), 1200);
            }
        })
        .catch(() => showToast('Terjadi kesalahan saat menghapus', 'error'));
    }

    // ── Toast ───────────────────────────────────
    function showToast(msg, type='success') {
        const wrap = document.getElementById('toast-wrap');
        const t = document.createElement('div');
        t.className = 'toast';
        t.style.background = type === 'error' ? '#b91c1c' : '#15803d';
        t.innerHTML = `<i class="ph-bold ph-${type==='error'?'x':'check'}-circle"></i> ${msg}`;
        wrap.appendChild(t);
        requestAnimationFrame(() => t.classList.add('show'));
        setTimeout(() => { t.classList.remove('show'); setTimeout(() => t.remove(), 300); }, 3000);
    }
</script>
</body>
</html>
