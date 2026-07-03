<?php
/**
 * SIMPK-Digital — Project Updater Utility
 * ─────────────────────────────────────────
 * Upload file ZIP update proyek, lalu ekstrak & timpa file lama
 * TANPA menghapus file-file penting (database, .env, upload, dsb).
 *
 * Fitur:
 *  - Upload ZIP → ekstrak → overwrite file yang berubah
 *  - Proteksi file & folder penting agar tidak tertimpa/dihapus
 *  - Real-time console output
 *  - Jalankan perintah Artisan setelah update (migrate, cache clear, dll.)
 *
 * SECURITY NOTE: Ganti ACCESS_KEY sebelum upload ke server!
 */

define('ACCESS_KEY', 'wava123');

// Deteksi root proyek Laravel (satu folder di atas public/)
// Pada shared hosting: public/ = htdocs, project root = satu level di atas
// Pada local dev: __DIR__ = public/, parent = project root
define('PROJECT_ROOT', realpath(__DIR__ . '/..') ?: __DIR__);

// File & folder yang TIDAK BOLEH ditimpa / dihapus saat update
// Path relatif terhadap root proyek
$PROTECTED_ITEMS = [
    'database/database.sqlite',
    '.env',
    '.env.production',
    '.htaccess',
    'public/.htaccess',
    'storage/app',
    'storage/logs',
    'storage/framework/sessions',
    'storage/framework/cache',
    'public/update.php',
    'clean.php',
    'unzip.php',
    'build_split_zip.ps1',
    'build_zip.ps1'
];

session_start();

// ── AUTH ──────────────────────────────────────
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

$is_auth = isset($_SESSION['update_auth']) && $_SESSION['update_auth'] === ACCESS_KEY;
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['access_key'])) {
    if ($_POST['access_key'] === ACCESS_KEY) {
        $_SESSION['update_auth'] = ACCESS_KEY;
        $is_auth = true;
    } else {
        $error_msg = 'Access Key Salah!';
    }
}

// ── HELPERS ──────────────────────────────────
function isProtected($relativePath, $protectedItems) {
    $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
    foreach ($protectedItems as $item) {
        $item = ltrim(str_replace('\\', '/', $item), '/');
        if ($relativePath === $item || strpos($relativePath, rtrim($item, '/') . '/') === 0) {
            return true;
        }
    }
    return false;
}

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

function consoleLine($msg, $type = 'info') {
    $colors = [
        'info'    => '#a8e6cf',
        'success' => '#4ade80',
        'warn'    => '#fbbf24',
        'error'   => '#f87171',
        'skip'    => '#94a3b8',
        'header'  => '#67e8f9',
    ];
    $color = $colors[$type] ?? $colors['info'];
    echo "<div style=\"color:{$color}\">{$msg}</div>";
    if (ob_get_level() > 0) ob_flush();
    flush();
}

// ── EXTRACT LOGIC ────────────────────────────
$update_done = false;
$stats = ['updated' => 0, 'skipped' => 0, 'created' => 0, 'dirs' => 0, 'errors' => 0];
$artisan_output = '';

