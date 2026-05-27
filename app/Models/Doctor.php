<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'nama_dokter', 'nomor_sip', 'spesialisasi'
    ];

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
