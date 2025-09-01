<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{
    public function create()
    {
        return view('absensi.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'konsentrasi_keahlian' => 'required|string|max:100',
            'nama_murid' => 'required|string|max:150',
            'kelas' => 'required|string|max:100',
            'nama_perusahaan' => 'required|string|max:150',
            'alamat_perusahaan' => 'required|string|max:255',
            'nama_pembimbing_sekolah' => 'required|string|max:150',
            'nama_pembimbing_dudika' => 'required|string|max:150',
            'sesi_presensi' => 'required|in:10.00 WIB (Pagi),14.00 WIB (Siang),16.30 WIB (Sore)',
            'foto_murid' => 'required|image|max:10240', // 10MB
        ]);

        // Validasi jam sesuai sesi (rentang toleransi 30 menit sebelum & sesudah)
        $now = now();
        $sessionWindows = [
            '10.00 WIB (Pagi)' => ['start' => '09:30', 'end' => '10:30'],
            '14.00 WIB (Siang)' => ['start' => '13:30', 'end' => '14:30'],
            '16.30 WIB (Sore)' => ['start' => '16:00', 'end' => '17:00'],
        ];
        $window = $sessionWindows[$validated['sesi_presensi']];
        $start = $now->copy()->setTime(...explode(':', $window['start']));
        $end = $now->copy()->setTime(...explode(':', $window['end']));
        if (!($now->between($start, $end))) {
            return back()->withErrors(['sesi_presensi' => 'Presensi untuk sesi ini hanya boleh antara '.$window['start'].' - '.$window['end'].' WIB'])->withInput();
        }

        // Simpan foto
        $path = $request->file('foto_murid')->store('absensi_foto', 'public');

        $validated['foto_path'] = $path;
        $validated['presensi_at'] = now();

        Absensi::create($validated);

        return redirect()->route('absensi.create')->with('success', 'Presensi berhasil disimpan. Terima kasih.');
    }

    public function index()
    {
        $data = Absensi::latest()->paginate(25);
        return view('absensi.index', compact('data'));
    }

    public function export()
    {
        return Excel::download(new AbsensiExport, 'absensi_'.now()->format('Ymd_His').'.xlsx');
    }
}
