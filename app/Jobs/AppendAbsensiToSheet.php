<?php

namespace App\Jobs;

use App\Models\Absensi;
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AppendAbsensiToSheet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $absensiId) {}

    public function handle(): void
    {
        if (!config('sheets.enabled')) {
            return; // skip if disabled
        }
        $absensi = Absensi::find($this->absensiId);
        if (!$absensi) return;

        try {
            $client = new Client();
            $json = config('sheets.service_account_json');
            if ($json && is_file(storage_path($json))) {
                $client->setAuthConfig(storage_path($json));
            } elseif ($json) {
                // treat as raw json string
                $client->setAuthConfig(json_decode($json, true));
            } else {
                Log::warning('Sheets: service account json not configured');
                return;
            }
            $client->setScopes([Sheets::SPREADSHEETS]);
            $service = new Sheets($client);

            $values = [[
                $absensi->id,
                $absensi->presensi_date,
                $absensi->presensi_at,
                $absensi->sesi_presensi,
                $absensi->konsentrasi_keahlian,
                $absensi->nama_murid,
                $absensi->kelas,
                $absensi->nama_perusahaan,
                $absensi->alamat_perusahaan,
                $absensi->nama_pembimbing_sekolah,
                $absensi->nama_pembimbing_dudika,
                $absensi->user_email,
                $absensi->foto_path,
                now()->toDateTimeString(),
            ]];
            $body = new ValueRange(['values' => $values]);
            $params = ['valueInputOption' => 'RAW','insertDataOption' => 'INSERT_ROWS'];
            $service->spreadsheets_values->append(
                config('sheets.spreadsheet_id'),
                config('sheets.range'),
                $body,
                $params
            );
        } catch (\Throwable $e) {
            Log::error('Sheets append failed: '.$e->getMessage());
            $this->release(30); // retry later
        }
    }
}
