<?php

namespace App\Http\Controllers\Api;

use App\Notifications\AbsensiNotification;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    protected $qrService;

    public function __construct(QRCodeService $qrService)
    {
        $this->qrService = $qrService;
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'qr_code' => 'required',
            'location' => 'required|string'
        ]);

        // Validasi QR Code
        $qrData = $this->qrService->validateAttendanceQR($request->qr_code, Auth::id());
        if (!$qrData) {
            return response()->json(['error' => 'Invalid QR Code'], 400);
        }

        $attendance = Absensi::firstOrCreate(
            ['user_id' => Auth::id(), 'date' => $qrData['date']],
            [
                'check_in' => now()->toTimeString(),
                'location_in' => $request->location,
                'status' => 'hadir'
            ]
        );

        // Cek keterlambatan
        $workStartTime = \App\Models\Setting::get('work_start_time', '08:00:00');
        $checkInTime = now()->toTimeString();

        if ($checkInTime > $workStartTime) {
            // Hitung keterlambatan dalam menit
            $lateMinutes = (strtotime($checkInTime) - strtotime($workStartTime)) / 60;

            // Kirim notifikasi keterlambatan
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\AbsensiNotification([
                    'type' => 'late',
                    'employee_name' => Auth::user()->name,
                    'time' => $checkInTime,
                    'late_minutes' => round($lateMinutes)
                ]));
            }
        }

        return response()->json([
            'message' => 'Check-in successful',
            'absensi' => $absensi
        ]);
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'qr_code' => 'required',
            'location' => 'required|string'
        ]);

        // Validasi QR Code
        $qrData = $this->qrService->validateAttendanceQR($request->qr_code, Auth::id());
        if (!$qrData) {
            return response()->json(['error' => 'Invalid QR Code'], 400);
        }

        $absensi = Absensi::where('user_id', Auth::id())
            ->where('date', $qrData['date'])
            ->first();

        if (!$absensi || !$absensi->check_in) {
            return response()->json(['error' => 'You must check in first'], 400);
        }

        $absensi->update([
            'check_out' => now()->toTimeString(),
            'location_out' => $request->location
        ]);

        return response()->json([
            'message' => 'Check-out successful',
            'absensi' => $absensi
        ]);
    }

    public function history(Request $request)
    {
        $attendances = Auth::user()->absensis()
            ->orderBy('date', 'desc')
            ->paginate(20);

        return response()->json($absensis);
    }
}
