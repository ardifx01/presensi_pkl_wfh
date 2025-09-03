<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestingUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user testing yang bisa absen kapan saja
        User::updateOrCreate(
            ['email' => 'testing@smkn1sby.sch.id'],
            [
                'name' => 'Testing User',
                'password' => Hash::make('testing123'),
                'is_admin' => true,
                'is_testing' => true, // Flag khusus untuk testing
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Testing user created: testing@smkn1sby.sch.id (password: testing123)');
        $this->command->info('User ini bisa absen kapan saja tanpa batasan waktu!!');
    }
}
