<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat admin user default
        User::updateOrCreate(
            ['email' => 'admin@smkn1sby.sch.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('Surabaya99'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created: admin@smkn1sby.sch.id (password: Surabaya99)');
    }
}
