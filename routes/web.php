<?php

use App\Http\Controllers\AbsensiContrller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AbsensiController as AdminAttendanceController;
use App\Http\Controllers\Admin\AbsensiController;
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
        Route::get('/absensis', [AbsensiController::class, 'index'])->name('absensis.index');
        // Route::get('/absensis/report', [AbsensiController::class, 'report'])->name('absensis.report'); // Perbaikan route
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // Karyawan Routes
    Route::middleware(['role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/dashboard', [KaryawanDashboardController::class, 'index'])->name('dashboard');
        Route::get('/absensi', [KaryawanAbsensiController::class, 'index'])->name('absensi.index');
        Route::post('/absensi/store', [KaryawanAbsensiController::class, 'store'])->name('absensi.store');
    });
});
