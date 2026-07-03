# build_split_zip.ps1
# Script to split the project into multiple smaller ZIP files for easier upload.
# Target: InfinityFree shared hosting (max ~10MB per upload)

$zipFiles = @("simpk-core.zip", "simpk-vendor-framework.zip", "simpk-vendor-filament.zip", "simpk-vendor-others.zip")

foreach ($file in $zipFiles) {
    if (Test-Path $file) { Remove-Item $file }
}

Write-Output "=============================================="
Write-Output "  SIMPK-Digital Deployment Package Builder"
Write-Output "  Target: InfinityFree Shared Hosting"
Write-Output "=============================================="
Write-Output ""

# 1. Core Source Code (Smallest but most important)
Write-Output "[1/4] Creating simpk-core.zip (Source + Build Assets)..."
tar -a -c -f simpk-core.zip --exclude="node_modules" --exclude=".git" --exclude="vendor" --exclude="*.zip" --exclude=".tmp_*" --exclude="database/database.sqlite" app bootstrap config database public resources routes storage tests artisan composer.json composer.lock package.json package-lock.json vite.config.js .env.production .env.example .htaccess unzip.php clean.php

# 2. Vendor Framework (Laravel & Core Deps)
Write-Output "[2/4] Creating simpk-vendor-framework.zip (Laravel Core)..."
tar -a -c -f simpk-vendor-framework.zip vendor/laravel vendor/composer vendor/livewire vendor/monolog vendor/nesbot vendor/psr vendor/symfony

# 3. Vendor Filament & Spatie (Large part)
Write-Output "[3/4] Creating simpk-vendor-filament.zip (Filament + Spatie)..."
tar -a -c -f simpk-vendor-filament.zip vendor/filament vendor/spatie vendor/league vendor/doctrine vendor/fruitcake vendor/guzzlehttp vendor/ramsey vendor/brick vendor/nette

# 4. Vendor Others (Remaining dependencies - Dynamically calculated)
Write-Output "[4/4] Calculating remaining vendor dependencies for simpk-vendor-others.zip..."
$frameworkDirs = @("laravel", "composer", "livewire", "monolog", "nesbot", "psr", "symfony")
$filamentDirs = @("filament", "spatie", "league", "doctrine", "fruitcake", "guzzlehttp", "ramsey", "brick", "nette")

$others = @()
$allVendorItems = Get-ChildItem -Path vendor
foreach ($item in $allVendorItems) {
    $name = $item.Name
    if ($frameworkDirs -notcontains $name -and $filamentDirs -notcontains $name) {
        $others += "vendor/$name"
    }
}

Write-Output "  -> Packaging $( $others.Count ) remaining vendor packages..."
tar -a -c -f simpk-vendor-others.zip $others

Write-Output ""
Write-Output "=============================================="
Write-Output "  Summary of Created ZIP Files:"
Write-Output "=============================================="
$totalSize = 0
foreach ($file in $zipFiles) {
    if (Test-Path $file) {
        $size = (Get-Item $file).Length
        $sizeMb = [Math]::Round($size / 1MB, 2)
        $totalSize += $size
        Write-Output "  [OK] $file ($sizeMb MB)"
    } else {
        Write-Output "  [FAIL] $file - NOT CREATED!"
    }
}
$totalMb = [Math]::Round($totalSize / 1MB, 2)
Write-Output ""
Write-Output "  Total Package Size: $totalMb MB"
Write-Output "=============================================="
Write-Output ""
Write-Output "NEXT STEPS:"
Write-Output "  1. Upload clean.php ke htdocs via File Manager InfinityFree"
Write-Output "  2. Buka clean.php di browser untuk wipe folder lama"
Write-Output "  3. Upload 4 file ZIP di atas ke htdocs"
Write-Output "  4. Upload unzip.php ke htdocs"
Write-Output "  5. Buka unzip.php, Extract All, lalu konfigurasi .env"
Write-Output "  6. Jalankan artisan migrate:fresh via console panel"
Write-Output ""
