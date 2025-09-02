<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\LoginLinkMail;
use App\Models\LoginToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class EmailAuthController extends Controller
{
    protected int $cooldownSeconds = 30; // Kurangi dari 60 ke 30 detik

    public function show(): \Illuminate\View\View
    {
        return view('auth.login');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);
        $email = strtolower($validated['email']);

        // Bypass cooldown jika ada parameter 'force' atau user adalah admin
        $forceBypass = $request->has('force') || ($request->user() && $request->user()->is_admin);
        
        if (!$forceBypass) {
            $last = session('last_login_link_sent_at');
            if ($last) {
                $elapsed = now()->diffInSeconds($last);
                if ($elapsed < $this->cooldownSeconds) {
                    $wait = $this->cooldownSeconds - $elapsed;
                    return back()->with('status', "⏱️ Link baru saja dikirim! Tunggu {$wait} detik atau <a href='?force=1' class='alert-link'>kirim ulang paksa</a>.");
                }
            }
        }

        $raw = Str::random(40);
        $hash = hash('sha256', $raw);
    LoginToken::create([
            'email' => $email,
            'token_hash' => $hash,
            'expires_at' => now()->addMinutes(15),
        ]);
    // Bangun URL berbasis APP_URL; fallback ke host request jika masih localhost/127 agar email pakai domain benar
    $baseUrl = rtrim(config('app.url') ?: url('/'), '/');
    if (preg_match('/127\.0\.0\.1|localhost/i', $baseUrl)) {
        $baseUrl = $request->getSchemeAndHttpHost();
    }
    $loginUrl = $baseUrl.'/login/magic?email='.urlencode($email).'&token='.$raw;
        try {
            Mail::to($email)->send(new LoginLinkMail($loginUrl));
            session(['last_login_link_sent_at' => now()]);
            $driver = config('mail.default');
            if (in_array($driver, ['log','array'])) {
                return back()->with('status','📋 MODE '.$driver.' → Email hanya dicatat di log, tidak dikirim ke inbox. Aktifkan SMTP untuk pengiriman nyata.');
            }
            $successMsg = '✅ Link login berhasil dikirim ke '.$email.'! Cek inbox dalam 1-2 menit.';
            if ($forceBypass) {
                $successMsg .= ' (Bypass cooldown)';
            }
            return back()->with('status', $successMsg);
        } catch (\Throwable $e) {
            \Log::error('Failed to send login email', [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $msg = '❌ Gagal mengirim email. Coba lagi dalam beberapa saat.';
            if (config('app.env') !== 'production') {
                $msg .= ' DETAIL: '.$e->getMessage();
            }
            return back()->with('error', $msg);
        }
    }

    public function magic(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required'
        ]);
        $email = strtolower($request->email);
        $hash = hash('sha256', $request->token);
        $token = LoginToken::where('email',$email)->where('token_hash',$hash)->latest()->first();
        if (!$token || !$token->isValid()) {
            return redirect()->route('login')->with('error','🔒 Link login tidak valid atau sudah kedaluwarsa. Silakan minta link baru.');
        }
        $token->used_at = now();
        $token->save();
        $user = User::firstOrCreate(['email'=>$email],[
            'name' => preg_replace('/@.+$/','',$email),
            'password' => bcrypt(Str::random(32)),
        ]);
        Auth::login($user,true);
        
        $redirectMsg = $user->is_admin ? 'Selamat datang, Admin!' : 'Login berhasil! Selamat bekerja.';
        session()->flash('status', '🎉 ' . $redirectMsg);
        
        return redirect()->intended($user->is_admin ? route('admin.dashboard') : route('absensi.create'));
    }

    public function logout(Request $request)
    {
        $userName = Auth::user()->name ?? 'User';
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('status', '👋 Sampai jumpa, '.$userName.'! Anda telah logout.');
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials = [
            'email' => $request->username,
            'password' => $request->password,
        ];
        if (Auth::attempt($credentials, true)) {
            $user = Auth::user();
            if ($user->is_admin ?? false) {
                session()->flash('status', '🎉 Selamat datang kembali, Admin '.$user->name.'!');
                return redirect()->intended(route('admin.dashboard'));
            }
            Auth::logout();
            return redirect()->route('login')->with('error','🚫 Akses ditolak. Akun ini bukan admin.');
        }
        return redirect()->route('login')->with('error','❌ Username atau password salah. Coba lagi.');
    }
}
