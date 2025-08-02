<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AbsensiController as AdminAbsensiController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Karyawan\DashboardController as KaryawanDashboardController;
use App\Http\Controllers\Karyawan\AbsensiController as KaryawanAbsensiController;
use App\Http\Controllers\Karyawan\ProfileController as KaryawanProfileController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\LogController;


// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('employees', EmployeeController::class);
        Route::post('/employees/{employee}/reset-device', [EmployeeController::class, 'resetDevice'])->name('employees.reset-device');
        Route::get('/employees/{employee}/device', [EmployeeController::class, 'showDevice'])->name('employees.device');
        Route::get('/absensis', [AdminAbsensiController::class, 'index'])->name('absensis.index');
        Route::get('/absensis/raport', [AdminAbsensiController::class, 'raport'])->name('absensis.raport');
        Route::post('/absensis/manual', [AdminAbsensiController::class, 'manualAttendance'])->name('absensis.manual');
        
        // Profil Routes untuk Admin
        Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/remove-photo', [AdminProfileController::class, 'removePhoto'])->name('profile.remove-photo');
        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
        // Report Routes
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
        Route::get('/reports/export-all', [ReportController::class, 'exportAll'])->name('reports.export-all');
        
        // Logs Routes - Tambahkan ini
        Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
        Route::get('/logs/{log}', [LogController::class, 'show'])->name('logs.show');
        Route::delete('/logs/{log}', [LogController::class, 'destroy'])->name('logs.destroy');
        Route::post('/logs/clear', [LogController::class, 'clear'])->name('logs.clear');

        // Settings Routes
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // Karyawan Routes
    Route::middleware(['role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/dashboard', [KaryawanDashboardController::class, 'index'])->name('dashboard');
        Route::get('/absensi', [KaryawanAbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/absensi/qr-code', [KaryawanAbsensiController::class, 'qrCode'])->name('absensi.qr-code');
        Route::get('/absensi/fingerprint', [KaryawanAbsensiController::class, 'fingerprint'])->name('absensi.fingerprint');
        Route::post('/absensi/store', [KaryawanAbsensiController::class, 'store'])->name('absensi.store');
        Route::post('/absensi/scan-qr', [KaryawanAbsensiController::class, 'scanQRCode'])->name('absensi.scan-qr');
        Route::post('/absensi/store-fingerprint', [KaryawanAbsensiController::class, 'storeFingerprint'])->name('absensi.store-fingerprint');
        Route::post('/absensi/reset-device', [KaryawanAbsensiController::class, 'resetDevice'])->name('absensi.reset-device');
    });
});

// Route untuk PWA offline
Route::get('/pwa/offline', function () {
    return view('pwa.offline');
})->name('pwa.offline');

// Route untuk PWA manifest
Route::get('/manifest.json', function () {
    return response()->json([
        "name" => "Absensi Karyawan",
        "short_name" => "Absensi",
        "description" => "Aplikasi absensi karyawan offline",
        "start_url" => "/",
        "display" => "standalone",
        "background_color" => "#ffffffff",
        "theme_color" => "#4e73df",
        "icons" => [
            [
                "src" => "/icons/icon-192x192.png",
                "sizes" => "192x192",
                "type" => "image/png"
            ],
            [
                "src" => "/icons/icon-512x512.png",
                "sizes" => "512x512",
                "type" => "image/png"
            ]
        ]
    ])->header('Content-Type', 'application/json');
});

// Route untuk favicon
Route::get('/favicon.ico', function () {
    return response()->file(public_path('favicon.ico'));
})->name('favicon');

// Route utama
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/admin/dashboard');
    }
    return redirect('/login');
});