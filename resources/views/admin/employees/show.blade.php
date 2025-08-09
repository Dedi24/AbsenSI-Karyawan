@extends('layouts.app')

@section('title', 'Detail Karyawan - ' . $employee->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">👤 Detail Karyawan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.employees.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $employee->id }}, '{{ $employee->name }}')">
                <i class="bi bi-trash"></i> Hapus
            </button>
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
    <!-- Profil Karyawan -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="avatar avatar-xl mx-auto">
                        <div class="avatar-content bg-{{ $employee->avatar_color }} text-white rounded-circle" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ $employee->initials }}
                        </div>
                    </div>
                </div>
                <h4>{{ $employee->name }}</h4>
                <p class="text-muted mb-1">{{ $employee->jabatan ?? '-' }}</p>
                <p class="text-muted">{{ $employee->departemen ?? '-' }}</p>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-envelope"></i> Kirim Email
                    </button>
                    <button class="btn btn-outline-success btn-sm">
                        <i class="bi bi-whatsapp"></i> Kirim WhatsApp
                    </button>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Statistik Bulan Ini</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="h5 mb-0 text-success">{{ $employee->absensi_bulan_ini }}</div>
                        <small class="text-muted">Hadir</small>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h5 mb-0 text-warning">{{ $employee->keterlambatan_bulan_ini }}</div>
                        <small class="text-muted">Terlambat</small>
                    </div>
                    <div class="col-6">
                        <div class="h5 mb-0 text-danger">{{ $employee->alpha_bulan_ini }}</div>
                        <small class="text-muted">Alpha</small>
                    </div>
                    <div class="col-6">
                        <div class="h5 mb-0 text-info">{{ $employee->izin_sakit_bulan_ini }}</div>
                        <small class="text-muted">Izin/Sakit</small>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <div class="h6 mb-0">Presensi: {{ $employee->presentase_kehadiran }}%</div>
                    <small class="text-muted">Rata-rata: {{ $employee->rata_rata_jam_kerja }} jam/hari</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Detail -->
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-person-vcard"></i> Informasi Pribadi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted">NIK</label>
                        <div>{{ $employee->nik ?? '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted">NIP</label>
                        <div>{{ $employee->nip ?? '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted">Email</label>
                        <div>{{ $employee->email }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted">No. HP</label>
                        <div>{{ $employee->no_hp_formatted }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted">Tempat, Tanggal Lahir</label>
                        <div>{{ $employee->tempat_lahir ?? '-' }}, {{ $employee->tanggal_lahir_formatted }}</div>
                        <small class="text-muted">(Usia: {{ $employee->age }} tahun)</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted">Jenis Kelamin</label>
                        <div><i class="{{ $employee->jenis_kelamin_icon }}"></i> {{ $employee->jenis_kelamin == 'L' ? 'Laki-laki' : ($employee->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted">Status Perkawinan</label>
                        <div>{{ $employee->status_perkawinan_formatted }}</div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="small text-muted">Alamat</label>
                        <div>{{ $employee->alamat_singkat }}</div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="small text-muted">Pendidikan Terakhir</label>
                        <div>{{ $employee->pendidikan_terakhir_singkat }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-briefcase"></i> Informasi Kepegawaian</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted">Jabatan</label>
                        <div>{{ $employee->jabatan ?? '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted">Departemen</label>
                        <div>{{ $employee->departemen ?? '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted">Tanggal Masuk</label>
                        <div>{{ $employee->tanggal_masuk_formatted }}</div>
                        <small class="text-muted">(Masa Kerja: {{ $employee->masa_kerja }})</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted">Status</label>
                        <div>
                            <span class="badge bg-{{ $employee->status_badge_color }}">{{ ucfirst($employee->status) }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted">Role</label>
                        <div>
                            <span class="badge bg-{{ $employee->role_badge_color }}">{{ ucfirst($employee->role) }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted">Device Fingerprint</label>
                        <div>
                            @if($employee->device_fingerprint)
                                <span class="badge bg-success">Terdaftar</span>
                                <button class="btn btn-sm btn-outline-danger ms-2" onclick="confirmResetDevice({{ $employee->id }}, '{{ $employee->name }}')">
                                    <i class="bi bi-arrow-repeat"></i> Reset
                                </button>
                            @else
                                <span class="badge bg-warning">Belum Terdaftar</span>
                            @endif
                        </div>
                        <small class="text-muted">{{ $employee->device_fingerprint_short }}</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small text-muted">Terakhir Login</label>
                        <div>{{ $employee->last_login_at_formatted }}</div>
                        <small class="text-muted">IP: {{ $employee->last_login_ip ?? '-' }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Hapus Tersembunyi -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Form Reset Device Tersembunyi -->
<form id="reset-device-form" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>
@endsection

@section('scripts')
<script>
    function confirmDelete(id, name) {
        if (confirm(`Apakah Anda yakin ingin menghapus karyawan "${name}"? Tindakan ini tidak dapat dibatalkan.`)) {
            const form = document.getElementById('delete-form');
            form.action = `{{ url('admin/employees') }}/${id}`;
            form.submit();
        }
    }

    function confirmResetDevice(id, name) {
        if (confirm(`Apakah Anda yakin ingin mereset device fingerprint untuk karyawan "${name}"?`)) {
            const form = document.getElementById('reset-device-form');
            form.action = `{{ url('admin/employees') }}/${id}/reset-device`;
            form.submit();
        }
    }
</script>
@endsection