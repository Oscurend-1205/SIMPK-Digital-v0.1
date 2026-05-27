<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'nomor_sertifikat', 'jenis', 'patient_id', 'doctor_id', 'waktu_meninggal', 'status', 'data'
    ];

    protected $casts = [
        'waktu_meninggal' => 'datetime',
        'data' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
