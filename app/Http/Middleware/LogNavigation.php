<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\ActivityLog;

class LogNavigation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya log request GET (navigasi halaman) dan bukan request AJAX/API internal jika perlu
        // Namun user minta "tanpa terkecuali", jadi kita log navigasi utama
        if ($request->isMethod('GET') && !$request->routeIs('up')) {
            $url = $request->fullUrl();
            $path = $request->path();
            
            // Abaikan file statis atau debug bar jika ada
            if (!str_contains($path, 'api/') && !str_contains($path, '_debugbar')) {
                $activity = 'Navigasi Halaman';
                $description = "Pengguna membuka halaman: " . $url;

                // Detail lebih lanjut berdasarkan path
                if ($path === '/') $description = "Membuka Dashboard Utama";
                elseif ($path === 'form') $description = "Membuka halaman Pilih Jenis Sertifikat";
                elseif (str_contains($path, 'form/kematian-dewasa')) $description = "Membuka Form Kematian Dewasa (Baru)";
                elseif (str_contains($path, 'form/kematian-bayi')) $description = "Membuka Form Kematian Bayi (Baru)";
                elseif (str_contains($path, 'form/edit/')) $description = "Membuka Form Edit Sertifikat (ID: " . basename($path) . ")";
                elseif (str_contains($path, 'output/dewasa/')) $description = "Melihat Sertifikat Dewasa (ID: " . basename($path) . ")";
                elseif (str_contains($path, 'output/bayi/')) $description = "Melihat Sertifikat Bayi (ID: " . basename($path) . ")";
                elseif ($path === 'drafts') $description = "Membuka Daftar Draft Sertifikat";
                elseif ($path === 'settings') $description = "Mengakses Halaman Log Aktivitas/Pengaturan";

                ActivityLog::log($activity, $description);
            }
        }

        return $response;
    }
}
