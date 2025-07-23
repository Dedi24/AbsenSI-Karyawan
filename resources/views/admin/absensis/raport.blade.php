@extends('layouts.app')

@section('title', 'Laporan Absensi')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Laporan Absensi</h1>
    </div>

    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="month" class="form-label">Bulan</label>
                <input type="month" class="form-control" id="month" name="month" value="{{ request('month', now()->format('Y-m')) }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary form-control">Filter</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Hadir</th>
                    <th>Alpha</th>
                    <th>Izin/Sakit</th>
                    <th>Total Jam Kerja</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $absensis->get($employee->id)?->where('status', 'hadir')->count() ?? 0 }}</td>
                    <td>{{ $absensis->get($employee->id)?->where('status', 'alpha')->count() ?? 0 }}</td>
                    <td>{{ $absensis->get($employee->id)?->whereIn('status', ['izin', 'sakit'])->count() ?? 0 }}</td>
                    <td>
                        @php
                            $totalHours = $absensis->get($employee->id)?->sum('working_hours') ?? 0;
                        @endphp
                        {{ $totalHours }} jam
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
