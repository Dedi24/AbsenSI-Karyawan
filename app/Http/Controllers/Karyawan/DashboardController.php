<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalHadir = $user->absensis()->where('status', 'hadir')->count();
        $totalTerlambat = $user->absensis()->where('status', 'hadir')
            ->whereTime('check_in', '>', \App\Models\Setting::getWorkStartTime())
            ->count();
        $totalAlpha = $user->absensis()->where('status', 'alpha')->count();

        return view('karyawan.dashboard', compact('totalHadir', 'totalTerlambat', 'totalAlpha'));
    }
}
