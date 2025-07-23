@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Karyawan</h1>
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

                @if(!$todayAbsensi || !$todayAbsensi->check_in)
                    <a href="{{ route('karyawan.absensi.store', ['type' => 'in']) }}"
                       class="btn btn-success"
                       onclick="event.preventDefault(); document.getElementById('absen-form-in').submit();">
                        <i class="bi bi-box-arrow-in-right"></i> Absen Masuk
                    </a>
                    <form id="absen-form-in" action="{{ route('karyawan.absensi.store') }}" method="POST" class="d-none">
                        @csrf
                        <input type="hidden" name="type" value="in">
                    </form>
                @elseif($todayAbsensi->check_in && !$todayAbsensi->check_out)
                    <a href="{{ route('karyawan.absensi.store', ['type' => 'out']) }}"
                       class="btn btn-danger"
                       onclick="event.preventDefault(); document.getElementById('absen-form-out').submit();">
                        <i class="bi bi-box-arrow-right"></i> Absen Pulang
                    </a>
                    <form id="absen-form-out" action="{{ route('karyawan.absensi.store') }}" method="POST" class="d-none">
                        @csrf
                        <input type="hidden" name="type" value="out">
                    </form>
                @else
                    <div class="alert alert-info">
                        Anda sudah absen hari ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
