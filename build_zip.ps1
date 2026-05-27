# build_zip.ps1
# Script to build a zip file of the project using tar for fast, reliable compression.

$destination = "simpk-digital.zip"

if (Test-Path $destination) {
    Remove-Item $destination
}

Write-Output "Creating zip archive with tar..."

# Run tar to compress explicitly the required files/directories
tar -a -c -f $destination --exclude="node_modules" --exclude=".git" app bootstrap config database public resources routes storage tests vendor artisan composer.json composer.lock package.json package-lock.json vite.config.js .env .env.example .htaccess unzip.php

if (Test-Path $destination) {
    $size = (Get-Item $destination).Length
    $sizeMb = [Math]::Round($size / 1MB, 2)
    Write-Output "Zip file created successfully: $destination ($sizeMb MB)"
} else {
    Write-Error "Failed to create ZIP archive."
}
