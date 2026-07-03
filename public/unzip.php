<?php
/**
 * SIMPK-Digital Secure Unzipper & Deployment Tool
 * Dedicated for InfinityFree Shared Hosting & RS Wava Husada Clinical Systems
 */

session_start();

// Security Key - Keep it matching user's original key for seamless access
define('SECURITY_KEY', 'wava123'); 

define('PROJECT_ROOT', realpath(__DIR__ . '/..') ?: __DIR__);
chdir(PROJECT_ROOT);
$targetDir = PROJECT_ROOT;

// Helper: Read .env file
function getEnvData() {
    $envPath = PROJECT_ROOT . '/.env';
    if (!file_exists($envPath)) {
        if (file_exists(PROJECT_ROOT . '/.env.production')) {
            copy(PROJECT_ROOT . '/.env.production', $envPath);
        } elseif (file_exists(PROJECT_ROOT . '/.env.example')) {
            copy(PROJECT_ROOT . '/.env.example', $envPath);
        } else {
            return '';
        }
    }
    return file_get_contents($envPath);
}

// Helper: Save raw .env file content
function saveEnvData($content) {
    $envPath = PROJECT_ROOT . '/.env';
    return file_put_contents($envPath, $content) !== false;
}

// Helper: Parse .env file key-value pairs
function parseEnv($content) {
    $lines = explode("\n", $content);
    $data = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val);
            // Strip quotes if they wrap the value
            if (preg_match('/^"([^"]*)"$/', $val, $matches)) {
                $val = $matches[1];
            } elseif (preg_match('/^\'([^\']*)\'$/', $val, $matches)) {
                $val = $matches[1];
            }
            $data[$key] = $val;
        }
    }
    return $data;
}

// Helper: Update individual .env parameters
function updateEnv($newData) {
    $envPath = PROJECT_ROOT . '/.env';
    if (!file_exists($envPath)) {
        getEnvData(); 
    }
    
    $content = file_get_contents($envPath);
    foreach ($newData as $key => $val) {
        $key = strtoupper(trim($key));
        
        // Escape and wrap value with quotes if it has spaces or special chars
        if (preg_match('/\s/', $val) || preg_match('/[#=$]/', $val)) {
            $val = '"' . str_replace('"', '\\"', $val) . '"';
        }
        
        $pattern = "/^" . preg_quote($key, '/') . "=(.*)$/m";
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, $key . "=" . $val, $content);
        } else {
            $content .= "\n" . $key . "=" . $val;
        }
    }
    return file_put_contents($envPath, trim($content) . "\n") !== false;
}

// Helper: Test database connection using PHP's PDO
function testDbConnection() {
    $envContent = getEnvData();
    $env = parseEnv($envContent);
    
    $host = isset($env['DB_HOST']) ? $env['DB_HOST'] : '';
    $port = isset($env['DB_PORT']) ? $env['DB_PORT'] : '3306';
    $database = isset($env['DB_DATABASE']) ? $env['DB_DATABASE'] : '';
    $username = isset($env['DB_USERNAME']) ? $env['DB_USERNAME'] : '';
    $password = isset($env['DB_PASSWORD']) ? $env['DB_PASSWORD'] : '';
    
    if (empty($host) || empty($database)) {
        return ['success' => false, 'message' => 'Host atau Database kosong di file .env.'];
    }
    
    try {
        $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5, // 5s timeout
        ];
        $pdo = new PDO($dsn, $username, $password, $options);
        return ['success' => true, 'message' => "Koneksi PDO Berhasil! Terhubung ke database '$database' di '$host'."];
    } catch (\PDOException $e) {
        return ['success' => false, 'message' => "PDO Koneksi Gagal: " . $e->getMessage()];
    }
}

// Helper: Run Laravel Artisan command internally (in-process bootstrap)
function runArtisan($commandLine) {
    if (!file_exists(PROJECT_ROOT . '/vendor/autoload.php') || !file_exists(PROJECT_ROOT . '/bootstrap/app.php')) {
        return "[ERROR] Laravel belum terinstal atau belum diekstrak secara lengkap (vendor/autoload.php tidak ditemukan).";
    }
    
    try {
        ob_start();
        
        // Bootstrapping Laravel in-process
        require_once PROJECT_ROOT . '/vendor/autoload.php';
        $app = require_once PROJECT_ROOT . '/bootstrap/app.php';
        
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        
        $status = $kernel->call($commandLine, [], $output);
        $result = $output->fetch();
        
        ob_end_clean();
        
        return "[STATUS CODE: $status]\n" . ($result ?: "[SUCCESS] Perintah dijalankan tanpa output.");
    } catch (\Throwable $e) {
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return "[ERROR] Gagal menjalankan perintah: " . $e->getMessage() . "\n" . $e->getTraceAsString();
    }
}

// Logout handler
if (isset($_GET['logout'])) {
    unset($_SESSION['auth_key']);
    header('Location: unzip.php');
    exit;
}

// Authentication handler
if (isset($_POST['auth_key'])) {
    if ($_POST['auth_key'] === SECURITY_KEY) {
        $_SESSION['auth_key'] = SECURITY_KEY;
    } else {
        $error = 'Security Key tidak valid!';
    }
}

$isAuthenticated = isset($_SESSION['auth_key']) && $_SESSION['auth_key'] === SECURITY_KEY;

// Process Actions
$log = [];
$activeTab = isset($_POST['tab']) ? $_POST['tab'] : 'extractor';

