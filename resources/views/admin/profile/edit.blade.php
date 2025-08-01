@extends('layouts.app')

@section('title', 'Edit Profil - ' . Auth::user()->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">✏️ Edit Profil Administrator</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.profile.show') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

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
                    <i class="bi bi-person-lines-fill"></i> Formulir Edit Profil
                </h6>
                <span class="badge bg-info">ID: {{ Auth::user()->id }}</span>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                            <i class="bi bi-person"></i> Profil
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
                            <i class="bi bi-lock"></i> Password
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="device-tab" data-bs-toggle="tab" data-bs-target="#device" type="button" role="tab">
                            <i class="bi bi-phone"></i> Device
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="profileTabsContent">
                    <!-- Tab Profil -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form action="{{ route('admin.profile.update') }}" method="POST" class="mt-4">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-person"></i>
                                        </span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', Auth::user()->name) }}" 
                                               placeholder="Masukkan nama lengkap" required>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', Auth::user()->email) }}" 
                                               placeholder="email@perusahaan.com" required>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-shield"></i>
                                    </span>
                                    <input type="text" class="form-control" 
                                           id="role" name="role" value="{{ ucfirst(Auth::user()->role) }}" 
                                           readonly>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i> Role tidak dapat diubah melalui profil
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.profile.show') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Tab Password -->
                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <form action="{{ route('admin.profile.password') }}" method="POST" class="mt-4">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                               id="current_password" name="current_password" 
                                               placeholder="Masukkan password saat ini" required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password" 
                                               placeholder="Minimal 8 karakter" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="bi bi-info-circle"></i> Password minimal 8 karakter
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock-fill"></i>
                                        </span>
                                        <input type="password" class="form-control" 
                                               id="password_confirmation" name="password_confirmation" 
                                               placeholder="Ulangi password baru" required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.profile.show') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Ganti Password
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Tab Device -->
                    <div class="tab-pane fade" id="device" role="tabpanel" aria-labelledby="device-tab">
                        <div class="mt-4">
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
                                            <div class="mb-2">
                                                <strong>Status Device:</strong>
                                                @if(Auth::user()->device_fingerprint)
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
                                                <span class="badge bg-primary ms-2">
                                                    {{ ucfirst(Auth::user()->role) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            @if(Auth::user()->device_fingerprint)
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="bi bi-fingerprint"></i> Device Fingerprint</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" 
                                                   value="{{ Auth::user()->device_fingerprint }}" 
                                                   readonly>
                                            <button class="btn btn-outline-primary" type="button" 
                                                    onclick="copyToClipboard('{{ Auth::user()->device_fingerprint }}')">
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
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-phone-x fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Device Belum Terdaftar</h5>
                                    <p class="text-muted">
                                        Anda belum pernah login atau device fingerprint telah direset.
                                    </p>
                                </div>
                            @endif
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('admin.profile.show') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                
                                @if(Auth::user()->device_fingerprint)
                                    <button type="button" class="btn btn-danger" 
                                            onclick="confirmResetDevice({{ Auth::user()->id }}, '{{ Auth::user()->name }}')">
                                        <i class="bi bi-trash"></i> Reset Device Fingerprint
                                    </button>
                                    
                                    <form id="reset-device-form-{{ Auth::user()->id }}" 
                                          action="{{ route('admin.employees.reset-device', Auth::user()) }}" 
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
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-info-circle"></i> Informasi
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="bi bi-lightbulb"></i> Tips</h6>
                    <ul class="mb-0">
                        <li>Nama lengkap administrator</li>
                        <li>Email harus unik dan valid</li>
                        <li>Password minimal 8 karakter</li>
                        <li>Device akan menerima email notifikasi</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="bi bi-shield-lock"></i> Keamanan</h6>
                    <p class="mb-0">
                        Setelah dibuat, administrator harus mengganti password saat login pertama kali.
                    </p>
                </div>
                
                <div class="text-center mt-3">
                    <i class="bi bi-person-badge fa-3x text-primary mb-2"></i>
                    <h5 class="mt-2">Administrator</h5>
                    <p class="text-muted small">
                        Formulir ini digunakan untuk mengedit profil administrator.
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
                        <i class="bi bi-people"></i> Lihat Semua Karyawan
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

.nav-tabs .nav-link {
    border: 1px solid transparent;
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
}

.nav-tabs .nav-link.active {
    color: #4e73df;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
}

.nav-tabs .nav-link:hover {
    border-color: #e9ecef #e9ecef #dee2e6;
}

.tab-content {
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 0.5rem 0.5rem;
    padding: 1.5rem;
    background-color: #fff;
}
</style>

<script>
// Reset device confirmation
function confirmResetDevice(employeeId, employeeName) {
    if (confirm(`Apakah Anda yakin ingin mereset device fingerprint untuk administrator "${employeeName}"?\n\nAdministrator akan perlu login ulang dan device fingerprint akan terdaftar kembali.`)) {
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

// Password visibility toggle
document.addEventListener('DOMContentLoaded', function() {
    const currentPasswordInput = document.getElementById('current_password');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const toggleCurrentPassword = document.getElementById('toggleCurrentPassword');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    
    if (toggleCurrentPassword) {
        toggleCurrentPassword.addEventListener('click', function() {
            const type = currentPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            currentPasswordInput.setAttribute('type', type);
            this.querySelector('i').className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
        });
    }
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
        });
    }
    
    if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            this.querySelector('i').className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
        });
    }
    
    // Password confirmation
    confirmPasswordInput.addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        
        if (password !== confirmPassword && confirmPassword !== '') {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection