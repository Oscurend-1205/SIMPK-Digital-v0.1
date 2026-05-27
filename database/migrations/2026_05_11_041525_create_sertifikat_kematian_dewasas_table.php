<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sertifikat_kematian_dewasas', function (Blueprint $table) {
            $table->id();
            
            // Identitas Jenazah
            $table->string('nik', 16);
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('pekerjaan')->nullable();
            
            // Waktu Kematian
            $table->dateTime('waktu_meninggal');
            $table->integer('usia');
            
            // Penyebab Kematian (WHO)
            $table->string('penyebab_langsung')->nullable();
            $table->string('interval_langsung')->nullable();
            
            $table->string('penyebab_antara')->nullable();
            $table->string('interval_antara')->nullable();
            
            $table->string('penyebab_dasar')->nullable();
            $table->string('interval_dasar')->nullable();
            
            $table->string('penyebab_utama')->nullable();
            $table->string('interval_utama')->nullable();
            
            // Pengesahan
            $table->string('nama_dokter');
            $table->string('nomor_sip');
            $table->string('tanda_tangan');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikat_kematian_dewasas');
    }
};
