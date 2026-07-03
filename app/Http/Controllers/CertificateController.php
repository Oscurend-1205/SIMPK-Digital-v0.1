<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;
use App\Models\ActivityLog;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['patient', 'doctor']);

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->where('nomor_sertifikat', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($pq) use ($search) {
                      $pq->where('nama_lengkap', 'like', "%{$search}%")
                         ->orWhere('nrm', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type !== 'all') {
            $query->where('jenis', $request->type);
        }

        if ($request->has('doa') && $request->doa == '1') {
            $query->where(function ($q) {
                $q->where('data->doa', 'Ya')
                  ->orWhere('data->doa_bayi', 'Ya');
            });
        }

        $certificates = $query->orderBy('updated_at', 'desc')->paginate(15)->appends($request->all());

        return view('simpk.certificates', compact('certificates'));
    }

    public function drafts()
    {
        $drafts = Certificate::with('patient')
            ->where('status', 'Draft')
            ->orderBy('updated_at', 'desc')
            ->get();
            
        return view('simpk.drafts', compact('drafts'));
    }

    public function edit($id)
    {
        $certificate = Certificate::findOrFail($id);
        if ($certificate->jenis == 'Dewasa') {
            return view('form.kematian-dewasa', compact('certificate'));
        } else {
            return view('form.kematian-bayi', compact('certificate'));
        }
    }

    public function showOutputDewasa($id)
    {
        $certificate = Certificate::with(['patient', 'doctor'])->findOrFail($id);
        return view('output.output-dewasa', compact('certificate'));
    }

    public function showOutputBayi($id)
    {
        $certificate = Certificate::with(['patient', 'doctor'])->findOrFail($id);
        return view('output.output-bayi', compact('certificate'));
    }

    public function saveDraft(Request $request)
    {
        $id = $request->input('id');
        $formData = $request->input('data');
        $jenis = $request->input('jenis');
        $status = $request->input('status', 'Draft');

        // Check for redundancy before proceeding
        if (!$id) {
            if ($jenis === 'Dewasa') {
                $nik = $formData['nik'] ?? null;
                $nrm = $formData['nrm'] ?? null;

                if ($nik) {
                    $existing = Certificate::where('data->nik', $nik)->first();
                    if ($existing) {
                        return response()->json([
                            'success' => false, 
                            'message' => 'Redundansi Data: Sertifikat dengan NIK ' . $nik . ' sudah ada dalam sistem.'
                        ], 422);
                    }
                }
                
                if ($nrm) {
                    $existing = Certificate::where('data->nrm', $nrm)->first();
                    if ($existing) {
                        return response()->json([
                            'success' => false, 
                            'message' => 'Redundansi Data: Sertifikat dengan NRM ' . $nrm . ' sudah ada dalam sistem.'
                        ], 422);
                    }
                }
            } else {
                // Bayi
                $nrmBayi = $formData['nrm_bayi'] ?? null;
                $namaBayi = $formData['nama_bayi'] ?? null;

                if ($nrmBayi) {
                    $existing = Certificate::where('data->nrm_bayi', $nrmBayi)->first();
                    if ($existing) {
                        return response()->json([
                            'success' => false, 
                            'message' => 'Redundansi Data: Sertifikat dengan NRM Bayi ' . $nrmBayi . ' sudah ada dalam sistem.'
                        ], 422);
                    }
                }

                if ($namaBayi) {
                    $tglLahir = $formData['tanggal_lahir_bayi'] ?? null;
                    if ($tglLahir) {
                        $existing = Certificate::where('data->nama_bayi', $namaBayi)
                            ->where('data->tanggal_lahir_bayi', $tglLahir)
                            ->first();
                        if ($existing) {
                            return response()->json([
                                'success' => false, 
                                'message' => 'Redundansi Data: Sertifikat untuk bayi ' . $namaBayi . ' (lahir: ' . $tglLahir . ') sudah ada.'
                            ], 422);
                        }
                    }
                }
            }
        }

        // Extract patient data
        $patientData = [];
        $nrm = null;
        $nik = null;

        if ($jenis === 'Dewasa') {
            $nrm = !empty($formData['nrm']) ? $formData['nrm'] : null;
            $nik = !empty($formData['nik']) ? $formData['nik'] : null;
            $patientData = [
                'nik' => $nik,
                'nama_lengkap' => !empty($formData['nama_lengkap']) ? $formData['nama_lengkap'] : 'Unknown',
                'jenis_kelamin' => (isset($formData['gender']) && $formData['gender'] === 'Laki-laki') ? 'L' : 'P',
                'tanggal_lahir' => !empty($formData['tanggal_lahir']) ? $formData['tanggal_lahir'] : null,
                'alamat' => $formData['alamat'] ?? null,
            ];
        } else {
            // Bayi
            $nrm = !empty($formData['nrm_bayi']) ? $formData['nrm_bayi'] : null;
            $nik = null;
            $patientData = [
                'nik' => $nik,
                'nama_lengkap' => !empty($formData['nama_bayi']) ? $formData['nama_bayi'] : 'Bayi Unknown',
                'jenis_kelamin' => (isset($formData['gender_bayi']) && $formData['gender_bayi'] === 'Laki-laki') ? 'L' : 'P',
                'tanggal_lahir' => !empty($formData['tanggal_lahir_bayi']) ? $formData['tanggal_lahir_bayi'] : null,
                'alamat' => null,
            ];
        }

        // Improved Patient resolution to avoid unique constraint violations
        $patient = null;
        if ($id) {
            $cert = Certificate::find($id);
            if ($cert && $cert->patient) {
                $patient = $cert->patient;
            }
        }

        if ($patient) {
            // Check if NRM/NIK update conflicts with OTHER patients
            if ($nrm && $nrm !== $patient->nrm) {
                if (\App\Models\Patient::where('nrm', $nrm)->where('id', '!=', $patient->id)->exists()) {
                    return response()->json(['success' => false, 'message' => 'NRM sudah digunakan oleh pasien lain'], 422);
                }
            }
            if ($nik && $nik !== $patient->nik) {
                if (\App\Models\Patient::where('nik', $nik)->where('id', '!=', $patient->id)->exists()) {
                    return response()->json(['success' => false, 'message' => 'NIK sudah digunakan oleh pasien lain'], 422);
                }
            }
            $patient->update(array_merge($patientData, ['nrm' => $nrm ?? $patient->nrm]));
        } else {
            // Try to find existing patient by NRM or NIK before creating
            if ($nrm) {
                $patient = \App\Models\Patient::where('nrm', $nrm)->first();
            }
            if (!$patient && $nik) {
                $patient = \App\Models\Patient::where('nik', $nik)->first();
            }

            if ($patient) {
                $patient->update($patientData);
            } else {
                if (empty($nrm)) {
                    $nrm = 'TEMP-' . time() . '-' . rand(1000, 9999);
                }
                $patient = \App\Models\Patient::create(array_merge($patientData, ['nrm' => $nrm]));
            }
        }

        // Doctor resolution - lookup by name first, then by SIP
        $sip = $formData['nomor_sip'] ?? null;
        $namaDokter = $formData['nama_dokter'] ?? null;
        $doctor = null;

        if (!empty($namaDokter)) {
            $doctor = \App\Models\Doctor::where('nama_dokter', $namaDokter)->first();
        }

        if (!$doctor && !empty($sip)) {
            $doctor = \App\Models\Doctor::where('nomor_sip', $sip)->first();
        }

        if ($doctor) {
            // Update SIP if provided and different
            if (!empty($sip) && $sip !== $doctor->nomor_sip) {
                $doctor->update(['nomor_sip' => $sip]);
            }
        } elseif (!empty($namaDokter)) {
            $doctor = \App\Models\Doctor::create([
                'nama_dokter' => $namaDokter,
                'nomor_sip' => $sip ?: null,
                'spesialisasi' => ($jenis === 'Bayi') ? 'Anak' : 'Umum'
            ]);
        }

        if (!$doctor) {
            $doctor = \App\Models\Doctor::first() ?? \App\Models\Doctor::create([
                'nama_dokter' => 'Dr. Default',
                'nomor_sip' => null,
                'spesialisasi' => 'Umum'
            ]);
        }

        // Waktu meninggal logic
        $waktuMeninggal = null;
        if ($jenis === 'Dewasa') {
            if (!empty($formData['tanggal_kematian']) && !empty($formData['jam_kematian'])) {
                $waktuMeninggal = $formData['tanggal_kematian'] . ' ' . $formData['jam_kematian'];
            }
        } else {
            if (!empty($formData['tanggal_meninggal_bayi']) && !empty($formData['jam_meninggal_bayi'])) {
                $waktuMeninggal = $formData['tanggal_meninggal_bayi'] . ' ' . $formData['jam_meninggal_bayi'];
            }
        }

        // Certificate Save logic
        $noSertifikat = $request->input('no_sertifikat') ?? ($formData['no_sertifikat'] ?? null);
        if ($id) {
            $cert = Certificate::find($id);
            if (!$cert) return response()->json(['success' => false, 'message' => 'Draft not found'], 404);
            
            if (!empty($noSertifikat) && $noSertifikat !== $cert->nomor_sertifikat) {
                if (Certificate::where('nomor_sertifikat', $noSertifikat)->where('id', '!=', $id)->exists()) {
                    return response()->json(['success' => false, 'message' => 'Nomor sertifikat sudah digunakan'], 422);
                }
                $cert->nomor_sertifikat = $noSertifikat;
            }
        } else {
            $cert = new Certificate();
            if (empty($noSertifikat)) {
                $noSertifikat = ($jenis == 'Dewasa' ? 'SKD-' : 'SKB-') . date('Y') . '/' . str_pad(Certificate::count() + 1, 5, '0', STR_PAD_LEFT);
            }
            while (Certificate::where('nomor_sertifikat', $noSertifikat)->exists()) {
                $noSertifikat .= '-' . rand(10, 99);
            }
            $cert->nomor_sertifikat = $noSertifikat;
        }

        $cert->jenis = $jenis;
        $cert->patient_id = $patient->id;
        $cert->doctor_id = $doctor->id;
        $cert->waktu_meninggal = $waktuMeninggal ?? ($cert->waktu_meninggal ?? now());
        $cert->status = $status;
        $cert->data = $formData;
        $cert->save();

        ActivityLog::log(
            $status === 'Saved' ? 'Simpan Sertifikat' : 'Simpan Draft',
            sprintf("Sertifikat %s (%s) atas nama %s berhasil %s.", 
                $cert->nomor_sertifikat, $jenis, $patient->nama_lengkap, 
                $status === 'Saved' ? 'disimpan secara final' : 'disimpan sebagai draft')
        );

        return response()->json([
            'success' => true,
            'message' => $status === 'Saved' ? 'Sertifikat berhasil disimpan' : 'Draft berhasil disimpan',
            'id' => $cert->id,
            'redirect' => $status === 'Saved' ? ($jenis === 'Dewasa' ? route('output.dewasa', $cert->id) : route('output.bayi', $cert->id)) : null
        ]);
    }

    public function deleteDraft($id)
    {
        $cert = Certificate::find($id);
        if (!$cert) {
            return response()->json(['success' => false, 'message' => 'Draft not found'], 404);
        }

        if ($cert->status !== 'Draft') {
            return response()->json(['success' => false, 'message' => 'Only drafts can be deleted'], 403);
        }

        $cert->delete();

        ActivityLog::log(
            'Hapus Draft',
            "Draft sertifikat dengan ID " . $id . " telah dihapus."
        );

        return response()->json(['success' => true, 'message' => 'Draft deleted successfully']);
    }

    /**
     * Return certificate detail as JSON (for detail modal).
     */
    public function show($id)
    {
        $cert = Certificate::with(['patient', 'doctor'])->find($id);
        if (!$cert) {
            return response()->json(['success' => false, 'message' => 'Sertifikat tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'certificate' => [
                'id'                => $cert->id,
                'nomor_sertifikat'  => $cert->nomor_sertifikat,
                'jenis'             => $cert->jenis,
                'status'            => $cert->status,
                'waktu_meninggal'   => $cert->waktu_meninggal ? $cert->waktu_meninggal->format('d M Y, H:i') : '-',
                'created_at'        => $cert->created_at->format('d M Y, H:i'),
                'updated_at'        => $cert->updated_at->format('d M Y, H:i'),
                'patient' => [
                    'nama_lengkap'  => $cert->patient->nama_lengkap,
                    'nrm'           => $cert->patient->nrm,
                    'nik'           => $cert->patient->nik ?? '-',
                    'jenis_kelamin' => $cert->patient->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                    'tanggal_lahir' => $cert->patient->tanggal_lahir ?? '-',
                    'alamat'        => $cert->patient->alamat ?? '-',
                ],
                'doctor' => [
                    'nama_dokter'   => $cert->doctor->nama_dokter,
                    'nomor_sip'     => $cert->doctor->nomor_sip ?? '-',
                    'spesialisasi'  => $cert->doctor->spesialisasi ?? '-',
                ],
                'data' => $cert->data,
            ],
        ]);
    }

    /**
     * Update certificate status (Draft, Saved, Printed).
     */
    public function saveCertificate($id)
    {
        $cert = Certificate::with(['patient', 'doctor'])->find($id);
        if (!$cert) {
            return response()->json(['success' => false, 'message' => 'Sertifikat tidak ditemukan'], 404);
        }

        if ($cert->status === 'Saved' || $cert->status === 'Printed') {
            return response()->json([
                'success' => false, 
                'message' => 'Sertifikat ini sudah disimpan secara final dan tidak dapat diubah lagi.'
            ], 422);
        }

        // Detailed validation for final save
        $missingFields = [];
        if ($cert->jenis === 'Dewasa') {
            $check = [
                'nik' => 'NIK',
                'nama_lengkap' => 'Nama Lengkap',
                'tanggal_lahir' => 'Tanggal Lahir',
                'tanggal_kematian' => 'Tanggal Kematian',
                'jam_kematian' => 'Jam Kematian',
                'penyebab_a' => 'Penyebab Kematian (a)',
                'fucod' => 'FUCOD (Deskripsi)',
                'nama_dokter' => 'Nama Dokter',
            ];
        } else {
            $check = [
                'nama_bayi' => 'Nama Bayi',
                'tanggal_lahir_bayi' => 'Tanggal Lahir Bayi',
                'tanggal_meninggal_bayi' => 'Tanggal Meninggal Bayi',
                'penyebab_utama_bayi' => 'Penyebab Kematian Utama (Bayi)',
                'nama_dokter' => 'Nama Dokter',
            ];
        }

        foreach ($check as $field => $label) {
            if (empty($cert->data[$field] ?? null)) {
                $missingFields[] = $label;
            }
        }
        
        if (!empty($missingFields)) {
            return response()->json([
                'success' => false,
                'message' => 'Data belum lengkap. Harap lengkapi: ' . implode(', ', $missingFields)
            ], 422);
        }

        $cert->status = 'Saved';
        $cert->save();

        ActivityLog::log(
            'Simpan Sertifikat Final',
            sprintf("Sertifikat %s (%s) atas nama %s berhasil disimpan ke arsip.",
                $cert->nomor_sertifikat, $cert->jenis, $cert->patient->nama_lengkap)
        );

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil disimpan secara final.',
            'id' => $cert->id,
            'status' => $cert->status,
        ]);
    }

    /**
     * Update certificate status (Draft, Saved, Printed).
     */
    public function updateStatus(Request $request, $id)
    {
        $cert = Certificate::with('patient')->find($id);
        if (!$cert) {
            return response()->json(['success' => false, 'message' => 'Sertifikat tidak ditemukan'], 404);
        }

        $newStatus = $request->input('status');
        $allowed = ['Draft', 'Saved', 'Printed'];
        if (!in_array($newStatus, $allowed)) {
            return response()->json(['success' => false, 'message' => 'Status tidak valid'], 422);
        }

        $oldStatus = $cert->status;
        $cert->status = $newStatus;
        $cert->save();

        $statusLabels = ['Draft' => 'Draft', 'Saved' => 'Tersimpan', 'Printed' => 'Tercetak'];

        ActivityLog::log(
            'Ubah Status Sertifikat',
            sprintf(
                "Status sertifikat %s (%s) diubah dari %s menjadi %s.",
                $cert->nomor_sertifikat,
                $cert->patient->nama_lengkap,
                $statusLabels[$oldStatus] ?? $oldStatus,
                $statusLabels[$newStatus] ?? $newStatus
            )
        );

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah menjadi ' . ($statusLabels[$newStatus] ?? $newStatus),
            'status'  => $newStatus,
        ]);
    }

    /**
     * Delete any certificate (not just drafts).
     */
    public function destroy($id)
    {
        $cert = Certificate::with('patient')->find($id);
        if (!$cert) {
            return response()->json(['success' => false, 'message' => 'Sertifikat tidak ditemukan'], 404);
        }

        $nomorSertifikat = $cert->nomor_sertifikat;
        $namaPasien = $cert->patient->nama_lengkap ?? 'Unknown';
        $jenis = $cert->jenis;

        $cert->delete();

        ActivityLog::log(
            'Hapus Sertifikat',
            sprintf(
                "Sertifikat %s (%s) atas nama %s telah dihapus secara permanen.",
                $nomorSertifikat,
                $jenis,
                $namaPasien
            )
        );

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil dihapus',
        ]);
    }
}
