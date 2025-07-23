@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Riwayat Absensi</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
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
                <td>{{ $absensi->date->format('d M Y') }}</td>
                <td>{{ $absensi->check_in ?? '-' }}</td>
                <td>{{ $absensi->check_out ?? '-' }}</td>
                <td>
                    <span class="badge bg-{{ $absensi->status == 'hadir' ? 'success' : ($absensi->status == 'alpha' ? 'danger' : 'warning') }}">
                        {{ ucfirst($absensi->status) }}
                    </span>
                </td>
                <td>{{ $absensi->working_hours }} jam</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
