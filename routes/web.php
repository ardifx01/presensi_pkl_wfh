<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Auth\EmailAuthController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', fn() => redirect()->route('login'));

// Auth routes (Google login + Admin login)
Route::get('/login', [EmailAuthController::class,'show'])->name('login');
Route::post('/admin/login', [EmailAuthController::class, 'adminLogin'])->name('admin.login');
// Removed Google OAuth routes (migrated to passwordless email). If needed later, re-add here.
Route::post('/login/link', [EmailAuthController::class,'send'])->name('login.email.send');
Route::get('/login/magic', [EmailAuthController::class,'magic'])->name('login.magic');
Route::get('/login/reset-cooldown', function() {
    session()->forget('last_login_link_sent_at');
    return redirect()->route('login')->with('status', '🔄 Cooldown direset. Anda bisa kirim email lagi.');
})->name('login.reset.cooldown');
Route::post('/logout', [EmailAuthController::class, 'logout'])->name('logout');

// Protected routes - hanya untuk user yang sudah login
Route::middleware('auth')->group(function() {
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');
    Route::get('/absensi/form', [AbsensiController::class, 'create'])->name('absensi.create');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
});

// Admin area - hanya untuk admin
Route::middleware(['auth','admin'])->group(function() {
    Route::get('/admin', [DashboardController::class,'index'])->name('admin.dashboard');
});

// Debug mail route (only enable locally). Hit /debug/mail?to=someone@example.com
if (config('app.env') !== 'production') {
    Route::get('/debug/mail', function(\Illuminate\Http\Request $request) {
        $to = $request->query('to');
        if (!$to) {
            return 'Tambahkan parameter ?to=email@domain';
        }
        try {
            \Illuminate\Support\Facades\Mail::raw('Tes kirim email debug '.now(), function($m) use ($to) {
                $m->to($to)->subject('Tes SMTP');
            });
            return 'Dikirim (cek Gmail inbox). Driver: '.config('mail.default');
        } catch (\Throwable $e) {
            return 'Gagal: '.$e->getMessage();
        }
    });
    
    // Test magic link creation and sending
    Route::get('/debug/magic', function(\Illuminate\Http\Request $request) {
        $email = $request->query('email', 'ajulian.fernando123@gmail.com');
        try {
            $raw = \Illuminate\Support\Str::random(40);
            $hash = hash('sha256', $raw);
            \App\Models\LoginToken::create([
                'email' => $email,
                'token_hash' => $hash,
                'expires_at' => now()->addMinutes(15),
            ]);
            $baseUrl = $request->getSchemeAndHttpHost();
            $loginUrl = $baseUrl.'/login/magic?email='.urlencode($email).'&token='.$raw;
            \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\LoginLinkMail($loginUrl));
            return 'Magic link dikirim ke '.$email.'. Cek Gmail! URL: '.$loginUrl;
        } catch (\Throwable $e) {
            return 'Error: '.$e->getMessage();
        }
    });
}

