@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Karyawan</h1>
    <div class="text-muted">
        {{ now()->timezone('Asia/Jakarta')->isoFormat('dddd, D MMMM YYYY') }}
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Total Hadir</div>
            <div class="card-body">
                <h5 class="card-title">{{ $totalHadir }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header">Terlambat</div>
            <div class="card-body">
                <h5 class="card-title">{{ $totalTerlambat }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger mb-3">
            <div class="card-header">Tidak Hadir</div>
            <div class="card-body">
                <h5 class="card-title">{{ $totalAlpha }}</h5>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Absen Hari Ini</h5>
            </div>
            <div class="card-body">
                @php
                    $todayAbsensi = Auth::user()->absensis()->where('date', today())->first();
                @endphp

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(!$todayAbsensi || !$todayAbsensi->check_in)
                    <div class="d-grid gap-3">
                        <a href="{{ route('karyawan.absensi.fingerprint') }}" class="btn btn-success btn-lg">
                            <i class="bi bi-fingerprint"></i> Absen Masuk dengan Fingerprint
                        </a>
                        <div class="text-center">
                            <small class="text-muted">Atau pilih metode lain:</small>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('karyawan.absensi.qr-code') }}" class="btn btn-outline-primary">
                                <i class="bi bi-qr-code-scan"></i> Absen dengan QR Code
                            </a>
                            <form action="{{ route('karyawan.absensi.store') }}" method="POST" class="d-inline">
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
                    <div class="d-grid gap-3">
                        <a href="{{ route('karyawan.absensi.fingerprint') }}" class="btn btn-danger btn-lg">
                            <i class="bi bi-fingerprint"></i> Absen Pulang dengan Fingerprint
                        </a>
                        <div class="text-center">
                            <small class="text-muted">Atau pilih metode lain:</small>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('karyawan.absensi.qr-code') }}" class="btn btn-outline-primary">
                                <i class="bi bi-qr-code-scan"></i> Absen dengan QR Code
                            </a>
                            <form action="{{ route('karyawan.absensi.store') }}" method="POST" class="d-inline">
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
                    <div class="alert alert-success text-center">
                        <i class="bi bi-check-circle-fill"></i>
                        <h5>Anda sudah absen hari ini</h5>
                        <p class="mb-0">
                            Masuk: {{ $todayAbsensi->check_in_formatted }} |
                            Pulang: {{ $todayAbsensi->check_out_formatted }}
                        </p>
                        <small class="text-muted">{{ now()->timezone('Asia/Jakarta')->isoFormat('dddd, D MMMM YYYY') }}</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
