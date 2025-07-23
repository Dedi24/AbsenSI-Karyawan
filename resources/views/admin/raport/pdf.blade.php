<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Absensi</h2>
        <p>Periode: {{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}</p>
        @if($request->employee_id)
            <p>Karyawan: {{ \App\Models\User::find($request->employee_id)->name }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Status</th>
                <th>Jam Kerja</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensis as $absensi)
            <tr>
                <td>{{ $absensi->user->name }}</td>
                <td>{{ $absensi->date->format('d/m/Y') }}</td>
                <td>{{ $absensi->check_in ?? '-' }}</td>
                <td>{{ $absensi->check_out ?? '-' }}</td>
                <td>{{ ucfirst($absensi->status) }}</td>
                <td>{{ $absensi->working_hours }} jam</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
