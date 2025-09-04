<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetAllStudentPasswords extends Command
{
    // Tambah opsi: --emails=, --ids=, --kelas=
    protected $signature = 'students:reset-passwords
        {--emails= : Comma separated email list}
        {--ids= : Comma separated user id list}
        {--kelas= : Filter kelas persis (misal: "12 AK 4")}
        {--random : Gunakan password acak unik untuk setiap siswa}
        {--pw= : Set password kustom (override default)}
        {--dry-run : Hanya tampilkan yang akan diubah}';

    protected $description = 'Reset password siswa (bisa semua / terpilih) + set temp_password & force ganti password';

    public function handle(): int
    {
        $useRandom = $this->option('random');
        $custom = $this->option('pw');
        $dry = $this->option('dry-run');
        $emailsOpt = $this->option('emails');
        $idsOpt = $this->option('ids');
        $kelasOpt = $this->option('kelas');

        $default = $custom ?: 'defaultpass123';

        $query = User::query()
            ->where('is_admin', false)
            ->where(function($q){
                $q->whereNull('is_testing')->orWhere('is_testing', false);
            });

        if ($emailsOpt) {
            $emails = collect(explode(',', $emailsOpt))
                ->map(fn($e)=>trim($e))
                ->filter()->unique();
            $query->whereIn('email', $emails);
        }

        if ($idsOpt) {
            $ids = collect(explode(',', $idsOpt))
                ->map(fn($e)=>trim($e))
                ->filter()->unique();
            $query->whereIn('id', $ids);
        }

        if ($kelasOpt) {
            // Asumsikan kolom kelas disimpan di kolom 'kelas' pada users
            $query->where('kelas', $kelasOpt);
        }

        $users = $query->get();
        if ($users->isEmpty()) {
            $this->warn('Tidak ada user sesuai filter.');
            return self::SUCCESS;
        }

        $this->info('Jumlah user terpilih: '.$users->count());
        $rows = [];

        foreach ($users as $user) {
            $plain = $useRandom ? Str::password(10) : $default;

            $rows[] = [
                'ID' => $user->id,
                'Email' => $user->email,
                'Kelas' => $user->kelas ?? '-',
                'Temp Password' => $plain,
            ];

            if (!$dry) {
                $user->forceFill([
                    'password' => Hash::make($plain),
                    'temp_password' => $plain,
                    'force_password_change' => true,
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ])->save();
            }
        }

        $this->table(['ID','Email','Kelas','Temp Password'], $rows);

        if ($dry) {
            $this->warn('DRY RUN: Tidak ada perubahan disimpan.');
        } else {
            $this->info('Selesai reset password.');
        }

        return self::SUCCESS;
    }
}
