<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('logs:prune {days=30}', function ($days) {
    $count = \App\Models\ActivityLog::where('created_at', '<', now()->subDays($days))->delete();
    $this->info("Berhasil menghapus {$count} log aktivitas yang lebih tua dari {$days} hari.");
})->purpose('Prune activity logs older than specified days');
