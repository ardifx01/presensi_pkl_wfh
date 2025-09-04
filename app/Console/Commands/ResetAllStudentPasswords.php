<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetAllStudentPasswords extends Command
{
    protected $signature = 'students:reset-passwords {--random : Gunakan password acak unik untuk setiap siswa} {--pw= : Set password kustom (override default)} {--dry-run : Hanya tampilkan yang akan diubah}';

    protected $description = 'Reset password seluruh siswa non-admin & non-testing, set temp_password & force ganti password';

    public function handle(): int
    {
        $useRandom = $this->option('random');
        $custom = $this->option('pw');
        $dry = $this->option('dry-run');

        $default = $custom ?: 'defaultpass123';

        $query = User::query()
            ->where('is_admin', false)
            ->where(function($q){
                $q->whereNull('is_testing')->orWhere('is_testing', false);
            });

        $users = $query->get();
        if ($users->isEmpty()) {
            $this->info('Tidak ada siswa ditemukan.');
            return self::SUCCESS;
        }

        $this->info('Total siswa: '.$users->count());
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $rows = [];
        foreach ($users as $user) {
            $plain = $useRandom ? Str::password(10) : $default;

            $rows[] = [
                'email' => $user->email,
                'password' => $plain,
            ];

            if (!$dry) {
                $user->forceFill([
                    'password' => Hash::make($plain),
                    'temp_password' => $plain,
                    'force_password_change' => true,
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ])->save();
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine(2);

        $this->table(['Email','Temp Password'], $rows);

        if ($dry) {
            $this->warn('DRY RUN: Tidak ada perubahan disimpan. Jalankan tanpa --dry-run untuk menerapkan.');
        } else {
            $this->info('Selesai reset password semua siswa. Mereka akan dipaksa ganti saat login.');
        }

        return self::SUCCESS;
    }
}
