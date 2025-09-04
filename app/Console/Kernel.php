<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check and fix invalid attendance data every hour
        $schedule->command('attendance:fix-time')
                 ->hourly()
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/attendance-fix.log'));
        
        // Verify attendance integrity daily at 23:30
        $schedule->command('attendance:verify')
                 ->dailyAt('23:30')
                 ->appendOutputTo(storage_path('logs/attendance-verify.log'));
        
        // Clean testing data weekly (Sundays at 02:00)
        $schedule->command('testing:clean')
                 ->weekly()
                 ->sundays()
                 ->at('02:00')
                 ->appendOutputTo(storage_path('logs/testing-cleanup.log'));

    // NOTE: Command "students:reset-passwords" sengaja TIDAK dijadwalkan otomatis
    // karena reset massal password siswa harus tindakan sadar admin.
    // Jalankan manual jika diperlukan:
    //   php artisan students:reset-passwords              (pakai defaultpass123)
    //   php artisan students:reset-passwords --pw=Rahasia2025
    //   php artisan students:reset-passwords --random    (acak unik per siswa)
    //   php artisan students:reset-passwords --dry-run   (preview tanpa simpan)
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
