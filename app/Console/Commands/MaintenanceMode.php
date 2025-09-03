<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MaintenanceMode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:toggle {action? : up/down/status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Toggle maintenance mode for the attendance system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action') ?? 'status';
        $configPath = config_path('app.php');
        
        switch ($action) {
            case 'down':
                $this->enableMaintenance();
                break;
            case 'up':
                $this->disableMaintenance();
                break;
            case 'status':
                $this->showStatus();
                break;
            default:
                $this->error('Invalid action. Use: up, down, or status');
        }
    }

    private function enableMaintenance()
    {
        // Set maintenance mode di environment
        $envPath = base_path('.env');
        
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            
            if (strpos($envContent, 'MAINTENANCE_MODE=') !== false) {
                $envContent = preg_replace('/MAINTENANCE_MODE=.*/', 'MAINTENANCE_MODE=true', $envContent);
            } else {
                $envContent .= "\nMAINTENANCE_MODE=true\n";
            }
            
            File::put($envPath, $envContent);
        }

        // Buat file flag maintenance
        $flagPath = storage_path('framework/maintenance');
        File::put($flagPath, json_encode([
            'enabled_at' => now()->toISOString(),
            'message' => 'Sistem sedang dalam maintenance',
            'admin_bypass' => true
        ]));

        $this->info('🔧 Maintenance mode ENABLED');
        $this->info('💡 Admin masih bisa mengakses sistem');
        $this->line('📋 Untuk menonaktifkan: php artisan maintenance:toggle up');
    }

    private function disableMaintenance()
    {
        // Hapus flag maintenance
        $flagPath = storage_path('framework/maintenance');
        if (File::exists($flagPath)) {
            File::delete($flagPath);
        }

        // Update environment
        $envPath = base_path('.env');
        
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            $envContent = preg_replace('/MAINTENANCE_MODE=.*/', 'MAINTENANCE_MODE=false', $envContent);
            File::put($envPath, $envContent);
        }

        $this->info('✅ Maintenance mode DISABLED');
        $this->info('🚀 Sistem kembali normal dan dapat diakses semua user');
    }

    private function showStatus()
    {
        $flagPath = storage_path('framework/maintenance');
        $isMaintenanceActive = File::exists($flagPath);
        
        if ($isMaintenanceActive) {
            $data = json_decode(File::get($flagPath), true);
            $this->warn('🔧 STATUS: MAINTENANCE MODE ACTIVE');
            $this->line('📅 Enabled at: ' . $data['enabled_at']);
            $this->line('💬 Message: ' . ($data['message'] ?? 'No message'));
            $this->line('👑 Admin bypass: ' . ($data['admin_bypass'] ? 'Yes' : 'No'));
        } else {
            $this->info('✅ STATUS: NORMAL MODE');
            $this->line('🚀 Sistem berjalan normal');
        }
        
        $this->line('');
        $this->line('📋 Available commands:');
        $this->line('  php artisan maintenance:toggle down  - Enable maintenance');
        $this->line('  php artisan maintenance:toggle up    - Disable maintenance');
        $this->line('  php artisan maintenance:toggle       - Show status');
    }
}
