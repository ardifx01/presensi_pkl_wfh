<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;
use Carbon\Carbon;

class FixInvalidAttendanceTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:fix-time {--dry-run : Show what would be fixed without actually fixing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix attendance records with invalid times that don\'t match their session';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Analyzing attendance records for time inconsistencies...');
        
        $sessionTimes = [
            'Pagi (09.00-12.00 WIB)' => ['start' => '09:00', 'end' => '12:00'],
            'Siang (13.00-15.00 WIB)' => ['start' => '13:00', 'end' => '15:00'], 
            'Malam (16.30-23.59 WIB)' => ['start' => '16:30', 'end' => '23:59'],
            // Old formats that need to be updated
            '10.00 WIB (Pagi)' => ['start' => '09:00', 'end' => '12:00'],
            '14.00 WIB (Siang)' => ['start' => '13:00', 'end' => '15:00'],
            '16.30 WIB (Sore)' => ['start' => '16:30', 'end' => '23:59'],
        ];
        
        $invalidRecords = collect();
        $totalChecked = 0;
        
        // Get ALL records instead of filtering by session
        $allRecords = Absensi::all();
        $totalChecked = $allRecords->count();
        
        foreach ($allRecords as $record) {
            $presensiTime = Carbon::parse($record->presensi_at, 'Asia/Jakarta');
            $correctSession = $this->detectCorrectSession($presensiTime, $sessionTimes);
            
            // Check if current session matches the time OR if it's old format that needs updating
            $needsUpdate = false;
            $isOldFormat = in_array($record->sesi_presensi, [
                '10.00 WIB (Pagi)', 
                '14.00 WIB (Siang)', 
                '16.30 WIB (Sore)'
            ]);
            
            if ($isOldFormat) {
                $needsUpdate = true;
            } elseif ($record->sesi_presensi !== $correctSession) {
                $needsUpdate = true;
            }
            
            if ($needsUpdate) {
                $invalidRecords->push([
                    'id' => $record->id,
                    'nama' => $record->nama_murid,
                    'email' => $record->user_email,
                    'sesi' => $record->sesi_presensi,
                    'waktu_tercatat' => $presensiTime->format('H:i:s'),
                    'tanggal' => $presensiTime->format('Y-m-d'),
                    'seharusnya_sesi' => $correctSession,
                    'is_old_format' => $isOldFormat,
                    'record' => $record
                ]);
            }
        }
        
        $this->table(
            ['ID', 'Nama', 'Email', 'Sesi Tercatat', 'Waktu', 'Tanggal', 'Seharusnya Sesi'],
            $invalidRecords->map(function($item) {
                return [
                    $item['id'],
                    $item['nama'],
                    $item['email'],
                    $item['sesi'],
                    $item['waktu_tercatat'],
                    $item['tanggal'],
                    $item['seharusnya_sesi'] ?: 'Di luar jam sesi'
                ];
            })->toArray()
        );
        
        $this->info("📊 Total records checked: {$totalChecked}");
        $this->warn("⚠️  Invalid records found: " . $invalidRecords->count());
        
        if ($invalidRecords->count() > 0) {
            if ($this->option('dry-run')) {
                $this->info('🔍 DRY RUN MODE - No changes made. Use without --dry-run to fix.');
                return;
            }
            
            if ($this->confirm('Do you want to fix these records by updating their session to match their time?')) {
                $fixed = 0;
                $skipped = 0;
                $deleted = 0;
                
                foreach ($invalidRecords as $item) {
                    if ($item['seharusnya_sesi']) {
                        try {
                            // Check if updating would create duplicate
                            $existingRecord = Absensi::where('nama_murid', $item['record']->nama_murid)
                                ->where('kelas', $item['record']->kelas)
                                ->where('sesi_presensi', $item['seharusnya_sesi'])
                                ->where('presensi_date', $item['record']->presensi_date)
                                ->where('id', '!=', $item['record']->id)
                                ->first();
                            
                            if ($existingRecord) {
                                // Delete the invalid record instead of updating to avoid duplicate
                                $item['record']->delete();
                                $deleted++;
                                $this->warn("🗑️  Deleted duplicate record ID {$item['id']}: {$item['nama']} (correct session already exists)");
                            } else {
                                $item['record']->update(['sesi_presensi' => $item['seharusnya_sesi']]);
                                $fixed++;
                                $this->info("✅ Fixed record ID {$item['id']}: {$item['nama']} -> {$item['seharusnya_sesi']}");
                            }
                        } catch (\Exception $e) {
                            $skipped++;
                            $this->error("❌ Error fixing record ID {$item['id']}: {$e->getMessage()}");
                        }
                    } else {
                        // Record is outside all session times - delete it
                        $item['record']->delete();
                        $deleted++;
                        $this->warn("🗑️  Deleted invalid record ID {$item['id']}: {$item['nama']} (time outside all sessions)");
                    }
                }
                
                $this->info("🎉 Summary: Fixed {$fixed} records, deleted {$deleted} invalid records, skipped {$skipped} records due to errors.");
            }
        } else {
            $this->info('✅ All attendance records have correct session timing!');
        }
    }
    
    private function detectCorrectSession($time, $sessionTimes)
    {
        // Define the standard session windows
        $standardSessions = [
            'Pagi (09.00-12.00 WIB)' => ['start' => '09:00', 'end' => '12:00'],
            'Siang (13.00-15.00 WIB)' => ['start' => '13:00', 'end' => '15:00'],
            'Malam (16.30-23.59 WIB)' => ['start' => '16:30', 'end' => '23:59'],
        ];
        
        foreach ($standardSessions as $session => $timeRange) {
            $start = $time->copy()->setTime(...explode(':', $timeRange['start']));
            $end = $time->copy()->setTime(...explode(':', $timeRange['end']));
            
            if ($time->between($start, $end)) {
                return $session;
            }
        }
        
        return null; // Outside all session times
    }
}
