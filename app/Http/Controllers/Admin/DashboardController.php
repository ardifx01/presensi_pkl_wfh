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
        $data = $q->latest('presensi_at')->paginate(50)->withQueryString();
        $rekapPerSesi = clone $q;
        $rekapPerSesi = Absensi::select('sesi_presensi')
            ->selectRaw('count(*) as total')
            ->when($request->filled('tanggal'), fn($qq)=>$qq->whereDate('presensi_date',$request->tanggal))
            ->groupBy('sesi_presensi')->get();
        return view('admin.dashboard', compact('data','rekapPerSesi'));
    }
}
