<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUsersForTraditionalLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update admin user
        User::updateOrCreate(
            ['email' => 'admin@smkn1sby.sch.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('Surabaya99'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Update testing user  
        User::updateOrCreate(
            ['email' => 'testing@smkn1sby.sch.id'],
            [
                'name' => 'Testing User',
                'password' => Hash::make('testing123'),
                'is_admin' => true,
                'is_testing' => true,
                'email_verified_at' => now(),
            ]
        );

        // Update all existing users who need a password (no password OR no temp_password flag set)
        $usersNeedingPassword = User::where(function($q){
                $q->whereNull('password')->orWhere('password','');
            })
            ->orWhereNull('temp_password')
            ->where('is_admin', false)
            ->get();

        foreach ($usersNeedingPassword as $user) {
            $user->forceFill([
                'password' => Hash::make('defaultpass123'),
                'temp_password' => 'defaultpass123',
                'force_password_change' => true,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();

            $this->command->info("Reset user: {$user->email} -> defaultpass123");
        }

        $this->command->info('Users updated successfully for traditional login system!');
        $this->command->info('Admin: admin@smkn1sby.sch.id / Surabaya99');
        $this->command->info('Testing: testing@smkn1sby.sch.id / testing123');
        $this->command->info('Other users: [their_email] / defaultpass123 (WAJIB ganti password saat login)');
    }
}
