<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class VerifyAttendanceIntegrity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify attendance data integrity and show system status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verifying attendance data integrity...');
        $this->newLine();
        
        // Basic statistics
        $this->showBasicStats();
        
        // Check for time inconsistencies
        $this->checkTimeConsistency();
        
        // Check for testing data
        $this->checkTestingData();
        
        // Check for duplicates
        $this->checkDuplicates();
        
        // Show session distribution
        $this->showSessionDistribution();
        
        // Show recent activity
        $this->showRecentActivity();
        
        $this->newLine();
        $this->info('✅ Verification completed!');
    }
    
    private function showBasicStats()
    {
        $totalRecords = Absensi::count();
        $totalUsers = User::count();
        $todayRecords = Absensi::whereDate('presensi_at', today())->count();
        $thisWeekRecords = Absensi::whereBetween('presensi_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
        
        $this->info('📊 BASIC STATISTICS');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Attendance Records', $totalRecords],
                ['Total Users', $totalUsers],
                ['Today\'s Attendance', $todayRecords],
                ['This Week\'s Attendance', $thisWeekRecords],
            ]
        );
    }
    
    private function checkTimeConsistency()
    {
        $this->info('⏰ CHECKING TIME CONSISTENCY');
        
        $sessionTimes = [
            'Pagi (09.00-12.00 WIB)' => ['start' => '09:00', 'end' => '12:00'],
            'Siang (13.00-15.00 WIB)' => ['start' => '13:00', 'end' => '15:00'],
            'Malam (16.30-23.59 WIB)' => ['start' => '16:30', 'end' => '23:59'],
        ];
        
        $inconsistentRecords = collect();
        
        $allRecords = Absensi::all();
        foreach ($allRecords as $record) {
            $presensiTime = Carbon::parse($record->presensi_at, 'Asia/Jakarta');
            $correctSession = $this->detectCorrectSession($presensiTime, $sessionTimes);
            
            if ($record->sesi_presensi !== $correctSession) {
                $inconsistentRecords->push([
                    'ID' => $record->id,
                    'Nama' => $record->nama_murid,
                    'Current Session' => $record->sesi_presensi,
                    'Expected Session' => $correctSession ?: 'Invalid Time',
                    'Time' => $presensiTime->format('H:i:s'),
                    'Date' => $presensiTime->format('Y-m-d')
                ]);
            }
        }
        
        if ($inconsistentRecords->isEmpty()) {
            $this->info('✅ All attendance records have consistent session timing!');
        } else {
            $this->warn("⚠️  Found {$inconsistentRecords->count()} records with time inconsistencies:");
            $this->table(
                ['ID', 'Nama', 'Current Session', 'Expected Session', 'Time', 'Date'],
                $inconsistentRecords->take(10)->toArray()
            );
            if ($inconsistentRecords->count() > 10) {
                $this->warn("... and " . ($inconsistentRecords->count() - 10) . " more records.");
            }
        }
    }
    
    private function checkTestingData()
    {
        $this->info('🧪 CHECKING FOR TESTING DATA');
        
        $testingRecords = Absensi::where(function ($query) {
            $query->where('nama_murid', 'LIKE', '%Testing%')
                  ->orWhere('nama_murid', 'LIKE', '%Test%')
                  ->orWhere('user_email', 'LIKE', '%testing@%')
                  ->orWhere('user_email', 'LIKE', '%test@%');
        })->count();
        
        $testingUsers = User::where(function ($query) {
            $query->where('name', 'LIKE', '%Testing%')
                  ->orWhere('name', 'LIKE', '%Test%')
                  ->orWhere('email', 'LIKE', '%testing@%')
                  ->orWhere('email', 'LIKE', '%test@%');
        })->count();
        
        if ($testingRecords === 0 && $testingUsers === 0) {
            $this->info('✅ No testing data found - system is clean!');
        } else {
            $this->warn("⚠️  Found {$testingRecords} testing attendance records and {$testingUsers} testing users.");
            $this->warn('💡 Run "php artisan testing:clean" to remove testing data.');
        }
    }
    
    private function checkDuplicates()
    {
        $this->info('👥 CHECKING FOR DUPLICATE RECORDS');
        
        $duplicates = Absensi::select('nama_murid', 'kelas', 'sesi_presensi', 'presensi_date')
            ->groupBy('nama_murid', 'kelas', 'sesi_presensi', 'presensi_date')
            ->havingRaw('COUNT(*) > 1')
            ->get();
        
        if ($duplicates->isEmpty()) {
            $this->info('✅ No duplicate records found!');
        } else {
            $this->warn("⚠️  Found {$duplicates->count()} potential duplicate groups:");
            foreach ($duplicates->take(5) as $duplicate) {
                $count = Absensi::where('nama_murid', $duplicate->nama_murid)
                    ->where('kelas', $duplicate->kelas)
                    ->where('sesi_presensi', $duplicate->sesi_presensi)
                    ->where('presensi_date', $duplicate->presensi_date)
                    ->count();
                $this->line("- {$duplicate->nama_murid} ({$duplicate->kelas}) - {$duplicate->sesi_presensi} on {$duplicate->presensi_date}: {$count} records");
            }
        }
    }
    
    private function showSessionDistribution()
    {
        $this->info('📈 SESSION DISTRIBUTION');
        
        $distribution = Absensi::selectRaw('sesi_presensi, COUNT(*) as count')
            ->groupBy('sesi_presensi')
            ->orderBy('count', 'desc')
            ->get();
        
        $this->table(
            ['Session', 'Count', 'Percentage'],
            $distribution->map(function($item) use ($distribution) {
                $total = $distribution->sum('count');
                $percentage = $total > 0 ? round(($item->count / $total) * 100, 1) : 0;
                return [
                    $item->sesi_presensi,
                    $item->count,
                    $percentage . '%'
                ];
            })->toArray()
        );
    }
    
    private function showRecentActivity()
    {
        $this->info('🕒 RECENT ACTIVITY (Last 10 records)');
        
        $recent = Absensi::latest('presensi_at')->take(10)->get();
        
        $this->table(
            ['ID', 'Nama', 'Sesi', 'Waktu', 'Email'],
            $recent->map(function($record) {
                return [
                    $record->id,
                    Str::limit($record->nama_murid, 20),
                    $record->sesi_presensi,
                    Carbon::parse($record->presensi_at)->format('Y-m-d H:i:s'),
                    Str::limit($record->user_email, 25)
                ];
            })->toArray()
        );
    }
    
    private function detectCorrectSession($time, $sessionTimes)
    {
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
