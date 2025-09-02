<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $q = Absensi::query();
        if ($request->filled('sesi')) {
            $q->where('sesi_presensi', $request->sesi);
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
        
        // Get total count before pagination
        $totalRecords = $q->count();
        
        $data = $q->latest('presensi_at')->paginate(50)->withQueryString();
        // Normalisasi nama sesi agar konsisten (data lama & baru)
        $canonicalSessions = [
            'Pagi (09.00-12.00 WIB)'  => ['pagi', '10.00', '09.00'],
            'Siang (13.00-15.00 WIB)' => ['siang', '14.00', '13.00'],
            'Malam (16.30-23.59 WIB)' => ['malam', '16.30', 'sore', '17.00']
        ];

        // Ambil semua sesi (hanya kolom sesi)
        $sessionValues = (clone $q)->pluck('sesi_presensi');
        $rekapMap = [];
        foreach (array_keys($canonicalSessions) as $key) {
            $rekapMap[$key] = 0;
        }

        foreach ($sessionValues as $value) {
            $valLower = strtolower($value ?? '');
            $matched = false;
            foreach ($canonicalSessions as $canonical => $needles) {
                foreach ($needles as $needle) {
                    if (str_contains($valLower, $needle)) {
                        $rekapMap[$canonical]++;
                        $matched = true;
                        break 2;
                    }
                }
            }
            if (!$matched && $value) { // sesi yang tidak terdeteksi, tampilkan apa adanya
                $rekapMap[$value] = ($rekapMap[$value] ?? 0) + 1;
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

        return view('admin.dashboard', compact('data','rekapPerSesi','totalRecords'));
    }
}
