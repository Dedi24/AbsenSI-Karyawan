@extends('layouts.app')

@section('title', 'Absensi QR Code')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">
                        <i class="bi bi-qr-code-scan"></i> Absensi QR Code
                    </h4>
                    <small>{{ \App\Models\Setting::getCompanyName() }}</small>
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
                        <h5 class="text-muted">Halo, {{ Auth::user()->name }}</h5>
                        <p class="text-muted">Tanggal: {{ now()->format('d F Y') }}</p>
                    </div>

                    <!-- Device Info -->
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-phone"></i>
                        <strong>Device Info:</strong><br>
                        <small>
                            Fingerprint: {{ substr(Auth::user()->device_fingerprint ?? 'Belum terdaftar', 0, 20) }}...
                        </small>
                        @if(!Auth::user()->device_fingerprint)
                            <div class="mt-2">
                                <small class="text-warning">Device akan terdaftar setelah absen pertama kali</small>
                            </div>
                        @endif
                    </div>

                    <!-- Status Absensi -->
                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Masuk</h6>
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
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Pulang</h6>
                                    @if($todayAbsensi->check_out)
                                        <span class="badge bg-success">
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

                    <!-- QR Code Display -->
                    <div class="mb-4">
                        <div class="bg-white p-3 rounded shadow-sm d-inline-block">
                            {!! $qrCode !!}
                        </div>
                        <p class="text-muted mt-2">
                            <small>Scan QR Code ini untuk absen</small>
                        </p>
                    </div>

                    <!-- Informasi -->
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Informasi:</strong>
                        <ul class="mb-0 mt-2 text-start">
                            <li>QR Code ini hanya berlaku untuk hari ini</li>
                            <li>QR Code akan kadaluarsa dalam 5 menit</li>
                            <li>Pastikan kamera dapat membaca QR Code dengan jelas</li>
                            <li><strong>Device Fingerprint Protection Active</strong></li>
                        </ul>
                    </div>

                    <!-- Tombol Refresh dan Reset -->
                    <div class="mb-3">
                        <a href="{{ route('karyawan.absensi.qr-code') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-clockwise"></i> Refresh QR Code
                        </a>
                        <a href="{{ route('karyawan.absensi.index') }}" class="btn btn-secondary">
                            <i class="bi bi-list"></i> Riwayat Absensi
                        </a>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    <small>QR Code akan diperbarui setiap 5 menit</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk hasil scan -->
<div class="modal fade" id="resultModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hasil Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="resultContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="location.reload()">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Scanner QR Code (Hidden by default) -->
<div class="modal fade" id="scannerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scanner QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="scanner-container">
                    <video id="scanner-video" width="100%" height="300"></video>
                    <canvas id="scanner-canvas" width="300" height="300" style="display:none;"></canvas>
                    <p class="mt-2">Arahkan kamera ke QR Code untuk scan</p>
                </div>
                <div id="scanner-result" style="display:none;">
                    <div class="alert" id="scanner-alert"></div>
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
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrCode;

function startScanner() {
    $('#scannerModal').modal('show');

    setTimeout(() => {
        html5QrCode = new Html5Qrcode("scanner-container");

        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        };

        html5QrCode.start(
            { facingMode: "environment" },
            config,
            (decodedText, decodedResult) => {
                // QR Code berhasil di-scan
                processQRCode(decodedText);
                html5QrCode.stop().then(() => {
                    $('#scannerModal').modal('hide');
                }).catch(err => {
                    console.log("Error stopping scanner:", err);
                    $('#scannerModal').modal('hide');
                });
            },
            (errorMessage) => {
                // Error scanning
                console.log("Scan error:", errorMessage);
            }
        ).catch(err => {
            console.log("Unable to start scanning:", err);
            $('#scanner-alert')
                .removeClass('alert-success alert-danger')
                .addClass('alert-danger')
                .html('<i class="bi bi-exclamation-triangle"></i> Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan.')
                .show();
        });
    }, 500);
}

function processQRCode(qrData) {
    $.ajax({
        url: '{{ route("karyawan.absensi.scan-qr") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            qr_data: qrData
        },
        success: function(response) {
            if (response.success) {
                $('#resultContent').html(`
                    <div class="text-success">
                        <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">${response.message}</h4>
                    </div>
                `);
                $('#resultModal').modal('show');
            }
        },
        error: function(xhr) {
            let errorMessage = 'Terjadi kesalahan saat memproses absensi';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }

            $('#resultContent').html(`
                <div class="text-danger">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">${errorMessage}</h4>
                </div>
            `);
            $('#resultModal').modal('show');
        }
    });
}

// Auto refresh QR Code setiap 5 menit
setTimeout(function() {
    location.reload();
}, 300000); // 5 menit
</script>
@endsection
