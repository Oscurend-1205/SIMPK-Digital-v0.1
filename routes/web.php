<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DoctorController;

Route::get('/', [DashboardController::class, 'index']);

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    if ($request->username === 'admin123' && $request->password === 'admin123') {
        // Redirect ke halaman utama untuk sementara
        return redirect('/');
    }
    return back()->with('error', 'Nama Pengguna atau Kata Sandi salah!');
})->name('login.post');

Route::get('/form', function () {
    return view('simpk.pilih-sertifikat');
});

Route::get('/form/kematian-dewasa', function () {
    return view('form.kematian-dewasa');
})->name('form.dewasa');

Route::get('/form/kematian-bayi', function () {
    return view('form.kematian-bayi');
})->name('form.bayi');

Route::get('/form/edit/{id}', [CertificateController::class, 'edit'])->name('form.edit');

Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
Route::get('/drafts', [CertificateController::class, 'drafts']);
Route::get('/output/dewasa/{id}', [CertificateController::class, 'showOutputDewasa'])->name('output.dewasa');
Route::get('/output/bayi/{id}', [CertificateController::class, 'showOutputBayi'])->name('output.bayi');
Route::post('/api/drafts/save', [CertificateController::class, 'saveDraft']);
Route::delete('/api/drafts/{id}', [CertificateController::class, 'deleteDraft']);

// Certificate CRUD API
Route::get('/api/certificates/{id}', [CertificateController::class, 'show']);
Route::post('/api/certificates/{id}/save', [CertificateController::class, 'saveCertificate']);
Route::patch('/api/certificates/{id}/status', [CertificateController::class, 'updateStatus']);
Route::delete('/api/certificates/{id}', [CertificateController::class, 'destroy']);

// Settings
Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings/auth', [SettingsController::class, 'authenticate'])->name('settings.auth');
Route::post('/settings/logout', [SettingsController::class, 'logout'])->name('settings.logout');
Route::post('/settings/reset-app', [SettingsController::class, 'resetApplication'])->name('settings.reset');

// Doctors
Route::get('/api/doctors/search', [DoctorController::class, 'search']);
Route::post('/settings/doctors', [DoctorController::class, 'store'])->name('doctors.store');
Route::delete('/settings/doctors/{id}', [DoctorController::class, 'destroy'])->name('doctors.destroy');

// IP Tracking
Route::post('/settings/ips/{id}', [SettingsController::class, 'updateIpName'])->name('settings.ip.update');

// Notifications / Update Now (CRUD)
Route::get('/update-now', [NotificationController::class, 'index']);
Route::post('/update-now', [NotificationController::class, 'store']);
Route::delete('/update-now/{id}', [NotificationController::class, 'destroy']);
Route::get('/api/notifications', [NotificationController::class, 'apiGetNotifications']);
Route::get('/notifications/{id}', [NotificationController::class, 'show']);
