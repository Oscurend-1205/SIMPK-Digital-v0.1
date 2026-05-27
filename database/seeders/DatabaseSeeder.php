<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Real Doctors Data
        $doctor1 = \App\Models\Doctor::create([
            'nama_dokter' => 'Dr. Ahmad Santoso',
            'nomor_sip' => 'SIP.12345.2023',
            'spesialisasi' => 'Penyakit Dalam'
        ]);

        $doctor2 = \App\Models\Doctor::create([
            'nama_dokter' => 'Dr. Siti Aminah',
            'nomor_sip' => 'SIP.67890.2023',
            'spesialisasi' => 'Anak'
        ]);

        // Real Patients Data
        $patient1 = \App\Models\Patient::create([
            'nrm' => 'RM-100021',
            'nik' => '3573012345670001',
            'nama_lengkap' => 'Budi Raharjo',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Malang',
            'tanggal_lahir' => '1965-04-12',
            'pekerjaan' => 'Pensiunan',
            'alamat' => 'Jl. Merdeka No. 10, Malang'
        ]);

        $patient2 = \App\Models\Patient::create([
            'nrm' => 'RM-100022',
            'nik' => '3573012345670002',
            'nama_lengkap' => 'Ratna Sari',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1980-08-25',
            'pekerjaan' => 'Ibu Rumah Tangga',
            'alamat' => 'Jl. Pahlawan No. 45, Kepanjen'
        ]);

        $patient3 = \App\Models\Patient::create([
            'nrm' => 'RM-100023',
            'nik' => '3573012345670003',
            'nama_lengkap' => 'Bayi Ny. Ratna',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Kepanjen',
            'tanggal_lahir' => '2026-05-10',
            'pekerjaan' => '-',
            'alamat' => 'Jl. Pahlawan No. 45, Kepanjen'
        ]);

        // Real Certificates Data
        \App\Models\Certificate::create([
            'nomor_sertifikat' => 'SKD-2026/00001',
            'jenis' => 'Dewasa',
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor1->id,
            'waktu_meninggal' => '2026-05-20 14:30:00',
            'status' => 'Printed',
            'data' => json_encode([
                'penyebab_langsung' => 'Gagal Jantung',
                'usia' => 61
            ])
        ]);

        \App\Models\Certificate::create([
            'nomor_sertifikat' => 'SKD-2026/00002',
            'jenis' => 'Dewasa',
            'patient_id' => $patient2->id,
            'doctor_id' => $doctor1->id,
            'waktu_meninggal' => '2026-05-21 09:15:00',
            'status' => 'Saved',
            'data' => json_encode([
                'penyebab_langsung' => 'Stroke',
                'usia' => 45
            ])
        ]);

        \App\Models\Certificate::create([
            'nomor_sertifikat' => 'SKD-2026/00003',
            'jenis' => 'Bayi',
            'patient_id' => $patient3->id,
            'doctor_id' => $doctor2->id,
            'waktu_meninggal' => '2026-05-22 11:30:00',
            'status' => 'Draft',
            'data' => json_encode([
                'penyebab_langsung' => 'Asfiksia',
                'usia' => 0 // months/days logic can be implemented in data
            ])
        ]);
    }
}
