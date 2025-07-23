<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use illuminate\support\Facades\Auth;
use App\Models\Absensi;

class AbsensiContrller extends Controller
{
     public function index()
    {
        $absensis = Absensi::where('user_id', Auth::id())->get();
        return view('karyawan.absensi.index', compact('absensis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:in,out',
        ]);

        if ($request->type == 'in') {
            Absensi::create([
                'user_id' => Auth::id(),
                'date' => now()->toDateString(),
                'check_in' => now()->toTimeString(),
                'location_in' => $request->location,
            ]);
        } else {
            $absensi = Absensi::where('user_id', Auth::id())
                ->where('date', now()->toDateString())
                ->first();

            if ($absensi) {
                $absensi->update([
                    'check_out' => now()->toTimeString(),
                    'location_out' => $request->location,
                ]);
            }
        }

        return back()->with('success', 'Absen berhasil dicatat.');
    }

}
