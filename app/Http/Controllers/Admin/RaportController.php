<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'karyawan')->get();
        return view('admin.raports.index', compact('employees'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'employee_id' => 'nullable|exists:users,id'
        ]);

        $query = Absensi::with('user')
            ->whereBetween('date', [$request->start_date, $request->end_date]);

        if ($request->employee_id) {
            $query->where('user_id', $request->employee_id);
        }

        $absensis = $query->get();

        if ($request->format === 'pdf') {
            return $this->generatePDF($absensis, $request);
        } else {
            return $this->generateExcel($absensis, $request);
        }
    }

    protected function generatePDF($absensis, $request)
    {
        $pdf = PDF::loadView('admin.report.pdf', compact('absensis', 'request'));
        return $pdf->download('laporan-absensi' . now()->format('Y-m-d') . '.pdf');
    }

    protected function generateExcel($absensis, $request)
    {
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="laporan-absensi-' . now()->format('Y-m-d') . '.xls"',
        ];

        $callback = function() use ($absensis) {
            echo "Nama\tTanggal\tJam Masuk\tJam Pulang\tStatus\tJam Kerja\n";
            foreach ($absensis as $absensi) {
                echo "{$absensi->user->name}\t";
                echo "{$absensi->date->format('d/m/Y')}\t";
                echo ($absensi->check_in ?? '-') . "\t";
                echo ($absensi->check_out ?? '-') . "\t";
                echo ucfirst($absensi->status) . "\t";
                echo $absensi->working_hours . " jam\n";
            }
        };

        return response()->stream($callback, 200, $headers);
    }
}
