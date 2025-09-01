<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            if (!Schema::hasColumn('absensis', 'presensi_date')) {
                // Tambahkan nullable dulu agar tidak error pada existing rows
                $table->date('presensi_date')->nullable()->after('presensi_at');
            }
        });

        // Isi nilai untuk data lama pakai tanggal dari presensi_at
        DB::table('absensis')->whereNull('presensi_date')->update(['presensi_date' => DB::raw('DATE(presensi_at)')]);

        // Unique index (cek dulu via SHOW INDEX)
        $hasIndex = collect(DB::select("SHOW INDEX FROM absensis WHERE Key_name='absensi_unique_per_day'"))->count() > 0;
        if (!$hasIndex) {
            Schema::table('absensis', function (Blueprint $table) {
                $table->unique(['nama_murid','kelas','sesi_presensi','presensi_date'],'absensi_unique_per_day');
            });
        }
    }

    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropUnique('absensi_unique_per_day');
            $table->dropColumn('presensi_date');
        });
    }
};
