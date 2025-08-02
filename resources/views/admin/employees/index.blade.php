@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">👥 Data Karyawan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Tambah Karyawan
            </a>
            <button class="btn btn-outline-secondary" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
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
                            Device Terdaftar
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $employees->where('device_fingerprint', '!=', null)->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-phone-fill fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Online Hari Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ App\Models\Absensi::where('date', today())->count() }}
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
                            Tidak Hadir
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ App\Models\Absensi::where('date', today())->where('status', 'alpha')->count() }}
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

<!-- Search and Filter Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-funnel"></i> Filter & Pencarian
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Cari karyawan...">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <select class="form-control" id="deviceFilter">
                    <option value="">Semua Device</option>
                    <option value="device">Device Terdaftar</option>
                    <option value="no_device">Belum Terdaftar</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <select class="form-control" id="roleFilter">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="karyawan">Karyawan</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <button class="btn btn-outline-secondary w-100" onclick="resetFilter()">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-list"></i> Daftar Karyawan
        </h6>
        <div class="text-muted small">
            Menampilkan {{ $employees->count() }} karyawan
        </div>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="employeeTable">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th class="text-center" width="12%">Role</th>
                        <th class="text-center" width="15%">Device Status</th>
                        <th class="text-center" width="15%">Last Login</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $index => $employee)
                    <tr class="employee-row" 
                        data-name="{{ strtolower($employee->name) }}" 
                        data-device="{{ $employee->device_fingerprint ? 'device' : 'no_device' }}"
                        data-role="{{ $employee->role }}">
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
                        <td>
                            <a href="mailto:{{ $employee->email }}" class="text-decoration-none">
                                {{ $employee->email }}
                            </a>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $employee->role === 'admin' ? 'primary' : 'success' }}">
                                {{ ucfirst($employee->role) }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($employee->device_fingerprint)
                                <span class="badge bg-success" data-bs-toggle="tooltip" 
                                      title="Device Fingerprint: {{ substr($employee->device_fingerprint, 0, 10) }}...">
                                    <i class="bi bi-phone-fill"></i> Terdaftar
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="bi bi-phone"></i> Belum
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($employee->last_login_at)
                                <div class="small">
                                    <div>{{ \Carbon\Carbon::parse($employee->last_login_at)->isoFormat('D/MMM/YYYY') }}</div>
                                    <div class="text-muted small">{{ \Carbon\Carbon::parse($employee->last_login_at)->format('H:i') }}</div>
                                </div>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.employees.edit', $employee) }}" 
                                   class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('admin.employees.device', $employee) }}" 
                                   class="btn btn-outline-info" data-bs-toggle="tooltip" title="Device Info">
                                    <i class="bi bi-phone"></i>
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
    
    @if($employees->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Menampilkan {{ $employees->firstItem() }} sampai {{ $employees->lastItem() }} dari {{ $employees->total() }} karyawan
            </div>
            <div>
                {{ $employees->links() }}
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

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
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
// Search and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const deviceFilter = document.getElementById('deviceFilter');
    const roleFilter = document.getElementById('roleFilter');
    const employeeRows = document.querySelectorAll('.employee-row');

    // Search functionality
    searchInput.addEventListener('input', function() {
        filterEmployees();
    });

    // Device filter functionality
    deviceFilter.addEventListener('change', function() {
        filterEmployees();
    });

    // Role filter functionality
    roleFilter.addEventListener('change', function() {
        filterEmployees();
    });

    function filterEmployees() {
        const searchTerm = searchInput.value.toLowerCase();
        const deviceValue = deviceFilter.value;
        const roleValue = roleFilter.value;

        employeeRows.forEach(row => {
            const name = row.getAttribute('data-name');
            const deviceStatus = row.getAttribute('data-device');
            const role = row.getAttribute('data-role');
            
            let show = true;

            // Filter by search term
            if (searchTerm && !name.includes(searchTerm)) {
                show = false;
            }

            // Filter by device status
            if (deviceValue && deviceStatus !== deviceValue) {
                show = false;
            }

            // Filter by role
            if (roleValue && role !== roleValue) {
                show = false;
            }

            if (show) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
});

// Reset filter
function resetFilter() {
    document.getElementById('searchInput').value = '';
    document.getElementById('deviceFilter').value = '';
    document.getElementById('roleFilter').value = '';
    
    // Show all rows
    document.querySelectorAll('.employee-row').forEach(row => {
        row.style.display = '';
    });
}

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