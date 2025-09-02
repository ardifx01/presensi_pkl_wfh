<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Step 1: Add presensi_date column if not exists
        if (!Schema::hasColumn('absensis', 'presensi_date')) {
            Schema::table('absensis', function (Blueprint $table) {
                $table->date('presensi_date')->nullable()->after('presensi_at');
            });
            
            // Fill existing records with date from presensi_at
            try {
                $count = DB::table('absensis')->whereNull('presensi_date')->count();
                if ($count > 0) {
                    // Update in chunks to avoid memory issues
                    DB::table('absensis')
                        ->whereNull('presensi_date')
                        ->chunkById(100, function ($records) {
                            foreach ($records as $record) {
                                DB::table('absensis')
                                    ->where('id', $record->id)
                                    ->update([
                                        'presensi_date' => date('Y-m-d', strtotime($record->presensi_at))
                                    ]);
                            }
                        });
                }
            } catch (Exception $e) {
                // Fallback: set today's date if error
                DB::table('absensis')
                    ->whereNull('presensi_date')
                    ->update(['presensi_date' => now()->toDateString()]);
            }
        }

        // Step 2: Add unique constraint (with error handling)
        try {
            // Check if index already exists
            $indexExists = false;
            
            // Try to check existing indexes
            try {
                $indexes = DB::select("SHOW INDEX FROM absensis WHERE Key_name = 'absensi_unique_per_day'");
                $indexExists = count($indexes) > 0;
            } catch (Exception $e) {
                // For non-MySQL databases or if SHOW INDEX fails
                $indexExists = false;
            }

            if (!$indexExists) {
                Schema::table('absensis', function (Blueprint $table) {
                    $table->unique(['nama_murid', 'kelas', 'sesi_presensi', 'presensi_date'], 'absensi_unique_per_day');
                });
            }
        } catch (Exception $e) {
            // Log error but don't fail migration
            logger('Warning: Could not add unique constraint to absensis table: ' . $e->getMessage());
        }
    }

    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            // Drop unique constraint if exists
            try {
                $table->dropUnique('absensi_unique_per_day');
            } catch (Exception $e) {
                // Ignore if constraint doesn't exist
            }
            
            // Drop column if exists
            if (Schema::hasColumn('absensis', 'presensi_date')) {
                $table->dropColumn('presensi_date');
            }
        });
    }
};
