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
            $table->string('nama_lengkap', 500);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 500);
            $table->date('tanggal_lahir');
            $table->string('pekerjaan', 500)->nullable();
            
            // Waktu Kematian
            $table->dateTime('waktu_meninggal');
            $table->integer('usia');
            
            // Penyebab Kematian (WHO)
            $table->string('penyebab_langsung', 500)->nullable();
            $table->string('interval_langsung', 500)->nullable();
            
            $table->string('penyebab_antara', 500)->nullable();
            $table->string('interval_antara', 500)->nullable();
            
            $table->string('penyebab_dasar', 500)->nullable();
            $table->string('interval_dasar', 500)->nullable();
            
            $table->string('penyebab_utama', 500)->nullable();
            $table->string('interval_utama', 500)->nullable();
            
            // Pengesahan
            $table->string('nama_dokter', 500);
            $table->string('nomor_sip', 500);
            $table->string('tanda_tangan', 1000);

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
