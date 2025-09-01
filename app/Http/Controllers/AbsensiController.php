<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Exports\AbsensiExport;
use App\Exports\SimpleAbsensiExport;
use App\Exports\CleanAbsensiExport;
use App\Exports\LightweightAbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\AppendAbsensiToSheet;

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

        // Normalisasi nama murid & kelas (trim) untuk pencarian
        $nama = trim($validated['nama_murid']);
        $kelas = trim($validated['kelas']);
        $today = $now->toDateString();

        $exists = Absensi::whereDate('presensi_date', $today)
            ->where('sesi_presensi', $validated['sesi_presensi'])
            ->whereRaw('LOWER(nama_murid)=?', [mb_strtolower($nama)])
            ->whereRaw('LOWER(kelas)=?', [mb_strtolower($kelas)])
            ->exists();
        if ($exists) {
            return back()->withErrors(['sesi_presensi' => 'Anda sudah melakukan presensi untuk sesi ini hari ini.'])->withInput();
        }

        // Simpan foto
        $path = $request->file('foto_murid')->store('absensi_foto', 'public');

        $validated['foto_path'] = $path;
        $validated['presensi_at'] = now();
        $validated['presensi_date'] = $today;
        $validated['nama_murid'] = $nama; // simpan versi trim
        $validated['kelas'] = $kelas;
        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
            $validated['user_email'] = auth()->user()->email;
        }

    $absensi = Absensi::create($validated);

    // Dispatch job to Google Sheets (queue: default)
    AppendAbsensiToSheet::dispatch($absensi->id);

        return redirect()->route('absensi.create')->with('success', 'Presensi berhasil disimpan. Terima kasih.');
    }

    public function index()
    {
        // Hanya tampilkan data presensi milik user yang sedang login
        $data = Absensi::where('user_email', auth()->user()->email)
                      ->latest()
                      ->paginate(25);
        return view('absensi.index', compact('data'));
    }

    public function export(Request $request)
    {
        // Set memory limit tinggi dan timeout
        ini_set('memory_limit', '-1'); // Unlimited memory
        set_time_limit(0); // No timeout
        
        $filters = $request->only(['sesi', 'kelas', 'tanggal', 'konsentrasi']);
        
        // Cek jumlah data untuk menentukan export method
        $query = Absensi::query();
        
        if (!empty($filters['sesi'])) {
            $query->where('sesi_presensi', $filters['sesi']);
        }
        if (!empty($filters['kelas'])) {
            $query->where('kelas', 'like', '%'.$filters['kelas'].'%');
        }
        if (!empty($filters['tanggal'])) {
            $query->whereDate('presensi_date', $filters['tanggal']);
        }
        if (!empty($filters['konsentrasi'])) {
            $query->where('konsentrasi_keahlian', $filters['konsentrasi']);
        }
        
        $count = $query->count();
        
        $fileName = 'Data_Presensi_PKL_';
        
        if ($request->filled('tanggal')) {
            $fileName .= date('d-m-Y', strtotime($request->tanggal)) . '_';
        }
        if ($request->filled('sesi')) {
            $sesiName = str_replace([' ', '(', ')', '.'], '', $request->sesi);
            $fileName .= $sesiName . '_';
        }
        if ($request->filled('konsentrasi')) {
            $fileName .= str_replace(' ', '_', $request->konsentrasi) . '_';
        }
        
        $fileName .= now()->format('Ymd_His') . '.xlsx';
        
        // Jika data terlalu banyak, gunakan lightweight export
        if ($count > 500) {
            return Excel::download(new LightweightAbsensiExport($filters), $fileName);
        } else {
            return Excel::download(new SimpleAbsensiExport($filters), $fileName);
        }
    }
}
