<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SimpleAbsensiExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Absensi::query();
        
        if (!empty($this->filters['sesi'])) {
            $query->where('sesi_presensi', $this->filters['sesi']);
        }
        
        if (!empty($this->filters['kelas'])) {
            $query->where('kelas', 'like', '%'.$this->filters['kelas'].'%');
        }
        
        if (!empty($this->filters['tanggal'])) {
            $query->whereDate('presensi_date', $this->filters['tanggal']);
        }
        
        if (!empty($this->filters['konsentrasi'])) {
            $query->where('konsentrasi_keahlian', $this->filters['konsentrasi']);
        }
        
        // Select minimal fields to reduce memory usage
        return $query->select([
            'presensi_date',
            'presensi_at', 
            'sesi_presensi',
            'nama_murid',
            'kelas',
            'konsentrasi_keahlian',
            'nama_perusahaan',
            'alamat_perusahaan',
            'nama_pembimbing_sekolah',
            'nama_pembimbing_dudika',
            'user_email'
        ])->orderBy('presensi_date', 'desc')
          ->orderBy('presensi_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Presensi', 
            'Waktu Presensi',
            'Sesi Presensi',
            'Nama Siswa', 
            'Kelas', 
            'Konsentrasi Keahlian',
            'Nama Perusahaan', 
            'Alamat Perusahaan', 
            'Pembimbing Sekolah', 
            'Pembimbing DUDIKA',
            'Email Siswa'
        ];
    }

    public function map($row): array
    {
        static $counter = 0;
        $counter++;
        
        return [
            $counter,
            $row->presensi_date ? (is_string($row->presensi_date) ? $row->presensi_date : $row->presensi_date->format('d/m/Y')) : '',
            $row->presensi_at ? (is_string($row->presensi_at) ? $row->presensi_at : $row->presensi_at->format('H:i:s')) : '',
            $row->sesi_presensi,
            $row->nama_murid,
            $row->kelas,
            $row->konsentrasi_keahlian,
            $row->nama_perusahaan,
            $row->alamat_perusahaan,
            $row->nama_pembimbing_sekolah,
            $row->nama_pembimbing_dudika,
            $row->user_email,
        ];
    }

    public function chunkSize(): int
    {
        return 500; // Process data in smaller chunks
    }
}
