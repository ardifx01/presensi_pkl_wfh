<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestAdminLogin extends Command
{
    protected $signature = 'test:admin-login';
    protected $description = 'Test admin login functionality';

    public function handle()
    {
        $this->info('Testing admin login functionality...');
        
        // Check if admin user exists
        $admin = User::where('email', 'admin@smkn1sby.sch.id')->first();
        
        if (!$admin) {
            $this->error('Admin user not found!');
            return;
        }
        
        $this->info('✅ Admin user found:');
        $this->line('   Name: ' . $admin->name);
        $this->line('   Email: ' . $admin->email);
        $this->line('   Is Admin: ' . ($admin->is_admin ? 'Yes' : 'No'));
        
        // Test password
        $password = 'Surabaya99';
        if (Hash::check($password, $admin->password)) {
            $this->info('✅ Password verification successful');
        } else {
            $this->error('❌ Password verification failed');
        }
        
        // Check maintenance status
        $flagPath = storage_path('framework/maintenance');
        $maintenanceActive = file_exists($flagPath);
        
        $this->line('');
        $this->info('Maintenance Status: ' . ($maintenanceActive ? 'ACTIVE' : 'INACTIVE'));
        
        if ($maintenanceActive) {
            $this->line('Maintenance file content:');
            $this->line(file_get_contents($flagPath));
        }
        
        $this->line('');
        $this->info('🔧 During maintenance mode:');
        $this->line('1. Admin should be able to access /login');
        $this->line('2. Admin should be able to POST to /login');
        $this->line('3. After login, admin should bypass all maintenance checks');
        $this->line('4. Regular users should see maintenance page');
    }
}
