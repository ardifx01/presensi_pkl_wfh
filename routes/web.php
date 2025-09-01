<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', fn() => redirect()->route('login'));

// Auth routes (Google login + Admin login)
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/admin/login', [GoogleAuthController::class, 'adminLogin'])->name('admin.login');
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

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

