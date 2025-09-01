<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;

class GenerateAbsensiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:absensi {count=50 : Number of records to generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sample absensi data using factory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->argument('count');
        
        $this->info("Generating {$count} absensi records...");
        
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        for ($i = 0; $i < $count; $i++) {
            Absensi::factory()->create();
            $bar->advance();
        }
        
        $bar->finish();
        
        $total = Absensi::count();
        $this->newLine();
        $this->info("Successfully generated {$count} new records!");
        $this->info("Total absensi records: {$total}");
        
        return Command::SUCCESS;
    }
}
