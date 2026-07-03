<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $notification->judul }} - Notifikasi</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/RS-Wava-Husada.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet"/>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { font-family: 'IBM Plex Sans', sans-serif; background: #eef2f5; color: #0c1924; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center py-12 px-4">

<div class="w-full max-w-2xl bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-[#0f6e72] px-6 py-4 flex items-center justify-between">
        <div class="text-white font-bold tracking-wide flex items-center gap-2">
            <i class="ph-bold ph-bell-ringing"></i> Pemberitahuan Sistem
        </div>
        <a href="javascript:history.back()" class="text-[#e8f7f7] hover:text-white text-sm font-semibold transition-colors flex items-center gap-1">
            <i class="ph-bold ph-x"></i> Tutup
        </a>
    </div>

    <!-- Content -->
    <div class="p-8">
        <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-1.5">
            <i class="ph-bold ph-calendar-blank"></i> {{ $notification->created_at->format('d M Y, H:i') }}
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-6 leading-tight">{{ $notification->judul }}</h1>
        
        <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed whitespace-pre-wrap">{{ $notification->keterangan }}</div>
    </div>
    
    <!-- Footer -->
    <div class="bg-gray-50 px-8 py-4 border-t border-gray-100 flex justify-between items-center">
        <div class="text-xs text-gray-400 font-medium">Sistem Informasi Medis Penyebab Kematian Digital</div>
        <button onclick="window.close(); history.back();" class="text-sm font-bold text-[#0f6e72] hover:underline">Kembali</button>
    </div>
</div>

</body>
</html>
