# build_split_zip.ps1
# Script to split the project into multiple smaller ZIP files for easier upload.

$zipFiles = @("simpk-core.zip", "simpk-vendor-framework.zip", "simpk-vendor-filament.zip", "simpk-vendor-others.zip")

foreach ($file in $zipFiles) {
    if (Test-Path $file) { Remove-Item $file }
}

Write-Output "Creating split zip archives..."

# 1. Core Source Code (Smallest but most important)
Write-Output "Creating simpk-core.zip..."
tar -a -c -f simpk-core.zip --exclude="node_modules" --exclude=".git" --exclude="vendor" app bootstrap config database public resources routes storage tests artisan composer.json composer.lock package.json package-lock.json vite.config.js .env .env.example .htaccess unzip.php

# 2. Vendor Framework (Laravel & Core Deps)
Write-Output "Creating simpk-vendor-framework.zip..."
tar -a -c -f simpk-vendor-framework.zip vendor/laravel vendor/composer vendor/livewire vendor/monolog vendor/nesbot vendor/psr vendor/symfony

# 3. Vendor Filament & Spatie (Large part)
Write-Output "Creating simpk-vendor-filament.zip..."
tar -a -c -f simpk-vendor-filament.zip vendor/filament vendor/spatie vendor/league vendor/doctrine vendor/fruitcake vendor/guzzlehttp vendor/ramsey vendor/brick vendor/nette

# 4. Vendor Others (Remaining dependencies - Dynamically calculated)
Write-Output "Calculating remaining vendor dependencies for simpk-vendor-others.zip..."
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

Write-Output "Creating simpk-vendor-others.zip with $( $others.Count ) packages..."
tar -a -c -f simpk-vendor-others.zip $others

Write-Output "`nSummary of created ZIP files:"
foreach ($file in $zipFiles) {
    if (Test-Path $file) {
        $size = (Get-Item $file).Length
        $sizeMb = [Math]::Round($size / 1MB, 2)
        Write-Output "- $file ($sizeMb MB)"
    }
}

