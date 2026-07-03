<?php
/**
 * SIMPK-Digital Premium Directory Wiper Utility
 * Designed for clean remote deployments (htdocs / public_html)
 * 
 * SECURITY NOTE: Change the ACCESS_KEY below before uploading!
 */

define('ACCESS_KEY', 'wava123'); // Ganti key ini demi keamanan!

session_start();

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// Check Auth Status
$is_authenticated = isset($_SESSION['clean_auth']) && $_SESSION['clean_auth'] === ACCESS_KEY;

// Process Auth Form
$error_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['access_key'])) {
    if ($_POST['access_key'] === ACCESS_KEY) {
        $_SESSION['clean_auth'] = ACCESS_KEY;
        $is_authenticated = true;
    } else {
        $error_msg = 'Access Key Salah!';
    }
}

// Helper: Recursively delete directories & files
function recursiveWipe($dir, &$deletedCount, $selfFile) {
    if (!is_dir($dir)) {
        return;
    }

    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        
        // Don't delete this cleaner script itself
        if ($path === $selfFile) {
            continue;
        }

        if (is_dir($path)) {
            recursiveWipe($path, $deletedCount, $selfFile);
            if (@rmdir($path)) {
                $deletedCount['dirs']++;
                echo "<div class='text-emerald-600 font-mono text-xs'>[DELETED DIR] " . htmlspecialchars(str_replace(__DIR__, '', $path)) . "</div>";
            } else {
                echo "<div class='text-red-500 font-mono text-xs'>[FAILED DIR] " . htmlspecialchars(str_replace(__DIR__, '', $path)) . "</div>";
            }
        } else {
            if (@unlink($path)) {
                $deletedCount['files']++;
                echo "<div class='text-emerald-500 font-mono text-xs'>[DELETED FILE] " . htmlspecialchars(str_replace(__DIR__, '', $path)) . "</div>";
            } else {
                echo "<div class='text-red-500 font-mono text-xs'>[FAILED FILE] " . htmlspecialchars(str_replace(__DIR__, '', $path)) . "</div>";
            }
        }
        
        // Flush buffer to browser in real-time
        if (ob_get_level() > 0) {
            ob_flush();
        }
        flush();
    }
}

// Process Wipe Action
$wipe_output = '';
$wipe_done = false;
$deletedCount = ['files' => 0, 'dirs' => 0];

