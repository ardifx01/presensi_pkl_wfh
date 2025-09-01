<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absensi>
 */
class AbsensiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
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

        $kelasOptions = [
            '12 RPL 1', '12 RPL 2',
            '12 TKJ 1', '12 TKJ 2', 
            '12 MM 1', '12 MM 2',
            '12 TSM 1', '12 TSM 2',
            '12 TGB 1', '12 TGB 2',
            '12 TBSM 1', '12 TBSM 2',
            '12 TKRO 1', '12 TKRO 2',
            '12 TP 1', '12 TP 2'
        ];

        $sesiPresensi = [
            '10.00 WIB (Pagi)',
            '14.00 WIB (Siang)', 
            '16.30 WIB (Sore)'
        ];

        $perusahaanNames = [
            'PT. Telkom Indonesia',
            'PT. Indosat Ooredoo', 
            'CV. Digital Solution',
            'PT. Astra Honda Motor',
            'PT. Toyota Motor',
            'CV. Multimedia Kreatif',
            'PT. Pembangunan Jaya',
            'CV. Otomotif Sejahtera',
            'PT. Bank Mandiri',
            'CV. Teknologi Maju'
        ];

        $pembimbingSekolah = [
            'Pak Ainun', 'Bu Fitrah', 'Pak Maharani', 'Bu Humaira', 'Pak Komang',
            'Bu Sari', 'Pak Budi', 'Bu Dewi', 'Pak Agus', 'Bu Rina'
        ];

        $pembimbingDudika = [
            'Pak Hendro', 'Bu Marina', 'Pak Sucipto', 'Bu Lestari', 'Pak Rahman',
            'Bu Anita', 'Pak Wahyu', 'Bu Endah', 'Pak Toni', 'Bu Yanti'
        ];

        $sesi = fake()->randomElement($sesiPresensi);
        $tanggal = fake()->dateTimeBetween('-30 days', 'now');
        
        // Set waktu presensi berdasarkan sesi
        $waktuPresensi = \Carbon\Carbon::parse($tanggal);
        switch ($sesi) {
            case '10.00 WIB (Pagi)':
                $waktuPresensi->setTime(10, fake()->numberBetween(0, 15), fake()->numberBetween(0, 59));
                break;
            case '14.00 WIB (Siang)':
                $waktuPresensi->setTime(14, fake()->numberBetween(0, 15), fake()->numberBetween(0, 59));
                break;
            case '16.30 WIB (Sore)':
                $waktuPresensi->setTime(16, fake()->numberBetween(30, 45), fake()->numberBetween(0, 59));
                break;
        }

        $nama = fake()->name();
        
        return [
            'konsentrasi_keahlian' => fake()->randomElement($konsentrasiKeahlian),
            'nama_murid' => $nama,
            'kelas' => fake()->randomElement($kelasOptions),
            'nama_perusahaan' => fake()->randomElement($perusahaanNames),
            'alamat_perusahaan' => fake()->address(),
            'nama_pembimbing_sekolah' => fake()->randomElement($pembimbingSekolah),
            'nama_pembimbing_dudika' => fake()->randomElement($pembimbingDudika),
            'sesi_presensi' => $sesi,
            'foto_path' => 'default/sample_photo.jpg',
            'presensi_at' => $waktuPresensi,
            'presensi_date' => $waktuPresensi->toDateString(),
            'user_email' => strtolower(str_replace(' ', '.', $nama)) . '@student.smkn1sby.sch.id',
        ];
    }
}
