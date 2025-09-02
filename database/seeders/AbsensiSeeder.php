<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Absensi;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing sample data first
        Absensi::where('user_email', 'like', '%@student.smkn1sby.sch.id')->delete();
        
        echo "Creating sample attendance data for SMKN 1 Surabaya students...\n";
        // Data sample siswa SMKN 1 Surabaya
        $siswaData = [
            ['nama' => 'Ahmad Rizki', 'kelas' => '12 RPL 1'],
            ['nama' => 'Siti Nurhaliza', 'kelas' => '12 RPL 1'],
            ['nama' => 'Budi Santoso', 'kelas' => '12 RPL 2'],
            ['nama' => 'Ani Wijaya', 'kelas' => '12 RPL 2'],
            ['nama' => 'Deni Pratama', 'kelas' => '12 TKJ 1'],
            ['nama' => 'Eka Putri', 'kelas' => '12 TKJ 1'],
            ['nama' => 'Farid Hidayat', 'kelas' => '12 TKJ 2'],
            ['nama' => 'Gita Sari', 'kelas' => '12 TKJ 2'],
            ['nama' => 'Hendra Kurniawan', 'kelas' => '12 MM 1'],
            ['nama' => 'Indah Permatasari', 'kelas' => '12 MM 1'],
            ['nama' => 'Joko Widodo', 'kelas' => '12 TSM 1'],
            ['nama' => 'Kartika Dewi', 'kelas' => '12 TSM 1'],
            ['nama' => 'Lukman Hakim', 'kelas' => '12 TGB 1'],
            ['nama' => 'Maya Sari', 'kelas' => '12 TGB 1'],
            ['nama' => 'Nanda Pratiwi', 'kelas' => '12 TBSM 1'],
            ['nama' => 'Omar Abdullah', 'kelas' => '12 TBSM 1'],
            ['nama' => 'Putra Mahendra', 'kelas' => '12 TKRO 1'],
            ['nama' => 'Qori Amalia', 'kelas' => '12 TKRO 1'],
            ['nama' => 'Randi Firmansyah', 'kelas' => '12 TP 1'],
            ['nama' => 'Sari Mulyani', 'kelas' => '12 TP 1'],
        ];

        $konsentrasiKeahlian = [
            'Rekayasa Perangkat Lunak',
            'Teknik Komputer dan Jaringan',
            'Multimedia',
            'Teknik Kendaraan Ringan Otomotif',
            'Teknik dan Bisnis Sepeda Motor',
            'Teknik Bodi Otomotif',
            'Teknik Permesinan',
            'Teknik Pengelasan',
            'Teknik Gambar Bangunan'
        ];

        $perusahaanData = [
            ['nama' => 'PT. Telkom Indonesia', 'alamat' => 'Jl. Ketintang No. 156, Surabaya'],
            ['nama' => 'PT. Indosat Ooredoo', 'alamat' => 'Jl. Raya Darmo No. 68, Surabaya'],
            ['nama' => 'CV. Digital Solution', 'alamat' => 'Jl. Gubeng Pojok No. 25, Surabaya'],
            ['nama' => 'PT. Astra Honda Motor', 'alamat' => 'Jl. Waru Industrial Estate, Sidoarjo'],
            ['nama' => 'PT. Toyota Motor', 'alamat' => 'Jl. Ahmad Yani No. 288, Surabaya'],
            ['nama' => 'CV. Multimedia Kreatif', 'alamat' => 'Jl. Pemuda No. 31, Surabaya'],
            ['nama' => 'PT. Pembangunan Jaya', 'alamat' => 'Jl. Mayjen Sungkono No. 89, Surabaya'],
            ['nama' => 'CV. Otomotif Sejahtera', 'alamat' => 'Jl. Rungkut Industri No. 15, Surabaya'],
        ];

        $pembimbingSekolah = [
            'Pak Ainun', 'Bu Fitrah', 'Pak Maharani', 'Bu Humaira', 'Pak Komang',
            'Bu Sari', 'Pak Budi', 'Bu Dewi', 'Pak Agus', 'Bu Rina'
        ];

        $pembimbingDudika = [
            'Pak Hendro', 'Bu Marina', 'Pak Sucipto', 'Bu Lestari', 'Pak Rahman',
            'Bu Anita', 'Pak Wahyu', 'Bu Endah', 'Pak Toni', 'Bu Yanti'
        ];

        $sesiPresensi = [
            '10.00 WIB (Pagi)',
            '14.00 WIB (Siang)', 
            '16.30 WIB (Sore)'
        ];

        // Generate data untuk 30 hari terakhir
        for ($i = 30; $i >= 1; $i--) {
            $tanggal = Carbon::now()->subDays($i);
            
            // Skip weekend
            if ($tanggal->isWeekend()) {
                continue;
            }

            foreach ($siswaData as $siswa) {
                // 80% kemungkinan siswa hadir
                if (rand(1, 100) <= 80) {
                    $perusahaan = $perusahaanData[array_rand($perusahaanData)];
                    $sesi = $sesiPresensi[array_rand($sesiPresensi)];
                    
                    // Set waktu presensi berdasarkan sesi
                    $waktuPresensi = $tanggal->copy();
                    switch ($sesi) {
                        case '10.00 WIB (Pagi)':
                            $waktuPresensi->setTime(10, rand(0, 15), rand(0, 59));
                            break;
                        case '14.00 WIB (Siang)':
                            $waktuPresensi->setTime(14, rand(0, 15), rand(0, 59));
                            break;
                        case '16.30 WIB (Sore)':
                            $waktuPresensi->setTime(16, rand(30, 45), rand(0, 59));
                            break;
                    }

                    // Tentukan konsentrasi berdasarkan kelas
                    $konsentrasi = '';
                    if (str_contains($siswa['kelas'], 'RPL')) {
                        $konsentrasi = 'Rekayasa Perangkat Lunak';
                    } elseif (str_contains($siswa['kelas'], 'TKJ')) {
                        $konsentrasi = 'Teknik Komputer dan Jaringan';
                    } elseif (str_contains($siswa['kelas'], 'MM')) {
                        $konsentrasi = 'Multimedia';
                    } elseif (str_contains($siswa['kelas'], 'TSM')) {
                        $konsentrasi = 'Teknik dan Bisnis Sepeda Motor';
                    } elseif (str_contains($siswa['kelas'], 'TGB')) {
                        $konsentrasi = 'Teknik Gambar Bangunan';
                    } elseif (str_contains($siswa['kelas'], 'TBSM')) {
                        $konsentrasi = 'Teknik Bodi Otomotif';
                    } elseif (str_contains($siswa['kelas'], 'TKRO')) {
                        $konsentrasi = 'Teknik Kendaraan Ringan Otomotif';
                    } elseif (str_contains($siswa['kelas'], 'TP')) {
                        $konsentrasi = 'Teknik Permesinan';
                    }

                    Absensi::create([
                        'konsentrasi_keahlian' => $konsentrasi,
                        'nama_murid' => $siswa['nama'],
                        'kelas' => $siswa['kelas'],
                        'nama_perusahaan' => $perusahaan['nama'],
                        'alamat_perusahaan' => $perusahaan['alamat'],
                        'nama_pembimbing_sekolah' => $pembimbingSekolah[array_rand($pembimbingSekolah)],
                        'nama_pembimbing_dudika' => $pembimbingDudika[array_rand($pembimbingDudika)],
                        'sesi_presensi' => $sesi,
                        'foto_path' => 'default/sample_photo.jpg', // dummy photo path
                        'presensi_at' => $waktuPresensi,
                        'user_email' => strtolower(str_replace(' ', '.', $siswa['nama'])) . '@student.smkn1sby.sch.id',
                        'created_at' => $waktuPresensi,
                        'updated_at' => $waktuPresensi,
                    ]);
                }
            }
        }
    }
}
