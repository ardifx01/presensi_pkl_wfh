<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Helpers\KelasNormalizer;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Redirect testing user ke form presensi
        if (auth()->check() && auth()->user()->is_testing) {
            return redirect()->route('absensi.create')->with('info', 'Akun testing dialihkan ke form presensi.');
        }
        
        $q = Absensi::query();
        
        // Filter sesi dengan normalisasi
        if ($request->filled('sesi')) {
            $sesiFilter = $request->sesi;
            $q->where(function($query) use ($sesiFilter) {
                // Cari berdasarkan nama canonical dan variants
                $canonicalSessions = [
                    'Pagi (09.00-12.00 WIB)' => ['pagi', '10.00', '09.00', 'morning'],
                    'Siang (13.00-15.00 WIB)' => ['siang', '14.00', '13.00', 'afternoon'], 
                    'Malam (16.30-23.59 WIB)' => ['malam', '16.30', 'sore', '17.00', 'evening', 'night']
                ];
                
                if (isset($canonicalSessions[$sesiFilter])) {
                    $query->where('sesi_presensi', $sesiFilter);
                    foreach ($canonicalSessions[$sesiFilter] as $variant) {
                        $query->orWhere('sesi_presensi', 'like', '%' . $variant . '%');
                    }
                } else {
                    $query->where('sesi_presensi', 'like', '%' . $sesiFilter . '%');
                }
            });
        }
        
        if ($request->filled('kelas')) {
            $q->where('kelas', 'like', '%'.$request->kelas.'%');
        }
        if ($request->filled('tanggal')) {
            $q->whereDate('presensi_date', $request->tanggal);
        }
        if ($request->filled('konsentrasi')) {
            $q->where('konsentrasi_keahlian', $request->konsentrasi);
        }
        
        // Get total count untuk rekap gabungan semua page
        $totalRecords = $q->count();
        
        // Paginate data
        $data = $q->latest('presensi_at')->paginate(50)->withQueryString();
        
        // REKAP GABUNGAN SEMUA PAGE (bukan hanya current page)
        $rekapQuery = clone $q;
        $allRecords = $rekapQuery->get();
        
        // Normalisasi dan rekap per sesi untuk SEMUA data
        $canonicalSessions = [
            'Pagi (09.00-12.00 WIB)' => ['pagi', '10.00', '09.00', 'morning'],
            'Siang (13.00-15.00 WIB)' => ['siang', '14.00', '13.00', 'afternoon'],
            'Malam (16.30-23.59 WIB)' => ['malam', '16.30', 'sore', '17.00', 'evening', 'night']
        ];

        $rekapMap = [];
        foreach (array_keys($canonicalSessions) as $key) {
            $rekapMap[$key] = 0;
        }

        foreach ($allRecords as $record) {
            $sesiNormalized = KelasNormalizer::normalizeSesi($record->sesi_presensi);
            
            $matched = false;
            foreach ($canonicalSessions as $canonical => $needles) {
                foreach ($needles as $needle) {
                    if (stripos($record->sesi_presensi, $needle) !== false || $sesiNormalized === $canonical) {
                        $rekapMap[$canonical]++;
                        $matched = true;
                        break 2;
                    }
                }
            }
            if (!$matched && $record->sesi_presensi) {
                $rekapMap[$record->sesi_presensi] = ($rekapMap[$record->sesi_presensi] ?? 0) + 1;
            }
        }

        // Susun rekap untuk view (dengan persentase)
        $rekapPerSesi = collect();
        foreach ($rekapMap as $label => $count) {
            if ($count === 0) continue; // sembunyikan yang kosong agar rapi
            $rekapPerSesi->push([
                'label' => $label,
                'total' => $count,
                'percent' => $totalRecords > 0 ? round($count / $totalRecords * 100, 1) : 0
            ]);
        }

        // Urutkan sesuai urutan canonical
        $rekapPerSesi = $rekapPerSesi->sortBy(function($item) use ($canonicalSessions) {
            return array_search($item['label'], array_keys($canonicalSessions));
        })->values();

        // Normalisasi kelas untuk rekap SEMUA DATA
        $rekapPerKelas = collect();
        $kelasMap = [];
        
        foreach ($allRecords as $record) {
            $kelasNormalized = KelasNormalizer::normalize($record->kelas);
            $kelasMap[$kelasNormalized] = ($kelasMap[$kelasNormalized] ?? 0) + 1;
        }
        
        foreach ($kelasMap as $kelas => $count) {
            $rekapPerKelas->push([
                'kelas' => $kelas,
                'total' => $count
            ]);
        }
        
        $rekapPerKelas = $rekapPerKelas->sortBy('kelas');

        // Rekap per konsentrasi - normalisasi dari SEMUA DATA
        $rekapPerKonsentrasi = collect();
        $konsentrasiMap = [];
        
        foreach ($allRecords as $record) {
            $konsentrasi = trim($record->konsentrasi_keahlian);
            $konsentrasiMap[$konsentrasi] = ($konsentrasiMap[$konsentrasi] ?? 0) + 1;
        }
        
        foreach ($konsentrasiMap as $konsentrasi => $count) {
            $rekapPerKonsentrasi->push([
                'konsentrasi_keahlian' => $konsentrasi,
                'total' => $count
            ]);
        }
        
        $rekapPerKonsentrasi = $rekapPerKonsentrasi->sortBy('konsentrasi_keahlian');

        // Normalisasi kelas untuk display di tabel utama
        foreach ($data as $item) {
            $item->kelas_normalized = KelasNormalizer::normalize($item->kelas);
            $item->sesi_normalized = KelasNormalizer::normalizeSesi($item->sesi_presensi);
        }

        // Warna khusus per sesi
        $sessionColors = [
            'Pagi (09.00-12.00 WIB)'  => 'primary',
            'Siang (13.00-15.00 WIB)' => 'warning',
            'Malam (16.30-23.59 WIB)' => 'dark',
        ];

        return view('admin.dashboard', compact(
            'data',
            'rekapPerSesi',
            'totalRecords',
            'rekapPerKelas',
            'rekapPerKonsentrasi',
            'sessionColors'
        ))->with('totalAllRecords', $totalRecords);
    }
}
