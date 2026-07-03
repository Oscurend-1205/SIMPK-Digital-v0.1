<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\ActivityLog;

class DoctorController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $doctors = Doctor::where('nama_dokter', 'like', '%' . $query . '%')
            ->orWhere('nomor_sip', 'like', '%' . $query . '%')
            ->limit(10)
            ->get();

        return response()->json($doctors);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dokter' => 'required|string|max:255',
            'nomor_sip' => 'nullable|string|max:255|unique:doctors,nomor_sip',
            'spesialisasi' => 'nullable|string|max:255',
        ]);

        Doctor::create([
            'nama_dokter' => $request->nama_dokter,
            'nomor_sip' => $request->nomor_sip,
            'spesialisasi' => $request->spesialisasi,
        ]);

        ActivityLog::log('Simpan Dokter', 'Menambahkan dokter baru: ' . $request->nama_dokter);

        return redirect()->back()->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctorName = $doctor->nama_dokter;
        $doctor->delete();

        ActivityLog::log('Hapus Dokter', 'Menghapus dokter: ' . $doctorName);

        return redirect()->back()->with('success', 'Dokter berhasil dihapus.');
    }
}
