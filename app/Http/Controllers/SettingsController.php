<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class SettingsController extends Controller
{
    private $settingKey = "F-if@fFxDj";

    public function index()
    {
        if (session('settings_authenticated') !== true) {
            return view('simpk.settings-auth');
        }

        $logs = ActivityLog::orderBy('created_at', 'desc')->paginate(50);
        return view('simpk.settings', compact('logs'));
    }

    public function authenticate(Request $request)
    {
        $key = $request->input('key');

        if ($key === $this->settingKey) {
            session(['settings_authenticated' => true]);
            ActivityLog::log('Login Settings', 'Berhasil mengakses halaman pengaturan menggunakan key.');
            return redirect()->route('settings.index');
        }

        ActivityLog::log('Gagal Login Settings', 'Percobaan akses pengaturan dengan key salah: ' . $key);
        return back()->with('error', 'Key yang Anda masukkan salah!');
    }

    public function logout()
    {
        session()->forget('settings_authenticated');
        return redirect('/');
    }

    public function resetApplication(Request $request)
    {
        if (session('settings_authenticated') !== true) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            // Truncate all main data tables
            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            
            \App\Models\Certificate::truncate();
            \App\Models\Patient::truncate();
            \App\Models\Doctor::truncate();
            \App\Models\ActivityLog::truncate();
            
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

            // Clear any local storage/cache hints if necessary (handled by frontend usually, 
            // but we can clear server-side session here if needed)
            session()->forget('settings_authenticated');

            return response()->json([
                'success' => true,
                'message' => 'Seluruh data aplikasi telah berhasil direset ke kondisi awal.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset data: ' . $e->getMessage()
            ], 500);
        }
    }
}
