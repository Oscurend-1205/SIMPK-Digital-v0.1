<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Dashboard - SIMPK RS Wava Husada</title>
<link rel="icon" type="image/png" href="{{ asset('asset/RS-Wava-Husada.png') }}">
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
        background: var(--bg-app);
        color: var(--ink);
        display: flex;
        min-height: 100vh;
        font-size: 13px;
        -webkit-font-smoothing: antialiased;
    }

    /* ── MAIN AREA ───────────────────────────── */
    .main-wrap {
        margin-left: var(--sidebar-w);
        flex: 1;
        display: flex; flex-direction: column;
        min-height: 100vh;
    }

    /* ── CONTENT ─────────────────────────────── */
    .content { padding: 20px 24px; flex: 1; }

    /* ── PAGE HEAD ───────────────────────────── */
    .page-head { margin-bottom: 16px; }
    .page-head h1 { font-size: 15px; font-weight: 800; color: var(--ink); }
    .page-head p  { font-size: 11px; color: var(--ink-lt); margin-top: 1px; }

    /* ── METRIC CARDS ────────────────────────── */
    .metrics { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 18px; }
    .metric-card {
        background: var(--white);
        border: 1px solid var(--rule);
        border-radius: 6px;
        padding: 14px 16px;
        display: flex; align-items: center; gap: 12px;
    }
    .metric-card.alert { border-left: 3px solid #ef4444; }
    .metric-icon {
        width: 36px; height: 36px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: 18px;
    }
    .metric-icon.teal   { background: #e6f9f8; color: var(--brand); }
    .metric-icon.cyan   { background: #e0f7fa; color: #0097a7; }
    .metric-icon.orange { background: #fff3e0; color: #e65100; }
    .metric-icon.red    { background: #fef2f2; color: #dc2626; }
    .metric-val { font-size: 22px; font-weight: 800; color: var(--ink); line-height: 1; }
    .metric-val.orange { color: #e65100; }
    .metric-val.red    { color: #dc2626; }
    .metric-lbl { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--ink-lt); margin-top: 2px; }

    /* ── TABLE SECTION ───────────────────────── */
    .table-section {
        background: var(--white);
        border: 1px solid var(--rule);
        border-radius: 6px;
        overflow: hidden;
    }
    .table-head {
        padding: 12px 18px;
        border-bottom: 1px solid var(--rule);
        display: flex; justify-content: space-between; align-items: center;
    }
    .table-head h3 { font-size: 12px; font-weight: 800; color: var(--ink); }
    .table-head a  { font-size: 11px; font-weight: 600; color: var(--brand-md); text-decoration: none; }
    .table-head a:hover { text-decoration: underline; }
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
    tbody td {
        padding: 9px 18px;
        font-size: 12px; color: var(--ink);
        vertical-align: middle; white-space: nowrap;
    }
    .td-cert { font-weight: 700; font-family: 'IBM Plex Mono', monospace; font-size: 11px; }
    .td-name { font-weight: 600; }
    .td-muted { color: var(--ink-mid); }
    .td-center { text-align: center; }

    .badge {
        display: inline-block; padding: 2px 9px;
        border-radius: 100px; font-size: 10px; font-weight: 700;
        letter-spacing: .02em;
    }
    .badge-printed { background: #e6f9f8; color: var(--brand); border: 1px solid #b2e8e9; }
    .badge-saved   { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .badge-draft   { background: var(--bg-cell); color: var(--ink-lt); border: 1px solid var(--rule); }

    /* ── FAB ─────────────────────────────────── */
    .fab {
        position: fixed; bottom: 24px; right: 24px;
        width: 44px; height: 44px;
        background: var(--brand); color: #fff;
        border-radius: 50%; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 16px rgba(15,110,114,.4);
        transition: all .15s; text-decoration: none; font-size: 22px;
        z-index: 30;
    }
    .fab:hover { background: #0a5558; transform: scale(1.06); }
    .fab:active { transform: scale(.96); }

    /* ── CLICKABLE METRIC CARDS ──────────────── */
    a.metric-card { text-decoration: none; transition: transform 0.15s, box-shadow 0.15s; }
    a.metric-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); cursor: pointer; }
</style>
</head>
<body>

@include('partials.watermark')
@include('partials.sidebar', ['activePage' => 'dashboard'])

<!-- MAIN -->
<div class="main-wrap">

    <!-- CONTENT -->
    <div class="content">

        <!-- Metrics -->
        <div class="metrics">
            <div class="metric-card">
                <div class="metric-icon teal"><i class="ph-bold ph-file-text"></i></div>
                <div>
                    <div class="metric-val">{{ number_format($totalIssued) }}</div>
                    <div class="metric-lbl">Total Issued</div>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon cyan"><i class="ph-bold ph-calendar"></i></div>
                <div>
                    <div class="metric-val">{{ number_format($thisMonth) }}</div>
                    <div class="metric-lbl">This Month</div>
                </div>
            </div>
            <a href="/drafts" class="metric-card">
                <div class="metric-icon orange"><i class="ph-bold ph-clock"></i></div>
                <div>
                    <div class="metric-val orange">{{ number_format($pendingVerif) }}</div>
                    <div class="metric-lbl">Pending</div>
                </div>
            </a>
            <a href="/certificates?doa=1" class="metric-card alert">
                <div class="metric-icon red"><i class="ph-bold ph-warning"></i></div>
                <div>
                    <div class="metric-val red">{{ number_format($doaDeaths) }}</div>
                    <div class="metric-lbl">DOA Deaths</div>
                </div>
            </a>
        </div>

        <!-- Recent Certificates -->
        <div class="table-section">
            <div class="table-head">
                <h3>Sertifikat Terbaru</h3>
                <a href="/certificates">Lihat Semua →</a>
            </div>
            <div style="overflow-x:auto">
                <table>
                    <thead>
                        <tr>
                            <th>No. Sertifikat</th>
                            <th>Nama Jenazah</th>
                            <th>Waktu Meninggal</th>
                            <th>Dokter Pemeriksa</th>
                            <th class="td-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCertificates as $cert)
                        <tr>
                            <td class="td-cert">{{ $cert->nomor_sertifikat }}</td>
                            <td class="td-name">{{ $cert->patient?->nama_lengkap ?? '-' }}</td>
                            <td class="td-muted">{{ $cert->waktu_meninggal?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="td-muted">{{ $cert->doctor?->nama_dokter ?? '-' }}</td>
                            <td class="td-center">
                                @if($cert->status === 'Printed')
                                    <span class="badge badge-printed">Printed</span>
                                @elseif($cert->status === 'Saved')
                                    <span class="badge badge-saved">Saved</span>
                                @else
                                    <span class="badge badge-draft">Draft</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:40px;color:var(--ink-lt);font-size:12px">
                                Belum ada data sertifikat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div><!-- /content -->

    <footer style="padding:14px 24px;text-align:center;font-size:10px;color:var(--ink-lt);border-top:1px solid var(--rule);background:var(--white);margin-top:auto">
        &copy; 2026 RS Wava Husada — Sistem Informasi Medis Penyebab Kematian Digital
    </footer>
</div>

<!-- FAB -->
<a href="/form" class="fab"><i class="ph-bold ph-plus"></i></a>

</body>
</html>