if ($isAuthenticated && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // Action: Merge Parts
    if ($action === 'merge_parts') {
        $partPrefix = isset($_POST['part_prefix']) ? $_POST['part_prefix'] : 'simpk_deploy.zip';
        $parts = glob($partPrefix . '.[0-9][0-9][0-9]');
        sort($parts);
        
        if (empty($parts)) {
            $log[] = "[ERROR] Tidak ada file part ({$partPrefix}.001, dll) ditemukan.";
        } else {
            $log[] = "[INFO] Menggabungkan " . count($parts) . " file parts...";
            $outputZip = $partPrefix;
            $outputHandle = fopen($outputZip, 'wb');
            if ($outputHandle === false) {
                $log[] = "[ERROR] Gagal membuka file output: $outputZip";
            } else {
                $successMerge = true;
                foreach ($parts as $part) {
                    $log[] = "[MERGE] Menulis part: " . basename($part);
                    $inputHandle = fopen($part, 'rb');
                    if ($inputHandle === false) {
                        $log[] = "[ERROR] Gagal membaca part: $part";
                        $successMerge = false;
                        break;
                    }
                    while (!feof($inputHandle)) {
                        fwrite($outputHandle, fread($inputHandle, 8192));
                    }
                    fclose($inputHandle);
                }
                fclose($outputHandle);
                
                if ($successMerge) {
                    $log[] = "[SUCCESS] File zip utuh berhasil disatukan: $outputZip (" . number_format(filesize($outputZip)/1024/1024, 2) . " MB)";
                } else {
                    $log[] = "[ERROR] Penggabungan gagal.";
                }
            }
        }
    }
    
    // Action: Extract Selected Zip
    elseif ($action === 'extract_zip') {
        $zipFile = isset($_POST['zip_file']) ? $_POST['zip_file'] : '';
        if (empty($zipFile) || !file_exists($zipFile)) {
            $log[] = "[ERROR] File zip tidak ditemukan: $zipFile";
        } else {
            $log[] = "[INFO] Mengekstraksi $zipFile ke $targetDir...";
            if (!class_exists('ZipArchive')) {
                $log[] = "[ERROR] PHP ZipArchive extension tidak aktif di server ini.";
            } else {
                $zip = new ZipArchive();
                if ($zip->open($zipFile) === true) {
                    $zip->extractTo($targetDir);
                    $zip->close();
                    $log[] = "[SUCCESS] Berhasil mengekstraksi $zipFile!";
                    
                    // Auto-delete if selected
                    if (isset($_POST['auto_delete']) && $_POST['auto_delete'] == '1') {
                        unlink($zipFile);
                        $log[] = "[CLEANUP] Menghapus file zip asli setelah diekstrak: $zipFile";
                    }
                } else {
                    $log[] = "[ERROR] Gagal membuka file zip: $zipFile";
                }
            }
        }
    }
    
    // Action: Extract All Zips
    elseif ($action === 'extract_all') {
        $zipFiles = glob('*.zip');
        if (empty($zipFiles)) {
            $log[] = "[ERROR] Tidak ada file .zip ditemukan di direktori.";
        } else {
            if (!class_exists('ZipArchive')) {
                $log[] = "[ERROR] PHP ZipArchive extension tidak aktif di server ini.";
            } else {
                $log[] = "[INFO] Memulai ekstraksi masal untuk " . count($zipFiles) . " file ZIP...";
                foreach ($zipFiles as $zipFile) {
                    $log[] = "[INFO] Mengekstraksi " . basename($zipFile) . "...";
                    $zip = new ZipArchive();
                    if ($zip->open($zipFile) === true) {
                        $zip->extractTo($targetDir);
                        $zip->close();
                        $log[] = "[SUCCESS] Ekstraksi " . basename($zipFile) . " berhasil!";
                        
                        if (isset($_POST['auto_delete']) && $_POST['auto_delete'] == '1') {
                            unlink($zipFile);
                            $log[] = "[CLEANUP] Menghapus file zip: " . basename($zipFile);
                        }
                    } else {
                        $log[] = "[ERROR] Gagal mengekstraksi " . basename($zipFile);
                    }
                }
                $log[] = "[SUCCESS] Selesai memproses semua file ZIP!";
            }
        }
    }
    
    // Action: Clean Up Zips & Parts
    elseif ($action === 'cleanup') {
        $zips = glob('*.zip');
        $parts = glob('*.zip.[0-9][0-9][0-9]');
        $count = 0;
        foreach (array_merge($zips, $parts) as $f) {
            if (file_exists($f)) {
                unlink($f);
                $log[] = "[CLEANUP] Menghapus: " . basename($f);
                $count++;
            }
        }
        $log[] = "[SUCCESS] Berhasil membersihkan $count file zip/parts temporer.";
    }
    
    // Action: Save .env
    elseif ($action === 'save_env') {
        if (isset($_POST['raw_env'])) {
            $content = $_POST['raw_env'];
            if (saveEnvData($content)) {
                $log[] = "[SUCCESS] File .env berhasil disimpan secara raw.";
            } else {
                $log[] = "[ERROR] Gagal menyimpan file .env.";
            }
        } else {
            $fields = [
                'APP_NAME', 'APP_ENV', 'APP_DEBUG', 'APP_URL',
                'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'
            ];
            $newData = [];
            foreach ($fields as $f) {
                if (isset($_POST[$f])) {
                    $newData[$f] = $_POST[$f];
                }
            }
            if (updateEnv($newData)) {
                $log[] = "[SUCCESS] Parameter .env berhasil diperbarui.";
            } else {
                $log[] = "[ERROR] Gagal memperbarui parameter .env.";
            }
        }
    }
    
    // Action: Run Artisan Command
    elseif ($action === 'run_artisan') {
        $cmd = isset($_POST['artisan_cmd']) ? trim($_POST['artisan_cmd']) : '';
        if (empty($cmd)) {
            $log[] = "[ERROR] Perintah Artisan kosong.";
        } else {
            $log[] = "[CONSOLE] Menjalankan: php artisan $cmd";
            $res = runArtisan($cmd);
            $log[] = $res;
        }
    }
    
    // Action: Test DB
    elseif ($action === 'test_db') {
        $log[] = "[INFO] Menguji koneksi database...";
        $test = testDbConnection();
        if ($test['success']) {
            $log[] = "[SUCCESS] " . $test['message'];
        } else {
            $log[] = "[ERROR] " . $test['message'];
        }
    }
    
    // Action: Fix Folders & Permissions
    elseif ($action === 'fix_folders') {
        $log[] = "[INFO] Memulai inisialisasi direktori storage & bootstrap cache...";
        $folders = [
            'bootstrap/cache',
            'storage',
            'storage/app',
            'storage/app/public',
            'storage/framework',
            'storage/framework/cache',
            'storage/framework/cache/data',
            'storage/framework/sessions',
            'storage/framework/views',
            'storage/logs'
        ];
        
        $created = 0;
        $fixed = 0;
        foreach ($folders as $dir) {
            $path = $targetDir . '/' . $dir;
            if (!file_exists($path)) {
                if (mkdir($path, 0777, true)) {
                    $log[] = "[FOLDER] Membuat folder baru: $dir";
                    $created++;
                } else {
                    $log[] = "[ERROR] Gagal membuat folder: $dir";
                }
            }
            // Set permissions
            if (file_exists($path)) {
                chmod($path, 0777);
                $fixed++;
            }
        }
        $log[] = "[SUCCESS] Inisialisasi selesai. Membuat $created folder baru, mengatur permission pada $fixed folder.";
        
        // Storage link
        $storageLink = $targetDir . '/public/storage';
        if (!file_exists($storageLink)) {
            if (@symlink($targetDir . '/storage/app/public', $storageLink)) {
                $log[] = "[STORAGE] Symbolic link public/storage berhasil dibuat.";
            } else {
                $log[] = "[WARNING] Gagal membuat symbolic link secara otomatis. Anda mungkin harus membuatnya manual.";
            }
        } else {
            $log[] = "[INFO] Symbolic link public/storage sudah ada.";
        }
    }
}

