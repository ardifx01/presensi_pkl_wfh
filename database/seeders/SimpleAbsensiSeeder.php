<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Absensi;
use Carbon\Carbon;

class SimpleAbsensiSeeder extends Seeder
{
    public function run(): void
    {
        echo "Creating simple test attendance data...\n";
        
        try {
            // Create just one test record
            Absensi::create([
                'konsentrasi_keahlian' => 'Rekayasa Perangkat Lunak',
                'nama_murid' => 'Test Student',
                'kelas' => '12 RPL 1',
                'nama_perusahaan' => 'Test Company',
                'alamat_perusahaan' => 'Test Address, Surabaya',
                'nama_pembimbing_sekolah' => 'Test Teacher',
                'nama_pembimbing_dudika' => 'Test Supervisor',
                'sesi_presensi' => 'Pagi (09.00-12.00 WIB)',
                'foto_path' => 'default/sample_photo.jpg',
                'presensi_at' => now(),
                'presensi_date' => now()->toDateString(),
                'user_email' => 'test.student@student.smkn1sby.sch.id',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            echo "✅ Test record created successfully!\n";
            
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    }
}
