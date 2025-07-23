@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Admin</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Total Karyawan</div>
            <div class="card-body">
                <h5 class="card-title">{{ $totalKaryawan }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Hadir Hari Ini</div>
            <div class="card-body">
                <h5 class="card-title">{{ $hadirHariIni }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger mb-3">
            <div class="card-header">Tidak Hadir</div>
            <div class="card-body">
                <h5 class="card-title">{{ $tidakHadir }}</h5>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Absensi Hari Ini</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(App\Models\Absensi::where('date', today())->with('user')->get() as $absensi)
                        <tr>
                            <td>{{ $absensi->user->name }}</td>
                            <td>{{ $absensi->check_in ?? '-' }}</td>
                            <td>{{ $absensi->check_out ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $absensi->status == 'hadir' ? 'success' : ($absensi->status == 'alpha' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($absensi->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
