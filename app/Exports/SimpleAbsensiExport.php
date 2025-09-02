<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SimpleAbsensiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Absensi::query();

        // Apply filters
        if (!empty($this->filters['sesi'])) {
            $query->where('sesi_presensi', $this->filters['sesi']);
        }
        
        if (!empty($this->filters['kelas'])) {
            $query->where('kelas', 'like', '%' . $this->filters['kelas'] . '%');
        }
        
        if (!empty($this->filters['tanggal'])) {
            $query->whereDate('presensi_date', $this->filters['tanggal']);
        }
        
        if (!empty($this->filters['konsentrasi'])) {
            $query->where('konsentrasi_keahlian', $this->filters['konsentrasi']);
        }

        return $query->orderBy('presensi_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Waktu',
            'Sesi Presensi',
            'Nama Siswa',
            'Kelas',
            'Konsentrasi Keahlian',
            'Nama Perusahaan',
            'Pembimbing Sekolah',
            'Pembimbing DUDIKA',
            'Email User'
        ];
    }

    public function map($absensi): array
    {
        static $no = 1;
        
        return [
            $no++,
            $absensi->presensi_date,
            $absensi->presensi_at ? $absensi->presensi_at->format('H:i:s') : '-',
            $absensi->sesi_presensi,
            $absensi->nama_murid,
            $absensi->kelas,
            $absensi->konsentrasi_keahlian,
            $absensi->nama_perusahaan,
            $absensi->nama_pembimbing_sekolah,
            $absensi->nama_pembimbing_dudika,
            $absensi->user_email
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header row
        ];
    }
}