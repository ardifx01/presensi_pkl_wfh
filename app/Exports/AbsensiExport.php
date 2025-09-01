<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Absensi::orderByDesc('presensi_at')->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Waktu Presensi', 'Konsentrasi Keahlian', 'Nama Murid', 'Kelas', 'Nama Perusahaan', 'Alamat Perusahaan', 'Pembimbing Sekolah', 'Pembimbing DUDIKA', 'Sesi Presensi', 'Foto Path'
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->presensi_at,
            $row->konsentrasi_keahlian,
            $row->nama_murid,
            $row->kelas,
            $row->nama_perusahaan,
            $row->alamat_perusahaan,
            $row->nama_pembimbing_sekolah,
            $row->nama_pembimbing_dudika,
            $row->sesi_presensi,
            $row->foto_path,
        ];
    }
}
