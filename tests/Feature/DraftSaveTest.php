<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Certificate;
use App\Models\Patient;
use App\Models\Doctor;

class DraftSaveTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup initial default doctor
        Doctor::create([
            'nama_dokter' => 'Dr. Default',
            'nomor_sip' => 'SIP.00000.2023',
            'spesialisasi' => 'Umum'
        ]);
    }

    public function test_save_new_adult_draft_creates_patient_and_certificate()
    {
        $payload = [
            'id' => null,
            'jenis' => 'Dewasa',
            'status' => 'Draft',
            'data' => [
                'nrm' => 'RM-100222',
                'nik' => '3573012345670099',
                'nama_lengkap' => 'Adit Nugroho',
                'gender' => 'Laki-laki',
                'tanggal_lahir' => '1985-05-15',
                'alamat' => 'Jl. Kebagusan No. 5',
                'tanggal_kematian' => '2026-05-22',
                'jam_kematian' => '10:00',
                'nama_dokter' => 'Dr. Ahmad Santoso',
                'nomor_sip' => 'SIP.12345.2023'
            ]
        ];

        $response = $this->postJson('/api/drafts/save', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'message', 'id']);
        
        $this->assertTrue($response['success']);
        
        // Verify patient created
        $patient = Patient::where('nrm', 'RM-100222')->first();
        $this->assertNotNull($patient);
        $this->assertEquals('Adit Nugroho', $patient->nama_lengkap);
        $this->assertEquals('L', $patient->jenis_kelamin);

        // Verify doctor created
        $doctor = Doctor::where('nomor_sip', 'SIP.12345.2023')->first();
        $this->assertNotNull($doctor);
        $this->assertEquals('Dr. Ahmad Santoso', $doctor->nama_dokter);

        // Verify certificate created
        $cert = Certificate::find($response['id']);
        $this->assertNotNull($cert);
        $this->assertEquals('Dewasa', $cert->jenis);
        $this->assertEquals('Draft', $cert->status);
        $this->assertEquals($patient->id, $cert->patient_id);
        $this->assertEquals($doctor->id, $cert->doctor_id);
        $this->assertEquals('2026-05-22 10:00:00', $cert->waktu_meninggal->format('Y-m-d H:i:s'));
    }

    public function test_save_new_infant_draft_creates_patient_and_certificate()
    {
        $payload = [
            'id' => null,
            'jenis' => 'Bayi',
            'status' => 'Draft',
            'data' => [
                'nrm_bayi' => 'RM-999888',
                'nik_ibu' => '3573012345670002',
                'nama_bayi' => 'Bayi Ny. Ratna',
                'gender_bayi' => 'Perempuan',
                'tanggal_lahir_bayi' => '2026-05-20',
                'alamat_ibu' => 'Jl. Pahlawan No. 45',
                'tanggal_meninggal_bayi' => '2026-05-22',
                'jam_meninggal_bayi' => '11:30',
                'nama_dokter' => 'Dr. Siti Aminah',
                'nomor_sip' => 'SIP.67890.2023'
            ]
        ];

        $response = $this->postJson('/api/drafts/save', $payload);

        $response->assertStatus(200);
        $this->assertTrue($response['success']);

        // Verify patient created
        $patient = Patient::where('nrm', 'RM-999888')->first();
        $this->assertNotNull($patient);
        $this->assertEquals('Bayi Ny. Ratna', $patient->nama_lengkap);
        $this->assertEquals('P', $patient->jenis_kelamin);

        // Verify certificate created
        $cert = Certificate::find($response['id']);
        $this->assertNotNull($cert);
        $this->assertEquals('Bayi', $cert->jenis);
        $this->assertEquals('2026-05-22 11:30:00', $cert->waktu_meninggal->format('Y-m-d H:i:s'));
    }

    public function test_update_existing_draft_modifies_patient_and_certificate()
    {
        // First create
        $payload = [
            'id' => null,
            'jenis' => 'Dewasa',
            'status' => 'Draft',
            'data' => [
                'nrm' => 'RM-100222',
                'nama_lengkap' => 'Adit Nugroho',
                'gender' => 'Laki-laki',
                'nomor_sip' => 'SIP.00000.2023'
            ]
        ];

        $res = $this->postJson('/api/drafts/save', $payload);
        $certId = $res['id'];

        // Now update
        $updatePayload = [
            'id' => $certId,
            'jenis' => 'Dewasa',
            'status' => 'Saved', // Final Submit
            'data' => [
                'nrm' => 'RM-100222',
                'nama_lengkap' => 'Adit Nugroho Updated',
                'gender' => 'Laki-laki',
                'alamat' => 'New Address',
                'nomor_sip' => 'SIP.00000.2023'
            ]
        ];

        $response = $this->postJson('/api/drafts/save', $updatePayload);

        $response->assertStatus(200);
        $this->assertEquals($certId, $response['id']);

        // Verify patient updated
        $patient = Patient::where('nrm', 'RM-100222')->first();
        $this->assertNotNull($patient);
        $this->assertEquals('Adit Nugroho Updated', $patient->nama_lengkap);
        $this->assertEquals('New Address', $patient->alamat);

        // Verify certificate updated status to Saved
        $cert = Certificate::find($certId);
        $this->assertEquals('Saved', $cert->status);
    }

    public function test_custom_certificate_number_conflict_handling()
    {
        // Save first with specific certificate number
        $payload1 = [
            'id' => null,
            'jenis' => 'Dewasa',
            'status' => 'Draft',
            'no_sertifikat' => 'SKD-2026/00001',
            'data' => [
                'nrm' => 'RM-111',
                'nama_lengkap' => 'Patient One',
                'nomor_sip' => 'SIP.00000.2023'
            ]
        ];
        $this->postJson('/api/drafts/save', $payload1);

        // Try to save second with same number (which will auto-resolve collision on create)
        $payload2 = [
            'id' => null,
            'jenis' => 'Dewasa',
            'status' => 'Draft',
            'no_sertifikat' => 'SKD-2026/00001',
            'data' => [
                'nrm' => 'RM-222',
                'nama_lengkap' => 'Patient Two',
                'nomor_sip' => 'SIP.00000.2023'
            ]
        ];

        $response = $this->postJson('/api/drafts/save', $payload2);
        $response->assertStatus(200);

        $cert2 = Certificate::find($response['id']);
        $this->assertStringContainsString('SKD-2026/00001-', $cert2->nomor_sertifikat);
    }
}
