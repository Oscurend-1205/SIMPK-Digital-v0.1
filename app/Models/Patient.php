<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'nrm', 'nik', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'pekerjaan', 'alamat'
    ];

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
