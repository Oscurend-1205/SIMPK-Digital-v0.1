<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Kelola Notifikasi (Update Now)</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/RS-Wava-Husada.png') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet"/>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { font-family: 'IBM Plex Sans', sans-serif; background: #eef2f5; color: #0c1924; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center py-10 px-4">

<div class="w-full max-w-4xl bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Manajemen Notifikasi & Update</h1>
            <p class="text-xs text-gray-500 mt-1">Halaman rahasia tanpa login untuk mengelola notifikasi sistem.</p>
        </div>
        <a href="/" class="text-sm font-semibold text-[#0f6e72] hover:underline flex items-center gap-1"><i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6 text-sm flex items-center gap-2">
            <i class="ph-bold ph-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Form Tambah -->
    <div class="mb-8 bg-gray-50 p-5 rounded border border-gray-200">
        <h2 class="text-sm font-bold mb-4 uppercase tracking-wider text-gray-600"><i class="ph-bold ph-plus"></i> Tambah Notifikasi Baru</h2>
        <form action="/update-now" method="POST" class="flex flex-col gap-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Judul Notifikasi</label>
                <input type="text" name="judul" required class="w-full text-sm border-gray-300 rounded px-3 py-2 focus:ring-[#0f6e72] focus:border-[#0f6e72]">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan / Isi Pesan</label>
                <textarea name="keterangan" rows="3" required class="w-full text-sm border-gray-300 rounded px-3 py-2 focus:ring-[#0f6e72] focus:border-[#0f6e72]"></textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-[#0f6e72] hover:bg-[#0a5558] text-white text-sm font-bold py-2 px-6 rounded transition-colors">
                    Simpan Notifikasi
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Notifikasi -->
    <div>
        <h2 class="text-sm font-bold mb-4 uppercase tracking-wider text-gray-600"><i class="ph-bold ph-list"></i> Daftar Notifikasi</h2>
        <div class="border border-gray-200 rounded overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 font-bold border-b border-gray-200 w-1/4">Tanggal</th>
                        <th class="px-4 py-3 font-bold border-b border-gray-200 w-1/3">Judul</th>
                        <th class="px-4 py-3 font-bold border-b border-gray-200">Keterangan</th>
                        <th class="px-4 py-3 font-bold border-b border-gray-200 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($notifications as $notif)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $notif->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $notif->judul }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            <div class="line-clamp-2">{{ $notif->keterangan }}</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form action="/update-now/{{ $notif->id }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus notifikasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded transition-colors" title="Hapus">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada notifikasi yang ditambahkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
