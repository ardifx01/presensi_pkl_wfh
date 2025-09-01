<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\GoogleAuthController;

Route::get('/', fn() => redirect()->route('login'));

// Auth (Google + Manual bypass)
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

// Absensi (form bisa diakses tanpa login, index dilindungi)
Route::middleware('auth')->group(function() {
	Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
	Route::get('/absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');
});
Route::get('/absensi/form', [AbsensiController::class, 'create'])->name('absensi.create');
Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');


