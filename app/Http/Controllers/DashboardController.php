<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;

class DashboardController extends Controller
{
    public function index()
    {
        $totalIssued = Certificate::whereIn('status', ['Printed', 'Saved'])->count();
        $thisMonth = Certificate::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();
        $pendingVerif = Certificate::where('status', 'Draft')->count();
        $doaDeaths = Certificate::where(function ($query) {
            $query->where('data->doa', 'Ya')
                  ->orWhere('data->doa_bayi', 'Ya');
        })->count();
        
        $recentCertificates = Certificate::with(['patient', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('dashboard', compact('totalIssued', 'thisMonth', 'pendingVerif', 'doaDeaths', 'recentCertificates'));
    }
}
