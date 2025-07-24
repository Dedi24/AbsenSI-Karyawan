@extends('layouts.app')

@section('title', 'Absensi Fingerprint')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white text-center py-4">
                    <h2 class="mb-0">
                        <i class="bi bi-fingerprint"></i> Absensi Fingerprint
                    </h2>
                    <p class="mb-0">{{ \App\Models\Setting::getCompanyName() }}</p>
                </div>
                <div class="card-body text-center">
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

                    <div class="mb-4">
                        <div class="avatar avatar-xl mb-3">
                            <div class="avatar-content bg-primary text-white" style="width: 80px; height: 80px; border-radius: 50%;">
                                <i class="bi bi-person-fill" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                        <h4 class="text-primary">{{ Auth::user()->name }}</h4>
                        <p class="text-muted">{{ now()->format('d F Y') }}</p>
                    </div>

                    <!-- Status Absensi -->
                    <div class="row mb-4 g-3">
                        <div class="col-6">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h6 class="card-title text-success">
                                        <i class="bi bi-box-arrow-in-right"></i> Masuk
                                    </h6>
                                    @if($todayAbsensi->check_in)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check"></i>
                                            {{ substr($todayAbsensi->check_in, 0, 5) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Belum</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border-danger">
                                <div class="card-body">
                                    <h6 class="card-title text-danger">
                                        <i class="bi bi-box-arrow-right"></i> Pulang
                                    </h6>
                                    @if($todayAbsensi->check_out)
                                        <span class="badge bg-danger">
                                            <i class="bi bi-check"></i>
                                            {{ substr($todayAbsensi->check_out, 0, 5) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Belum</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fingerprint Scanner Area -->
                    <div class="mb-4">
                        <div class="fingerprint-scanner bg-light rounded p-4 mb-3">
                            <div class="scanner-animation">
                                <div class="scanner-circle mx-auto mb-3" style="width: 150px; height: 150px; border: 3px dashed #0d6efd; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-fingerprint" style="font-size: 4rem; color: #0d6efd;"></i>
                                </div>
                                <p class="text-muted">Tempelkan jari Anda pada sensor</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mb-4">
                        @if(!$todayAbsensi->check_in)
                            <button type="button" class="btn btn-success btn-lg w-100 mb-2" onclick="scanFingerprint('in')">
                                <i class="bi bi-fingerprint"></i> Absen Masuk dengan Fingerprint
                            </button>
                        @elseif($todayAbsensi->check_in && !$todayAbsensi->check_out)
                            <button type="button" class="btn btn-danger btn-lg w-100 mb-2" onclick="scanFingerprint('out')">
                                <i class="bi bi-fingerprint"></i> Absen Pulang dengan Fingerprint
                            </button>
                        @else
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill"></i>
                                <strong>Anda sudah absen hari ini!</strong>
                                <p class="mb-0">Masuk: {{ substr($todayAbsensi->check_in, 0, 5) }} |
                                Pulang: {{ substr($todayAbsensi->check_out, 0, 5) }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Alternative Options -->
                    <div class="border-top pt-3">
                        <p class="text-muted mb-2">Metode absen lainnya:</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('karyawan.absensi.qr-code') }}" class="btn btn-outline-primary">
                                <i class="bi bi-qr-code-scan"></i> Absen dengan QR Code
                            </a>
                            <a href="{{ route('karyawan.absensi.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-list"></i> Lihat Riwayat Absensi
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center bg-light">
                    <small class="text-muted">
                        <i class="bi bi-shield-lock"></i> Device Fingerprint Protection Active
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk hasil fingerprint -->
<div class="modal fade" id="fingerprintModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hasil Absensi Fingerprint</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="fingerprintResult"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="location.reload()">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk scanner fingerprint -->
<div class="modal fade" id="scannerFingerprintModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scanner Fingerprint</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <div class="scanner-animation">
                        <div class="scanner-circle mx-auto mb-3" style="width: 120px; height: 120px; border: 3px solid #0d6efd; border-radius: 50%; display: flex; align-items: center; justify-content: center; position: relative;">
                            <i class="bi bi-fingerprint" style="font-size: 3rem; color: #0d6efd;"></i>
                            <div class="scanner-line" style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: #0d6efd; animation: scan 2s infinite;"></div>
                        </div>
                        <p>Tempelkan jari Anda pada sensor...</p>
                    </div>
                </div>
                <div id="scannerResult">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
@keyframes scan {
    0% { transform: translateY(0); }
    100% { transform: translateY(116px); }
}

.scanner-circle {
    position: relative;
    overflow: hidden;
}

.fingerprint-scanner {
    transition: all 0.3s ease;
}

.fingerprint-scanner:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>

<script>
let currentType = '';

function scanFingerprint(type) {
    currentType = type;
    $('#scannerFingerprintModal').modal('show');

    // Simulasi proses scanning fingerprint
    setTimeout(function() {
        processFingerprint();
    }, 3000);
}

function processFingerprint() {
    // Generate fingerprint data simulasi
    const fingerprintData = generateFingerprintData();

    $.ajax({
        url: '{{ route("karyawan.absensi.store-fingerprint") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            type: currentType,
            fingerprint_data: fingerprintData
        },
        success: function(response) {
            if (response.success) {
                $('#scannerFingerprintModal').modal('hide');
                showResult('success', response.message, response.time);
            }
        },
        error: function(xhr) {
            $('#scannerFingerprintModal').modal('hide');
            let errorMessage = 'Terjadi kesalahan saat memproses fingerprint';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }
            showResult('error', errorMessage);
        }
    });
}

function generateFingerprintData() {
    // Simulasi generate fingerprint data
    return btoa(Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15));
}

function showResult(type, message, time = null) {
    let icon, colorClass;

    if (type === 'success') {
        icon = '<i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>';
        colorClass = 'text-success';
    } else {
        icon = '<i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>';
        colorClass = 'text-danger';
    }

    let timeInfo = '';
    if (time) {
        timeInfo = `<p class="mt-2"><small>Waktu: ${time}</small></p>`;
    }

    $('#fingerprintResult').html(`
        <div class="${colorClass}">
            ${icon}
            <h4 class="mt-3">${message}</h4>
            ${timeInfo}
        </div>
    `);

    $('#fingerprintModal').modal('show');
}
</script>
@endsection
