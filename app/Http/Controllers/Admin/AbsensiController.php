<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        // Gunakan paginate() bukan get() untuk mendapatkan objek Paginator
        $absensis = Absensi::with('user')
            ->where('date', today())
            ->orderBy('check_in', 'asc')
            ->paginate(15); // 15 items per page
        
        return view('admin.absensis.index', compact('absensis'));
    }

    public function raport()
    {
        $employees = User::where('role', 'karyawan')->get();
        
        // Gunakan paginate() untuk mendapatkan objek Paginator
        $absensis = Absensi::with('user')
            ->orderBy('date', 'desc')
            ->paginate(20); // 20 items per page
        
        // Group absensis by user_id for reporting
        $groupedAbsensis = $absensis->groupBy('user_id');
        
        return view('admin.absensis.raport', compact('employees', 'groupedAbsensis', 'absensis'));
    }

    // Method untuk absensi manual
    public function manualAttendance(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:hadir,alpha,izin,sakit',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absensi = Absensi::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'date' => $request->date
            ],
            [
                'status' => $request->status,
                'check_in' => $request->check_in ? $request->check_in . ':00' : null,
                'check_out' => $request->check_out ? $request->check_out . ':00' : null,
                'keterangan' => $request->keterangan,
            ]
        );

        return redirect()->back()->with('success', 'Absensi manual berhasil disimpan.');
    }
}