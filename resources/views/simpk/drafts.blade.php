<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Draft Sertifikat - SIMPK RS Wava Husada</title>
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
        background: var(--bg-app); color: var(--ink);
        display: flex; min-height: 100vh;
        font-size: 13px; -webkit-font-smoothing: antialiased;
    }

    /* ── MAIN ────────────────────────────────── */
    .main-wrap { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

    /* ── CONTENT ─────────────────────────────── */
    .content { padding: 20px 24px; flex: 1; }
    .content-inner { max-width: 100%; }

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

    .type-badge {
        display: inline-block; padding: 2px 8px;
        background: var(--brand-lt); color: var(--brand);
        border: 1px solid #b2e8e9; border-radius: 100px;
        font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: .04em;
    }

    .draft-indicator { display: flex; align-items: center; gap: 5px; }
    .draft-dot { width: 6px; height: 6px; border-radius: 50%; background: #f59e0b; }
    .draft-lbl { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #92400e; }

    .actions { display: flex; align-items: center; justify-content: flex-end; gap: 4px; }
    .action-btn {
        display: flex; align-items: center; justify-content: center;
        width: 30px; height: 30px; border-radius: 4px;
        border: none; cursor: pointer; background: transparent;
        font-size: 16px; transition: all .15s;
    }
    .action-btn.edit  { color: var(--brand-md); }
    .action-btn.edit:hover  { background: var(--brand-lt); color: var(--brand); }
    .action-btn.del   { color: var(--ink-lt); }
    .action-btn.del:hover   { background: #fef2f2; color: #dc2626; }

    /* ── EMPTY STATE ─────────────────────────── */
    .empty-state {
        padding: 56px 24px; text-align: center;
        color: var(--ink-lt); opacity: .55;
    }
    .empty-state i { font-size: 48px; margin-bottom: 10px; display: block; }
    .empty-state p { font-size: 12px; font-weight: 500; }

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
</style>
</head>
<body>

@include('partials.watermark')
@include('partials.sidebar', ['activePage' => 'drafts'])

<!-- MAIN -->
<div class="main-wrap">

    <div class="content">
        <div class="content-inner">

            <div class="page-head">
                <div>
                    <h1>Daftar Draft Sertifikat Kematian</h1>
                    <p>Formulir yang belum selesai diisi atau masih berstatus draft.</p>
                </div>
                <a href="/form" class="btn-new">
                    <i class="ph-bold ph-plus"></i> Buat Baru
                </a>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No. Sertifikat</th>
                            <th>Pasien</th>
                            <th>Jenis</th>
                            <th>Terakhir Diubah</th>
                            <th>Status</th>
                            <th style="text-align:right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drafts as $draft)
                        <tr>
                            <td class="td-cert-no">{{ $draft->nomor_sertifikat }}</td>
                            <td>
                                <div class="td-name">{{ $draft->patient->nama_lengkap }}</div>
                                <div class="td-rm">RM: {{ $draft->patient->nrm }}</div>
                            </td>
                            <td><span class="type-badge">{{ $draft->jenis }}</span></td>
                            <td class="td-muted">{{ $draft->updated_at->format('d M Y, H:i') }}</td>
                            <td>
                                <div class="draft-indicator">
                                    <div class="draft-dot"></div>
                                    <span class="draft-lbl">Draft</span>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('form.edit', $draft->id) }}" class="action-btn edit" title="Lanjut Mengisi">
                                        <i class="ph-bold ph-pencil-line"></i>
                                    </a>
                                    <button data-id="{{ $draft->id }}" onclick="deleteDraft(this.getAttribute('data-id'))" class="action-btn del" title="Hapus Draft">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="ph ph-file-dashed"></i>
                                    <p>Belum ada draft sertifikat.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div><!-- /content-inner -->
    </div><!-- /content -->

    <footer style="padding:14px 24px;text-align:center;font-size:10px;color:var(--ink-lt);border-top:1px solid var(--rule);background:var(--white);margin-top:auto">
        &copy; 2026 RS Wava Husada — Sistem Informasi Medis Penyebab Kematian Digital
    </footer>
</div>

<!-- TOAST -->
<div class="toast-wrap" id="toast-wrap"></div>

<script>
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

    function deleteDraft(id) {
        if (!confirm('Hapus draft ini? Data yang sudah diisi tidak dapat dikembalikan.')) return;
        fetch(`/api/drafts/${id}`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(data => {
            showToast(data.success ? data.message : (data.message || 'Gagal menghapus draft'), data.success ? 'success' : 'error');
            if (data.success) setTimeout(() => location.reload(), 1200);
        })
        .catch(() => showToast('Terjadi kesalahan saat menghapus draft', 'error'));
    }
</script>
</body>
</html>