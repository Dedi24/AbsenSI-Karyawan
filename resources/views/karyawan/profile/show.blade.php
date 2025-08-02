@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">👤 Profil Saya</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-person-circle"></i> Informasi Profil
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3 text-center mb-3">
                        <div class="avatar avatar-xl mx-auto">
                            @if(Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                     alt="Foto Profil" 
                                     class="rounded-circle img-fluid" 
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="avatar-content bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 100px; height: 100px;">
                                    <span style="font-size: 2rem;">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil"></i> Edit Profil
                            </a>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h4>{{ Auth::user()->name }}</h4>
                        <p class="text-muted">{{ Auth::user()->email }}</p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="mb-2">
                                    <strong>Role:</strong>
                                    <span class="badge bg-{{ Auth::user()->role === 'admin' ? 'primary' : 'success' }} ms-2">
                                        {{ ucfirst(Auth::user()->role) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="mb-2">
                                    <strong>Status Device:</strong>
                                    @if(Auth::user()->device_fingerprint)
                                        <span class="badge bg-success ms-2">
                                            <i class="bi bi-phone-fill"></i> Terdaftar
                                        </span>
                                    @else
                                        <span class="badge bg-warning ms-2">
                                            <i class="bi bi-phone"></i> Belum
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="mb-2">
                                    <strong>Terdaftar Sejak:</strong>
                                    <div>{{ Auth::user()->created_at->isoFormat('D MMMM YYYY') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="mb-2">
                                    <strong>Login Terakhir:</strong>
                                    <div>
                                        @if(Auth::user()->last_login_at)
                                            {{ \Carbon\Carbon::parse(Auth::user()->last_login_at)->diffForHumans() }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-envelope"></i> Email</label>
                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-person"></i> Nama Lengkap</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-phone"></i> Device Fingerprint</label>
                        <input type="text" class="form-control" 
                               value="{{ Auth::user()->device_fingerprint ? substr(Auth::user()->device_fingerprint, 0, 20) . '...' : '-' }}" 
                               readonly>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-geo-alt"></i> IP Address Terakhir</label>
                        <input type="text" class="form-control" 
                               value="{{ Auth::user()->last_login_ip ?? '-' }}" readonly>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
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
                    <i class="bi bi-shield-lock"></i> Keamanan
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="bi bi-lightbulb"></i> Tips Keamanan</h6>
                    <ul class="mb-0">
                        <li>Ganti password secara berkala</li>
                        <li>Jangan berbagi akun dengan orang lain</li>
                        <li>Gunakan device yang aman</li>
                        <li>Logout setelah selesai</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle"></i> Peringatan</h6>
                    <p class="mb-0">
                        Device fingerprint akan membatasi Anda hanya bisa login dari satu device.
                    </p>
                </div>
                
                <div class="text-center mt-3">
                    <i class="bi bi-person-badge fa-2x text-primary mb-2"></i>
                    <h6>Profil Pengguna</h6>
                    <p class="text-muted small">
                        Informasi profil Anda dalam sistem absensi karyawan.
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
                        <i class="bi bi-people"></i> Data Karyawan
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

.avatar-xl {
    width: 100px;
    height: 100px;
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
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection