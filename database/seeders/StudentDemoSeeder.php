<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo student users
        $students = [
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@student.smkn1sby.sch.id',
                'password' => 'student123'
            ],
            [
                'name' => 'Siti Nurhaliza', 
                'email' => 'siti.nurhaliza@student.smkn1sby.sch.id',
                'password' => 'student123'
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@student.smkn1sby.sch.id', 
                'password' => 'student123'
            ]
        ];

        foreach ($students as $student) {
            User::updateOrCreate(
                ['email' => $student['email']],
                [
                    'name' => $student['name'],
                    'password' => Hash::make($student['password']),
                    'is_admin' => false,
                    'is_testing' => false,
                    'email_verified_at' => now(),
                ]
            );
            
            $this->command->info("Demo student created: {$student['email']} (password: {$student['password']})");
        }

        $this->command->info('Demo student users created successfully!');
        $this->command->info('Semua siswa demo bisa login dan data absensi mereka akan tersimpan berdasarkan email.');
    }
}
