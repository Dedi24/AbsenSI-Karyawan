@extends('layouts.app')

@section('title', 'Profil Admin - ' . Auth::user()->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">👤 Profil Administrator</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit Profil
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
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
                    <i class="bi bi-person-badge"></i> Informasi Profil
                </h6>
                <span class="badge bg-info">ID: {{ Auth::user()->id }}</span>
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
                        <h4>{{ Auth::user()->name }}</h4>
                        <p class="text-muted">{{ Auth::user()->email }}</p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-person"></i> Nama Lengkap</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-envelope"></i> Email</label>
                                    <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-shield"></i> Role</label>
                                    <input type="text" class="form-control" value="{{ ucfirst(Auth::user()->role) }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-calendar-check"></i> Member Sejak</label>
                                    <input type="text" class="form-control" 
                                           value="{{ Auth::user()->created_at->isoFormat('D MMMM YYYY') }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-phone"></i> Device Fingerprint</label>
                        <div class="input-group">
                            <input type="text" class="form-control" 
                                   value="{{ Auth::user()->device_fingerprint ?? '-' }}" readonly>
                            <button class="btn btn-outline-primary" type="button" 
                                    onclick="copyToClipboard('{{ Auth::user()->device_fingerprint ?? '-' }}')">
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
                               value="{{ Auth::user()->last_login_ip ?? '-' }}" readonly>
                        <div class="form-text">
                            IP address saat login terakhir
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-clock"></i> Login Terakhir</label>
                        <input type="text" class="form-control" 
                               value="{{ Auth::user()->last_login_at ? \Carbon\Carbon::parse(Auth::user()->last_login_at)->isoFormat('D MMMM YYYY H:mm') : '-' }}" 
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
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-shield-lock"></i> Keamanan Profil
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle"></i> Informasi Keamanan</h6>
                    <ul class="mb-0">
                        <li>Device fingerprint dibuat dari kombinasi browser, IP, dan bahasa</li>
                        <li>Satu admin hanya bisa terdaftar di satu device</li>
                        <li>Reset diperlukan jika admin ganti device</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle"></i> Peringatan</h6>
                    <p class="mb-0">
                        Reset device fingerprint akan memungkinkan admin login dari device baru.
                    </p>
                </div>
                
                <div class="text-center mt-3">
                    <i class="bi bi-person-badge fa-2x text-primary mb-2"></i>
                    <h6>Profil Administrator</h6>
                    <p class="text-muted small">
                        Sistem ini mencegah "titip absen" dengan membatasi satu admin hanya bisa login dari satu device.
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
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-people"></i> Karyawan
                    </a>
                    <a href="{{ route('admin.absensis.index') }}" class="btn btn-outline-success">
                        <i class="bi bi-calendar-check"></i> Absensi
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-warning">
                        <i class="bi bi-gear"></i> Pengaturan
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