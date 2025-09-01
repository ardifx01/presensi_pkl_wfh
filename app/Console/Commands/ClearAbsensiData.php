<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;

class ClearAbsensiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:absensi {--force : Force deletion without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all absensi data from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $totalRecords = Absensi::count();
        
        if ($totalRecords === 0) {
            $this->info('No absensi records found to delete.');
            return Command::SUCCESS;
        }
        
        $this->warn("Found {$totalRecords} absensi records.");
        
        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to delete ALL absensi data? This action cannot be undone.')) {
                $this->info('Operation cancelled.');
                return Command::FAILURE;
            }
        }
        
        $this->info('Deleting all absensi records...');
        
        $bar = $this->output->createProgressBar($totalRecords);
        $bar->start();
        
        // Delete in chunks to avoid memory issues
        Absensi::chunk(100, function ($records) use ($bar) {
            foreach ($records as $record) {
                $record->delete();
                $bar->advance();
            }
        });
        
        $bar->finish();
        $this->newLine();
        $this->info("Successfully deleted {$totalRecords} absensi records!");
        
        return Command::SUCCESS;
    }
}
