<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - RS WAVA HUSADA</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/RS-Wava-Husada.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="flex min-h-screen bg-slate-50 text-slate-900">
    @include('partials.sidebar', ['activePage' => 'settings'])

    <div class="main-wrap">
        @include('partials.watermark')
        
        <header class="bg-white px-8 py-3 flex justify-between items-center border-b border-slate-200 sticky top-[48px] z-40 shrink-0 shadow-sm">
            <div>
                <p class="text-[9px] text-slate-400 font-bold tracking-widest uppercase">Panel Pengaturan</p>
                <h2 class="text-sm font-bold text-slate-700">Manajemen Log & Sistem</h2>
            </div>
            <div class="flex items-center space-x-3">
                <button type="button" onclick="showResetModal()" class="flex items-center gap-2 px-3 py-1.5 text-[11px] font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-md hover:bg-amber-100 transition-colors">
                    <i class="ph-bold ph-arrow-counter-clockwise"></i>
                    Reset Aplikasi
                </button>
                <form action="{{ route('settings.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-3 py-1.5 text-[11px] font-bold text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 transition-colors">
                        <i class="ph-bold ph-sign-out"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </header>

        <div class="p-6 max-w-7xl mx-auto w-full flex-grow flex flex-col">
            
            <div class="sticky top-[87px] z-30 bg-slate-50/95 backdrop-blur-sm py-4 flex justify-between items-end">
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Log Aktivitas</h1>
                    <p class="text-[11px] text-slate-500">Rekaman aktivitas sistem secara real-time.</p>
                </div>
                <div class="text-[10px] text-slate-400 font-mono">
                    Total: {{ $logs->total() }} entries
                </div>
            </div>

            <div class="bg-white rounded-lg border border-slate-200 shadow-sm">
                <div class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-280px)] relative">
                    <table class="w-full text-left border-collapse table-fixed min-w-[600px]">
                        <thead class="sticky top-0 z-20 bg-slate-50 shadow-sm">
                            <tr class="text-[9px] font-bold text-slate-500 uppercase tracking-tighter border-b border-slate-200">
                                <th class="px-4 py-3 w-[140px]">Waktu</th>
                                <th class="px-4 py-3 w-[120px]">Aktivitas</th>
                                <th class="px-4 py-3">Keterangan</th>
                                <th class="px-4 py-3 w-[110px]">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($logs as $log)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap w-[140px]">
                                        <span class="text-[10px] font-medium text-slate-600">{{ $log->created_at->format('d/m/y H:i:s') }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap w-[120px]">
                                        <span class="px-1.5 py-0.5 rounded-[3px] text-[8px] font-black uppercase tracking-tighter
                                            {{ str_contains($log->activity, 'Login') ? 'bg-blue-100 text-blue-700' : 
                                                (str_contains($log->activity, 'Hapus') ? 'bg-red-100 text-red-700' : 
                                                (str_contains($log->activity, 'Simpan') ? 'bg-green-100 text-green-700' : 
                                                (str_contains($log->activity, 'Navigasi') ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-700'))) }}">
                                            {{ $log->activity }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-[10px] text-slate-600 truncate hover:whitespace-normal transition-all" title="{{ $log->description }}">
                                            {{ $log->description ?? '-' }}
                                        </p>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap w-[110px]">
                                        <span class="text-[9px] font-mono text-slate-400">{{ $log->ip_address }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center opacity-40">
                                            <i class="ph ph-scroll text-5xl mb-2"></i>
                                            <p class="text-sm font-bold">Belum ada aktivitas tercatat</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="border-t border-slate-200 px-4 py-3 bg-slate-50/50">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="reset-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="hideResetModal()"></div>
        <div class="relative bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform transition-all p-8 text-center">
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="ph-bold ph-warning-octagon text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Reset Seluruh Data?</h3>
            <p class="text-slate-600 mb-8 text-sm leading-relaxed">
                Tindakan ini akan <strong>menghapus permanen</strong> seluruh sertifikat, data pasien, dokter, dan riwayat aktivitas. Aplikasi akan kembali ke kondisi kosong seperti baru diinstal. Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex flex-col gap-3">
                <button onclick="executeReset()" id="reset-confirm-btn" class="w-full py-3 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i class="ph-bold ph-trash"></i>
                    Ya, Reset Sekarang
                </button>
                <button onclick="hideResetModal()" class="w-full py-3 bg-slate-100 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-200 transition-all active:scale-95">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <div id="toast-wrap" class="fixed bottom-8 right-8 z-[110] flex flex-col gap-3"></div>

    <script>
        function showResetModal() {
            document.getElementById('reset-modal').classList.remove('hidden');
            document.getElementById('reset-modal').classList.add('flex');
        }

        function hideResetModal() {
            document.getElementById('reset-modal').classList.add('hidden');
            document.getElementById('reset-modal').classList.remove('flex');
        }

        function showToast(msg, type='success') {
            const wrap = document.getElementById('toast-wrap');
            const t = document.createElement('div');
            t.className = `px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 transform transition-all duration-300 text-white font-bold text-sm ${type === 'error' ? 'bg-red-600' : 'bg-emerald-600'}`;
            t.innerHTML = `<i class="ph-bold ${type==='error'?'ph-x-circle':'ph-check-circle'} text-lg"></i> ${msg}`;
            wrap.appendChild(t);
            setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3000);
        }

        function executeReset() {
            const btn = document.getElementById('reset-confirm-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="ph-bold ph-circle-notch animate-spin"></i> Memproses...';

            fetch("{{ route('settings.reset') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    localStorage.clear();
                    sessionStorage.clear();
                    
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 2000);
                } else {
                    showToast(data.message || 'Terjadi kesalahan', 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ph-bold ph-trash"></i> Ya, Reset Sekarang';
                }
            })
            .catch(err => {
                showToast('Gagal menghubungi server', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="ph-bold ph-trash"></i> Ya, Reset Sekarang';
            });
        }
    </script>
</body>
</html>