if ($is_auth && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    @set_time_limit(600);
    @ini_set('memory_limit', '512M');

    // ── UPLOAD & EXTRACT ──
    if ($_POST['action'] === 'update' && isset($_FILES['zipfile']) && $_FILES['zipfile']['error'] === UPLOAD_ERR_OK) {
        $update_done = true;
    }

    // ── ARTISAN RUNNER ──
    if ($_POST['action'] === 'artisan' && isset($_POST['command'])) {
        $cmd = trim($_POST['command']);
        $allowed = ['migrate', 'migrate --force', 'optimize:clear', 'config:clear', 'cache:clear', 'view:clear', 'route:clear', 'storage:link'];
        if (in_array($cmd, $allowed)) {
            $fullCmd = 'cd ' . escapeshellarg(PROJECT_ROOT) . ' && php artisan ' . $cmd . ' 2>&1';
            $artisan_output = shell_exec($fullCmd);
        } else {
            $artisan_output = "Perintah tidak diizinkan: {$cmd}";
        }
    }

    // ── WIPE RUNNER ──
    if ($_POST['action'] === 'wipe') {
        $wipe_stats = ['files_deleted' => 0, 'dirs_deleted' => 0, 'skipped' => 0];
        
        if (!function_exists('wipeDirectory')) {
            function wipeDirectory($dir, $protectedItems, $baseDir, &$stats) {
                if (!is_dir($dir)) return;
                $files = array_diff(scandir($dir), ['.', '..']);
                foreach ($files as $file) {
                    $path = $dir . DIRECTORY_SEPARATOR . $file;
                    $relativePath = ltrim(str_replace('\\', '/', str_replace($baseDir, '', $path)), '/');
                    
                    if (isProtected($relativePath, $protectedItems)) {
                        $stats['skipped']++;
                        continue;
                    }
                    
                    if (is_dir($path)) {
                        wipeDirectory($path, $protectedItems, $baseDir, $stats);
                        if (@rmdir($path)) {
                            $stats['dirs_deleted']++;
                        }
                    } else {
                        if (@unlink($path)) {
                            $stats['files_deleted']++;
                        }
                    }
                }
            }
        }
        
        wipeDirectory(PROJECT_ROOT, $PROTECTED_ITEMS, PROJECT_ROOT, $wipe_stats);
        $artisan_output = "WIPE SELESAI:\n- File Dihapus: {$wipe_stats['files_deleted']}\n- Folder Kosong Dihapus: {$wipe_stats['dirs_deleted']}\n- Item Dilindungi (Skipped): {$wipe_stats['skipped']}\n\nKini Anda bisa mengunggah file ZIP pembaruan yang bersih.";
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPK - Project Updater</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body {
            font-family: 'IBM Plex Sans', sans-serif;
            background: #eef2f5;
            color: #0c1924;
        }
        .console-box {
            background: #0c1924;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11px;
            line-height: 1.6;
            max-height: 420px;
            overflow-y: auto;
            padding: 16px;
            border-radius: 8px;
        }
        .protected-badge {
            display: inline-flex; align-items: center; gap: 3px;
            font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
            padding: 2px 6px; border-radius: 4px;
            background: #fef3c7; color: #92400e; border: 1px solid #fde68a;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

<?php if (!$is_auth): ?>
    <!-- ═══ LOGIN ═══ -->
    <div class="w-full max-w-sm bg-white rounded-xl border border-gray-200 shadow-sm p-8 text-center">
        <div class="mb-6">
            <div class="w-16 h-16 bg-[#e8f7f7] text-[#0f6e72] rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                <i class="ph-bold ph-arrows-clockwise"></i>
            </div>
            <h1 class="text-xl font-bold text-slate-800">SIMPK Project Updater</h1>
            <p class="text-xs text-slate-400 mt-1">Masukkan Security Key untuk mengakses panel update</p>
        </div>

        <form method="POST" class="space-y-4">
            <input type="password" name="access_key" placeholder="Security Key" required autofocus
                class="w-full h-12 px-4 border border-slate-300 rounded-lg text-center tracking-[0.3em] text-lg font-bold focus:outline-none focus:border-[#0f6e72] focus:ring-2 focus:ring-[#0f6e72]/10 transition-all">
            
            <?php if ($error_msg): ?>
                <p class="text-red-500 text-xs font-bold"><?= htmlspecialchars($error_msg) ?></p>
            <?php endif; ?>

            <button type="submit" class="w-full h-11 bg-[#0f6e72] hover:bg-[#0a5558] text-white font-bold text-sm rounded-lg shadow-md transition-colors flex items-center justify-center gap-2">
                <i class="ph-bold ph-key"></i> Buka Akses
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-slate-100 text-[10px] text-slate-400 font-bold uppercase tracking-wider flex items-center justify-center gap-1.5">
            <i class="ph-fill ph-shield-check text-[#0f6e72] text-sm"></i> Secure Update Tool
        </div>
    </div>

<?php else: ?>
    <!-- ═══ MAIN PANEL ═══ -->
    <div class="w-full max-w-3xl bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-[#0f6e72] to-[#1da1a6] text-white px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-xl">
                    <i class="ph-bold ph-arrows-clockwise"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold leading-none">Project Updater</h2>
                    <p class="text-[10px] text-white/70 mt-1 font-mono">ROOT: <?= htmlspecialchars(PROJECT_ROOT) ?></p>
                </div>
            </div>
            <a href="?logout=1" class="text-xs font-bold bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-md transition-all flex items-center gap-1">
                <i class="ph-bold ph-sign-out"></i> Keluar
            </a>
        </div>

        <div class="p-6 space-y-6">

        <?php if ($update_done): ?>
            <!-- ═══ EXTRACTION OUTPUT ═══ -->
            <div class="console-box" id="console-output">
                <?php
                $zipFile = $_FILES['zipfile']['tmp_name'];
                $zipName = $_FILES['zipfile']['name'];
                $zipSize = $_FILES['zipfile']['size'];

                consoleLine("╔══════════════════════════════════════════════════╗", 'header');
                consoleLine("║  SIMPK-Digital — Project Updater                ║", 'header');
                consoleLine("╚══════════════════════════════════════════════════╝", 'header');
                consoleLine("");
                consoleLine("[INFO] File ZIP  : {$zipName} (" . formatBytes($zipSize) . ")", 'info');
                consoleLine("[INFO] Target    : " . PROJECT_ROOT, 'info');
                consoleLine("[INFO] Waktu     : " . date('d M Y H:i:s'), 'info');
                consoleLine("");

                $zip = new ZipArchive();
                $result = $zip->open($zipFile);

                if ($result !== true) {
                    consoleLine("[ERROR] Gagal membuka file ZIP! Error code: {$result}", 'error');
                    $stats['errors']++;
                } else {
                    $totalFiles = $zip->numFiles;
                    consoleLine("[INFO] Total entries dalam ZIP: {$totalFiles}", 'info');
                    consoleLine("────────────────────────────────────────────────", 'header');

                    // Deteksi apakah ZIP punya folder root tunggal (misal: "project-name/")
                    $firstEntry = $zip->getNameIndex(0);
                    $zipPrefix = '';
                    if (substr($firstEntry, -1) === '/') {
                        // Cek apakah semua entry dimulai dari folder ini
                        $potentialPrefix = $firstEntry;
                        $allMatch = true;
                        for ($i = 1; $i < min($totalFiles, 20); $i++) {
                            if (strpos($zip->getNameIndex($i), $potentialPrefix) !== 0) {
                                $allMatch = false;
                                break;
                            }
                        }
                        if ($allMatch) {
                            $zipPrefix = $potentialPrefix;
                            consoleLine("[INFO] Terdeteksi root folder di ZIP: {$zipPrefix}", 'warn');
                            consoleLine("[INFO] Strip prefix saat ekstrak.", 'warn');
                            consoleLine("");
                        }
                    }

                    for ($i = 0; $i < $totalFiles; $i++) {
                        $entryName = $zip->getNameIndex($i);

                        // Strip root folder prefix jika ada
                        $relativePath = $entryName;
                        if ($zipPrefix && strpos($entryName, $zipPrefix) === 0) {
                            $relativePath = substr($entryName, strlen($zipPrefix));
                        }

                        // Lewati jika kosong (root folder itu sendiri)
                        if ($relativePath === '' || $relativePath === false) continue;

                        $destPath = PROJECT_ROOT . '/' . $relativePath;

                        // Cek apakah file dilindungi
                        if (isProtected($relativePath, $GLOBALS['PROTECTED_ITEMS'])) {
                            consoleLine("[SKIP] {$relativePath}  ← Dilindungi", 'skip');
                            $stats['skipped']++;
                            continue;
                        }

                        // Jika entry adalah folder
                        if (substr($entryName, -1) === '/') {
                            if (!is_dir($destPath)) {
                                if (@mkdir($destPath, 0755, true)) {
                                    consoleLine("[MKDIR] {$relativePath}", 'info');
                                    $stats['dirs']++;
                                }
                            }
                            continue;
                        }

                        // Pastikan parent folder ada
                        $parentDir = dirname($destPath);
                        if (!is_dir($parentDir)) {
                            @mkdir($parentDir, 0755, true);
                        }

                        // Baca konten dari ZIP
                        $content = $zip->getFromIndex($i);
                        if ($content === false) {
                            consoleLine("[ERROR] Gagal baca: {$relativePath}", 'error');
                            $stats['errors']++;
                            continue;
                        }

                        $existed = file_exists($destPath);
                        if (@file_put_contents($destPath, $content) !== false) {
                            if ($existed) {
                                consoleLine("[UPDATE] {$relativePath}", 'success');
                                $stats['updated']++;
                            } else {
                                consoleLine("[NEW] {$relativePath}", 'success');
                                $stats['created']++;
                            }
                        } else {
                            consoleLine("[ERROR] Gagal tulis: {$relativePath}", 'error');
                            $stats['errors']++;
                        }
                    }

                    $zip->close();

                    consoleLine("");
                    consoleLine("════════════════════════════════════════════════", 'header');
                    consoleLine("  UPDATE SELESAI!", 'header');
                    consoleLine("  File Diperbarui : {$stats['updated']}", 'success');
                    consoleLine("  File Baru       : {$stats['created']}", 'success');
                    consoleLine("  Folder Dibuat   : {$stats['dirs']}", 'info');
                    consoleLine("  Dilindungi/Skip : {$stats['skipped']}", 'skip');
                    consoleLine("  Error           : {$stats['errors']}", $stats['errors'] > 0 ? 'error' : 'info');
                    consoleLine("════════════════════════════════════════════════", 'header');
                }
                ?>
            </div>

            <script>
                const c = document.getElementById('console-output');
                if (c) c.scrollTop = c.scrollHeight;
            </script>

            <!-- Result Banner -->
            <?php if ($stats['errors'] === 0): ?>
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 flex gap-3 text-emerald-900">
                <i class="ph-bold ph-check-circle text-2xl text-emerald-600 shrink-0"></i>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wide">Update Berhasil Diterapkan!</h4>
                    <p class="text-xs leading-relaxed mt-1">
                        <?= $stats['updated'] ?> file diperbarui, <?= $stats['created'] ?> file baru, <?= $stats['skipped'] ?> file dilindungi (tidak ditimpa).
                        Jalankan perintah Artisan di bawah jika diperlukan.
                    </p>
                </div>
            </div>
            <?php else: ?>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex gap-3 text-amber-900">
                <i class="ph-bold ph-warning text-2xl text-amber-600 shrink-0"></i>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wide">Update Selesai Dengan <?= $stats['errors'] ?> Error</h4>
                    <p class="text-xs leading-relaxed mt-1">Periksa console di atas untuk detail error. Beberapa file mungkin tidak bisa ditulis karena izin akses.</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Post-Update Artisan Commands -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-50 px-4 py-2.5 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 flex items-center gap-1.5">
                    <i class="ph-bold ph-terminal"></i> Jalankan Artisan (Post-Update)
                </div>
                <div class="p-4 grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <?php
                    $cmds = [
                        ['migrate --force', 'ph-database', 'Migrate DB'],
                        ['optimize:clear', 'ph-broom', 'Clear Cache'],
                        ['config:clear', 'ph-gear', 'Config Clear'],
                        ['storage:link', 'ph-link', 'Storage Link'],
                    ];
                    foreach ($cmds as $c): ?>
                    <form method="POST">
                        <input type="hidden" name="action" value="artisan">
                        <input type="hidden" name="command" value="<?= $c[0] ?>">
                        <button type="submit" class="w-full py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg border border-slate-200 transition-colors flex flex-col items-center gap-1">
                            <i class="ph-bold <?= $c[1] ?> text-base text-[#0f6e72]"></i>
                            <?= $c[2] ?>
                        </button>
                    </form>
                    <?php endforeach; ?>
                </div>
            </div>

            <a href="<?= basename(__FILE__) ?>" class="block text-center py-3 bg-[#0f6e72] hover:bg-[#0a5558] text-white font-bold text-sm rounded-lg shadow transition-colors">
                <i class="ph-bold ph-arrows-clockwise"></i> Upload Update Lagi
            </a>

        <?php else: ?>

            <!-- ═══ UPLOAD FORM ═══ -->

            <!-- Info Banner -->
            <div class="bg-sky-50 border border-sky-200 rounded-lg p-4 flex gap-3 text-sky-900">
                <i class="ph-bold ph-info text-2xl text-sky-500 shrink-0"></i>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wide">Cara Menggunakan Updater</h4>
                    <p class="text-xs leading-relaxed mt-1">
                        Upload file <code class="bg-sky-100 px-1 py-0.5 rounded font-mono text-[10px]">.zip</code> berisi file proyek terbaru. 
                        File akan diekstrak & menimpa file lama. File yang dilindungi (database, .env, uploads) <strong>tidak akan dihapus atau ditimpa</strong>.
                    </p>
                </div>
            </div>

            <!-- Protected Items List -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-amber-50 px-4 py-2.5 text-xs font-bold text-amber-700 uppercase tracking-wider border-b border-amber-200 flex items-center gap-1.5">
                    <i class="ph-bold ph-shield-check"></i> File & Folder Dilindungi (Tidak Akan Ditimpa)
                </div>
                <div class="p-3 flex flex-wrap gap-2">
                    <?php foreach ($PROTECTED_ITEMS as $item): ?>
                        <span class="protected-badge">
                            <i class="ph-bold ph-lock-simple"></i> <?= htmlspecialchars($item) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Upload Form -->
            <form method="POST" enctype="multipart/form-data" id="update-form" class="space-y-4">
                <input type="hidden" name="action" value="update">

                <label class="block border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-[#0f6e72] hover:bg-[#e8f7f7]/30 transition-all group" id="drop-zone">
                    <input type="file" name="zipfile" accept=".zip" required class="hidden" id="zip-input">
                    <i class="ph-bold ph-cloud-arrow-up text-4xl text-gray-400 group-hover:text-[#0f6e72] transition-colors"></i>
                    <p class="text-sm font-bold text-gray-600 mt-3" id="file-label">Klik atau drag file ZIP update di sini</p>
                    <p class="text-[10px] text-gray-400 mt-1">Maksimum sesuai konfigurasi server (biasanya 10-50 MB)</p>
                </label>

                <button type="submit" id="btn-submit" disabled
                    class="w-full py-3.5 bg-[#0f6e72] hover:bg-[#0a5558] disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold text-sm rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <i class="ph-bold ph-arrows-clockwise"></i> Mulai Update Proyek
                </button>
            </form>

            <?php if ($artisan_output): ?>
            <!-- Artisan Output -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-50 px-4 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 flex items-center gap-1.5">
                    <i class="ph-bold ph-terminal"></i> Output Artisan
                </div>
                <pre class="console-box text-xs whitespace-pre-wrap"><?= htmlspecialchars($artisan_output) ?></pre>
            </div>
            <?php endif; ?>

            <!-- Quick Artisan Section -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-50 px-4 py-2.5 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 flex items-center gap-1.5">
                    <i class="ph-bold ph-terminal"></i> Perintah Artisan Cepat
                </div>
                <div class="p-4 grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <?php
                    $cmds = [
                        ['migrate --force', 'ph-database', 'Migrate DB'],
                        ['optimize:clear', 'ph-broom', 'Clear Cache'],
                        ['config:clear', 'ph-gear', 'Config Clear'],
                        ['storage:link', 'ph-link', 'Storage Link'],
                    ];
                    foreach ($cmds as $c): ?>
                    <form method="POST">
                        <input type="hidden" name="action" value="artisan">
                        <input type="hidden" name="command" value="<?= $c[0] ?>">
                        <button type="submit" class="w-full py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg border border-slate-200 transition-colors flex flex-col items-center gap-1">
                            <i class="ph-bold <?= $c[1] ?> text-base text-[#0f6e72]"></i>
                            <?= $c[2] ?>
                        </button>
                    </form>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="border border-red-200 rounded-lg overflow-hidden mt-6">
                <div class="bg-red-50 px-4 py-2.5 text-xs font-bold text-red-600 uppercase tracking-wider border-b border-red-200 flex items-center gap-1.5">
                    <i class="ph-bold ph-warning-octagon"></i> Danger Zone: Bersihkan File Lama
                </div>
                <div class="p-4 bg-white">
                    <p class="text-xs text-slate-600 mb-3">Menghapus seluruh file dan folder proyek <strong>KECUALI</strong> file yang dilindungi (seperti <code>.env</code>, database, dan file penting lainnya). Gunakan fitur ini sebelum mengunggah versi baru agar sisa-sisa file lama tidak menumpuk dan menyebabkan error.</p>
                    <form method="POST" onsubmit="return confirm('YAKIN INGIN MENGHAPUS SEMUA FILE PROYEK?\n\nFile/folder yang terdaftar di bagian (Dilindungi) AKAN DIPERTAHANKAN.\nTindakan ini tidak bisa dibatalkan!');">
                        <input type="hidden" name="action" value="wipe">
                        <button type="submit" class="py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-lg shadow-sm transition-colors flex items-center gap-2">
                            <i class="ph-bold ph-trash"></i> Bersihkan Folder Htdocs
                        </button>
                    </form>
                </div>
            </div>

        <?php endif; ?>
        </div>

        <!-- Footer -->
        <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 text-center text-[10px] text-slate-400 font-bold uppercase tracking-wide">
            &copy; 2026 RS Wava Husada — Sistem Informasi Medis Penyebab Kematian Digital
        </div>
    </div>

    <script>
        // File input interaction
        const zipInput = document.getElementById('zip-input');
        const fileLabel = document.getElementById('file-label');
        const btnSubmit = document.getElementById('btn-submit');
        const dropZone = document.getElementById('drop-zone');

        if (zipInput) {
            zipInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const f = this.files[0];
                    const sizeMB = (f.size / 1024 / 1024).toFixed(2);
                    fileLabel.innerHTML = `<span class="text-[#0f6e72] font-bold">${f.name}</span> <span class="text-gray-400">(${sizeMB} MB)</span>`;
                    btnSubmit.disabled = false;
                }
            });

            // Drag & drop
            if (dropZone) {
                ['dragenter', 'dragover'].forEach(event => {
                    dropZone.addEventListener(event, e => {
                        e.preventDefault();
                        dropZone.classList.add('border-[#0f6e72]', 'bg-[#e8f7f7]/30');
                    });
                });
                ['dragleave', 'drop'].forEach(event => {
                    dropZone.addEventListener(event, e => {
                        e.preventDefault();
                        dropZone.classList.remove('border-[#0f6e72]', 'bg-[#e8f7f7]/30');
                    });
                });
                dropZone.addEventListener('drop', e => {
                    e.preventDefault();
                    if (e.dataTransfer.files.length > 0) {
                        zipInput.files = e.dataTransfer.files;
                        zipInput.dispatchEvent(new Event('change'));
                    }
                });
            }
        }

        // Show loading state on submit
        const form = document.getElementById('update-form');
        if (form) {
            form.addEventListener('submit', function() {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Mengupload & Mengekstrak...';
            });
        }
    </script>
<?php endif; ?>

</body>
</html>
