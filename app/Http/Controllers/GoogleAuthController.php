<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request)
    {
        // Tambah parameter prompt=select_account agar selalu bisa ganti akun
        $response = Socialite::driver('google')->redirect();
        $target = $response->getTargetUrl();
        // Sisipkan prompt jika belum ada
        if (!str_contains($target, 'prompt=')) {
            $sep = str_contains($target, '?') ? '&' : '?';
            $target .= $sep . 'prompt=select_account';
        }
        return redirect()->away($target);
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login Google.');
        }

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(str()->random(16)),
            ]
        );

        Auth::login($user, true);
        
        // Redirect berdasarkan status admin
        if ($user->is_admin) {
            return redirect()->intended(route('admin.dashboard'));
        } else {
            return redirect()->intended(route('absensi.create'));
        }
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Coba login dengan username (email) dan password
        $credentials = [
            'email' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, true)) {
            $user = Auth::user();
            
            // Pastikan user adalah admin
            if ($user->is_admin) {
                return redirect()->intended(route('admin.dashboard'));
            } else {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Akses ditolak. Hanya admin yang dapat login.');
            }
        }

        return redirect()->route('login')->with('error', 'Username atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
