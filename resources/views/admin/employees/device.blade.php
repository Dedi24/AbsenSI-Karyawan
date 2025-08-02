@extends('layouts.app')

@section('title', 'Device Info - ' . $employee->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">📱 Device Information</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Karyawan
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

<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-phone"></i> Device Information
                </h6>
                <span class="badge bg-info">ID: {{ $employee->id }}</span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3 text-center mb-3">
                        <div class="avatar avatar-xl mx-auto">
                            <div class="avatar-content bg-primary text-white rounded-circle" style="width: 80px; height: 80px;">
                                <i class="bi bi-person-fill" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h4>{{ $employee->name }}</h4>
                        <p class="text-muted">{{ $employee->email }}</p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Status Device:</strong>
                                    @if($employee->device_fingerprint)
                                        <span class="badge bg-success ms-2">
                                            <i class="bi bi-check-circle"></i> Terdaftar
                                        </span>
                                    @else
                                        <span class="badge bg-warning ms-2">
                                            <i class="bi bi-exclamation-circle"></i> Belum Terdaftar
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <strong>Role:</strong>
                                    <span class="badge bg-{{ $employee->role === 'admin' ? 'primary' : 'success' }} ms-2">
                                        {{ ucfirst($employee->role) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                @if($employee->device_fingerprint)
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-fingerprint"></i> Device Fingerprint</label>
                            <div class="input-group">
                                <input type="text" class="form-control" 
                                       value="{{ $employee->device_fingerprint }}" 
                                       readonly>
                                <button class="btn btn-outline-primary" type="button" 
                                        onclick="copyToClipboard('{{ $employee->device_fingerprint }}')">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                ID unik device yang terdaftar
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-geo-alt"></i> IP Address Terakhir</label>
                            <input type="text" class="form-control" 
                                   value="{{ $employee->last_login_ip ?? '-' }}" readonly>
                            <div class="form-text">
                                IP address saat login terakhir
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-clock"></i> Login Terakhir</label>
                            <input type="text" class="form-control" 
                                   value="{{ $employee->last_login_at ? \Carbon\Carbon::parse($employee->last_login_at)->isoFormat('D MMMM YYYY H:mm') : '-' }}" 
                                   readonly>
                            <div class="form-text">
                                Waktu login terakhir
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-globe"></i> Browser Info</label>
                            <input type="text" class="form-control" 
                                   value="Tidak tersedia" readonly>
                            <div class="form-text">
                                Informasi browser (akan tersedia di versi berikutnya)
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-phone-x fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Device Belum Terdaftar</h5>
                        <p class="text-muted">
                            Karyawan belum pernah login atau device fingerprint telah direset.
                        </p>
                    </div>
                @endif
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    
                    @if($employee->device_fingerprint)
                        <button type="button" class="btn btn-danger" 
                                onclick="confirmResetDevice({{ $employee->id }}, '{{ $employee->name }}')">
                            <i class="bi bi-trash"></i> Reset Device Fingerprint
                        </button>
                        
                        <form id="reset-device-form-{{ $employee->id }}" 
                              action="{{ route('admin.employees.reset-device', $employee) }}" 
                              method="POST" style="display: none;">
                            @csrf
                            @method('POST')
                        </form>
                    @else
                        <button type="button" class="btn btn-success" disabled>
                            <i class="bi bi-check-circle"></i> Device Siap Digunakan
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-shield-lock"></i> Keamanan Device
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle"></i> Cara Kerja</h6>
                    <ul class="mb-0">
                        <li>Device fingerprint dibuat dari kombinasi browser, IP, dan bahasa</li>
                        <li>Satu karyawan hanya bisa terdaftar di satu device</li>
                        <li>Reset diperlukan jika karyawan ganti device</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle"></i> Peringatan</h6>
                    <p class="mb-0">
                        Reset device fingerprint akan memungkinkan karyawan login dari device baru.
                    </p>
                </div>
                
                <div class="text-center mt-3">
                    <i class="bi bi-phone-fill fa-2x text-primary mb-2"></i>
                    <h6>Device Protection</h6>
                    <p class="text-muted small">
                        Sistem ini mencegah "titip absen" dengan membatasi satu karyawan hanya bisa login dari satu device.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="bi bi-lightning"></i> Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-outline-primary">
                        <i class="bi bi-pencil"></i> Edit Karyawan
                    </a>
                    <a href="{{ route('admin.absensis.index') }}" class="btn btn-outline-success">
                        <i class="bi bi-calendar-check"></i> Absensi Hari Ini
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-warning">
                        <i class="bi bi-gear"></i> Pengaturan Sistem
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
.card {
    border: none;
    border-radius: 0.5rem;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.shadow {
    box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15) !important;
}

.input-group-text {
    background-color: #f8f9fc;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.avatar-xl {
    width: 80px;
    height: 80px;
}

.avatar-content {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 0.875rem;
}
</style>

<script>
// Reset device confirmation
function confirmResetDevice(employeeId, employeeName) {
    if (confirm(`Apakah Anda yakin ingin mereset device fingerprint untuk karyawan "${employeeName}"?\n\nKaryawan akan perlu login ulang dan device fingerprint akan terdaftar kembali.`)) {
        document.getElementById(`reset-device-form-${employeeId}`).submit();
    }
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Device fingerprint berhasil disalin ke clipboard!');
    }).catch(function(err) {
        console.error('Gagal menyalin: ', err);
        // Fallback untuk browser yang tidak support
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Device fingerprint berhasil disalin!');
    });
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