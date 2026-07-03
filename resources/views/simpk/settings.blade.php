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
            
            <!-- MANAJEMEN DOKTER -->
            <div class="sticky top-[87px] z-30 bg-slate-50/95 backdrop-blur-sm py-4 flex justify-between items-end">
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Direktori Dokter</h1>
                    <p class="text-[11px] text-slate-500">Kelola profil tenaga medis untuk pengesahan sertifikat.</p>
                </div>
                <div class="text-[10px] text-slate-400 font-mono">
                    Total: {{ $doctors->count() }} Dokter
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-10">
                <!-- Form Tambah Dokter -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-lg border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] p-4">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-7 h-7 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i class="ph-bold ph-stethoscope text-sm"></i>
                            </div>
                            <h3 class="text-xs font-bold text-slate-800">Tambah Dokter</h3>
                        </div>
                        
                        <form action="{{ route('doctors.store') }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Nama Lengkap & Gelar</label>
                                <input type="text" name="nama_dokter" required class="w-full bg-slate-50/50 border border-slate-200 rounded-md px-2.5 py-1.5 text-xs focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none" placeholder="">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Nomor SIP (Opsional)</label>
                                <input type="text" name="nomor_sip" class="w-full bg-slate-50/50 border border-slate-200 rounded-md px-2.5 py-1.5 text-xs focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none font-mono" placeholder="">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Spesialisasi / Unit (Opsional)</label>
                                <input type="text" name="spesialisasi" class="w-full bg-slate-50/50 border border-slate-200 rounded-md px-2.5 py-1.5 text-xs focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all outline-none" placeholder="">
                            </div>
                            <div class="pt-1.5">
                                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-[10px] py-2 rounded-md transition-all shadow-sm hover:shadow-md flex justify-center items-center gap-1.5 uppercase tracking-wide">
                                    <i class="ph-bold ph-plus"></i>
                                    Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data Dokter -->
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-lg border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] overflow-hidden">
                        <div class="overflow-x-auto overflow-y-auto max-h-[300px] relative custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <thead class="sticky top-0 z-20 bg-white/90 backdrop-blur shadow-sm">
                                    <tr class="text-[8px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                        <th class="px-4 py-3">Profil Dokter</th>
                                        <th class="px-4 py-3 w-[150px]">Legalitas</th>
                                        <th class="px-4 py-3 w-[70px] text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($doctors as $doctor)
                                        <tr class="hover:bg-slate-50/50 transition-colors group">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 border border-slate-200">
                                                        <i class="ph-fill ph-user text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-bold text-slate-800">{{ $doctor->nama_dokter }}</p>
                                                        @if($doctor->spesialisasi)
                                                            <p class="text-[9px] font-medium text-slate-500 mt-0.5 flex items-center gap-1">
                                                                <i class="ph-fill ph-medal text-emerald-500 text-[10px]"></i> {{ $doctor->spesialisasi }}
                                                            </p>
                                                        @else
                                                            <p class="text-[9px] text-slate-400 mt-0.5">Dokter Umum / Belum diatur</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-col">
                                                    <span class="text-[7px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">No. SIP</span>
                                                    @if($doctor->nomor_sip)
                                                        <span class="text-[10px] font-mono font-medium text-slate-700 bg-slate-50 px-1.5 py-0.5 rounded inline-block w-max border border-slate-100">{{ $doctor->nomor_sip }}</span>
                                                    @else
                                                        <span class="text-[9px] text-slate-400 italic">Belum diatur</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" onsubmit="return confirm('Hapus dokter ini dari direktori?');" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-6 h-6 rounded-full text-red-400 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition-all mx-auto" title="Hapus">
                                                        <i class="ph-bold ph-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-10 text-center">
                                                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-2 text-slate-300">
                                                    <i class="ph-fill ph-users text-2xl"></i>
                                                </div>
                                                <p class="text-xs font-bold text-slate-700">Belum Ada Data</p>
                                                <p class="text-[10px] text-slate-400 mt-1 max-w-[200px] mx-auto leading-relaxed">Tambahkan dokter dari form di samping.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LOG AKTIVITAS -->
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
                                <th class="px-4 py-3 w-[160px]">IP Address</th>
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
                                    <td class="px-4 py-3 whitespace-nowrap w-[160px]">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[9px] font-mono text-slate-400">{{ $log->ip_address }}</span>
                                            @if(isset($ipMap[$log->ip_address]))
                                                <span class="bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-[3px] text-[8px] font-bold tracking-tight">{{ $ipMap[$log->ip_address] }}</span>
                                            @endif
                                        </div>
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

            <!-- MANAJEMEN IP TRACKING -->
            <div class="sticky top-[87px] z-30 bg-slate-50/95 backdrop-blur-sm py-4 mt-8 flex justify-between items-end">
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Pelacakan IP Address</h1>
                    <p class="text-[11px] text-slate-500">Berikan nama pada IP address untuk memudahkan identifikasi perangkat.</p>
                </div>
                <div class="text-[10px] text-slate-400 font-mono">
                    Total: {{ $ipTrackers->count() }} IP
                </div>
            </div>

            <div class="bg-white rounded-lg border border-slate-200 shadow-sm">
                <div class="overflow-x-auto overflow-y-auto max-h-[300px] relative">
                    <table class="w-full text-left border-collapse table-fixed min-w-[500px]">
                        <thead class="sticky top-0 z-20 bg-slate-50 shadow-sm">
                            <tr class="text-[9px] font-bold text-slate-500 uppercase tracking-tighter border-b border-slate-200">
                                <th class="px-4 py-3 w-[150px]">IP Address</th>
                                <th class="px-4 py-3">Nama Perangkat / Pemilik</th>
                                <th class="px-4 py-3 w-[150px]">Tercatat Pada</th>
                                <th class="px-4 py-3 w-[100px] text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($ipTrackers as $ip)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="text-[10px] font-mono text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">{{ $ip->ip_address }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <form action="{{ route('settings.ip.update', $ip->id) }}" method="POST" class="flex gap-2 items-center w-full max-w-sm">
                                            @csrf
                                            <input type="text" name="name" value="{{ $ip->name }}" placeholder="Beri nama untuk IP ini" class="w-full border border-slate-300 rounded px-2 py-1 text-xs focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="text-[10px] text-slate-500">{{ $ip->created_at->format('d/m/y H:i:s') }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                            <button type="submit" class="bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white px-2 py-1 rounded text-[10px] font-bold transition-colors border border-emerald-200">
                                                Simpan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                        <i class="ph-bold ph-globe text-3xl mb-2"></i>
                                        <p class="text-xs">Belum ada IP terekam</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast("{{ session('success') }}", 'success');
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast("{{ session('error') }}", 'error');
        });
    </script>
    @endif

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