// Diagnostic Statuses
$laravelExtracted = file_exists($targetDir . '/vendor/autoload.php') && file_exists($targetDir . '/bootstrap/app.php');
$envExists = file_exists($targetDir . '/.env');
$envData = getEnvData();
$parsedEnv = parseEnv($envData);

// Check DB Connection
$dbStatus = false;
$dbMessage = '';
if ($envExists) {
    $dbTestResult = testDbConnection();
    $dbStatus = $dbTestResult['success'];
    $dbMessage = $dbTestResult['message'];
}

// Scans for files
$zipFiles = glob('*.zip');
$zipParts = glob('*.zip.[0-9][0-9][0-9]');
sort($zipFiles);
sort($zipParts);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPK-Digital Deployment & Management Utility</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        :root {
            --brand:       #0f6e72;
            --brand-glow:  rgba(15, 110, 114, 0.15);
            --brand-md:    #1da1a6;
            --brand-lt:    #e8f7f7;
            --dark-bg:     #0c141a;
            --card-bg:     #ffffff;
            --body-bg:     #f3f7f8;
            --border-color:#d7e2e4;
            --text-main:   #17282c;
            --text-muted:  #536b70;
            --success:     #10b981;
            --success-lt:  #ecfdf5;
            --error:       #ef4444;
            --error-lt:    #fef2f2;
            --warning:     #f59e0b;
            --warning-lt:  #fffbeb;
            --terminal-bg: #090f13;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--body-bg); color: var(--text-main);
            min-height: 100vh; display: flex; flex-direction: column;
            font-size: 13.5px; line-height: 1.6;
        }

        /* Header Navigation Styles */
        .top-navbar {
            background: linear-gradient(135deg, var(--brand) 0%, #084c4f 100%);
            color: #ffffff; padding: 16px 28px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 4px 20px rgba(8, 76, 79, 0.15);
        }
        .nav-logo { display: flex; align-items: center; gap: 12px; }
        .nav-logo-icon {
            width: 42px; height: 42px; background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 12px; display: flex; align-items: center; justify-content: center;
            font-size: 22px; color: #ffffff;
        }
        .nav-title h1 { font-size: 16px; font-weight: 800; letter-spacing: -0.02em; }
        .nav-title p { font-size: 11px; opacity: 0.8; }
        .nav-actions { display: flex; align-items: center; gap: 16px; }
        
        .status-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 12px; border-radius: 99px; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.02em; background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        .status-pill.success { background: rgba(16, 185, 129, 0.2); border-color: rgba(16, 185, 129, 0.3); color: #6ee7b7; }
        .status-pill.danger { background: rgba(239, 68, 68, 0.2); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5; }

        .btn-logout {
            background: rgba(255, 255, 255, 0.1); color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2); padding: 8px 14px;
            border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 12px;
            display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;
        }
        .btn-logout:hover { background: rgba(255, 255, 255, 0.2); border-color: #ffffff; }

        /* Main Workspace Container */
        .main-container {
            max-width: 1200px; width: 100%; margin: 30px auto; padding: 0 20px;
            display: grid; grid-template-columns: 280px 1fr; gap: 24px; flex-grow: 1;
        }

        /* Sidebar Navigation */
        .sidebar {
            display: flex; flex-direction: column; gap: 16px;
        }
        .sidebar-menu {
            background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color);
            padding: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); display: flex; flex-direction: column; gap: 4px;
        }
        .menu-btn {
            display: flex; align-items: center; gap: 12px; padding: 12px 16px;
            background: none; border: none; text-align: left; width: 100%;
            font-family: inherit; font-size: 13px; font-weight: 600; color: var(--text-muted);
            border-radius: 8px; cursor: pointer; transition: all 0.15s ease-in-out;
        }
        .menu-btn i { font-size: 18px; }
        .menu-btn:hover { background: var(--brand-lt); color: var(--brand); }
        .menu-btn.active { background: var(--brand); color: #ffffff; }

        .sidebar-stats {
            background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color);
            padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); display: flex; flex-direction: column; gap: 14px;
        }
        .stat-item { display: flex; flex-direction: column; gap: 4px; border-bottom: 1px solid var(--body-bg); padding-bottom: 10px; }
        .stat-item:last-child { border: none; padding-bottom: 0; }
        .stat-label { font-size: 10px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
        .stat-val { font-weight: 700; color: var(--text-main); font-size: 13px; display: flex; align-items: center; gap: 6px; }

        /* Main Workspace Contents */
        .workspace-content {
            display: flex; flex-direction: column; gap: 20px;
        }

        /* Log / Terminal Output Area */
        .global-log-container {
            background: var(--terminal-bg); border-radius: 12px; border: 1px solid #1c272a;
            padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); display: flex; flex-direction: column; gap: 12px;
        }
        .log-header {
            display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #1c272a; padding-bottom: 10px;
        }
        .log-title { color: #a5f3fc; font-weight: 700; font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px; }
        .log-title i { font-size: 16px; color: var(--brand-md); }
        .log-body {
            font-family: 'Fira Code', monospace; font-size: 11.5px; color: #a5f3fc;
            max-height: 250px; overflow-y: auto; white-space: pre-wrap; line-height: 1.6;
        }
        .log-line-error { color: #fca5a5; }
        .log-line-success { color: #6ee7b7; }
        .log-line-info { color: #93c5fd; }

        /* Card panels */
        .panel-card {
            background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color);
            padding: 24px 28px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03); display: none;
        }
        .panel-card.active { display: block; }
        
        .panel-title { display: flex; align-items: center; gap: 12px; border-bottom: 2px solid var(--body-bg); padding-bottom: 16px; margin-bottom: 20px; }
        .panel-title i { font-size: 26px; color: var(--brand); }
        .panel-title h2 { font-size: 17px; font-weight: 800; color: var(--text-main); }
        .panel-description { font-size: 12px; color: var(--text-muted); margin-top: -15px; margin-bottom: 20px; }

        /* Auth Form Styles */
        .auth-container {
            max-width: 420px; width: 100%; margin: 100px auto;
            background: var(--card-bg); border: 1px solid var(--border-color);
            border-radius: 16px; padding: 40px 32px; box-shadow: 0 20px 40px rgba(12, 20, 26, 0.08);
            text-align: center;
        }
        .auth-logo {
            width: 60px; height: 60px; background: var(--brand-lt); color: var(--brand);
            border-radius: 16px; display: flex; align-items: center; justify-content: center;
            font-size: 32px; margin: 0 auto 20px;
        }
        .auth-container h2 { font-size: 18px; font-weight: 800; color: var(--text-main); margin-bottom: 6px; }
        .auth-container p { font-size: 12px; color: var(--text-muted); margin-bottom: 24px; }

        /* Input & Form Controls */
        .form-group { margin-bottom: 16px; display: flex; flex-direction: column; gap: 6px; text-align: left; }
        label { font-size: 11px; font-weight: 800; color: var(--text-main); text-transform: uppercase; letter-spacing: 0.04em; }
        input[type="text"], input[type="password"], textarea, select {
            width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px;
            font-family: inherit; font-size: 13.5px; color: var(--text-main); background: #fafcff;
            outline: none; transition: all 0.15s;
        }
        input[type="text"]:focus, input[type="password"]:focus, textarea:focus, select:focus {
            border-color: var(--brand); background: #ffffff; box-shadow: 0 0 0 3px var(--brand-glow);
        }
        textarea { resize: vertical; min-height: 120px; }
        
        /* Grid layout for Env editor form */
        .env-editor-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* Buttons & Actions */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px 20px; background: var(--brand); color: #ffffff; border: none;
            border-radius: 8px; font-size: 12.5px; font-weight: 700; cursor: pointer;
            transition: all 0.15s; font-family: inherit; text-decoration: none;
        }
        .btn:hover { background: #0b5154; box-shadow: 0 4px 12px rgba(15, 110, 114, 0.2); }
        .btn-sec { background: var(--body-bg); color: var(--text-muted); border: 1px solid var(--border-color); }
        .btn-sec:hover { background: var(--border-color); color: var(--text-main); box-shadow: none; }
        .btn-danger { background: var(--error); }
        .btn-danger:hover { background: #d32f2f; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2); }
        .btn-success { background: var(--success); }
        .btn-success:hover { background: #059669; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }
        
        .btn:disabled { opacity: 0.5; cursor: not-allowed; box-shadow: none !important; }

        /* Utility lists & tables */
        .zip-table {
            width: 100%; border-collapse: collapse; margin-bottom: 20px; text-align: left;
        }
        .zip-table th, .zip-table td { padding: 12px 16px; border-bottom: 1px solid var(--border-color); }
        .zip-table th { font-weight: 700; color: var(--text-muted); text-transform: uppercase; font-size: 10px; letter-spacing: 0.05em; background: var(--body-bg); }
        .zip-table tr:hover { background: #fafcff; }

        .console-shortcuts {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; margin-bottom: 20px;
        }
        .shortcut-card {
            background: var(--body-bg); border: 1px solid var(--border-color); border-radius: 8px;
            padding: 12px 14px; text-align: center; cursor: pointer; transition: all 0.15s;
            display: flex; flex-direction: column; gap: 4px; align-items: center; justify-content: center;
        }
        .shortcut-card:hover { border-color: var(--brand); background: var(--brand-lt); color: var(--brand); }
        .shortcut-card i { font-size: 20px; margin-bottom: 2px; }
        .shortcut-card span { font-weight: 700; font-size: 12px; }
        .shortcut-card p { font-size: 10px; color: var(--text-muted); }

        /* Alert notifications */
        .toast-alert {
            display: flex; align-items: center; gap: 12px; padding: 12px 16px;
            border-radius: 8px; margin-bottom: 20px; font-weight: 600; font-size: 13px;
        }
        .toast-alert.error { background: var(--error-lt); color: var(--error); border: 1px solid #fecaca; }
        .toast-alert.success { background: var(--success-lt); color: var(--success); border: 1px solid #a7f3d0; }
        .toast-alert.warning { background: var(--warning-lt); color: var(--warning); border: 1px solid #fde68a; }

        .diagnostics-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;
        }
        .diag-card {
            background: var(--body-bg); border: 1px solid var(--border-color); border-radius: 10px; padding: 16px 20px;
        }
        .diag-card h3 { font-size: 12px; font-weight: 800; color: var(--text-main); text-transform: uppercase; margin-bottom: 12px; border-bottom: 1px solid var(--border-color); padding-bottom: 6px; display: flex; align-items: center; gap: 8px; }
        .diag-card h3 i { font-size: 16px; color: var(--brand); }
        
        .diag-list { display: flex; flex-direction: column; gap: 8px; }
        .diag-item { display: flex; justify-content: space-between; font-size: 12px; }
        .diag-item-lbl { color: var(--text-muted); }
        .diag-item-val { font-weight: 700; display: flex; align-items: center; gap: 4px; }

        .text-green { color: var(--success); }
        .text-red { color: var(--error); }
        .text-yellow { color: var(--warning); }

        /* Tabs inside Env tab */
        .env-tabs { display: flex; gap: 10px; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; }
        .env-tab-btn {
            background: none; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;
            font-family: inherit; font-size: 12px; font-weight: 700; color: var(--text-muted);
        }
        .env-tab-btn.active { background: var(--brand-lt); color: var(--brand); }

        /* Sticky Footer */
        footer {
            margin-top: auto; background: var(--dark-bg); color: rgba(255, 255, 255, 0.4);
            text-align: center; padding: 20px; font-size: 11px; border-top: 1px solid #1c272a;
        }
    </style>
</head>
<body>

    <?php if (!$isAuthenticated): ?>
        <!-- Authentication Panel -->
        <div class="auth-container">
            <div class="auth-logo">
                <i class="ph-bold ph-key"></i>
            </div>
            <h2>SIMPK Deployment Utility</h2>
            <p>Masukkan Security Key untuk melanjutkan konfigurasi.</p>
            
            <?php if (isset($error)): ?>
                <div class="toast-alert error">
                    <i class="ph-bold ph-x-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="auth_key">Security Key</label>
                    <input type="password" id="auth_key" name="auth_key" placeholder="Masukkan security key..." required autocomplete="off">
                </div>
                <button type="submit" class="btn" style="width: 100%;">
                    <i class="ph-bold ph-sign-in"></i> Masuk Panel
                </button>
            </form>
        </div>
    <?php else: ?>
        <!-- Main Dashboard Panel -->
        <div class="top-navbar">
            <div class="nav-logo">
                <div class="nav-logo-icon">
                    <i class="ph-bold ph-hospital"></i>
                </div>
                <div class="nav-title">
                    <h1>SIMPK - Digital Deployment Panel</h1>
                    <p>RS Wava Husada Kepanjen — Managed Environment Tools</p>
                </div>
            </div>
            <div class="nav-actions">
                <!-- Laravel status indicator -->
                <?php if ($laravelExtracted): ?>
                    <span class="status-pill success"><i class="ph-fill ph-check-circle"></i> Laravel Core Active</span>
                <?php else: ?>
                    <span class="status-pill danger"><i class="ph-fill ph-x-circle"></i> Laravel Core Offline</span>
                <?php endif; ?>

                <!-- DB Status Indicator -->
                <?php if ($dbStatus): ?>
                    <span class="status-pill success"><i class="ph-fill ph-database"></i> DB Connected</span>
                <?php else: ?>
                    <span class="status-pill danger"><i class="ph-fill ph-database"></i> DB Disconnected</span>
                <?php endif; ?>

                <a href="unzip.php?logout=1" class="btn-logout">
                    <i class="ph-bold ph-sign-out"></i> Keluar
                </a>
            </div>
        </div>

        <div class="main-container">
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="sidebar-menu">
                    <button class="menu-btn <?php echo $activeTab === 'extractor' ? 'active' : ''; ?>" onclick="switchTab('extractor')">
                        <i class="ph-bold ph-package"></i> Ekstraktor ZIP
                    </button>
                    <button class="menu-btn <?php echo $activeTab === 'env_editor' ? 'active' : ''; ?>" onclick="switchTab('env_editor')">
                        <i class="ph-bold ph-gear"></i> Konfigurasi .env
                    </button>
                    <button class="menu-btn <?php echo $activeTab === 'artisan' ? 'active' : ''; ?>" onclick="switchTab('artisan')">
                        <i class="ph-bold ph-terminal-window"></i> Konsol Artisan
                    </button>
                    <button class="menu-btn <?php echo $activeTab === 'diagnostic' ? 'active' : ''; ?>" onclick="switchTab('diagnostic')">
                        <i class="ph-bold ph-heartbeat"></i> Diagnostik & DB
                    </button>
                </div>

                <!-- Stats summary -->
                <div class="sidebar-stats">
                    <div class="stat-item">
                        <span class="stat-label">File ZIP Ditemukan</span>
                        <span class="stat-val">
                            <i class="ph ph-files"></i> <?php echo count($zipFiles); ?> File
                        </span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Split Parts (.00x)</span>
                        <span class="stat-val">
                            <i class="ph ph-file-zip"></i> <?php echo count($zipParts); ?> Part
                        </span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">PHP Version</span>
                        <span class="stat-val">
                            <i class="ph ph-cpu"></i> <?php echo phpversion(); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Panel Content -->
            <div class="workspace-content">
                
                <!-- Display log lines immediately if available -->
                <?php if (!empty($log)): ?>
                    <div class="global-log-container">
                        <div class="log-header">
                            <span class="log-title"><i class="ph-bold ph-terminal"></i> Log Hasil Operasi</span>
                            <button class="btn btn-sec" style="padding: 4px 10px; font-size: 10px;" onclick="clearLogs()">Clear Screen</button>
                        </div>
                        <div class="log-body" id="log-body"><?php 
                            foreach ($log as $line) {
                                $class = '';
                                if (strpos($line, '[SUCCESS]') === 0) $class = 'log-line-success';
                                elseif (strpos($line, '[ERROR]') === 0) $class = 'log-line-error';
                                elseif (strpos($line, '[INFO]') === 0 || strpos($line, '[MERGE]') === 0) $class = 'log-line-info';
                                echo "<div class=\"{$class}\">" . htmlspecialchars($line) . "</div>";
                            }
                        ?></div>
                    </div>
                <?php endif; ?>

                <!-- Tab 1: ZIP & Extractor -->
                <div id="panel-extractor" class="panel-card <?php echo $activeTab === 'extractor' ? 'active' : ''; ?>">
                    <div class="panel-title">
                        <i class="ph-bold ph-package"></i>
                        <div>
                            <h2>Ekstraktor ZIP & Penggabung Part</h2>
                        </div>
                    </div>
                    <p class="panel-description">Ekstrak file proyek yang telah Anda pecah ke InfinityFree secara otomatis. Script mendeteksi berkas ZIP individual dan berkas ZIP part.</p>

                    <!-- Split Parts Actions -->
                    <?php if (!empty($zipParts)): ?>
                        <div style="background: var(--warning-lt); border: 1px solid #fcd34d; border-radius: 8px; padding: 16px; margin-bottom: 20px; display: flex; flex-direction: column; gap: 10px;">
                            <div style="font-weight: 700; color: var(--warning); display: flex; align-items: center; gap: 8px; font-size: 13px;">
                                <i class="ph-bold ph-warning-diamond" style="font-size: 18px;"></i> Terdeteksi <?php echo count($zipParts); ?> File Zip Parts!
                            </div>
                            <p style="font-size: 11.5px; color: var(--text-muted);">Kami mendeteksi file pecahan (seperti <code>simpk_deploy.zip.001</code>). Anda harus menggabungkannya terlebih dahulu menjadi file ZIP tunggal sebelum diekstrak.</p>
                            
                            <form method="POST" style="display: flex; gap: 10px; align-items: center;">
                                <input type="hidden" name="tab" value="extractor">
                                <input type="hidden" name="action" value="merge_parts">
                                <input type="hidden" name="part_prefix" value="<?php 
                                    // Extract prefix from part name (e.g. simpk_deploy.zip.001 -> simpk_deploy.zip)
                                    echo preg_replace('/\.\d+$/', '', basename($zipParts[0])); 
                                ?>">
                                <button type="submit" class="btn btn-success">
                                    <i class="ph-bold ph-arrows-merge"></i> Satukan File Parts
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <!-- ZIP Files List and Extract All -->
                    <h3>File ZIP Ditemukan di Root:</h3>
                    <?php if (empty($zipFiles)): ?>
                        <div class="toast-alert warning" style="margin-top: 10px;">
                            <i class="ph-bold ph-info"></i> Tidak ada file .zip yang terdeteksi di root direktori. Silakan upload file `.zip` (misal <code>simpk-core.zip</code>, dll) ke direktori server.
                        </div>
                    <?php else: ?>
                        <table class="zip-table" style="margin-top: 12px;">
                            <thead>
                                <tr>
                                    <th>Nama Berkas</th>
                                    <th>Ukuran File</th>
                                    <th style="text-align: right;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($zipFiles as $zipFile): ?>
                                    <tr>
                                        <td style="font-weight: 600; color: var(--text-main);">
                                            <i class="ph-bold ph-file-zip" style="margin-right: 6px; color: var(--brand);"></i>
                                            <?php echo basename($zipFile); ?>
                                        </td>
                                        <td style="font-family: 'Fira Code', monospace; font-size: 12px;">
                                            <?php echo number_format(filesize($zipFile) / 1024 / 1024, 2); ?> MB
                                        </td>
                                        <td style="text-align: right;">
                                            <form method="POST" style="display: inline-block;">
                                                <input type="hidden" name="tab" value="extractor">
                                                <input type="hidden" name="action" value="extract_zip">
                                                <input type="hidden" name="zip_file" value="<?php echo htmlspecialchars($zipFile); ?>">
                                                
                                                <label style="font-size: 11px; display: inline-flex; align-items: center; gap: 4px; margin-right: 12px; text-transform: none; font-weight: normal; cursor: pointer;">
                                                    <input type="checkbox" name="auto_delete" value="1" checked> Hapus zip setelah selesai
                                                </label>
                                                
                                                <button type="submit" class="btn" style="padding: 6px 12px; font-size: 11px;">
                                                    <i class="ph-bold ph-folder-open"></i> Ekstrak
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Bulk Extract Action -->
                        <div style="background: var(--brand-lt); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h4 style="font-weight: 700; color: var(--brand); font-size: 13.5px;">Ekstraksi Masal (Rekomendasi!)</h4>
                                <p style="font-size: 11px; color: var(--text-muted);">Mengekstraksi seluruh file <code>.zip</code> di atas secara berurutan dalam satu kali klik.</p>
                            </div>
                            
                            <form method="POST" style="display: flex; align-items: center; gap: 14px;">
                                <input type="hidden" name="tab" value="extractor">
                                <input type="hidden" name="action" value="extract_all">
                                
                                <label style="font-size: 11.5px; display: inline-flex; align-items: center; gap: 6px; text-transform: none; font-weight: 600; cursor: pointer; color: var(--brand);">
                                    <input type="checkbox" name="auto_delete" value="1" checked> Hapus file zip setelah selesai
                                </label>
                                
                                <button type="submit" class="btn btn-success">
                                    <i class="ph-bold ph-copy-simple"></i> Ekstrak Semua File ZIP
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <!-- Cleanup Action -->
                    <div style="margin-top: 30px; border-top: 1px dashed var(--border-color); padding-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h4 style="font-weight: 700; color: var(--error);">Bersihkan File Sampah ZIP / Parts</h4>
                            <p style="font-size: 11px; color: var(--text-muted);">Menghapus file zip dan part yang tersisa untuk menghemat kuota inode/disk space InfinityFree.</p>
                        </div>
                        <form method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua file ZIP dan part di root? Tindakan ini tidak bisa dibatalkan.');">
                            <input type="hidden" name="tab" value="extractor">
                            <input type="hidden" name="action" value="cleanup">
                            <button type="submit" class="btn btn-danger">
                                <i class="ph-bold ph-trash"></i> Bersihkan Semua ZIP & Part
                            </button>
                        </form>
                    </div>

                </div>

                <!-- Tab 2: .env Editor -->
                <div id="panel-env_editor" class="panel-card <?php echo $activeTab === 'env_editor' ? 'active' : ''; ?>">
                    <div class="panel-title">
                        <i class="ph-bold ph-gear"></i>
                        <div>
                            <h2>Konfigurasi Lingkungan (.env)</h2>
                        </div>
                    </div>
                    
                    <?php if (!$envExists): ?>
                        <div class="toast-alert warning">
                            <i class="ph-bold ph-warning"></i> File <code>.env</code> belum dibuat! Anda dapat menginisialisasinya secara otomatis dengan menekan tombol simpan di bawah (akan disalin dari .env.example / .env.production).
                        </div>
                    <?php endif; ?>

                    <div class="env-tabs">
                        <button class="env-tab-btn active" id="env-btn-form" onclick="switchEnvTab('form')">Editor Form</button>
                        <button class="env-tab-btn" id="env-btn-raw" onclick="switchEnvTab('raw')">Editor Teks (Raw)</button>
                    </div>

                    <!-- Env Form Editor -->
                    <form method="POST" id="env-form-section">
                        <input type="hidden" name="tab" value="env_editor">
                        <input type="hidden" name="action" value="save_env">
                        
                        <h3 style="font-size: 12px; text-transform: uppercase; color: var(--brand); margin-bottom: 12px; border-bottom: 1px solid var(--border-color); padding-bottom: 4px;">Konfigurasi Database</h3>
                        <div class="env-editor-grid">
                            <div class="form-group">
                                <label for="DB_CONNECTION">Koneksi DB</label>
                                <input type="text" id="DB_CONNECTION" name="DB_CONNECTION" value="<?php echo htmlspecialchars(isset($parsedEnv['DB_CONNECTION']) ? $parsedEnv['DB_CONNECTION'] : 'mysql'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="DB_HOST">Host Database</label>
                                <input type="text" id="DB_HOST" name="DB_HOST" value="<?php echo htmlspecialchars(isset($parsedEnv['DB_HOST']) ? $parsedEnv['DB_HOST'] : '127.0.0.1'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="DB_PORT">Port Database</label>
                                <input type="text" id="DB_PORT" name="DB_PORT" value="<?php echo htmlspecialchars(isset($parsedEnv['DB_PORT']) ? $parsedEnv['DB_PORT'] : '3306'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="DB_DATABASE">Nama Database</label>
                                <input type="text" id="DB_DATABASE" name="DB_DATABASE" value="<?php echo htmlspecialchars(isset($parsedEnv['DB_DATABASE']) ? $parsedEnv['DB_DATABASE'] : ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="DB_USERNAME">Username DB</label>
                                <input type="text" id="DB_USERNAME" name="DB_USERNAME" value="<?php echo htmlspecialchars(isset($parsedEnv['DB_USERNAME']) ? $parsedEnv['DB_USERNAME'] : ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="DB_PASSWORD">Password DB</label>
                                <input type="text" id="DB_PASSWORD" name="DB_PASSWORD" value="<?php echo htmlspecialchars(isset($parsedEnv['DB_PASSWORD']) ? $parsedEnv['DB_PASSWORD'] : ''); ?>">
                            </div>
                        </div>

                        <h3 style="font-size: 12px; text-transform: uppercase; color: var(--brand); margin-top: 16px; margin-bottom: 12px; border-bottom: 1px solid var(--border-color); padding-bottom: 4px;">Konfigurasi Aplikasi</h3>
                        <div class="env-editor-grid">
                            <div class="form-group">
                                <label for="APP_NAME">Nama Aplikasi</label>
                                <input type="text" id="APP_NAME" name="APP_NAME" value="<?php echo htmlspecialchars(isset($parsedEnv['APP_NAME']) ? $parsedEnv['APP_NAME'] : 'Laravel'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="APP_ENV">Environment</label>
                                <select id="APP_ENV" name="APP_ENV">
                                    <option value="production" <?php echo (isset($parsedEnv['APP_ENV']) && $parsedEnv['APP_ENV'] === 'production') ? 'selected' : ''; ?>>production (Live)</option>
                                    <option value="local" <?php echo (isset($parsedEnv['APP_ENV']) && $parsedEnv['APP_ENV'] === 'local') ? 'selected' : ''; ?>>local (Development)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="APP_DEBUG">Debug Mode</label>
                                <select id="APP_DEBUG" name="APP_DEBUG">
                                    <option value="false" <?php echo (isset($parsedEnv['APP_DEBUG']) && $parsedEnv['APP_DEBUG'] === 'false') ? 'selected' : ''; ?>>false (Direkomendasikan)</option>
                                    <option value="true" <?php echo (isset($parsedEnv['APP_DEBUG']) && $parsedEnv['APP_DEBUG'] === 'true') ? 'selected' : ''; ?>>true (Tampilkan Error)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="APP_URL">URL Aplikasi</label>
                                <input type="text" id="APP_URL" name="APP_URL" value="<?php echo htmlspecialchars(isset($parsedEnv['APP_URL']) ? $parsedEnv['APP_URL'] : 'http://localhost'); ?>">
                            </div>
                        </div>

                        <div style="margin-top: 20px;">
                            <button type="submit" class="btn">
                                <i class="ph-bold ph-floppy-disk"></i> Simpan Parameter .env
                            </button>
                        </div>
                    </form>

                    <!-- Env Raw Text Editor -->
                    <form method="POST" id="env-raw-section" style="display: none;">
                        <input type="hidden" name="tab" value="env_editor">
                        <input type="hidden" name="action" value="save_env">
                        
                        <div class="form-group">
                            <label for="raw_env">Isi File .env (Raw)</label>
                            <textarea id="raw_env" name="raw_env" style="font-family: 'Fira Code', monospace; font-size: 12px; height: 350px;"><?php echo htmlspecialchars($envData); ?></textarea>
                        </div>
                        <div>
                            <button type="submit" class="btn">
                                <i class="ph-bold ph-floppy-disk"></i> Simpan Berkas .env
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tab 3: Artisan Console -->
                <div id="panel-artisan" class="panel-card <?php echo $activeTab === 'artisan' ? 'active' : ''; ?>">
                    <div class="panel-title">
                        <i class="ph-bold ph-terminal-window"></i>
                        <div>
                            <h2>Konsol Laravel Artisan</h2>
                        </div>
                    </div>
                    <p class="panel-description">Jalankan perintah Artisan in-process langsung dari browser. Bypass limitasi InfinityFree yang tidak memiliki akses SSH terminal shell.</p>

                    <?php if (!$laravelExtracted): ?>
                        <div class="toast-alert error">
                            <i class="ph-bold ph-x-circle"></i> Laravel Core belum diekstraksi atau belum lengkap! Ekstrak zip project terlebih dahulu sebelum menggunakan menu konsol Artisan.
                        </div>
                    <?php else: ?>
                        <!-- Command Shortcuts -->
                        <h3>Pilihan Cepat Command:</h3>
                        <div class="console-shortcuts">
                            <div class="shortcut-card" onclick="submitArtisan('migrate --force')">
                                <i class="ph ph-database text-green"></i>
                                <span>Run Migrate</span>
                                <p>php artisan migrate --force</p>
                            </div>
                            <div class="shortcut-card" onclick="submitArtisan('db:seed --force')">
                                <i class="ph ph-leaf text-green"></i>
                                <span>Seed Database</span>
                                <p>php artisan db:seed --force</p>
                            </div>
                            <div class="shortcut-card" onclick="submitArtisan('migrate:fresh --seed --force')">
                                <i class="ph ph-arrows-clockwise text-yellow"></i>
                                <span>Fresh Migrate & Seed</span>
                                <p>Reset, migrate ulang & seed</p>
                            </div>
                            <div class="shortcut-card" onclick="submitArtisan('storage:link')">
                                <i class="ph ph-link text-green"></i>
                                <span>Storage Link</span>
                                <p>Buat public symlink</p>
                            </div>
                            <div class="shortcut-card" onclick="submitArtisan('key:generate')">
                                <i class="ph ph-key text-green"></i>
                                <span>Key Generate</span>
                                <p>Generate APP_KEY baru</p>
                            </div>
                            <div class="shortcut-card" onclick="submitArtisan('optimize:clear')">
                                <i class="ph ph-trash text-green"></i>
                                <span>Clear Cache</span>
                                <p>Hapus seluruh cache & config</p>
                            </div>
                        </div>

                        <!-- Custom Command Form -->
                        <form method="POST" id="artisan-custom-form">
                            <input type="hidden" name="tab" value="artisan">
                            <input type="hidden" name="action" value="run_artisan">
                            
                            <div class="form-group">
                                <label for="artisan_cmd">Jalankan Perintah Kustom</label>
                                <div style="display: flex; gap: 10px;">
                                    <div style="flex-grow: 1; position: relative; display: flex; align-items: center;">
                                        <span style="position: absolute; left: 14px; font-family: 'Fira Code', monospace; font-size: 13.5px; color: var(--text-muted);">php artisan</span>
                                        <input type="text" id="artisan_cmd" name="artisan_cmd" placeholder="migrate:status" required style="padding-left: 100px; font-family: 'Fira Code', monospace;">
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="ph-bold ph-lightning"></i> Jalankan
                                    </button>
                                </div>
                                <p style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">*Catatan: Parameter interaktif (seperti konfirmasi y/n) tidak didukung. Selalu tambahkan opsi <code>--force</code> atau <code>-n</code>.</p>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>

                <!-- Tab 4: System & Diagnostics -->
                <div id="panel-diagnostic" class="panel-card <?php echo $activeTab === 'diagnostic' ? 'active' : ''; ?>">
                    <div class="panel-title">
                        <i class="ph-bold ph-heartbeat"></i>
                        <div>
                            <h2>Diagnostik Sistem & Database</h2>
                        </div>
                    </div>
                    <p class="panel-description">Lakukan pengujian terhadap server shared hosting InfinityFree untuk memastikan environment siap melayani request web SIMPK.</p>

                    <div class="diagnostics-grid">
                        
                        <!-- DB Connection Card -->
                        <div class="diag-card">
                            <h3><i class="ph-fill ph-database"></i> Status Database</h3>
                            <div class="diag-list">
                                <div class="diag-item">
                                    <span class="diag-item-lbl">Database Configured</span>
                                    <span class="diag-item-val"><?php echo $envExists ? '<span class="text-green"><i class="ph-bold ph-check"></i> Ya</span>' : '<span class="text-red"><i class="ph-bold ph-x"></i> Tidak (.env hilang)</span>'; ?></span>
                                </div>
                                <div class="diag-item">
                                    <span class="diag-item-lbl">DB Connection</span>
                                    <span class="diag-item-val"><?php echo $dbStatus ? '<span class="text-green"><i class="ph-bold ph-check-circle"></i> Connected</span>' : '<span class="text-red"><i class="ph-bold ph-warning"></i> Failed</span>'; ?></span>
                                </div>
                                <div style="margin-top: 14px; font-size: 11px; color: var(--text-muted); border-top: 1px solid var(--border-color); padding-top: 10px; word-break: break-all;">
                                    <strong>Status Log:</strong><br>
                                    <?php echo !empty($dbMessage) ? htmlspecialchars($dbMessage) : 'Koneksi belum diuji.'; ?>
                                </div>
                                
                                <form method="POST" style="margin-top: 14px;">
                                    <input type="hidden" name="tab" value="diagnostic">
                                    <input type="hidden" name="action" value="test_db">
                                    <button type="submit" class="btn btn-sec" style="width: 100%; padding: 6px 12px; font-size: 11.5px;">
                                        <i class="ph ph-plugs"></i> Uji Koneksi DB Ulang
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- System Specs Card -->
                        <div class="diag-card">
                            <h3><i class="ph-fill ph-cpu"></i> Informasi PHP</h3>
                            <div class="diag-list">
                                <div class="diag-item">
                                    <span class="diag-item-lbl">PHP Version</span>
                                    <span class="diag-item-val"><?php echo phpversion(); ?></span>
                                </div>
                                <div class="diag-item">
                                    <span class="diag-item-lbl">Memory Limit</span>
                                    <span class="diag-item-val"><?php echo ini_get('memory_limit'); ?></span>
                                </div>
                                <div class="diag-item">
                                    <span class="diag-item-lbl">Max Execution Time</span>
                                    <span class="diag-item-val"><?php echo ini_get('max_execution_time'); ?> detik</span>
                                </div>
                                <div class="diag-item">
                                    <span class="diag-item-lbl">Upload Max Size</span>
                                    <span class="diag-item-val"><?php echo ini_get('upload_max_filesize'); ?></span>
                                </div>
                                <div class="diag-item">
                                    <span class="diag-item-lbl">ZipArchive Support</span>
                                    <span class="diag-item-val"><?php echo class_exists('ZipArchive') ? '<span class="text-green">Aktif</span>' : '<span class="text-red">Tidak Aktif</span>'; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Directory Permissions Card -->
                    <div class="diag-card" style="margin-bottom: 20px;">
                        <h3><i class="ph-fill ph-folder"></i> Izin Direktori Laravel</h3>
                        <p style="font-size: 11px; color: var(--text-muted); margin-bottom: 12px;">Untuk kelancaran Laravel, folder storage dan bootstrap cache harus berstatus writeable (dapat ditulis).</p>
                        
                        <div class="diag-list">
                            <?php
                            $pathsToCheck = [
                                'bootstrap/cache' => 'bootstrap/cache',
                                'storage' => 'storage',
                                'storage/framework/views' => 'storage/framework/views',
                                'storage/logs' => 'storage/logs'
                            ];
                            foreach ($pathsToCheck as $label => $relPath):
                                $fullPath = $targetDir . '/' . $relPath;
                                $exists = file_exists($fullPath);
                                $writeable = $exists && is_writable($fullPath);
                            ?>
                                <div class="diag-item" style="border-bottom: 1px solid rgba(0,0,0,0.02); padding-bottom: 6px;">
                                    <span class="diag-item-lbl"><code><?php echo $label; ?></code></span>
                                    <span class="diag-item-val">
                                        <?php if (!$exists): ?>
                                            <span class="text-yellow"><i class="ph ph-warning"></i> Folder Hilang</span>
                                        <?php elseif ($writeable): ?>
                                            <span class="text-green"><i class="ph ph-check"></i> Writeable (0777)</span>
                                        <?php else: ?>
                                            <span class="text-red"><i class="ph ph-x"></i> Read Only (Gagal Tulis)</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <form method="POST" style="margin-top: 16px;">
                            <input type="hidden" name="tab" value="diagnostic">
                            <input type="hidden" name="action" value="fix_folders">
                            <button type="submit" class="btn btn-success" style="width: 100%;">
                                <i class="ph-bold ph-shield-check"></i> Inisialisasi & Atur Izin Folder (0777)
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>

        <footer>
            &copy; 2026 RS Wava Husada Kepanjen. Sistem Informasi Medis Penyebab Kematian (SIMPK - Digital).
        </footer>

        <!-- Navigation Tab Switch JavaScript -->
        <script>
            function switchTab(tabId) {
                // Remove active classes
                document.querySelectorAll('.panel-card').forEach(card => card.classList.remove('active'));
                document.querySelectorAll('.menu-btn').forEach(btn => btn.classList.remove('active'));

                // Add active classes
                document.getElementById('panel-' + tabId).classList.add('active');
                
                // Highlight button
                event.currentTarget.classList.add('active');
            }

            function switchEnvTab(type) {
                document.querySelectorAll('.env-tab-btn').forEach(btn => btn.classList.remove('active'));
                if (type === 'form') {
                    document.getElementById('env-btn-form').classList.add('active');
                    document.getElementById('env-form-section').style.display = 'block';
                    document.getElementById('env-raw-section').style.display = 'none';
                } else {
                    document.getElementById('env-btn-raw').classList.add('active');
                    document.getElementById('env-form-section').style.display = 'none';
                    document.getElementById('env-raw-section').style.display = 'block';
                }
            }

            function submitArtisan(cmd) {
                if (confirm('Jalankan "php artisan ' + cmd + '"?')) {
                    document.getElementById('artisan_cmd').value = cmd;
                    document.getElementById('artisan-custom-form').submit();
                }
            }

            function clearLogs() {
                const body = document.getElementById('log-body');
                if (body) {
                    body.innerHTML = '<div style="color: var(--text-muted);">[Log cleared]</div>';
                }
            }

            // Scroll log to bottom automatically
            window.onload = function() {
                const logBody = document.getElementById('log-body');
                if (logBody) {
                    logBody.scrollTop = logBody.scrollHeight;
                }
            };
        </script>
    <?php endif; ?>
</body>
</html>
