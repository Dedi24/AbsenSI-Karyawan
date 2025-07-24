<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi - {{ $company_name }}</title>
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
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $company_name }}</div>
        <div class="report-title">LAPORAN ABSENSI KARYAWAN</div>
        <div class="report-info">
            Periode: {{ \Carbon\Carbon::parse($request->start_date)->isoFormat('D MMMM YYYY') }} - {{ \Carbon\Carbon::parse($request->end_date)->isoFormat('D MMMM YYYY') }}
        </div>
        @if($request->employee_id)
            <div class="report-info">
                Karyawan: {{ \App\Models\User::find($request->employee_id)->name }}
            </div>
        @endif
        <div class="report-info">
            Di-generate pada: {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->isoFormat('D MMMM YYYY HH:mm:ss') }} WIB
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama</th>
                <th width="10%">Tanggal</th>
                <th width="10%">Hari</th>
                <th width="10%">Jam Masuk</th>
                <th width="10%">Jam Pulang</th>
                <th width="10%">Status</th>
                <th width="10%">Jam Kerja</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensis as $index => $absensi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $absensi->user->name }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($absensi->date)->isoFormat('D/MMM/YYYY') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($absensi->date)->isoFormat('dddd') }}</td>
                <td class="text-center">{{ $absensi->check_in ? substr($absensi->check_in, 0, 5) : '-' }}</td>
                <td class="text-center">{{ $absensi->check_out ? substr($absensi->check_out, 0, 5) : '-' }}</td>
                <td class="text-center">
                    @if($absensi->status == 'hadir')
                        <span style="color: green;">Hadir</span>
                    @elseif($absensi->status == 'alpha')
                        <span style="color: red;">Alpha</span>
                    @else
                        <span style="color: orange;">{{ ucfirst($absensi->status) }}</span>
                    @endif
                </td>
                <td class="text-center">{{ $absensi->working_hours }} jam</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data absensi</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <table width="50%">
            <tr>
                <td><strong>Total Data:</strong></td>
                <td class="text-right">{{ $absensis->count() }}</td>
            </tr>
            <tr>
                <td><strong>Total Hadir:</strong></td>
                <td class="text-right">{{ $absensis->where('status', 'hadir')->count() }}</td>
            </tr>
            <tr>
                <td><strong>Total Alpha:</strong></td>
                <td class="text-right">{{ $absensis->where('status', 'alpha')->count() }}</td>
            </tr>
            <tr>
                <td><strong>Total Izin/Sakit:</strong></td>
                <td class="text-right">{{ $absensis->whereIn('status', ['izin', 'sakit'])->count() }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Laporan ini di-generate secara otomatis oleh sistem pada {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->isoFormat('D MMMM YYYY HH:mm:ss') }} WIB
    </div>
</body>
</html>
