<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;

class AbsensisController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('user')
            ->where('date', today())
            ->orderBy('check_in', 'asc')
            ->paginate(15);
        
        return view('admin.attendances.index', compact('attendances'));
    }

    public function report()
    {
        $employees = User::where('role', 'karyawan')->get();
        $attendances = collect();
        $month = request('month', now()->format('Y-m'));

        if ($month) {
            $attendances = Attendance::with('user')
                ->whereMonth('date', date('m', strtotime($month)))
                ->whereYear('date', date('Y', strtotime($month)))
                ->get()
                ->groupBy('user_id');
        }

        return view('admin.attendances.report', compact('employees', 'attendances', 'month'));
    }

    // Method untuk menambahkan izin/sakit
    public function addLeave(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'type' => 'required|in:izin,sakit',
            'keterangan' => 'required|string|max:255',
        ]);

        $attendance = Attendance::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'date' => $request->date
            ],
            [
                'status' => $request->type,
                'keterangan' => $request->keterangan,
                'check_in' => null,
                'check_out' => null,
            ]
        );

        return redirect()->back()->with('success', 'Izin/Sakit berhasil ditambahkan.');
    }

    // Method untuk mengedit status absensi
    public function updateStatus(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:hadir,alpha,izin,sakit',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $attendance->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Status absensi berhasil diupdate.');
    }
}
