@extends('layouts.app')

@section('title', 'Laporan Absensi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan Absensi</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.reports.export-all', ['format' => 'excel']) }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export Excel
            </a>
            <a href="{{ route('admin.reports.export-all', ['format' => 'pdf']) }}" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
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

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Filter Laporan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reports.generate') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                       value="{{ old('start_date', now()->subDays(7)->format('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                       value="{{ old('end_date', now()->format('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Karyawan (Opsional)</label>
                                <select class="form-control" id="employee_id" name="employee_id">
                                    <option value="">Semua Karyawan</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Format Laporan</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="format" id="format_pdf" value="pdf" checked>
                                        <label class="form-check-label" for="format_pdf">
                                            <i class="bi bi-file-earmark-pdf text-danger"></i> PDF
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="format" id="format_excel" value="excel">
                                        <label class="form-check-label" for="format_excel">
                                            <i class="bi bi-file-earmark-spreadsheet text-success"></i> Excel
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Generate Laporan
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Statistik Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4>{{ App\Models\Absensi::where('date', today())->where('status', 'hadir')->count() }}</h4>
                            <small>Hadir Hari Ini</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4>{{ App\Models\Absensi::where('date', today())->where('status', 'alpha')->count() }}</h4>
                            <small>Tidak Hadir Hari Ini</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4>{{ App\Models\Absensi::whereMonth('date', now()->month)->where('status', 'hadir')->count() }}</h4>
                            <small>Hadir Bulan Ini</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4>{{ App\Models\User::where('role', 'karyawan')->count() }}</h4>
                            <small>Total Karyawan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
