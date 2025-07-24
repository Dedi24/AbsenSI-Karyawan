@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Riwayat Absensi</h1>
    <div class="text-muted">
        {{ now()->timezone('Asia/Jakarta')->isoFormat('dddd, D MMMM YYYY') }}
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Hari</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Status</th>
                <th>Jam Kerja</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absensis as $absensi)
            <tr>
                <td>{{ $absensi->date_short_formatted }}</td>
                <td>{{ $absensi->day_name }}</td>
                <td>{{ $absensi->check_in_formatted }}</td>
                <td>{{ $absensi->check_out_formatted }}</td>
                <td>
                    <span class="badge bg-{{ $absensi->status == 'hadir' ? 'success' : ($absensi->status == 'alpha' ? 'danger' : 'warning') }}">
                        {{ ucfirst($absensi->status) }}
                    </span>
                </td>
                <td>{{ $absensi->working_hours }} jam</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data absensi</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($absensis->hasPages())
<div class="mt-3">
    <div class="d-flex justify-content-between align-items-center">
        <div class="text-muted">
            Menampilkan {{ $absensis->firstItem() }} sampai {{ $absensis->lastItem() }} dari {{ $absensis->total() }} data
        </div>
        <div>
            {{ $absensis->links() }}
        </div>
    </div>
</div>
@endif
@endsection
