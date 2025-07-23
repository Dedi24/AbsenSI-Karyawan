<?php

namespace App\Http\Controllers\Karyawan;

use App\Models\Setting;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensis = Auth::user()->absensis()
            ->orderBy('date', 'desc')
            ->paginate(10);
        return view('karyawan.absensi.index', compact('absensis'));
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
}
