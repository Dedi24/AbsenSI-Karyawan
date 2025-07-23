@extends('layouts.app')

@section('title', 'Absensi Hari Ini')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Absensi Hari Ini</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensis as $absensi)
                <tr>
                    <td>{{ $loop->iteration }}</td>
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
@endsection
