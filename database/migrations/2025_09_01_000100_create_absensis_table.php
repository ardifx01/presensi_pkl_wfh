<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->string('konsentrasi_keahlian');
            $table->string('nama_murid');
            $table->string('kelas');
            $table->string('nama_perusahaan');
            $table->string('alamat_perusahaan');
            $table->string('nama_pembimbing_sekolah');
            $table->string('nama_pembimbing_dudika');
            $table->string('sesi_presensi');
            // Waktu presensi eksplisit (selain created_at) untuk fleksibilitas laporan
            $table->timestamp('presensi_at')->useCurrent();
            $table->string('foto_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
