@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">👋 Halo, {{ Auth::user()->name }}!</h1>
    <div class="text-muted">
        <i class="bi bi-calendar-event"></i> {{ now()->timezone('Asia/Jakarta')->isoFormat('dddd, D MMMM YYYY') }}
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-left-success shadow-sm h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Hadir Bulan Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalHadir }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar-check fs-2 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-left-warning shadow-sm h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Terlambat Bulan Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTerlambat }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clock-history fs-2 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-left-danger shadow-sm h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Tidak Hadir (Alpha) Bulan Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAlpha }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar-x fs-2 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Absen Hari Ini -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center">
                <i class="bi bi-calendar-check me-2 text-primary"></i>
                <h5 class="mb-0">Absen Hari Ini</h5>
                <div class="ms-auto text-muted small">
                    <i class="bi bi-geo-alt"></i> Lokasi Kantor
                </div>
            </div>
            <div class="card-body">
                @php
                    $todayAbsensi = Auth::user()->absensis()->where('date', today())->first();
                @endphp

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(!$todayAbsensi || !$todayAbsensi->check_in)
                    <div class="text-center py-4">
                        <div class="mb-4">
                            <i class="bi bi-box-arrow-in-right text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-3">Saatnya Absen Masuk!</h4>
                        <p class="text-muted mb-4">Silakan pilih metode absensi Anda hari ini.</p>
                        
                        <div class="d-grid gap-3 col-md-6 mx-auto">
                            <a href="{{ route('karyawan.absensi.fingerprint') }}" class="btn btn-success btn-lg">
                                <i class="bi bi-fingerprint"></i> Absen Masuk dengan Fingerprint
                            </a>
                            <div class="text-center my-2">
                                <small class="text-muted">Atau pilih metode lain:</small>
                            </div>
                            <a href="{{ route('karyawan.absensi.qr-code') }}" class="btn btn-outline-primary">
                                <i class="bi bi-qr-code-scan"></i> Absen dengan QR Code
                            </a>
                            <form action="{{ route('karyawan.absensi.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="in">
                                <input type="hidden" name="location" value="office">
                                <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="bi bi-box-arrow-in-right"></i> Absen Masuk Manual
                                </button>
                            </form>
                        </div>
                    </div>
                @elseif($todayAbsensi->check_in && !$todayAbsensi->check_out)
                    <div class="text-center py-4">
                        <div class="mb-4">
                            <i class="bi bi-box-arrow-right text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-3">Saatnya Absen Pulang!</h4>
                        <p class="text-muted mb-2">Anda masuk pada: <strong>{{ $todayAbsensi->check_in_formatted }}</strong></p>
                        <p class="text-muted mb-4">Silakan selesaikan absensi Anda.</p>
                        
                        <div class="d-grid gap-3 col-md-6 mx-auto">
                            <a href="{{ route('karyawan.absensi.fingerprint') }}" class="btn btn-danger btn-lg">
                                <i class="bi bi-fingerprint"></i> Absen Pulang dengan Fingerprint
                            </a>
                            <div class="text-center my-2">
                                <small class="text-muted">Atau pilih metode lain:</small>
                            </div>
                            <a href="{{ route('karyawan.absensi.qr-code') }}" class="btn btn-outline-primary">
                                <i class="bi bi-qr-code-scan"></i> Absen dengan QR Code
                            </a>
                            <form action="{{ route('karyawan.absensi.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="out">
                                <input type="hidden" name="location" value="office">
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-box-arrow-right"></i> Absen Pulang Manual
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-3">Absensi Hari Ini Selesai!</h4>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-6 border-end">
                                                <small class="text-muted">Masuk</small>
                                                <div class="fw-bold">{{ $todayAbsensi->check_in_formatted }}</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Pulang</small>
                                                <div class="fw-bold">{{ $todayAbsensi->check_out_formatted }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3">
                            Terima kasih atas kerja keras Anda hari ini.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-hide alerts setelah 5 detik
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endsection