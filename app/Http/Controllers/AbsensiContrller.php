<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\AbsensiNotification; // Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Untuk handle foto profil

class AbsensiController extends Controller
{
    // ... (constructor dan method lainnya seperti index, qrCode, fingerprint, store, scanQRCode, resetDevice) ...

    /**
     * Menyimpan absensi dengan fingerprint
     */
    public function storeFingerprint(Request $request)
    {
        $request->validate([
            'type' => 'required|in:in,out',
            'fingerprint_data' => 'required|string',
        ]);

        $user = Auth::user();
        $today = now()->timezone('Asia/Jakarta')->toDateString();
        $currentTime = now()->timezone('Asia/Jakarta')->format('H:i:s');

        $absensi = Absensi::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['status' => 'alpha']
        );

        if ($request->type === 'in') {
            if ($absensi->check_in) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah absen masuk hari ini.'
                ], 400);
            }

            // Validasi fingerprint data
            if (!$this->validateFingerprint($request->fingerprint_data, $user)) {
                Log::warning("Verifikasi fingerprint gagal untuk user ID: {$user->id}");
                return response()->json([
                    'success' => false,
                    'message' => 'Fingerprint tidak valid atau device tidak diizinkan.'
                ], 401);
            }

            // Tentukan status berdasarkan waktu
            $workStartTime = Setting::getWorkStartTime();
            $status = ($currentTime > $workStartTime) ? 'terlambat' : 'hadir';

            $absensi->check_in = $currentTime;
            $absensi->status = $status;
            $absensi->save();

            // Kirim notifikasi
            $isLate = $status === 'terlambat';
            if ($isLate) {
                $lateMinutes = (strtotime($currentTime) - strtotime($workStartTime)) / 60;
                $this->sendLateNotification($user, $currentTime, round($lateMinutes));
                Log::info("Absen masuk berhasil (Terlambat) untuk user ID: {$user->id} pada {$currentTime}");
                return response()->json([
                    'success' => true,
                    'message' => 'Absen masuk berhasil (Terlambat)!',
                    'time' => $currentTime
                ]);
            } else {
                $this->sendCheckInNotification($user, $currentTime);
                Log::info("Absen masuk berhasil untuk user ID: {$user->id} pada {$currentTime}");
                return response()->json([
                    'success' => true,
                    'message' => 'Absen masuk berhasil!',
                    'time' => $currentTime
                ]);
            }

        } elseif ($request->type === 'out') {
            if (!$absensi->check_in) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum absen masuk.'
                ], 400);
            }
            if ($absensi->check_out) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah absen pulang hari ini.'
                ], 400);
            }

            // Validasi fingerprint data untuk absen pulang
            if (!$this->validateFingerprint($request->fingerprint_data, $user)) {
                Log::warning("Verifikasi fingerprint gagal untuk absen pulang user ID: {$user->id}");
                return response()->json([
                    'success' => false,
                    'message' => 'Fingerprint tidak valid atau device tidak diizinkan.'
                ], 401);
            }

            $absensi->check_out = $currentTime;
            $absensi->save();

            $this->sendCheckOutNotification($user, $currentTime);

            Log::info("Absen pulang berhasil untuk user ID: {$user->id} pada {$currentTime}");
            return response()->json([
                'success' => true,
                'message' => 'Absen pulang berhasil!',
                'time' => $currentTime
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tipe absen tidak valid.'
        ], 400);
    }

    /**
     * Validasi fingerprint dengan device fingerprint
     */
    private function validateFingerprint($fingerprintData, $user)
    {
        if ($user->device_fingerprint) {
            $deviceFingerprint = $this->generateDeviceFingerprint(request());
            return $user->device_fingerprint === $deviceFingerprint;
        }
        return true; // Izinkan registrasi pertama kali
    }

    /**
     * Generate device fingerprint
     */
    private function generateDeviceFingerprint(Request $request)
    {
        $userAgent = $request->userAgent();
        $ipAddress = $request->ip();
        $acceptLanguage = $request->header('Accept-Language');
        $deviceInfo = $userAgent . $ipAddress . $acceptLanguage;
        return hash('sha256', $deviceInfo);
    }

    // ... (method notifikasi seperti sendCheckInNotification, sendCheckOutNotification, sendLateNotification) ...
    private function sendCheckInNotification($user, $checkInTime)
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AbsensiNotification([
                'type' => 'check_in',
                'employee_name' => $user->name,
                'time' => now()->timezone('Asia/Jakarta')->format('H:i'),
                'date' => now()->timezone('Asia/Jakarta')->isoFormat('D MMMM YYYY')
            ]));
        }
    }

    private function sendCheckOutNotification($user, $checkOutTime)
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AbsensiNotification([
                'type' => 'check_out',
                'employee_name' => $user->name,
                'time' => now()->timezone('Asia/Jakarta')->format('H:i'),
                'date' => now()->timezone('Asia/Jakarta')->isoFormat('D MMMM YYYY')
            ]));
        }
    }

    private function sendLateNotification($user, $checkInTime, $lateMinutes)
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AbsensiNotification([
                'type' => 'late',
                'employee_name' => $user->name,
                'time' => now()->timezone('Asia/Jakarta')->format('H:i'),
                'late_minutes' => $lateMinutes,
                'date' => now()->timezone('Asia/Jakarta')->isoFormat('D MMMM YYYY')
            ]));
        }
    }
}