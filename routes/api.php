<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AbsensiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'device.fingerprint'])->group(function () {
    Route::post('/absensi/check-in', [AbsensiController::class, 'checkIn']);
    Route::post('/absensi/check-out', [AbsensiController::class, 'checkOut']);
    Route::get('/absensi/history', [AbsensiController::class, 'history']);
});
