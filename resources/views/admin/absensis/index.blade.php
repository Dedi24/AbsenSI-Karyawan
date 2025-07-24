@extends('layouts.app')

@section('title', 'Absensi Hari Ini')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Absensi Hari Ini - {{ now()->timezone('Asia/Jakarta')->isoFormat('dddd, D MMMM YYYY') }}</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
            @forelse($absensis as $absensi)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $absensi->user->name }}</td>
                <td>{{ $absensi->check_in_formatted }}</td>
                <td>{{ $absensi->check_out_formatted }}</td>
                <td>
                    <span class="badge bg-{{ $absensi->status == 'hadir' ? 'success' : ($absensi->status == 'alpha' ? 'danger' : 'warning') }}">
                        {{ ucfirst($absensi->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data absensi hari ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination untuk admin jika menggunakan paginate -->
@if(isset($absensis) && method_exists($absensis, 'links') && $absensis->hasPages())
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
