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
        $absensis = Absensi::with('user')->where('date', today())->get();
        return view('admin.absensis.index', compact('absensis'));
    }

    public function report()
    {
        $employees = User::where('role', 'karyawan')->get();
        $absensis = collect();
        $month = request('month', now()->format('Y-m'));

        if ($month) {
            $absensis = Absensi::with('user')
                ->whereMonth('date', date('m', strtotime($month)))
                ->whereYear('date', date('Y', strtotime($month)))
                ->get()
                ->groupBy('user_id');
        }

        return view('admin.absensis.raport', compact('employees', 'absensis', 'month'));
    }
}
