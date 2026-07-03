@php
    $activePage = $activePage ?? 'dashboard';
    $pageTitles = [
        'dashboard' => 'Main Dashboard',
        'forms' => 'Pembuatan SKMK',
        'drafts' => 'Draft Sertifikat',
        'documents' => 'Arsip Sertifikat',
        'reports' => 'Laporan',
        'settings' => 'Pengaturan'
    ];
    $pageTitle = $pageTitles[$activePage] ?? 'Sistem Informasi';
@endphp

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

    /* ── SIDEBAR ─────────────────────────────── */
    .sidebar {
        width: var(--sidebar-w);
        min-height: 100vh;
        background: #00A1C5;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        position: fixed;
        top: 0; left: 0;
        bottom: 0;
        z-index: 50;
        border-right: 1px solid rgba(255,255,255,.06);
        transition: transform 0.3s ease;
    }
    .sidebar-brand {
        padding: 24px 16px;
        border-bottom: 1px solid rgba(255,255,255,.15);
        display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;
        text-align: center;
        position: relative;
    }
    .sidebar-brand img {
        height: 56px; width: auto; drop-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .sidebar-close {
        background: none; border: none; color: #fff; font-size: 20px; cursor: pointer; display: none;
        position: absolute; top: 12px; right: 12px;
    }

    .sidebar-nav { padding: 10px 0; flex: 1; overflow-y: auto; }
    .nav-section-label {
        font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .12em; color: rgba(255,255,255,.6);
        padding: 10px 16px 4px;
    }
    .nav-item {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 16px;
        font-size: 13px; font-weight: 600;
        color: #fff;
        text-decoration: none;
        border-radius: 8px;
        margin: 4px 12px;
        transition: all .15s;
        cursor: pointer;
    }
    .nav-item:hover { background: rgba(0, 90, 135, 0.6); }
    .nav-item.active {
        background: #005a87;
        font-weight: 700;
    }
    .nav-item i { font-size: 16px; flex-shrink: 0; }

    .sidebar-footer {
        padding: 16px;
        border-top: 1px solid rgba(255,255,255,.15);
    }
    .user-row {
        display: flex; align-items: center; justify-content: space-between;
    }
    .user-row-left {
        display: flex; align-items: center; gap: 10px;
    }
    .user-avatar {
        width: 32px; height: 32px; border-radius: 50%;
        background: #005a87; object-fit: cover;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .user-info .name { font-size: 12px; font-weight: 700; color: #fff; }
    .user-info .role { font-size: 10px; color: rgba(255,255,255,.8); }

    .logout-btn {
        color: #fff; font-size: 18px; transition: color .15s; cursor: pointer; text-decoration: none;
        padding: 6px; border-radius: 6px;
    }
    .logout-btn:hover { background: rgba(239, 68, 68, 0.2); color: #fca5a5; }

    /* ── TOPBAR ──────────────────────────────── */
    .topbar-wrapper {
        position: fixed; top: 0; right: 0; left: var(--sidebar-w); z-index: 40;
        background: var(--white);
        border-bottom: 1px solid var(--rule);
        padding: 0 24px;
        height: 48px;
        display: flex; align-items: center; justify-content: space-between;
        transition: left 0.3s ease;
    }
    .topbar-left { display: flex; align-items: center; gap: 12px; }
    .sidebar-toggle {
        display: none; background: none; border: none; font-size: 20px; color: var(--ink); cursor: pointer; padding: 4px;
    }
    .topbar-sys { font-size: 9px; font-weight: 700; color: var(--ink-lt); text-transform: uppercase; letter-spacing: .1em; line-height: 1; margin-bottom: 2px; }
    .topbar-title { font-size: 14px; font-weight: 800; color: var(--ink); line-height: 1; }
    .topbar-right { display: flex; align-items: center; gap: 10px; }
    .topbar-time {
        font-size: 11px; font-weight: 600; color: var(--ink-mid);
        font-family: 'IBM Plex Mono', monospace;
        background: var(--bg-cell); border: 1px solid var(--rule);
        padding: 3px 8px; border-radius: 4px;
    }
    .topbar-bell { position: relative; color: var(--ink-lt); font-size: 18px; cursor: pointer; padding: 4px; }
    .topbar-bell:hover { color: var(--ink); }
    .topbar-bell .bell-badge {
        position: absolute; top: 2px; right: 2px;
        width: 8px; height: 8px; background: #ef4444; border-radius: 50%;
        border: 2px solid var(--white);
    }

    /* ── DROPDOWN NOTIFIKASI ─────────────────── */
    .notif-dropdown {
        position: absolute; top: 36px; right: 0;
        width: 280px; background: var(--white);
        border: 1px solid var(--rule); border-radius: 6px;
        box-shadow: 0 10px 25px rgba(0,0,0,.1);
        display: none; flex-direction: column;
        z-index: 100; text-align: left;
    }
    .notif-dropdown.show { display: flex; }
    .notif-head {
        padding: 10px 16px; border-bottom: 1px solid var(--rule);
        font-size: 12px; font-weight: 800; color: var(--ink);
        display: flex; justify-content: space-between; align-items: center;
    }
    .notif-head span { font-size: 10px; color: var(--brand); cursor: pointer; font-weight: 600; }
    .notif-body { max-height: 300px; overflow-y: auto; }
    .notif-item {
        padding: 12px 16px; border-bottom: 1px solid var(--bg-cell);
        display: flex; gap: 10px; transition: background .15s; cursor: pointer;
    }
    .notif-item:hover { background: var(--bg-cell); }
    .notif-icon {
        width: 32px; height: 32px; border-radius: 50%;
        background: #e0f7fa; color: var(--brand);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        font-size: 16px;
    }
    .notif-content .title { font-size: 11px; font-weight: 700; color: var(--ink); }
    .notif-content .desc { font-size: 10px; color: var(--ink-mid); margin-top: 2px; line-height: 1.4; }
    .notif-content .time { font-size: 9px; color: var(--ink-lt); margin-top: 4px; }

    /* ── RESPONSIVE & LAYOUT FIXES ───────────── */
    /* Membuat .main-wrap otomatis menyesuaikan topbar & sidebar */
    .main-wrap {
        margin-left: var(--sidebar-w);
        padding-top: 48px; /* Offset for fixed topbar */
        flex: 1; display: flex; flex-direction: column; min-height: 100vh;
        transition: margin-left 0.3s ease;
    }

    #sidebar-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,.5);
        z-index: 40; display: none; opacity: 0; transition: opacity 0.3s;
    }
    
    @media (max-width: 768px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.open { transform: translateX(0); }
        .sidebar-close { display: block; }
        .sidebar-toggle { display: block; }
        #sidebar-overlay.show { display: block; opacity: 1; }
        
        .topbar-wrapper { left: 0; padding: 0 16px; }
        .main-wrap { margin-left: 0; }
        .topbar-sys { display: none; }
    }
</style>

<!-- SIDEBAR -->
<aside class="sidebar" id="app-sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('asset/Logo-Wava-Husada-putih-menu.png') }}" alt="Logo RSWH">
        <button class="sidebar-close" onclick="toggleSidebar()"><i class="ph-bold ph-x"></i></button>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Utama</div>
        <a href="/" class="nav-item {{ $activePage === 'dashboard' ? 'active' : '' }}"><i class="ph-bold ph-squares-four"></i> Dashboard</a>
        <a href="/form" class="nav-item {{ $activePage === 'forms' ? 'active' : '' }}"><i class="ph-bold ph-file-plus"></i> Buat Sertifikat</a>
        <a href="/drafts" class="nav-item {{ $activePage === 'drafts' ? 'active' : '' }}"><i class="ph-bold ph-note-pencil"></i> Draft</a>

        <div class="nav-section-label">Arsip</div>
        <a href="/certificates" class="nav-item {{ $activePage === 'documents' ? 'active' : '' }}"><i class="ph-bold ph-files"></i> Semua Sertifikat</a>
        <a href="/reports" class="nav-item {{ $activePage === 'reports' ? 'active' : '' }}"><i class="ph-bold ph-chart-bar"></i> Laporan</a>

        <div class="nav-section-label">Sistem</div>
        <a href="/settings" class="nav-item {{ $activePage === 'settings' ? 'active' : '' }}"><i class="ph-bold ph-gear"></i> Pengaturan</a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-row">
            <div class="user-row-left">
                <div class="user-avatar">RM</div>
                <div class="user-info">
                    <div class="name">Rekam Medis</div>
                    <div class="role">User</div>
                </div>
            </div>
            <a href="#" class="logout-btn" title="Keluar"><i class="ph-bold ph-sign-out"></i></a>
        </div>
    </div>
</aside>

<!-- OVERLAY FOR MOBILE -->
<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- TOPBAR -->
<header class="topbar-wrapper">
    <div class="topbar-left">
        <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="ph-bold ph-list"></i></button>
        <div>
            <div class="topbar-sys">SIMPK – Digital</div>
            <div class="topbar-title">{{ $pageTitle }}</div>
        </div>
    </div>
    <div class="topbar-right">
        @if(isset($showStatusChip) && $showStatusChip)
            <!-- Status chip opsional (digunakan di halaman pembuatan sertifikat) -->
            <div class="status-chip status-editing" style="display: flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 100px; font-size: 10px; font-weight: 700; text-transform: uppercase; border: 1px solid #fde68a; background: #fffbeb; color: #92400e; margin-right: 8px;">
                <div class="status-dot dot-amber" style="width: 6px; height: 6px; border-radius: 50%; background: #f59e0b; animation: pulse 1.5s infinite;"></div>
                <span id="status-text">Editing</span>
            </div>
        @endif

        <div class="topbar-time" id="topbar-clock">--:--</div>
        
        <div style="position: relative;">
            <div class="topbar-bell" onclick="toggleNotif(event)">
                <i class="ph-bold ph-bell"></i>
                <div class="bell-badge"></div>
            </div>
            
            <!-- Notifikasi Dropdown -->
            <div class="notif-dropdown" id="notif-dropdown" onclick="event.stopPropagation()">
                <div class="notif-head">
                    Notifikasi <span onclick="markNotifAsRead(event)">Tandai dibaca</span>
                </div>
                <div class="notif-body" id="notif-body-list">
                    <div class="p-4 text-center text-xs text-gray-500">Memuat notifikasi...</div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('app-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        if(sidebar && overlay) {
            sidebar.classList.toggle('open');
            if (sidebar.classList.contains('open')) {
                overlay.classList.add('show');
            } else {
                overlay.classList.remove('show');
            }
        }
    }

    function toggleNotif(e) {
        if(e) e.stopPropagation();
        const dropdown = document.getElementById('notif-dropdown');
        if(dropdown) {
            dropdown.classList.toggle('show');
            if(dropdown.classList.contains('show')) {
                fetchNotifications();
            }
        }
    }

    // Tutup dropdown notif jika klik di luar
    window.addEventListener('click', function(e) {
        const dropdown = document.getElementById('notif-dropdown');
        if (dropdown && dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
        }
    });

    // ── NOTIFIKASI LOGIC ──
    function fetchNotifications() {
        const list = document.getElementById('notif-body-list');
        fetch('/api/notifications')
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    list.innerHTML = '';
                    let hasUnread = false;
                    const lastRead = localStorage.getItem('last_read_notif_time');

                    data.data.forEach(notif => {
                        const notifDate = new Date(notif.created_at);
                        const isUnread = !lastRead || new Date(lastRead) < notifDate;
                        if (isUnread) hasUnread = true;

                        // Gunakan onclick yang mengarah ke halaman show
                        list.innerHTML += `
                            <div class="notif-item" onclick="window.location.href='/notifications/${notif.id}'" style="${isUnread ? 'background-color:#f0fdfa;' : ''}">
                                <div class="notif-icon"><i class="ph-bold ph-info"></i></div>
                                <div class="notif-content">
                                    <div class="title">${notif.judul}</div>
                                    <div class="desc line-clamp-2">${notif.keterangan}</div>
                                    <div class="time">${notifDate.toLocaleString('id-ID')}</div>
                                </div>
                            </div>
                        `;
                    });
                    
                    updateBadge(hasUnread);
                } else {
                    list.innerHTML = '<div class="p-4 text-center text-xs text-gray-500">Tidak ada pemberitahuan.</div>';
                    updateBadge(false);
                }
            })
            .catch(err => {
                list.innerHTML = '<div class="p-4 text-center text-xs text-red-500">Gagal memuat.</div>';
            });
    }

    function checkUnreadNotifications() {
        fetch('/api/notifications')
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    const lastRead = localStorage.getItem('last_read_notif_time');
                    const latestNotif = new Date(data.data[0].created_at);
                    const hasUnread = !lastRead || new Date(lastRead) < latestNotif;
                    updateBadge(hasUnread);
                } else {
                    updateBadge(false);
                }
            });
    }

    function updateBadge(hasUnread) {
        const badge = document.querySelector('.bell-badge');
        if (badge) {
            badge.style.display = hasUnread ? 'block' : 'none';
        }
    }

    function markNotifAsRead(e) {
        if(e) e.stopPropagation();
        localStorage.setItem('last_read_notif_time', new Date().toISOString());
        updateBadge(false);
        fetchNotifications();
    }

    // Initialize check
    setTimeout(checkUnreadNotifications, 500);

    // ── CLOCK LOGIC ──
    function tickTopbarClock() {
        const clock = document.getElementById('topbar-clock');
        if (clock) {
            const now = new Date();
            clock.textContent = now.toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'});
        }
    }
    tickTopbarClock(); 
    setInterval(tickTopbarClock, 1000);
</script>

