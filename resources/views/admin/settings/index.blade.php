@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pengaturan Sistem</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    @method('POST')

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Perusahaan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Nama Perusahaan</label>
                        <input type="text" class="form-control" id="company_name" name="company_name"
                               value="{{ old('company_name', $settings['company_name']) }}" required>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pengaturan Lokasi</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="office_location" class="form-label">Lokasi Kantor (Latitude,Longitude)</label>
                        <input type="text" class="form-control" id="office_location" name="office_location"
                               value="{{ old('office_location', $settings['office_location']) }}" required>
                        <small class="form-text text-muted">Contoh: -6.200000,106.816666</small>
                    </div>

                    <div class="mb-3">
                        <label for="tolerance_radius" class="form-label">Radius Toleransi (meter)</label>
                        <input type="number" class="form-control" id="tolerance_radius" name="tolerance_radius"
                               value="{{ old('tolerance_radius', $settings['tolerance_radius']) }}"
                               min="50" max="1000" required>
                        <small class="form-text text-muted">Radius maksimal jarak karyawan dari kantor untuk bisa absen</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Jam Kerja</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="work_start_time" class="form-label">Jam Masuk Kerja</label>
                        <input type="time" class="form-control" id="work_start_time" name="work_start_time"
                               value="{{ old('work_start_time', substr($settings['work_start_time'], 0, 5)) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="work_end_time" class="form-label">Jam Pulang Kerja</label>
                        <input type="time" class="form-control" id="work_end_time" name="work_end_time"
                               value="{{ old('work_end_time', substr($settings['work_end_time'], 0, 5)) }}" required>
                    </div>

                    <div class="alert alert-info">
                        <strong>Info:</strong> Jam kerja digunakan untuk menghitung keterlambatan dan lembur.
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Notifikasi</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="whatsapp_group" class="form-label">ID Grup WhatsApp Admin</label>
                        <input type="text" class="form-control" id="whatsapp_group" name="whatsapp_group"
                               value="{{ old('whatsapp_group', $settings['whatsapp_group']) }}">
                        <small class="form-text text-muted">Untuk notifikasi real-time ke grup admin</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan Pengaturan
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</form>
@endsection
