@extends('layouts.app')

@section('title', 'Detail Device Karyawan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Device Karyawan</h1>
    <div>
        <a href="{{ route('admin.employees.index') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Karyawan</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="30%">Nama</th>
                        <td>{{ $employee->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $employee->email }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>
                            <span class="badge bg-{{ $employee->role === 'admin' ? 'primary' : 'success' }}">
                                {{ ucfirst($employee->role) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Device</h5>
            </div>
            <div class="card-body">
                @if($employee->device_fingerprint)
                    <table class="table">
                        <tr>
                            <th width="30%">Device Fingerprint</th>
                            <td>
                                <code>{{ substr($employee->device_fingerprint, 0, 20) }}...</code>
                                <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('{{ $employee->device_fingerprint }}')">
                                    <i class="bi bi-clipboard"></i> Copy
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th>IP Address Terakhir</th>
                            <td>{{ $employee->last_login_ip ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Login Terakhir</th>
                            <td>
                                @if($employee->last_login_at)
                                    {{ $employee->last_login_at->format('d M Y H:i:s') }}
                                    ({{ $employee->last_login_at->diffForHumans() }})
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status Device</th>
                            <td>
                                <span class="badge bg-success">Device Terdaftar</span>
                            </td>
                        </tr>
                    </table>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Karyawan belum memiliki device fingerprint yang terdaftar.
                    </div>
                @endif

                <div class="mt-3">
                    <form action="{{ route('admin.employees.reset-device', $employee) }}" method="POST" class="d-inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Apakah Anda yakin ingin mereset device fingerprint karyawan ini?')">
                            <i class="bi bi-trash"></i> Reset Device Fingerprint
                        </button>
                    </form>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Keamanan</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h6><i class="bi bi-shield-exclamation"></i> Keamanan Device</h6>
                    <p class="mb-0">
                        Device fingerprint digunakan untuk mencegah "titip absen" dan memastikan
                        bahwa karyawan hanya bisa absen dari device yang terdaftar.
                    </p>
                </div>

                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle"></i> Cara Kerja</h6>
                    <ul class="mb-0">
                        <li>Device fingerprint dibuat dari kombinasi browser, IP, dan bahasa</li>
                        <li>Satu karyawan hanya bisa terdaftar di satu device</li>
                        <li>Reset diperlukan jika karyawan ganti device</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Device fingerprint berhasil disalin!');
    }).catch(function(err) {
        console.error('Gagal menyalin: ', err);
    });
}
</script>
@endsection
