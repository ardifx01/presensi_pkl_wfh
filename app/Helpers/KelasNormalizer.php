<?php

namespace App\Helpers;

class KelasNormalizer
{
    /**
     * Mapping konsentrasi ke singkatan
     */
    private static $konsentrasiMapping = [
        'AKUNTANSI' => 'AK',
        'MANAJEMEN LOGISTIK' => 'ML', 
        'PERHOTELAN' => 'PH',
        'REKAYASA PERANGKAT LUNAK' => 'RPL',
        'BISNIS DIGITAL' => 'BD',
        'MANAJEMEN PERKANTORAN' => 'MP',
        'PRODUKSI DAN SIARAN PROGRAM TELEVISI' => 'PSPT',
        'TEKNIK KOMPUTER DAN JARINGAN' => 'TKJ',
        'DESAIN KOMUNIKASI VISUAL' => 'DKV',
    ];

    /**
     * Normalisasi nama kelas
     * Contoh: 12rpl1 -> 12 RPL 1, 12 AKUNTANSI 4 -> 12 AK 4
     */
    public static function normalize($kelas)
    {
        if (empty($kelas)) {
            return $kelas;
        }

        $kelas = strtoupper(trim($kelas));
        
        // Pattern untuk format seperti: 12RPL1, 12rpl1, 12 RPL 1, etc
        if (preg_match('/^(\d+)\s*([A-Z]+)\s*(\d+)$/i', $kelas, $matches)) {
            $tingkat = $matches[1];
            $jurusan = strtoupper($matches[2]);
            $nomor = $matches[3];
            
            // Cari singkatan jurusan
            $singkatan = self::getKonsentrasiSingkatan($jurusan);
            
            return "{$tingkat} {$singkatan} {$nomor}";
        }
        
        // Pattern untuk format dengan nama lengkap: 12 AKUNTANSI 4
        foreach (self::$konsentrasiMapping as $fullName => $shortName) {
            if (strpos($kelas, $fullName) !== false) {
                $kelas = str_replace($fullName, $shortName, $kelas);
                break;
            }
        }
        
        // Tambahkan spasi jika belum ada
        $kelas = preg_replace('/(\d+)([A-Z]+)(\d+)/', '$1 $2 $3', $kelas);
        
        return $kelas;
    }

    /**
     * Cari singkatan konsentrasi
     */
    private static function getKonsentrasiSingkatan($jurusan)
    {
        $jurusan = strtoupper($jurusan);
        
        // Cek jika sudah merupakan singkatan
        foreach (self::$konsentrasiMapping as $fullName => $shortName) {
            if ($jurusan === $shortName) {
                return $shortName; // Sudah benar, return as is
            }
        }
        
        // Cek mapping langsung
        if (isset(self::$konsentrasiMapping[$jurusan])) {
            return self::$konsentrasiMapping[$jurusan];
        }
        
        // Cek partial match
        foreach (self::$konsentrasiMapping as $fullName => $shortName) {
            if (strpos($fullName, $jurusan) !== false || strpos($jurusan, $shortName) !== false) {
                return $shortName;
            }
        }
        
        // Jika tidak ketemu, return original tapi uppercase
        return $jurusan;
    }

    /**
     * Normalisasi sesi presensi
     */
    public static function normalizeSesi($sesi)
    {
        if (empty($sesi)) {
            return $sesi;
        }

        $sesi = strtolower(trim($sesi));
        
        // Mapping sesi
        $canonicalSessions = [
            'Pagi (09.00-12.00 WIB)' => ['pagi', '10.00', '09.00', 'morning'],
            'Siang (13.00-15.00 WIB)' => ['siang', '14.00', '13.00', 'afternoon'], 
            'Malam (16.30-23.59 WIB)' => ['malam', '16.30', 'sore', '17.00', 'evening', 'night']
        ];

        foreach ($canonicalSessions as $canonical => $variants) {
            foreach ($variants as $variant) {
                if (strpos($sesi, $variant) !== false) {
                    return $canonical;
                }
            }
        }

        return $sesi;
    }
}
