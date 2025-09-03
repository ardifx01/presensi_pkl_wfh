<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CleanTestingData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testing:clean {--dry-run : Show what would be cleaned without actually cleaning} {--backup : Create backup before cleaning}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean testing data from attendance records and restore affected real data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Starting testing data cleanup process...');
        
        // Step 1: Identify testing data
        $testingRecords = $this->identifyTestingData();
        
        if ($testingRecords->isEmpty()) {
            $this->info('✅ No testing data found to clean.');
            return;
        }
        
        // Show what will be cleaned
        $this->displayTestingData($testingRecords);
        
        if ($this->option('dry-run')) {
            $this->info('🔍 DRY RUN MODE - No changes made. Use without --dry-run to clean.');
            return;
        }
        
        // Create backup if requested
        if ($this->option('backup')) {
            $this->createBackup($testingRecords);
        }
        
        // Step 2: Identify affected real data
        $affectedRealData = $this->identifyAffectedRealData($testingRecords);
        
        if (!$affectedRealData->isEmpty()) {
            $this->warn('⚠️  Found real student data that might be affected:');
            $this->displayAffectedData($affectedRealData);
        }
        
        // Confirm before proceeding
        if (!$this->confirm('Do you want to proceed with cleaning testing data?')) {
            $this->info('Operation cancelled.');
            return;
        }
        
        // Step 3: Clean testing data
        $this->cleanTestingData($testingRecords);
        
        // Step 4: Fix any corrupted real data (if any)
        if (!$affectedRealData->isEmpty()) {
            $this->fixAffectedRealData($affectedRealData);
        }
        
        $this->info('🎉 Testing data cleanup completed successfully!');
    }
    
    private function identifyTestingData()
    {
        $this->info('🔍 Identifying testing data...');
        
        return Absensi::where(function ($query) {
            $query->where('nama_murid', 'LIKE', '%Testing User%')
                  ->orWhere('nama_murid', 'LIKE', '%Test%')
                  ->orWhere('user_email', 'LIKE', '%testing@%')
                  ->orWhere('user_email', 'LIKE', '%test@%')
                  ->orWhere('nama_murid', '=', 'Testing User');
        })->get();
    }
    
    private function displayTestingData($records)
    {
        $this->table(
            ['ID', 'Nama', 'Email', 'Sesi', 'Tanggal', 'Waktu'],
            $records->map(function($record) {
                return [
                    $record->id,
                    $record->nama_murid,
                    $record->user_email,
                    $record->sesi_presensi,
                    Carbon::parse($record->presensi_at)->format('Y-m-d'),
                    Carbon::parse($record->presensi_at)->format('H:i:s')
                ];
            })->toArray()
        );
        
        $this->warn("🔥 Found {$records->count()} testing records to be cleaned.");
    }
    
    private function identifyAffectedRealData($testingRecords)
    {
        $this->info('🔍 Checking for affected real student data...');
        
        // Get dates when testing occurred
        $testingDates = $testingRecords->pluck('presensi_date')->unique();
        
        // Find real students who have suspicious timing on testing dates
        $affected = collect();
        
        foreach ($testingDates as $date) {
            $realRecords = Absensi::whereDate('presensi_at', $date)
                ->where('nama_murid', 'NOT LIKE', '%Testing%')
                ->where('nama_murid', 'NOT LIKE', '%Test%')
                ->where('user_email', 'NOT LIKE', '%testing@%')
                ->where('user_email', 'NOT LIKE', '%test@%')
                ->get();
            
            foreach ($realRecords as $record) {
                $time = Carbon::parse($record->presensi_at);
                $sessionTimes = [
                    'Pagi (09.00-12.00 WIB)' => ['start' => '09:00', 'end' => '12:00'],
                    'Siang (13.00-15.00 WIB)' => ['start' => '13:00', 'end' => '15:00'],
                    'Malam (16.30-23.59 WIB)' => ['start' => '16:30', 'end' => '23:59'],
                ];
                
                $isTimeValid = false;
                foreach ($sessionTimes as $session => $timeRange) {
                    if ($record->sesi_presensi === $session) {
                        $start = $time->copy()->setTime(...explode(':', $timeRange['start']));
                        $end = $time->copy()->setTime(...explode(':', $timeRange['end']));
                        if ($time->between($start, $end)) {
                            $isTimeValid = true;
                            break;
                        }
                    }
                }
                
                if (!$isTimeValid) {
                    $affected->push($record);
                }
            }
        }
        
        return $affected;
    }
    
    private function displayAffectedData($records)
    {
        $this->table(
            ['ID', 'Nama', 'Email', 'Sesi', 'Tanggal', 'Waktu', 'Status'],
            $records->map(function($record) {
                return [
                    $record->id,
                    $record->nama_murid,
                    $record->user_email,
                    $record->sesi_presensi,
                    Carbon::parse($record->presensi_at)->format('Y-m-d'),
                    Carbon::parse($record->presensi_at)->format('H:i:s'),
                    'Time mismatch'
                ];
            })->toArray()
        );
    }
    
    private function createBackup($records)
    {
        $this->info('💾 Creating backup...');
        
        $backupData = $records->map(function($record) {
            return $record->toArray();
        })->toArray();
        
        $backupFile = storage_path('backups/testing_data_backup_' . now()->format('Y_m_d_H_i_s') . '.json');
        
        // Create backup directory if not exists
        if (!file_exists(dirname($backupFile))) {
            mkdir(dirname($backupFile), 0755, true);
        }
        
        file_put_contents($backupFile, json_encode($backupData, JSON_PRETTY_PRINT));
        
        $this->info("✅ Backup created: {$backupFile}");
    }
    
    private function cleanTestingData($records)
    {
        $this->info('🗑️  Cleaning testing data...');
        
        $deletedCount = 0;
        
        foreach ($records as $record) {
            try {
                $record->delete();
                $deletedCount++;
                $this->line("✅ Deleted testing record ID {$record->id}: {$record->nama_murid}");
            } catch (\Exception $e) {
                $this->error("❌ Failed to delete record ID {$record->id}: {$e->getMessage()}");
            }
        }
        
        // Also clean testing users if they exist
        $testingUsers = User::where(function ($query) {
            $query->where('name', 'LIKE', '%Testing%')
                  ->orWhere('name', 'LIKE', '%Test%')
                  ->orWhere('email', 'LIKE', '%testing@%')
                  ->orWhere('email', 'LIKE', '%test@%');
        })->get();
        
        foreach ($testingUsers as $user) {
            try {
                $user->delete();
                $this->line("✅ Deleted testing user: {$user->name} ({$user->email})");
            } catch (\Exception $e) {
                $this->error("❌ Failed to delete user {$user->id}: {$e->getMessage()}");
            }
        }
        
        $this->info("🎉 Successfully deleted {$deletedCount} testing attendance records and {$testingUsers->count()} testing users.");
    }
    
    private function fixAffectedRealData($records)
    {
        $this->info('🔧 Attempting to fix affected real student data...');
        
        if (!$this->confirm('Do you want to attempt automatic correction of affected real data?')) {
            $this->warn('⚠️  Skipping automatic correction. Please review and fix manually if needed.');
            return;
        }
        
        $fixedCount = 0;
        
        foreach ($records as $record) {
            $time = Carbon::parse($record->presensi_at);
            $correctSession = $this->detectCorrectSession($time);
            
            if ($correctSession && $correctSession !== $record->sesi_presensi) {
                try {
                    // Check if updating would create duplicate
                    $existing = Absensi::where('nama_murid', $record->nama_murid)
                        ->where('kelas', $record->kelas)
                        ->where('sesi_presensi', $correctSession)
                        ->where('presensi_date', $record->presensi_date)
                        ->where('id', '!=', $record->id)
                        ->first();
                    
                    if ($existing) {
                        $this->warn("⚠️  Cannot fix record ID {$record->id} - would create duplicate");
                        continue;
                    }
                    
                    $record->update(['sesi_presensi' => $correctSession]);
                    $fixedCount++;
                    $this->line("✅ Fixed record ID {$record->id}: {$record->nama_murid} -> {$correctSession}");
                } catch (\Exception $e) {
                    $this->error("❌ Failed to fix record ID {$record->id}: {$e->getMessage()}");
                }
            } else {
                $this->warn("⚠️  Cannot determine correct session for record ID {$record->id}");
            }
        }
        
        $this->info("🎉 Successfully fixed {$fixedCount} affected records.");
    }
    
    private function detectCorrectSession($time)
    {
        $sessionTimes = [
            'Pagi (09.00-12.00 WIB)' => ['start' => '09:00', 'end' => '12:00'],
            'Siang (13.00-15.00 WIB)' => ['start' => '13:00', 'end' => '15:00'],
            'Malam (16.30-23.59 WIB)' => ['start' => '16:30', 'end' => '23:59'],
        ];
        
        foreach ($sessionTimes as $session => $timeRange) {
            $start = $time->copy()->setTime(...explode(':', $timeRange['start']));
            $end = $time->copy()->setTime(...explode(':', $timeRange['end']));
            
            if ($time->between($start, $end)) {
                return $session;
            }
        }
        
        return null;
    }
}
