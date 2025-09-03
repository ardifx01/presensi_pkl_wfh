<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\PasswordChangeController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\MaintenanceController;

// Maintenance routes (always accessible)
Route::get('/maintenance', [MaintenanceController::class, 'show'])->name('maintenance');
Route::get('/api/maintenance/status', [MaintenanceController::class, 'checkStatus'])->name('maintenance.status');

Route::get('/', function () {
    if (file_exists(storage_path('framework/maintenance'))) {
        return redirect()->route('maintenance');
    }
    return redirect()->route('login');
});

// Authentication routes (accessible during maintenance for admin login)
// Halaman login umum diblok saat maintenance, kecuali akses dengan query key khusus admin
Route::get('/login', function(Request $request) {
    if (file_exists(storage_path('framework/maintenance')) && !$request->has('admin_token')) {
        return redirect()->route('maintenance');
    }
    return app(\App\Http\Controllers\Auth\LoginController::class)->showLoginForm();
})->name('login');
// Login normal
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
// Route khusus login admin dari halaman maintenance
Route::post('/maintenance/admin-login', [LoginController::class, 'login'])->name('maintenance.admin.login');
Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register')->middleware('maintenance.check');
Route::post('/register', [LoginController::class, 'register'])->name('register.post')->middleware('maintenance.check');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes - diblok saat maintenance untuk non-admin
Route::middleware(['auth','maintenance.check','force.password.change'])->group(function() {
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');
    Route::get('/absensi/form', [AbsensiController::class, 'create'])->name('absensi.create');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
});

// Admin area - hanya admin dashboard dan maintenance management yang diizinkan
Route::middleware(['auth','admin','force.password.change'])->group(function() {
    Route::get('/admin', [DashboardController::class,'index'])->name('admin.dashboard');
    Route::post('/admin/maintenance/{action}', [MaintenanceController::class, 'toggle'])->name('admin.maintenance.toggle');
});

// Force password change routes
Route::middleware(['auth'])->group(function() {
    Route::get('/password/change', [PasswordChangeController::class,'form'])->name('password.change.form');
    Route::post('/password/change', [PasswordChangeController::class,'update'])->name('password.change.update');
    
    // Public self-service password change (dari halaman login)
    Route::get('/password/self-service', [PasswordChangeController::class,'selfServiceForm'])->name('password.self.form');
    Route::post('/password/self-service', [PasswordChangeController::class,'selfServiceUpdate'])->name('password.self.update');
});

// Debug routes disabled in production for security
// Enable only in local/staging environment if needed

