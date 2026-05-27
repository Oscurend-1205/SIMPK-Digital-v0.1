<?php

$sourceDir = __DIR__;
$zipFileName = 'simpk_deploy.zip';
$chunkSize = 9 * 1024 * 1024; // 9MB chunks

$excludes = [
    '.git',
    'node_modules',
    'prepare_deploy.php',
    'unzip.php',
    $zipFileName
];

echo "Mulai membuat zip archive...\n";

$zip = new ZipArchive();
if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Gagal membuat zip archive\n");
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::LEAVES_ONLY
);

$fileCount = 0;
foreach ($iterator as $file) {
    $filePath = $file->getRealPath();
    $relativePath = str_replace('\\', '/', substr($filePath, strlen($sourceDir) + 1));
    
    $skip = false;
    foreach ($excludes as $exclude) {
        if (strpos($relativePath, $exclude) === 0 || strpos($relativePath, $exclude . '/') === 0) {
            $skip = true;
            break;
        }
    }
    
    if (!$skip) {
        $zip->addFile($filePath, $relativePath);
        $fileCount++;
    }
}

$zip->close();
echo "Berhasil membuat $zipFileName dengan $fileCount file.\n";

echo "Mulai memecah file zip...\n";

// Split the zip file
$handle = fopen($zipFileName, 'rb');
$part = 1;
while (!feof($handle)) {
    $buffer = fread($handle, $chunkSize);
    if ($buffer === false || strlen($buffer) === 0) break;
    $partFileName = sprintf("%s.%03d", $zipFileName, $part);
    file_put_contents($partFileName, $buffer);
    echo "Dibuat: $partFileName (" . number_format(strlen($buffer) / 1024 / 1024, 2) . " MB)\n";
    $part++;
}
fclose($handle);

// Delete original zip to save space
unlink($zipFileName);
echo "File zip asli dihapus.\n";
echo "Proses selesai! File part siap diupload.\n";
