<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Buat Sertifikat - SIMPK RS Wava Husada</title>
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
        --danger:   #b91c1c;
        --sidebar-w: 220px;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'IBM Plex Sans', sans-serif;
        background: var(--bg-app);
        color: var(--ink);
        display: flex; min-height: 100vh;
        font-size: 13px;
        -webkit-font-smoothing: antialiased;
    }

    /* ── MAIN ────────────────────────────────── */
    .main-wrap { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

    /* ── CONTENT ─────────────────────────────── */
    .content { padding: 20px 24px; max-width: 820px; }
    .page-head { margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--rule); }
    .page-head h1 { font-size: 15px; font-weight: 800; color: var(--ink); margin-bottom: 2px; }
    .page-head p  { font-size: 11px; color: var(--ink-lt); }

    /* ── FORM CARD ───────────────────────────── */
    .form-card {
        background: var(--white); border: 1px solid var(--rule);
        border-radius: 6px; padding: 20px; margin-bottom: 14px;
    }
    .card-head {
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 16px; padding-bottom: 10px;
        border-bottom: 1px solid var(--bg-cell);
    }
    .card-head i { font-size: 17px; color: var(--ink-mid); }
    .card-head h3 { font-size: 12px; font-weight: 800; color: var(--ink); text-transform: uppercase; letter-spacing: .04em; }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .form-group { display: flex; flex-direction: column; gap: 4px; }
    .form-group.full { grid-column: 1 / -1; }
    label {
        font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .08em; color: var(--ink-mid);
    }
    label .req { color: var(--danger); }
    input[type="text"],
    select {
        width: 100%; border: 1px solid var(--rule);
        border-radius: 4px; padding: 7px 10px;
        font-size: 12px; font-family: inherit;
        color: var(--ink); background: var(--white);
        transition: border-color .15s;
        appearance: none;
    }
    input[type="text"]:focus,
    select:focus { outline: none; border-color: var(--brand-md); box-shadow: 0 0 0 2px rgba(29,161,166,.1); }
    input.err { border-color: var(--danger); }
    .select-wrap { position: relative; }
    .select-wrap select { padding-right: 28px; }
    .select-arrow { position: absolute; right: 9px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--ink-lt); font-size: 14px; }

    /* ── TYPE SELECTOR ───────────────────────── */
    .type-section { margin-top: 16px; padding-top: 14px; border-top: 1px solid var(--bg-cell); }
    .type-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--ink-mid); margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
    .type-label .req { color: var(--danger); }
    .type-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

    .type-btn {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 14px;
        border: 1.5px solid var(--rule);
        border-radius: 6px; background: var(--white);
        cursor: pointer; text-align: left; width: 100%;
        transition: all .15s;
    }
    .type-btn:hover { border-color: var(--brand-md); background: var(--brand-lt); }
    .type-btn.active { border-color: var(--brand); background: var(--brand-lt); }
    .type-btn .type-icon { font-size: 22px; color: var(--ink-lt); flex-shrink: 0; }
    .type-btn.active .type-icon { color: var(--brand); }
    .type-btn-text h4 { font-size: 12px; font-weight: 700; color: var(--ink); }
    .type-btn-text p  { font-size: 10px; color: var(--ink-lt); margin-top: 1px; }
    .type-btn.active .type-btn-text h4 { color: var(--brand); }
    .type-check { margin-left: auto; font-size: 18px; color: var(--brand); opacity: 0; flex-shrink: 0; }
    .type-btn.active .type-check { opacity: 1; }

    .info-box {
        display: flex; align-items: flex-start; gap: 7px;
        background: var(--bg-cell); border: 1px solid var(--rule);
        border-radius: 4px; padding: 8px 10px; margin-top: 10px;
    }
    .info-box i { font-size: 14px; color: var(--ink-lt); margin-top: 1px; flex-shrink: 0; }
    .info-box p { font-size: 10.5px; color: var(--ink-mid); line-height: 1.5; }
    .info-box strong { font-weight: 700; color: var(--ink); }

    /* ── ACTION BAR ──────────────────────────── */
    .action-bar {
        display: flex; justify-content: flex-end; gap: 8px;
        padding-top: 14px; border-top: 1px solid var(--rule);
    }
    .btn {
        display: flex; align-items: center; gap: 6px;
        padding: 7px 16px; border-radius: 4px;
        font-size: 12px; font-weight: 700;
        cursor: pointer; border: none; transition: all .15s;
        font-family: inherit; text-decoration: none;
    }
    .btn-ghost { background: var(--white); color: var(--ink-mid); border: 1px solid var(--rule); }
    .btn-ghost:hover { background: var(--bg-cell); }
    .btn-primary { background: var(--brand); color: #fff; }
    .btn-primary:hover { background: #0a5558; }

    /* ── MODAL ───────────────────────────────── */
    .modal-overlay {
        position: fixed; inset: 0; z-index: 100;
        background: rgba(12,25,36,.5); backdrop-filter: blur(4px);
        display: none; align-items: center; justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: var(--white); border-radius: 10px;
        padding: 28px; width: 100%; max-width: 380px;
        box-shadow: 0 8px 40px rgba(0,0,0,.2); text-align: center;
    }
    .modal-icon-ring {
        width: 52px; height: 52px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px; font-size: 26px;
    }
    .ring-amber { background: #fffbeb; color: #b45309; }
    .ring-blue  { background: #eff6ff; color: #2563eb; }
    .modal-title { font-size: 14px; font-weight: 800; color: var(--ink); margin-bottom: 6px; }
    .modal-desc  { font-size: 11.5px; color: var(--ink-mid); line-height: 1.55; margin-bottom: 20px; }
    .modal-actions { display: flex; flex-direction: column; gap: 8px; }
    .modal-btn {
        width: 100%; padding: 9px 16px; border-radius: 6px;
        font-size: 12px; font-weight: 700; font-family: inherit;
        cursor: pointer; border: none; display: flex; align-items: center;
        justify-content: center; gap: 7px; transition: all .15s;
    }
    .mb-blue    { background: #2563eb; color: #fff; } .mb-blue:hover    { background: #1d4ed8; }
    .mb-green   { background: #16a34a; color: #fff; } .mb-green:hover   { background: #15803d; }
    .mb-red     { background: var(--danger); color: #fff; } .mb-red:hover { background: #991b1b; }
    .mb-ghost   { background: var(--bg-cell); color: var(--ink-mid); } .mb-ghost:hover { background: var(--rule); }

    /* ── TOAST ───────────────────────────────── */
    .toast-wrap { position: fixed; bottom: 20px; right: 20px; z-index: 200; display: flex; flex-direction: column; gap: 8px; }
    .toast {
        background: #15803d; color: #fff;
        padding: 9px 16px; border-radius: 6px;
        font-size: 12px; font-weight: 600;
        display: flex; align-items: center; gap: 8px;
        box-shadow: 0 4px 16px rgba(0,0,0,.15);
        transform: translateY(12px); opacity: 0;
        transition: all .25s;
    }
    .toast.show { transform: translateY(0); opacity: 1; }
</style>
</head>
<body>

@include('partials.watermark')
@include('partials.sidebar', ['activePage' => 'forms', 'showStatusChip' => true])

<!-- MAIN -->
<div class="main-wrap">

    <div class="content">
        <div class="page-head">
            <h1>Identitas &amp; Jenis Sertifikat</h1>
            <p>Lengkapi data pembuat dan pilih kategori sertifikat kematian.</p>
        </div>

        <form id="mainForm">
            <!-- Card: Identitas Pembuat -->
            <div class="form-card">
                <div class="card-head">
                    <i class="ph ph-user-focus"></i>
                    <h3>Identitas Pembuat Surat</h3>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Pembuat Surat <span class="req">*</span></label>
                        <input type="text" name="nama_pembuat" required placeholder="Nama lengkap beserta gelar">
                    </div>
                    <div class="form-group">
                        <label>Jabatan <span class="req">*</span></label>
                        <input type="text" name="jabatan" required placeholder="Contoh: Dokter Umum / Spesialis">
                    </div>
                    <div class="form-group">
                        <label>Unit / Ruangan <span class="req">*</span></label>
                        <input type="text" name="unit" required placeholder="Contoh: IGD / ICU / Bangsal">
                    </div>
                    <div class="form-group">
                        <label>Dokter Penanggung Jawab (DPJP) <span class="req">*</span></label>
                        <input type="text" name="dpjp" required placeholder="Nama DPJP">
                    </div>
                    <div class="form-group full">
                        <label>Nomor Surat <span class="req">*</span></label>
                        <input type="text" name="no_surat" required placeholder="Contoh: 001/SKMK/RSWH/2026">
                    </div>
                </div>

                <!-- Pilih Jenis -->
                <div class="type-section">
                    <div class="type-label">
                        <i class="ph ph-identification-card" style="font-size:15px"></i>
                        Pilih Kategori Sertifikat <span class="req">*</span>
                    </div>
                    <div class="type-grid">
                        <button type="button" class="type-btn active" id="btn-dewasa" onclick="selectType('dewasa')">
                            <i class="ph ph-user type-icon"></i>
                            <div class="type-btn-text">
                                <h4>Kematian Dewasa</h4>
                                <p>Jenazah pasien berusia di atas 1 tahun</p>
                            </div>
                            <i class="ph-fill ph-check-circle type-check"></i>
                        </button>
                        <button type="button" class="type-btn" id="btn-bayi" onclick="selectType('bayi')">
                            <i class="ph ph-baby type-icon"></i>
                            <div class="type-btn-text">
                                <h4>Kematian Bayi / Perinatal</h4>
                                <p>Jenazah pasien berusia 0–1 tahun</p>
                            </div>
                            <i class="ph-fill ph-check-circle type-check"></i>
                        </button>
                    </div>
                    <div class="info-box">
                        <i class="ph ph-info"></i>
                        <p id="info-text"><strong>Sertifikat Dewasa</strong> terpilih. Pastikan rekam medis pasien sesuai batasan umur sebelum melanjutkan.</p>
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <button type="reset" class="btn btn-ghost" onclick="isFormDirty=false">
                    <i class="ph ph-eraser"></i> Kosongkan
                </button>
                <button type="button" class="btn btn-primary" onclick="validateAndSubmit()">
                    Lanjutkan Pengisian <i class="ph ph-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>

    <footer style="padding:14px 24px;text-align:center;font-size:10px;color:var(--ink-lt);border-top:1px solid var(--rule);background:var(--white);margin-top:auto">
        &copy; 2026 RS Wava Husada — Sistem Informasi Medis Penyebab Kematian Digital
    </footer>
</div>

<!-- MODAL -->
<div class="modal-overlay" id="modal">
    <div class="modal-box">
        <div class="modal-icon-ring" id="modal-icon-ring">
            <i id="modal-icon" class="ph-bold ph-warning-circle"></i>
        </div>
        <div class="modal-title" id="modal-title"></div>
        <div class="modal-desc" id="modal-desc"></div>
        <div class="modal-actions" id="modal-actions"></div>
    </div>
</div>

<!-- TOAST -->
<div class="toast-wrap" id="toast-wrap"></div>

<script>
    let selectedType = 'dewasa';
    let isFormDirty = false;

    document.querySelectorAll('input, select').forEach(el => {
        el.addEventListener('input', () => {
            isFormDirty = true;
            document.getElementById('status-text').textContent = 'Sedang Diedit';
        });
    });

    function selectType(type) {
        selectedType = type;
        const btnD = document.getElementById('btn-dewasa');
        const btnB = document.getElementById('btn-bayi');
        const info = document.getElementById('info-text');

        if (type === 'dewasa') {
            btnD.classList.add('active');
            btnB.classList.remove('active');
            info.innerHTML = '<strong>Sertifikat Dewasa</strong> terpilih. Pastikan rekam medis pasien sesuai batasan umur sebelum melanjutkan.';
        } else {
            btnB.classList.add('active');
            btnD.classList.remove('active');
            info.innerHTML = '<strong>Sertifikat Bayi / Perinatal</strong> terpilih. Khusus untuk pelaporan kematian perinatal (0–7 hari) dan neonatal/infant (8 hari – 1 tahun).';
        }
    }

    function validateAndSubmit() {
        const form = document.getElementById('mainForm');
        let valid = true;
        form.querySelectorAll('[required]').forEach(f => {
            if (!f.value) { f.classList.add('err'); valid = false; }
            else f.classList.remove('err');
        });
        if (!valid) { showToast('Mohon lengkapi semua field wajib (*)', 'error'); return; }

        openModal({
            type: 'blue',
            icon: 'ph-bold ph-question',
            title: 'Konfirmasi Data',
            desc: 'Apakah data yang dimasukkan sudah benar? Data akan disimpan secara otomatis.',
            actions: `
                <button class="modal-btn mb-blue" onclick="proceed()"><i class="ph-bold ph-check"></i> Lanjutkan</button>
                <button class="modal-btn mb-ghost" onclick="closeModal()">Batal</button>
            `
        });
    }

    function proceed() {
        isFormDirty = false;
        closeModal();
        window.location.href = selectedType === 'dewasa' ? '/form/kematian-dewasa' : '/form/kematian-bayi';
    }

    function openModal({ type, icon, title, desc, actions }) {
        const ring = document.getElementById('modal-icon-ring');
        const ic   = document.getElementById('modal-icon');
        ring.className = 'modal-icon-ring ' + (type === 'blue' ? 'ring-blue' : 'ring-amber');
        ic.className = icon;
        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-desc').textContent  = desc;
        document.getElementById('modal-actions').innerHTML = actions;
        document.getElementById('modal').classList.add('open');
    }
    function closeModal() { document.getElementById('modal').classList.remove('open'); }

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

    // Dirty check on leave
    window.addEventListener('beforeunload', e => {
        if (isFormDirty) { e.preventDefault(); e.returnValue = ''; }
    });

    // Clear error on input
    document.querySelectorAll('input, select').forEach(el => {
        el.addEventListener('input', () => el.classList.remove('err'));
    });
</script>
</body>
</html>