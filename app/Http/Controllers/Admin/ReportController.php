<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'karyawan')->get();
        return view('admin.reports.index', compact('employees'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'employee_id' => 'nullable|exists:users,id',
            'format' => 'required|in:pdf,excel'
        ]);

        $query = Absensi::with('user')
            ->whereBetween('date', [$request->start_date, $request->end_date]);

        if ($request->employee_id && $request->employee_id != '') {
            $query->where('user_id', $request->employee_id);
        }

        $absensis = $query->orderBy('date', 'asc')->get();

        if ($request->format === 'pdf') {
            return $this->generatePDF($absensis, $request);
        } else {
            return $this->generateExcel($absensis, $request);
        }
    }

    protected function generatePDF($absensis, $request)
    {
        $data = [
            'absensis' => $absensis,
            'request' => $request,
            'company_name' => \App\Models\Setting::getCompanyName(),
        ];

        $pdf = PDF::loadView('admin.reports.pdf', $data);
        $filename = 'laporan-absensi-' . now()->format('Y-m-d-H-i') . '.pdf';
        return $pdf->download($filename);
    }

    protected function generateExcel($absensis, $request)
    {
        $filename = 'laporan-absensi-' . now()->format('Y-m-d-H-i') . '.xls';
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
            'Pragma' => 'public',
        ];

        $callback = function() use ($absensis, $request) {
            // Header
            echo "LAPORAN ABSENSI KARYAWAN\n";
            echo "Perusahaan: " . \App\Models\Setting::getCompanyName() . "\n";
            echo "Periode: " . \Carbon\Carbon::parse($request->start_date)->format('d M Y') . " - " . \Carbon\Carbon::parse($request->end_date)->format('d M Y') . "\n";
            if (!empty($request->employee_id) && $request->employee_id != '') {
                $employee = \App\Models\User::find($request->employee_id);
                if ($employee) {
                    echo "Karyawan: " . $employee->name . "\n";
                }
            } else {
                echo "Karyawan: Semua Karyawan\n";
            }
            echo "Di-generate pada: " . now()->format('d M Y H:i:s') . "\n";
            echo "\n";

            // Data header
            echo "No\tNama Karyawan\tTanggal\tJam Masuk\tJam Pulang\tStatus\tJam Kerja\n";

            // Data
            foreach ($absensis as $index => $absensi) {
                echo ($index + 1) . "\t";
                echo ($absensi->user->name ?? 'N/A') . "\t";
                echo \Carbon\Carbon::parse($absensi->date)->format('d/m/Y') . "\t";

                // Jam Masuk
                if ($absensi->check_in) {
                    echo substr($absensi->check_in, 0, 5);
                } else {
                    echo '-';
                }
                echo "\t";

                // Jam Pulang
                if ($absensi->check_out) {
                    echo substr($absensi->check_out, 0, 5);
                } else {
                    echo '-';
                }
                echo "\t";

                // Status
                echo ucfirst($absensi->status) . "\t";

                // Jam Kerja
                if ($absensi->check_in && $absensi->check_out) {
                    // Konversi waktu ke format yang bisa dihitung
                    $checkInTime = substr($absensi->check_in, 0, 5);
                    $checkOutTime = substr($absensi->check_out, 0, 5);

                    // Parse waktu
                    $checkIn = \DateTime::createFromFormat('H:i', $checkInTime);
                    $checkOut = \DateTime::createFromFormat('H:i', $checkOutTime);

                    if ($checkIn && $checkOut) {
                        // Jika jam pulang lebih kecil dari jam masuk (melewati tengah malam)
                        if ($checkOut < $checkIn) {
                            $checkOut->modify('+1 day');
                        }

                        $interval = $checkIn->diff($checkOut);
                        $hours = $interval->h;
                        $minutes = $interval->i;

                        if ($hours > 0) {
                            echo $hours . 'h ' . $minutes . 'm';
                        } else {
                            echo $minutes . 'm';
                        }
                    } else {
                        echo '-';
                    }
                } else {
                    echo '-';
                }
                echo "\n";
            }

            // Summary
            echo "\nSUMMARY\n";
            echo "Total Data: " . $absensis->count() . "\n";
            echo "Hadir: " . $absensis->where('status', 'hadir')->count() . "\n";
            echo "Alpha: " . $absensis->where('status', 'alpha')->count() . "\n";
            echo "Izin: " . $absensis->where('status', 'izin')->count() . "\n";
            echo "Sakit: " . $absensis->where('status', 'sakit')->count() . "\n";
        };

        return response()->stream($callback, 200, $headers);
    }

    // Method untuk export semua data tanpa filter
    public function exportAll(Request $request)
    {
        $format = $request->get('format', 'excel');

        $absensis = Absensi::with('user')
            ->orderBy('date', 'desc')
            ->limit(1000) // Batasi untuk performa
            ->get();

        $requestData = new \stdClass();
        $requestData->start_date = now()->subDays(30)->format('Y-m-d');
        $requestData->end_date = now()->format('Y-m-d');
        $requestData->employee_id = '';

        if ($format === 'pdf') {
            return $this->generatePDF($absensis, $requestData);
        } else {
            return $this->generateExcel($absensis, $requestData);
        }
    }
}
