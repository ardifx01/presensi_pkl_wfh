<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\MaintenanceController;

// Maintenance routes (always accessible)
Route::get('/maintenance', [MaintenanceController::class, 'show'])->name('maintenance');
Route::get('/api/maintenance/status', [MaintenanceController::class, 'checkStatus'])->name('maintenance.status');

Route::get('/', fn() => redirect()->route('login'));

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [LoginController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

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
    Route::post('/admin/maintenance/{action}', [MaintenanceController::class, 'toggle'])->name('admin.maintenance.toggle');
});

// Debug routes disabled in production for security
// Enable only in local/staging environment if needed

