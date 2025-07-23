<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKaryawan = User::where('role', 'karyawan')->count();
        $hadirHariIni = Absensi::where('date', today())->where('status', 'hadir')->count();
        $tidakHadir = Absensi::where('date', today())->where('status', 'alpha')->count();

        return view('admin.dashboard', compact('totalKaryawan', 'hadirHariIni', 'tidakHadir'));
    }
}
