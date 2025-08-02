@extends('layouts.app')

@section('title', 'Laporan Absensi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">📋 Laporan Absensi</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.absensis.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Absensi
            </a>
        </div>
    </div>
</div>

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

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> Terdapat beberapa kesalahan dalam input:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-funnel"></i> Filter Laporan
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="month" class="form-label">Bulan</label>
                <input type="month" class="form-control" id="month" name="month" 
                       value="{{ request('month', now()->format('Y-m')) }}">
            </div>
            <div class="col-md-4">
                <label for="employee_id" class="form-label">Karyawan (Opsional)</label>
                <select class="form-control" id="employee_id" name="employee_id">
                    <option value="">Semua Karyawan</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.absensis.raport') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Karyawan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $employees->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Hadir Bulan Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            @php
                                $hadirCount = 0;
                                foreach($groupedAbsensis as $userAbsensis) {
                                    $hadirCount += $userAbsensis->where('status', 'hadir')->count();
                                }
                            @endphp
                            {{ $hadirCount }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-check-circle-fill fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Izin/Sakit
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            @php
                                $izinSakitCount = 0;
                                foreach($groupedAbsensis as $userAbsensis) {
                                    $izinSakitCount += $userAbsensis->whereIn('status', ['izin', 'sakit'])->count();
                                }
                            @endphp
                            {{ $izinSakitCount }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-exclamation-circle-fill fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Tidak Hadir
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            @php
                                $alphaCount = 0;
                                foreach($groupedAbsensis as $userAbsensis) {
                                    $alphaCount += $userAbsensis->where('status', 'alpha')->count();
                                }
                            @endphp
                            {{ $alphaCount }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-x-circle-fill fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Report Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-file-earmark-bar-graph"></i> Laporan Absensi
        </h6>
        <div class="text-muted small">
            Menampilkan {{ $absensis->count() }} data
        </div>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="reportTable">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th>Nama</th>
                        <th class="text-center" width="10%">Hadir</th>
                        <th class="text-center" width="10%">Alpha</th>
                        <th class="text-center" width="10%">Izin/Sakit</th>
                        <th class="text-center" width="10%">Total Jam</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $index => $employee)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <div class="avatar-content bg-primary text-white rounded-circle">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <div>
                                    <strong>{{ $employee->name }}</strong>
                                    <div class="small text-muted">{{ $employee->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @php
                                $userAbsensis = $groupedAbsensis->get($employee->id, collect());
                                $hadirCount = $userAbsensis->where('status', 'hadir')->count();
                            @endphp
                            <span class="badge bg-success">{{ $hadirCount }}</span>
                        </td>
                        <td class="text-center">
                            @php
                                $alphaCount = $userAbsensis->where('status', 'alpha')->count();
                            @endphp
                            <span class="badge bg-danger">{{ $alphaCount }}</span>
                        </td>
                        <td class="text-center">
                            @php
                                $izinSakitCount = $userAbsensis->whereIn('status', ['izin', 'sakit'])->count();
                            @endphp
                            <span class="badge bg-warning">{{ $izinSakitCount }}</span>
                        </td>
                        <td class="text-center">
                            @php
                                $totalHours = $userAbsensis->sum('working_hours');
                            @endphp
                            <span class="badge bg-primary">{{ $totalHours }} jam</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.employees.device', $employee) }}" 
                                   class="btn btn-outline-info" data-bs-toggle="tooltip" title="Device Info">
                                    <i class="bi bi-phone"></i>
                                </a>
                                <a href="{{ route('admin.employees.edit', $employee) }}" 
                                   class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="confirmDelete({{ $employee->id }}, '{{ $employee->name }}')" 
                                        data-bs-toggle="tooltip" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Hidden form for delete -->
                            <form id="delete-form-{{ $employee->id }}" 
                                  action="{{ route('admin.employees.destroy', $employee) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="py-5">
                                <i class="bi bi-people fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak ada data karyawan</h5>
                                <p class="text-muted">Belum ada karyawan yang terdaftar dalam sistem.</p>
                                <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
                                    <i class="bi bi-person-plus"></i> Tambah Karyawan Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Perbaikan pagination disini - hanya tampilkan jika absensis memiliki pages -->
    @if($absensis instanceof \Illuminate\Pagination\LengthAwarePaginator && $absensis->hasPages())
    <div class="card-footer">
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
</div>
@endsection

@section('scripts')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.shadow {
    box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15) !important;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.avatar-sm {
    width: 40px;
    height: 40px;
}

.avatar-content {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 0.875rem;
}

.employee-row {
    transition: all 0.2s ease;
}

.employee-row.filtered-out {
    display: none;
}
</style>

<script>
// Delete confirmation
function confirmDelete(employeeId, employeeName) {
    if (confirm(`⚠️ PERINGATAN!\n\nApakah Anda yakin ingin MENGHAPUS karyawan "${employeeName}"?\n\nTINDAKAN INI TIDAK DAPAT DIBATALKAN dan akan menghapus semua data terkait karyawan ini.`)) {
        document.getElementById(`delete-form-${employeeId}`).submit();
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection