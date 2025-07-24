<?php

namespace App\Http\Controllers\Karyawan;

use App\Models\User;
use App\Models\Setting;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensis = Auth::user()->absensis()
            ->orderBy('date', 'desc')
            ->paginate(10);
        return view('karyawan.absensi.index', compact('absensis'));
    }

    public function qrCode()
    {
        $user = Auth::user();
        $today = now()->toDateString();

        // Generate QR Code data
        $qrData = [
            'user_id' => $user->id,
            'date' => $today,
            'timestamp' => now()->timestamp,
            'device_fingerprint' => $user->device_fingerprint,
            'token' => md5($user->id . $today . now()->timestamp . env('APP_KEY'))
        ];

        $qrCodeData = json_encode($qrData);
        $qrCode = QrCode::size(300)->generate($qrCodeData);

        // Cek status absensi hari ini
        $todayAbsensi = Absensi::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['status' => 'alpha']
        );

        return view('karyawan.absensi.qr-code', compact('qrCode', 'qrCodeData', 'todayAbsensi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:in,out',
            'location' => 'nullable|string',
        ]);

        $today = today()->toDateString();
        $user = Auth::user();
        $workStartTime = Setting::getWorkStartTime();

        $absensi = Absensi::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['status' => 'hadir']
        );

        if ($request->type == 'in') {
            $checkInTime = now()->format('H:i:s');
            $absensi->update([
                'check_in' => $checkInTime,
                'location_in' => $request->location,
                'status' => 'hadir'
            ]);

        // Cek keterlambatan
            if ($checkInTime > $workStartTime) {
                // Bisa menambahkan logika notifikasi keterlambatan di sini
            }

            return back()->with('success', 'Absen masuk berhasil dicatat.');
        } else {
            // Validasi apakah sudah absen masuk
            if (!$absensi->check_in) {
                return back()->with('error', 'Anda harus absen masuk terlebih dahulu.');
            }

            $absensi->update([
                'check_out' => now()->format('H:i:s'),
                'location_out' => $request->location
            ]);

            return back()->with('success', 'Absen pulang berhasil dicatat.');
        }
    }

    public function scanQRCode(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string'
        ]);

        try {
            $qrData = json_decode($request->qr_data, true);

            if (!$qrData || !isset($qrData['user_id']) || !isset($qrData['date'])) {
                return response()->json(['error' => 'QR Code tidak valid'], 400);
            }

            // Validasi user
            if ($qrData['user_id'] != Auth::id()) {
                return response()->json(['error' => 'QR Code tidak valid untuk user ini'], 400);
            }

            // Validasi tanggal
            if ($qrData['date'] != now()->toDateString()) {
                return response()->json(['error' => 'QR Code sudah kadaluarsa'], 400);
            }

            // Validasi device fingerprint
            $user = Auth::user();
            if (isset($qrData['device_fingerprint']) && $qrData['device_fingerprint'] !== $user->device_fingerprint) {
                return response()->json(['error' => 'QR Code tidak dapat digunakan di device ini'], 400);
            }

            // Validasi token (5 menit expired)
            $qrTime = $qrData['timestamp'];
            $currentTime = now()->timestamp;

            if (($currentTime - $qrTime) > 300) { // 5 menit
                return response()->json(['error' => 'QR Code sudah kadaluarsa'], 400);
            }

            // Cek status absensi hari ini
            $today = now()->toDateString();
            $absensi = Absensi::firstOrCreate(
                ['user_id' => Auth::id(), 'date' => $today],
                ['status' => 'hadir']
            );

            // Tentukan apakah absen masuk atau pulang
            if (!$absensi->check_in) {
                // Absen masuk
                $absensi->update([
                    'check_in' => now()->format('H:i:s'),
                    'status' => 'hadir'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Absen masuk berhasil!',
                    'type' => 'check_in'
                ]);
            } elseif (!$absensi->check_out) {
                // Absen pulang
                $absensi->update([
                    'check_out' => now()->format('H:i:s')
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Absen pulang berhasil!',
                    'type' => 'check_out'
                ]);
            } else {
                return response()->json(['error' => 'Anda sudah absen hari ini'], 400);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat memproses QR Code'], 500);
        }
    }

     public function fingerprint()
    {
        $user = Auth::user();
        $today = now()->toDateString();

        // Cek status absensi hari ini
        $todayAbsensi = Absensi::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['status' => 'alpha']
        );

        return view('karyawan.absensi.fingerprint', compact('todayAbsensi'));
    }

    public function storeFingerprint(Request $request)
    {
        $request->validate([
            'type' => 'required|in:in,out',
            'fingerprint_data' => 'required|string'
        ]);

        $today = now()->toDateString();
        $user = Auth::user();
        $workStartTime = Setting::getWorkStartTime();

        // Validasi fingerprint data (simulasi - di production bisa pakai library fingerprint)
        if (!$this->validateFingerprint($request->fingerprint_data, $user)) {
            return response()->json(['error' => 'Fingerprint tidak valid'], 400);
        }

        $absensi = Absensi::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['status' => 'hadir']
        );

        if ($request->type == 'in') {
            $checkInTime = now()->format('H:i:s');
            $absensi->update([
                'check_in' => $checkInTime,
                'status' => 'hadir'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absen masuk berhasil!',
                'type' => 'check_in',
                'time' => $checkInTime
            ]);
        } else {
            // Validasi apakah sudah absen masuk
            if (!$absensi->check_in) {
                return response()->json(['error' => 'Anda harus absen masuk terlebih dahulu'], 400);
            }

            $checkOutTime = now()->format('H:i:s');
            $absensi->update([
                'check_out' => $checkOutTime
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absen pulang berhasil!',
                'type' => 'check_out',
                'time' => $checkOutTime
            ]);
        }
    }

    // Method simulasi validasi fingerprint
    private function validateFingerprint($fingerprintData, $user)
    {
        // Di production, di sini akan ada validasi fingerprint yang sebenarnya
        // Untuk demo, kita akan menerima semua fingerprint
        // Tapi tetap memeriksa device fingerprint yang sudah ada

        if ($user->device_fingerprint) {
            $deviceFingerprint = $this->generateDeviceFingerprint(request());
            return $user->device_fingerprint === $deviceFingerprint;
        }

        return true;
    }

    private function generateDeviceFingerprint(Request $request)
    {
        $userAgent = $request->userAgent();
        $ipAddress = $request->ip();
        $acceptLanguage = $request->header('Accept-Language');

        $deviceInfo = $userAgent . $ipAddress . $acceptLanguage;
        return hash('sha256', $deviceInfo);
    }

}
