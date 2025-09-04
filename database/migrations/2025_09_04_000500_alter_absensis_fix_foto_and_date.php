<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            if (!Schema::hasColumn('absensis','presensi_date')) {
                $table->date('presensi_date')->after('presensi_at');
            }
            // Ubah foto_path jadi nullable bila belum
            $table->string('foto_path')->nullable()->change();
        });

        // Bersihkan data foto_path yang berisi '0' menjadi NULL
        DB::table('absensis')->where('foto_path','0')->update(['foto_path'=>null]);
        // Isi presensi_date dari presensi_at jika null
        DB::statement("UPDATE absensis SET presensi_date = DATE(presensi_at) WHERE presensi_date IS NULL");
    }

    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            // Tidak drop presensi_date agar tidak hilangkan data penting
            // Kembalikan foto_path ke not nullable hanya jika semua baris punya nilai
            // (dibiarkan apa adanya demi keamanan rollback)
        });
    }
};
