<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_email',
        'konsentrasi_keahlian',
        'nama_murid',
        'kelas',
        'nama_perusahaan',
        'alamat_perusahaan',
        'nama_pembimbing_sekolah',
        'nama_pembimbing_dudika',
        'sesi_presensi',
        'presensi_at',
        'presensi_date',
        'foto_path',
    ];

    protected $casts = [
        'presensi_at' => 'datetime',
        'presensi_date' => 'date',
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically set presensi_date when saving
        static::saving(function ($model) {
            if ($model->presensi_at && !$model->presensi_date) {
                $model->presensi_date = Carbon::parse($model->presensi_at)->toDateString();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
