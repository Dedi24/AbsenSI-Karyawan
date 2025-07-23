@extends('layouts.app')

@section('title', 'Laporan Absensi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan Absensi</h1>
</div>

<form action="{{ route('admin.report.generate') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-4">
            <div class="mb-3">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>
        </div>

        <div class="col-md-4">
            <div class="mb-3">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required>
            </div>
        </div>

        <div class="col-md-4">
            <div class="mb-3">
                <label for="employee_id" class="form-label">Karyawan (Opsional)</label>
                <select class="form-control" id="employee_id" name="employee_id">
                    <option value="">Semua Karyawan</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
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
                        <label class="form-check-label" for="format_pdf">PDF</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="format" id="format_excel" value="excel">
                        <label class="form-check-label" for="format_excel">Excel</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-file-earmark-bar-graph"></i> Generate Laporan
    </button>
</form>
@endsection
