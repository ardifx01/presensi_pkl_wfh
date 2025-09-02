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
        
        // Shuffle the array to randomize the order
        shuffle($siswaData);

        $konsentrasiKeahlian = [
            'Rekayasa Perangkat Lunak' => ['RPL 1', 'RPL 2'],
            'Teknik Komputer dan Jaringan' => ['TKJ 1', 'TKJ 2'],
            'Multimedia' => ['MM 1', 'MM 2'],
            'Teknik Kendaraan Ringan Otomotif' => ['TSM 1', 'TSM 2'],
            'Teknik dan Bisnis Sepeda Motor' => ['TBSM 1', 'TBSM 2'],
            'Teknik Bodi Otomotif' => ['TGB 1', 'TGB 2'],
            'Teknik Permesinan' => ['TP 1', 'TP 2'],
            'Teknik Pengelasan' => ['TLAS 1', 'TLAS 2'],
            'Teknik Gambar Bangunan' => ['TGB 1', 'TGB 2']
        ];

        $perusahaan = [
            'PT. Telkom Indonesia' => 'Jl. Ketintang No. 156, Surabaya',
            'PT. Indosat Ooredoo' => 'Jl. Raya Darmo No. 68, Surabaya',
            'CV. Digital Solution' => 'Jl. Gubeng Pojok No. 25, Surabaya',
            'PT. Toyota Motor' => 'Jl. Ahmad Yani No. 288, Surabaya',
            'PT. Astra Honda Motor' => 'Jl. Laksda Adisucipto 166, Surabaya',
            'PT. Surya Toto Indonesia' => 'Jl. Raya Gresik Km. 19, Surabaya',
            'PT. Maspion' => 'Jl. Raya Kupang Indah, Surabaya',
            'PT. Sampoerna Tbk.' => 'Jl. Rungkut Industri Raya 18, Surabaya',
            'PT. Unilever Indonesia' => 'Jl. Rungkut Industri Raya 77, Surabaya',
            'PT. Sinar Sosro' => 'Jl. Raya Gresik Km. 22, Surabaya'
        ];

        $pembimbingSekolah = [
            'Bapak Ahmad, S.Kom' => 'ahmad@smkn1sby.sch.id',
            'Ibu Siti, S.Pd' => 'siti@smkn1sby.sch.id',
            'Bapak Budi, S.Kom' => 'budi@smkn1sby.sch.id',
            'Ibu Ani, S.Pd' => 'ani@smkn1sby.sch.id',
            'Bapak Joko, S.T' => 'joko@smkn1sby.sch.id',
        ];

        $pembimbingDudika = [
            'Bapak Agus, S.T.' => 'agus@jayaabadi.com',
            'Ibu Rini, S.E.' => 'rini@mandirisejahtera.com',
            'Bapak Eko, S.Kom' => 'eko@sumberrejeki.com',
            'Ibu Dewi, S.E.' => 'dewi@sentosajaya.com',
            'Bapak Bambang, S.T' => 'bambang@anugerahbaru.com',
        ];

        $sesiPresensi = [
            '10.00 WIB (Pagi)',
            '14.00 WIB (Siang)', 
            '16.30 WIB (Sore)'
        ];

        $absensiData = [];
        $now = now();
        
        // Generate 5 days of attendance data for each student
        for ($day = 0; $day < 5; $day++) {
            $presensiDate = $now->copy()->subDays($day);
            
            // Shuffle students for each day to ensure different order
            $shuffledSiswa = $siswaData;
            shuffle($shuffledSiswa);
            
            // Get a random selection of students (80% attendance rate)
            $attendanceCount = (int) (count($shuffledSiswa) * 0.8);
            $presentStudents = array_slice($shuffledSiswa, 0, $attendanceCount);
            
            foreach ($presentStudents as $index => $siswa) {
                // Determine konsentrasi based on class
                $konsentrasi = '';
                $kelasParts = explode(' ', $siswa['kelas']);
                $kelasJurusan = end($kelasParts);
                
                foreach ($konsentrasiKeahlian as $konsentrasiName => $jurusanList) {
                    if (in_array($kelasJurusan, $jurusanList)) {
                        $konsentrasi = $konsentrasiName;
                        break;
                    }
                }
                
                if (empty($konsentrasi)) {
                    $konsentrasi = array_rand($konsentrasiKeahlian);
                }
                
                // Select random company and mentors
                $perusahaanKey = array_rand($perusahaan);
                $perusahaanPilihan = $perusahaanKey;
                $alamatPerusahaan = $perusahaan[$perusahaanKey];
                
                $pembimbingSekolahKey = array_rand($pembimbingSekolah);
                $pembimbingSekolahPilihan = $pembimbingSekolahKey;
                $emailPembimbingSekolah = $pembimbingSekolah[$pembimbingSekolahKey];
                
                $pembimbingDudikaKey = array_rand($pembimbingDudika);
                $pembimbingDudikaPilihan = $pembimbingDudikaKey;
                $emailPembimbingDudika = $pembimbingDudika[$pembimbingDudikaKey];
                
                // Randomly select session (Pagi, Siang, or Sore)
                $sesi = $this->getRandomSesi();
                
                // Generate random time within the session
                $waktuPresensi = $this->generateRandomTimeForSesi($sesi, $presensiDate);
                
                // Generate a unique email for the student
                $emailPrefix = strtolower(str_replace(' ', '.', $siswa['nama']));
                $emailSuffix = '@student.smkn1sby.sch.id';
                $email = $emailPrefix . $emailSuffix;
                
                // Check if this student already has attendance for this date and session
                $existingAttendance = collect($absensiData)->first(function($item) use ($siswa, $waktuPresensi, $sesi) {
                    return $item['nama_murid'] === $siswa['nama'] && 
                           $item['presensi_date'] === $waktuPresensi->toDateString() &&
                           $item['sesi_presensi'] === $sesi;
                });
                
                if ($existingAttendance) {
                    // Skip if this student already has attendance for this date and session
                    continue;
                }
                
                // Add attendance record
                $absensiData[] = [
                    'user_id' => null, // Set to null since we don't have actual users
                    'user_email' => $email,
                    'konsentrasi_keahlian' => $konsentrasi,
                    'nama_murid' => $siswa['nama'],
                    'kelas' => $siswa['kelas'],
                    'nama_perusahaan' => $perusahaanPilihan,
                    'alamat_perusahaan' => $alamatPerusahaan,
                    'nama_pembimbing_sekolah' => $pembimbingSekolahPilihan,
                    'nama_pembimbing_dudika' => $pembimbingDudikaPilihan,
                    'sesi_presensi' => $sesi,
                    'presensi_at' => $waktuPresensi,
                    'presensi_date' => $waktuPresensi->toDateString(),
                    'foto_path' => 'sample/photo' . rand(1, 5) . '.jpg',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        
        // Save all attendance data to database
        echo "Saving " . count($absensiData) . " attendance records to database...\n";
        
        // Insert data in chunks to avoid memory issues
        $chunks = array_chunk($absensiData, 50);
        foreach ($chunks as $chunk) {
            Absensi::insert($chunk);
        }
        
        echo "Successfully created " . count($absensiData) . " attendance records!\n";
    }

    /**
     * Get a random session (Pagi, Siang, or Sore)
     */
    private function getRandomSesi(): string
    {
        $sessions = [
            '10.00 WIB (Pagi)',
            '14.00 WIB (Siang)',
            '16.30 WIB (Sore)'
        ];
        
        return $sessions[array_rand($sessions)];
    }

    /**
     * Generate a random time within the given session
     */
    private function generateRandomTimeForSesi(string $sesi, Carbon $date): Carbon
    {
        $time = clone $date;
        
        // Extract hour and minute from session string (e.g., '10.00 WIB (Pagi)' -> 10:00)
        preg_match('/(\d{1,2})\.(\d{2})/', $sesi, $matches);
        $hour = (int)$matches[1];
        $minute = (int)$matches[2];
        
        // Set base time
        $time->setTime($hour, $minute);
        
        // Add random minutes (0-59) to create some variation
        $randomMinutes = rand(0, 59);
        $time->addMinutes($randomMinutes);
        
        return $time;
    }
}
