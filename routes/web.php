<?php

use App\Http\Controllers\AbsensiContrller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AbsensisController as AdminAbsensisController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Karyawan\DashboardController as KaryawanDashboardController;
use App\Http\Controllers\Karyawan\AbsensiController as KaryawanAbsensiController;
use App\Models\Absensi;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
        // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('employees', EmployeeController::class);
        Route::post('/employees/{employee}/reset-device', [EmployeeController::class, 'resetDevice'])->name('employees.reset-device');
        Route::get('/employees/{employee}/device', [EmployeeController::class, 'showDevice'])->name('employees.device');
        Route::get('/absensis', [AdminAbsensisController::class, 'index'])->name('absensis.index');
        Route::get('/absensis/raport', [AdminAbsensisController::class, 'raport'])->name('absensis.raport');

        // Report Routes
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
        Route::get('/reports/export-all', [ReportController::class, 'exportAll'])->name('reports.export-all');

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
