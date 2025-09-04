<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Carbon\Carbon;
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
        
    // Base query (akan dipakai untuk dua tujuan: rekap penuh & pagination)
    $baseQuery = Absensi::query();

        // Default: tampilkan hanya presensi HARI INI kecuali user memberi filter tanggal
        $isDaily = false;
        if (!$request->filled('tanggal')) {
            $today = Carbon::today();
            $baseQuery->whereDate('presensi_date', $today);
            $isDaily = true;
        }
        
        // Filter sesi dengan normalisasi
        if ($request->filled('sesi')) {
            $sesiFilter = $request->sesi;
            $baseQuery->where(function($query) use ($sesiFilter) {
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
            $baseQuery->where('kelas', 'like', '%'.$request->kelas.'%');
        }
        if ($request->filled('tanggal')) {
            $baseQuery->whereDate('presensi_date', $request->tanggal);
        }
        if ($request->filled('konsentrasi')) {
            $baseQuery->where('konsentrasi_keahlian', $request->konsentrasi);
        }
        
        // Clone untuk rekap (FULL dataset TANPA limit/offset pagination)
        $rekapQuery = clone $baseQuery; // aman sebelum pagination

        // Ambil seluruh data untuk rekap & statistik
        $allPresence = $rekapQuery->get();
        $totalAllPresence = $allPresence->count();

        // Total record (bisa juga pakai $totalAllPresence agar konsisten)
        $totalRecords = $totalAllPresence;

        // Pagination: pakai clone terpisah agar tidak mempengaruhi rekap
        $data = (clone $baseQuery)
            ->latest('presensi_at')
            ->paginate(50)
            ->withQueryString();
        
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

        foreach ($allPresence as $record) {
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

        // Susun rekap untuk view (tampilkan juga yang 0 agar terlihat lengkap)
        $rekapPerSesi = collect();
        foreach ($rekapMap as $label => $count) {
            $rekapPerSesi->push([
                'label' => $label,
                'total' => $count,
                'percent' => $totalAllPresence > 0 ? round($count / $totalAllPresence * 100, 1) : 0
            ]);
        }

        // Urutkan sesuai urutan canonical
        $rekapPerSesi = $rekapPerSesi->sortBy(function($item) use ($canonicalSessions) {
            return array_search($item['label'], array_keys($canonicalSessions));
        })->values();

        // Normalisasi kelas untuk rekap SEMUA DATA
        $rekapPerKelas = collect();
        $kelasMap = [];
        
        foreach ($allPresence as $record) {
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
        
        foreach ($allPresence as $record) {
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

        // Label untuk menjelaskan cakupan data pada statistik
        $scopeLabel = $isDaily
            ? 'Harian (Hari Ini)'
            : ($request->filled('tanggal') ? 'Tanggal Dipilih' : 'Filter Saat Ini');

        return view('admin.dashboard', compact(
            'data',
            'rekapPerSesi',
            'totalRecords',
            'rekapPerKelas',
            'rekapPerKonsentrasi',
            'sessionColors',
            'isDaily',
            'scopeLabel'
        ))->with('totalAllRecords', $totalAllPresence);
    }
}
