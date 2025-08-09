<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // ✅ Ambil karyawan yang sedang login
        $employee = Auth::user(); // Bukan Employee::find(1)

        // ✅ Pastikan user yang login adalah karyawan
        if (!$employee || $employee->role !== 'karyawan') {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Akses ditolak.']);
        }

        // ✅ Hitung statistik absensi
        $totalHadir = $employee->absensis()->where('status', 'hadir')->count();
        $workStartTime = \App\Models\Setting::getWorkStartTime(); // Ambil jam masuk kerja
        $totalTerlambat = $employee->absensis()
            ->where('status', 'hadir')
            ->whereTime('check_in', '>', $workStartTime)
            ->count();
        $totalAlpha = $employee->absensis()->where('status', 'alpha')->count();

        return view('karyawan.dashboard', compact('employee', 'totalHadir', 'totalTerlambat', 'totalAlpha'));
    }
}
