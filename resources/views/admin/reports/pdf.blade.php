<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi - {{ $company_name ?? 'Perusahaan' }}</title>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .report-info {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .summary {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .status-hadir { color: green; font-weight: bold; }
        .status-alpha { color: red; font-weight: bold; }
        .status-izin { color: orange; font-weight: bold; }
        .status-sakit { color: blue; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $company_name ?? 'Perusahaan Kita' }}</div>
        <div class="report-title">LAPORAN ABSENSI KARYAWAN</div>

        @if(isset($request))
            <div class="report-info">
                Periode: {{ \Carbon\Carbon::parse($request->start_date ?? now()->subDays(30))->format('d M Y') }} -
                         {{ \Carbon\Carbon::parse($request->end_date ?? now())->format('d M Y') }}
            </div>
            @if(!empty($request->employee_id) && $request->employee_id != '')
                @php
                    $employee = \App\Models\User::find($request->employee_id);
                @endphp
                @if($employee)
                    <div class="report-info">
                        Karyawan: {{ $employee->name }}
                    </div>
                @endif
            @else
                <div class="report-info">Karyawan: Semua Karyawan</div>
            @endif
        @endif

        <div class="report-info">
            Di-generate pada: {{ now()->format('d M Y H:i:s') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Karyawan</th>
                <th width="10%">Tanggal</th>
                <th width="10%">Jam Masuk</th>
                <th width="10%">Jam Pulang</th>
                <th width="10%">Status</th>
                <th width="10%">Jam Kerja</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensis ?? [] as $index => $absensi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $absensi->user->name ?? 'N/A' }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($absensi->date)->format('d/m/Y') }}</td>

                <!-- Jam Masuk - Tampilkan langsung tanpa parsing kompleks -->
                <td class="text-center">
                    @if($absensi->check_in)
                        {{ substr($absensi->check_in, 0, 5) }}
                    @else
                        -
                    @endif
                </td>

                <!-- Jam Pulang - Tampilkan langsung tanpa parsing kompleks -->
                <td class="text-center">
                    @if($absensi->check_out)
                        {{ substr($absensi->check_out, 0, 5) }}
                    @else
                        -
                    @endif
                </td>

                <!-- Status -->
                <td class="text-center">
                    @switch($absensi->status)
                        @case('hadir')
                            <span class="status-hadir">Hadir</span>
                            @break
                        @case('alpha')
                            <span class="status-alpha">Alpha</span>
                            @break
                        @case('izin')
                            <span class="status-izin">Izin</span>
                            @break
                        @case('sakit')
                            <span class="status-sakit">Sakit</span>
                            @break
                        @default
                            <span>{{ ucfirst($absensi->status ?? '-') }}</span>
                    @endswitch
                </td>

                <!-- Jam Kerja - Hitung berdasarkan check_in dan check_out -->
                <td class="text-center">
                    @if($absensi->check_in && $absensi->check_out)
                        @php
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
                        @endphp
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data absensi yang tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if(isset($absensis) && $absensis->count() > 0)
    <div class="summary">
        <h4>Ringkasan Laporan</h4>
        <table width="60%">
            <tr>
                <td width="70%"><strong>Total Data Absensi:</strong></td>
                <td width="30%" class="text-right">{{ $absensis->count() }}</td>
            </tr>
            <tr>
                <td><strong>Hadir:</strong></td>
                <td class="text-right">{{ $absensis->where('status', 'hadir')->count() }}</td>
            </tr>
            <tr>
                <td><strong>Alpha:</strong></td>
                <td class="text-right">{{ $absensis->where('status', 'alpha')->count() }}</td>
            </tr>
            <tr>
                <td><strong>Izin:</strong></td>
                <td class="text-right">{{ $absensis->where('status', 'izin')->count() }}</td>
            </tr>
            <tr>
                <td><strong>Sakit:</strong></td>
                <td class="text-right">{{ $absensis->where('status', 'sakit')->count() }}</td>
            </tr>
            <tr>
                <td><strong>Total Karyawan:</strong></td>
                <td class="text-right">{{ \App\Models\User::where('role', 'karyawan')->count() }}</td>
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        Laporan ini di-generate secara otomatis oleh sistem pada {{ now()->format('d F Y H:i:s') }}
    </div>
</body>
</html>
