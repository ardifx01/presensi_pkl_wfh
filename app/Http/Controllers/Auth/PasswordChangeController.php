<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordChangeController extends Controller
{
    public function form()
    {
        return view('auth.force_change');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required','confirmed','min:8'],
        ]);

        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->temp_password = null;
        $user->force_password_change = false;
        $user->save();

        return redirect()->intended('/')->with('success','Password berhasil diperbarui.');
    }
    
    // Self-service (dari halaman login) - user memasukkan email & password lama
    public function selfServiceForm()
    {
        return view('auth.self_service_password');
    }

    public function selfServiceUpdate(Request $request)
    {
        $request->validate([
            'email' => ['required','email'],
            'current_password' => ['required','min:6'],
            'password' => ['required','confirmed','min:8'],
        ]);

        $user = \App\Models\User::where('email', strtolower($request->email))->first();
        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Email atau password lama tidak cocok'])->withInput();
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->force_password_change = false;
        $user->temp_password = null;
        $user->save();

        return redirect()->route('login')->with('success','Password berhasil diperbarui. Silakan login kembali.');
    }
}
