<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $isMaintenanceMode = file_exists(storage_path('framework/maintenance'));

        // Jika maintenance aktif, hanya izinkan kredensial admin (is_admin = true)
        if ($isMaintenanceMode) {
            // Cek user dulu
            $candidate = User::where('email', strtolower($request->input('email')))->first();
            if (!$candidate || !$candidate->is_admin) {
                return redirect()->route('maintenance')->with('info', 'Sistem maintenance. Hanya admin yang bisa login.');
            }
        }

        // Attempt login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Jika user wajib ganti password, arahkan langsung
            if ($user->force_password_change) {
                return redirect()->route('password.change.form');
            }
            
            // Redirect based on user role dan maintenance status
            if ($user->is_admin) {
                return redirect()->intended('/admin')->with('success', 'Selamat datang, ' . $user->name);
            } else {
                // Jika maintenance mode aktif, redirect user biasa ke maintenance page
                if ($isMaintenanceMode) {
                    return redirect()->route('maintenance')->with('info', 'Sistem sedang dalam maintenance. Silakan coba lagi nanti.');
                } else {
                    return redirect()->intended('/absensi/form')->with('success', 'Selamat datang, ' . $user->name);
                }
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak cocok.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(), // Auto verify for simplicity
        ]);

        Auth::login($user);

        return redirect('/absensi/form')->with('success', 'Akun berhasil dibuat! Selamat datang, ' . $user->name);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah logout.');
    }
}