if ($is_authenticated && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'wipe') {
    // Prevent execution timeouts
    @set_time_limit(600);
    @ini_set('memory_limit', '512M');
    
    $wipe_done = true;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPK - Directory Cleaner Tool</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body {
            font-family: 'IBM Plex Sans', sans-serif;
            background-color: #eef2f5;
            color: #0c1924;
        }
        .cleaner-card {
            border: 1px solid #c8d6df;
            box-shadow: 0 10px 25px rgba(12, 25, 36, 0.05);
        }
        .console-box {
            background-color: #0c1924;
            color: #a8ffb2;
            font-family: 'IBM Plex Mono', monospace;
            max-height: 350px;
            overflow-y: auto;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <!-- Auth Form -->
    <?php if (!$is_authenticated): ?>
        <div class="w-full max-w-sm bg-white rounded-xl cleaner-card p-8 text-center relative overflow-hidden">
            <!-- Header Brand -->
            <div class="mb-6">
                <div class="w-16 h-16 bg-[#e8f7f7] text-[#0f6e72] rounded-full flex items-center justify-center mx-auto mb-4 text-3xl shadow-sm">
                    <i class="ph-bold ph-shield-warning"></i>
                </div>
                <h1 class="text-xl font-bold text-slate-800">SIMPK Security Lock</h1>
                <p class="text-xs text-slate-400 mt-1">Masukkan Security Key untuk membersihkan htdocs</p>
            </div>

            <form method="POST" class="space-y-4">
                <div>
                    <input type="password" name="access_key" placeholder="Security Key (default: wava123)" required autofocus
                        class="w-full h-12 px-4 border border-slate-300 rounded-lg text-center tracking-[0.4em] text-lg font-bold focus:outline-none focus:border-[#0f6e72] focus:ring-2 focus:ring-[#0f6e72]/10 transition-all">
                </div>
                
                <?php if ($error_msg): ?>
                    <p class="text-red-500 text-xs font-bold"><?= htmlspecialchars($error_msg) ?></p>
                <?php endif; ?>

                <button type="submit" class="w-full h-11 bg-[#0f6e72] hover:bg-[#0a5558] text-white font-bold text-sm rounded-lg shadow-md transition-colors active:scale-95 duration-150 flex items-center justify-center gap-2">
                    <i class="ph-bold ph-key"></i> Buka Akses
                </button>
            </form>
            
            <div class="mt-8 pt-4 border-t border-slate-100 flex justify-center items-center gap-1.5 text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                <i class="ph-fill ph-shield-check text-[#0f6e72] text-sm"></i> Secure Deployment Tool
            </div>
        </div>
    <?php else: ?>

    <!-- Main Active Panel -->
    <div class="w-full max-w-2xl bg-white rounded-xl cleaner-card overflow-hidden">
        
        <!-- Header -->
        <div class="bg-[#0f6e72] text-white px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-xl">
                    <i class="ph-bold ph-trash"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold leading-none">Directory Cleaner</h2>
                    <p class="text-[10px] text-[#e8f7f7] mt-1 font-mono">ROOT: <?= htmlspecialchars(__DIR__) ?></p>
                </div>
            </div>
            <a href="?logout=1" class="text-xs font-bold bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-md transition-all flex items-center gap-1">
                <i class="ph-bold ph-sign-out"></i> Keluar
            </a>
        </div>

        <div class="p-6">
            <?php if (!$wipe_done): ?>
                <!-- Pre-Wipe Confirmation -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex gap-3 text-amber-900 mb-6">
                    <i class="ph-bold ph-warning-octagon text-2xl text-amber-600 shrink-0"></i>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wide">Peringatan Keras (DANGER ZONE)</h4>
                        <p class="text-xs leading-relaxed mt-1">
                            Tindakan ini akan <strong>menghapus seluruh file dan subfolder</strong> di dalam folder saat ini secara permanen. File cleaner ini (<span class="font-mono text-[11px] bg-amber-100 px-1 py-0.5 rounded"><?= basename(__FILE__) ?></span>) tidak akan dihapus sehingga Anda dapat melakukan upload file baru.
                        </p>
                    </div>
                </div>

                <!-- Directory Contents Summary -->
                <div class="border border-slate-200 rounded-lg overflow-hidden mb-6">
                    <div class="bg-slate-50 px-4 py-2 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200 flex justify-between">
                        <span>Daftar Item Saat Ini</span>
                        <span>Item Terdeteksi</span>
                    </div>
                    <div class="divide-y divide-slate-100 max-h-48 overflow-y-auto">
                        <?php
                        $items = array_diff(scandir(__DIR__), array('.', '..'));
                        $totalSize = 0;
                        if (empty($items)): ?>
                            <div class="p-4 text-center text-xs text-slate-400">
                                Folder ini sudah kosong (bersih).
                            </div>
                        <?php else:
                            foreach ($items as $item):
                                $path = __DIR__ . DIRECTORY_SEPARATOR . $item;
                                $isDir = is_dir($path);
                                ?>
                                <div class="px-4 py-2.5 flex justify-between items-center text-xs">
                                    <div class="flex items-center gap-2">
                                        <i class="ph-bold <?= $isDir ? 'ph-folder text-amber-500' : 'ph-file text-slate-400' ?> text-lg shrink-0"></i>
                                        <span class="font-mono <?= $item === basename(__FILE__) ? 'text-[#0f6e72] font-bold' : '' ?>">
                                            <?= htmlspecialchars($item) ?>
                                            <?= $item === basename(__FILE__) ? ' <span class="text-[9px] bg-[#e8f7f7] text-[#0f6e72] px-1.5 py-0.5 rounded font-sans">Cleaner Script</span>' : '' ?>
                                        </span>
                                    </div>
                                    <span class="text-slate-400 font-mono">
                                        <?= $isDir ? '[Direktori]' : number_format(filesize($path) / 1024, 2) . ' KB' ?>
                                    </span>
                                </div>
                            <?php 
                            endforeach;
                        endif; ?>
                    </div>
                </div>

                <!-- Action Form -->
                <form method="POST" onsubmit="return confirm('APAKAH ANDA YAKIN INGIN MENGHAPUS SEMUA DATA? Tindakan ini tidak dapat dibatalkan!');">
                    <input type="hidden" name="action" value="wipe">
                    <button type="submit" class="w-full py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold text-sm rounded-lg shadow-md hover:shadow-lg transition-all active:scale-[0.98] duration-150 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-trash"></i> Bersihkan Folder (Wipe htdocs)
                    </button>
                </form>

            <?php else: ?>
                
                <!-- Real-Time Output Console -->
                <div class="mb-4">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="ph-bold ph-terminal"></i> Proses Penghapusan File Real-Time
                    </h3>
                    
                    <div class="console-box rounded-lg p-4 font-mono text-xs space-y-1" id="console-output">
                        <?php
                        // Start wiping and echoing in real-time
                        $selfFile = __FILE__;
                        recursiveWipe(__DIR__, $deletedCount, $selfFile);
                        ?>
                        <div class="text-[#a8ffb2] font-bold mt-3">======================================</div>
                        <div class="text-[#a8ffb2] font-bold">WIPE SELESAI DENGAN SUKSES!</div>
                        <div class="text-[#a8ffb2]">Total File Dihapus: <?= $deletedCount['files'] ?></div>
                        <div class="text-[#a8ffb2]">Total Folder Dihapus: <?= $deletedCount['dirs'] ?></div>
                        <div class="text-[#a8ffb2] font-bold">======================================</div>
                    </div>
                </div>

                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 flex gap-3 text-emerald-900 mb-6">
                    <i class="ph-bold ph-check-circle text-2xl text-emerald-600 shrink-0"></i>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wide">htdocs Berhasil Dibersihkan!</h4>
                        <p class="text-xs leading-relaxed mt-1">
                            Seluruh file dan subfolder lama telah dihapus dengan sukses. Anda sekarang dapat mengunggah file ZIP baru dan mengekstraknya secara bersih.
                        </p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="unzip.php" class="flex-1 py-3 bg-[#0f6e72] hover:bg-[#0a5558] text-white font-bold text-sm rounded-lg shadow-md hover:shadow-lg transition-colors text-center flex items-center justify-center gap-2">
                        <i class="ph-bold ph-archive"></i> Buka Panel Unzip (unzip.php)
                    </a>
                    <a href="<?= basename(__FILE__) ?>" class="py-3 px-6 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-lg border border-slate-200 transition-colors text-center">
                        Muat Ulang Halaman
                    </a>
                </div>

                <script>
                    // Auto-scroll console to bottom
                    const consoleOut = document.getElementById('console-output');
                    if (consoleOut) {
                        consoleOut.scrollTop = consoleOut.scrollHeight;
                    }
                </script>

            <?php endif; ?>
        </div>

        <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 text-center text-[10px] text-slate-400 font-bold uppercase tracking-wide">
            &copy; 2026 RS Wava Husada — Sistem Informasi Medis Penyebab Kematian Digital
        </div>
    </div>
    <?php endif; ?>

</body>
</html>
