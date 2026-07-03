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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('nrm', 50)->unique();
            $table->string('nik', 16)->unique()->nullable();
            $table->string('nama_lengkap', 500);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 500)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pekerjaan', 500)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
