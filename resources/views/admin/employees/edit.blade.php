@extends('layouts.app')

@section('title', 'Edit Karyawan - ' . $employee->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">✏️ Edit Karyawan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">
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

<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-person-lines-fill"></i> Formulir Edit Karyawan
                </h6>
                <span class="badge bg-info">ID: {{ $employee->id }}</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.employees.update', $employee) }}" method="POST">
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
                                       id="name" name="name" value="{{ old('name', $employee->name) }}" 
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
                                       id="email" name="email" value="{{ old('email', $employee->email) }}" 
                                       placeholder="email@perusahaan.com" required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password Baru (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" 
                                       placeholder="Biarkan kosong jika tidak ingin diubah">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Kosongkan jika tidak ingin mengganti password
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" 
                                       placeholder="Ulangi password baru">
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-shield"></i>
                                </span>
                                <select class="form-control @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="admin" {{ old('role', $employee->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="karyawan" {{ old('role', $employee->role) == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                </select>
                            </div>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sendNotification" name="send_notification">
                                <label class="form-check-label" for="sendNotification">
                                    Kirim notifikasi perubahan ke karyawan
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.employees.device', $employee) }}" class="btn btn-info">
                                <i class="bi bi-phone"></i> Device Info
                            </a>
                        </div>
                    </div>
                </form>
                
                <!-- Hidden form for delete -->
                <form id="delete-form-{{ $employee->id }}" 
                      action="{{ route('admin.employees.destroy', $employee) }}" 
                      method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-person-badge"></i> Profil Karyawan
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="avatar avatar-xl mx-auto">
                        <div class="avatar-content bg-primary text-white rounded-circle" style="width: 80px; height: 80px;">
                            <i class="bi bi-person-fill" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
                
                <h5 class="card-title">{{ $employee->name }}</h5>
                <p class="card-text text-muted">{{ $employee->email }}</p>
                
                <hr>
                
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="mb-2">
                            <i class="bi bi-phone text-primary fa-lg"></i>
                            <div class="small">Device Status</div>
                        </div>
                        <div>
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
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="mb-2">
                            <i class="bi bi-calendar-check text-success fa-lg"></i>
                            <div class="small">Absensi Hari Ini</div>
                        </div>
                        <div>
                            @php
                                $todayAttendance = $employee->attendances()->where('date', today())->first();
                            @endphp
                            @if($todayAttendance)
                                <span class="badge bg-success">
                                    <i class="bi bi-check"></i> Hadir
                                </span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="small text-muted">
                    <div>Dibuat: {{ $employee->created_at->isoFormat('D MMM YYYY') }}</div>
                    <div>Terakhir Update: {{ $employee->updated_at->diffForHumans() }}</div>
                    @if($employee->last_login_at)
                        <div>Login Terakhir: {{ \Carbon\Carbon::parse($employee->last_login_at)->diffForHumans() }}</div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">
                    <i class="bi bi-exclamation-triangle"></i> Aksi Berbahaya
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h6><i class="bi bi-trash"></i> Hapus Karyawan</h6>
                    <p class="mb-2">
                        Menghapus karyawan akan menghapus semua data absensi dan riwayat terkait.
                    </p>
                    <button type="button" class="btn btn-outline-danger w-100" 
                            onclick="confirmDelete({{ $employee->id }}, '{{ $employee->name }}')">
                        <i class="bi bi-trash"></i> Hapus Karyawan
                    </button>
                </div>
            </div>
            
            <!-- Hidden form for delete -->
            <form id="delete-form-{{ $employee->id }}" 
                  action="{{ route('admin.employees.destroy', $employee) }}" 
                  method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
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

// Password visibility toggle
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').className = type === 'password' ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }
    
    if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            this.querySelector('i').className = type === 'password' ? 'bi bi-eye-slash' : 'bi bi-eye';
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
});
</script>
@endsection