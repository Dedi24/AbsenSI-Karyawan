@extends('layouts.app')

@section('title', 'Device Info - ' . $employee->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">📱 Device Information</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Profil
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-fingerprint"></i> Device Fingerprint Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted" style="width: 30%;">Status</td>
                                <td>
                                    @if($employee->device_fingerprint)
                                        <span class="badge bg-success">Terdaftar</span>
                                    @else
                                        <span class="badge bg-warning">Belum Terdaftar</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Device Fingerprint</td>
                                <td>
                                    @if($employee->device_fingerprint)
                                        <code class="bg-light p-1 rounded">{{ $employee->device_fingerprint }}</code>
                                        <button class="btn btn-outline-primary btn-sm ms-2" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                title="Copy to clipboard"
                                                onclick="copyToClipboard('{{ $employee->device_fingerprint }}')">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Terakhir Login IP</td>
                                <td>
                                    @if($employee->last_login_ip)
                                        <span class="badge bg-info">{{ $employee->last_login_ip }}</span>
                                        <a href="https://iplocation.io/ip/{{ $employee->last_login_ip }}" 
                                           target="_blank" 
                                           class="btn btn-outline-secondary btn-sm ms-2" 
                                           data-bs-toggle="tooltip" 
                                           data-bs-placement="top" 
                                           title="Lihat lokasi IP">
                                            <i class="bi bi-geo-alt"></i> Lokasi
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Terakhir Login Waktu</td>
                                <td>
                                    @if($employee->last_login_at)
                                        {{ $employee->last_login_at_formatted }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-shield-exclamation"></i> Device Security</h5>
            </div>
            <div class="card-body">
                <p class="mb-3">
                    Device fingerprint digunakan untuk meningkatkan keamanan akun karyawan dengan memvalidasi perangkat yang digunakan untuk login dan absensi.
                </p>
                <div class="alert alert-info">
                    <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Informasi</h6>
                    <ul class="mb-0">
                        <li>Fingerprint dibuat dari kombinasi informasi browser dan IP address.</li>
                        <li>Jika karyawan login dari perangkat baru, fingerprint akan diperbarui.</li>
                        <li>Reset fingerprint akan memaksa karyawan untuk login ulang dan mendaftarkan perangkat baru.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm sticky-top" style="top: 80px;">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-person"></i> {{ $employee->name }}</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="avatar avatar-xl mx-auto">
                        <div class="avatar-content bg-{{ $employee->avatar_color }} text-white rounded-circle" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ $employee->initials }}
                        </div>
                    </div>
                </div>
                <h5>{{ $employee->name }}</h5>
                <p class="text-muted mb-3">{{ $employee->jabatan ?? '-' }}</p>
                
                <div class="d-grid gap-2">
                    @if($employee->device_fingerprint)
                        <button type="button" class="btn btn-danger" onclick="confirmResetDevice({{ $employee->id }}, '{{ $employee->name }}')">
                            <i class="bi bi-arrow-repeat"></i> Reset Device Fingerprint
                        </button>
                    @else
                        <button type="button" class="btn btn-success" disabled>
                            <i class="bi bi-check-circle"></i> Device Belum Terdaftar
                        </button>
                    @endif
                    
                    <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye"></i> Lihat Profil Lengkap
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Reset Device Tersembunyi -->
<form id="reset-device-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>
@endsection

@section('scripts')
<script>
    function confirmResetDevice(id, name) {
        if (confirm(`⚠️ PERINGATAN!\n\nApakah Anda yakin ingin mereset device fingerprint untuk karyawan "${name}"?\n\nKaryawan akan diminta untuk mendaftarkan perangkat baru saat login berikutnya.`)) {
            const form = document.getElementById('reset-device-form');
            form.action = `{{ url('admin/employees') }}/${id}/reset-device`;
            form.submit();
        }
    }
    
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            // Tampilkan pesan sukses (opsional)
            const originalText = event.target.innerHTML;
            event.target.innerHTML = '<i class="bi bi-check"></i> Copied!';
            setTimeout(() => {
                event.target.innerHTML = originalText;
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
            alert('Gagal menyalin ke clipboard');
        });
    }
    
    // Inisialisasi tooltips
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection