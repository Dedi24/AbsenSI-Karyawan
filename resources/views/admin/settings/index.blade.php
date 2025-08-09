@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">⚙️ Pengaturan Sistem</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button class="btn btn-outline-secondary" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
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

<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-gear"></i> Pengaturan Sistem Absensi Karyawan
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" id="settingTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company" type="button" role="tab">
                                <i class="bi bi-building"></i> Perusahaan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="work-tab" data-bs-toggle="tab" data-bs-target="#work" type="button" role="tab">
                                <i class="bi bi-clock"></i> Jam Kerja
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location" type="button" role="tab">
                                <i class="bi bi-geo-alt"></i> Lokasi
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="notification-tab" data-bs-toggle="tab" data-bs-target="#notification" type="button" role="tab">
                                <i class="bi bi-bell"></i> Notifikasi
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Tabs Content -->
                    <div class="tab-content" id="settingTabsContent">
                        <!-- Company Tab -->
                        <div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
                            <div class="row mt-4">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="company_name" class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-building"></i>
                                                </span>
                                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                                       id="company_name" name="company_name" 
                                                       value="{{ old('company_name', $settings['company_name']) }}" 
                                                       placeholder="Masukkan nama perusahaan" required>
                                            </div>
                                            @error('company_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-12 mb-3">
                                            <label for="company_address" class="form-label">Alamat Perusahaan <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-geo-alt"></i>
                                                </span>
                                                <textarea class="form-control @error('company_address') is-invalid @enderror" 
                                                          id="company_address" name="company_address" 
                                                          rows="3" placeholder="Masukkan alamat perusahaan" required>{{ old('company_address', $settings['company_address']) }}</textarea>
                                            </div>
                                            @error('company_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="company_email" class="form-label">Email Perusahaan <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-envelope"></i>
                                                </span>
                                                <input type="email" class="form-control @error('company_email') is-invalid @enderror" 
                                                       id="company_email" name="company_email" 
                                                       value="{{ old('company_email', $settings['company_email']) }}" 
                                                       placeholder="info@perusahaan.com" required>
                                            </div>
                                            @error('company_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="company_phone" class="form-label">Telepon Perusahaan <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-telephone"></i>
                                                </span>
                                                <input type="text" class="form-control @error('company_phone') is-invalid @enderror" 
                                                       id="company_phone" name="company_phone" 
                                                       value="{{ old('company_phone', $settings['company_phone']) }}" 
                                                       placeholder="+62 812 3456 7890" required>
                                            </div>
                                            @error('company_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card shadow">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">
                                                <i class="bi bi-image"></i> Logo Perusahaan
                                            </h6>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <img src="{{ old('company_logo', $settings['company_logo']) }}" 
                                                     alt="Logo Perusahaan" 
                                                     class="img-fluid rounded" 
                                                     id="logoPreview" 
                                                     style="max-height: 150px; object-fit: contain;">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="company_logo" class="form-label">
                                                    <i class="bi bi-upload"></i> Upload Logo Baru
                                                </label>
                                                <input type="file" class="form-control @error('company_logo') is-invalid @enderror" 
                                                       id="company_logo" name="company_logo" 
                                                       accept="image/*">
                                                <div class="form-text">
                                                    <i class="bi bi-info-circle"></i> Format: JPG, PNG, GIF (Max 2MB)
                                                </div>
                                                @error('company_logo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="alert alert-info">
                                                <h6><i class="bi bi-lightbulb"></i> Tips</h6>
                                                <ul class="mb-0 small">
                                                    <li>Ukuran ideal: 200x200px</li>
                                                    <li>Format: JPG, PNG, GIF</li>
                                                    <li>Ukuran maksimal: 2MB</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Work Tab -->
                        <div class="tab-pane fade" id="work" role="tabpanel" aria-labelledby="work-tab">
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3">
                                    <label for="work_start_time" class="form-label">Jam Masuk Kerja <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-box-arrow-in-right"></i>
                                        </span>
                                        <input type="time" class="form-control @error('work_start_time') is-invalid @enderror" 
                                               id="work_start_time" name="work_start_time" 
                                               value="{{ old('work_start_time', substr($settings['work_start_time'], 0, 5)) }}" 
                                               required>
                                    </div>
                                    @error('work_start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="bi bi-info-circle"></i> Waktu mulai jam kerja
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="work_end_time" class="form-label">Jam Pulang Kerja <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-box-arrow-right"></i>
                                        </span>
                                        <input type="time" class="form-control @error('work_end_time') is-invalid @enderror" 
                                               id="work_end_time" name="work_end_time" 
                                               value="{{ old('work_end_time', substr($settings['work_end_time'], 0, 5)) }}" 
                                               required>
                                    </div>
                                    @error('work_end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="bi bi-info-circle"></i> Waktu selesai jam kerja
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="timezone" class="form-label">Zona Waktu <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-globe"></i>
                                        </span>
                                        <select class="form-control @error('timezone') is-invalid @enderror" 
                                                id="timezone" name="timezone" required>
                                            <option value="Asia/Jakarta" {{ old('timezone', $settings['timezone']) == 'Asia/Jakarta' ? 'selected' : '' }}>
                                                WIB (Jakarta)
                                            </option>
                                            <option value="Asia/Makassar" {{ old('timezone', $settings['timezone']) == 'Asia/Makassar' ? 'selected' : '' }}>
                                                WITA (Makassar)
                                            </option>
                                            <option value="Asia/Jayapura" {{ old('timezone', $settings['timezone']) == 'Asia/Jayapura' ? 'selected' : '' }}>
                                                WIT (Jayapura)
                                            </option>
                                        </select>
                                    </div>
                                    @error('timezone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="bi bi-info-circle"></i> Pilih zona waktu sesuai lokasi kantor
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="time_format" class="form-label">Format Jam <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-clock-history"></i>
                                        </span>
                                        <select class="form-control @error('time_format') is-invalid @enderror" 
                                                id="time_format" name="time_format" required>
                                            <option value="24" {{ old('time_format', $settings['time_format']) == '24' ? 'selected' : '' }}>
                                                24 Jam (00:00 - 23:59)
                                            </option>
                                            <option value="12" {{ old('time_format', $settings['time_format']) == '12' ? 'selected' : '' }}>
                                                12 Jam (12:00 AM - 11:59 PM)
                                            </option>
                                        </select>
                                    </div>
                                    @error('time_format')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="bi bi-info-circle"></i> Format tampilan jam di aplikasi
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning">
                                <h6><i class="bi bi-exclamation-triangle"></i> Peringatan</h6>
                                <p class="mb-0">
                                    Perubahan zona waktu dan format jam akan mempengaruhi tampilan waktu di seluruh aplikasi.
                                    Pastikan pengaturan sudah sesuai sebelum menyimpan.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Location Tab -->
                        <div class="tab-pane fade" id="location" role="tabpanel" aria-labelledby="location-tab">
                            <div class="row mt-4">
                                <div class="col-md-12 mb-3">
                                    <label for="office_location" class="form-label">Lokasi Kantor (Latitude,Longitude) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-geo"></i>
                                        </span>
                                        <input type="text" class="form-control @error('office_location') is-invalid @enderror" 
                                               id="office_location" name="office_location" 
                                               value="{{ old('office_location', $settings['office_location']) }}" 
                                               placeholder="Contoh: -6.200000,106.816666" required>
                                        <button class="btn btn-outline-primary" type="button" id="getLocationBtn">
                                            <i class="bi bi-geo-alt"></i> Dapatkan Lokasi
                                        </button>
                                    </div>
                                    @error('office_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="bi bi-info-circle"></i> Koordinat lokasi kantor untuk validasi absensi
                                    </div>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <div id="map" style="height: 300px; border: 1px solid #ddd; border-radius: 0.375rem;"></div>
                                    <div class="form-text">
                                        <i class="bi bi-map"></i> Peta lokasi kantor (akan tersedia di versi berikutnya)
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="tolerance_radius" class="form-label">Radius Toleransi (meter) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-rulers"></i>
                                        </span>
                                        <input type="number" class="form-control @error('tolerance_radius') is-invalid @enderror" 
                                               id="tolerance_radius" name="tolerance_radius" 
                                               value="{{ old('tolerance_radius', $settings['tolerance_radius']) }}" 
                                               min="50" max="1000" required>
                                        <span class="input-group-text">meter</span>
                                    </div>
                                    @error('tolerance_radius')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="bi bi-info-circle"></i> Radius maksimal jarak karyawan dari kantor untuk bisa absen
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <h6><i class="bi bi-lightbulb"></i> Tips</h6>
                                <ul class="mb-0">
                                    <li>Gunakan koordinat yang akurat untuk lokasi kantor</li>
                                    <li>Radius toleransi minimal 50 meter dan maksimal 1000 meter</li>
                                    <li>Lokasi akan digunakan untuk validasi GPS saat absen</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Notification Tab -->
                        <div class="tab-pane fade" id="notification" role="tabpanel" aria-labelledby="notification-tab">
                            <div class="row mt-4">
                                <div class="col-md-12 mb-3">
                                    <label for="whatsapp_group" class="form-label">ID Grup WhatsApp Admin</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-whatsapp"></i>
                                        </span>
                                        <input type="text" class="form-control @error('whatsapp_group') is-invalid @enderror" 
                                               id="whatsapp_group" name="whatsapp_group" 
                                               value="{{ old('whatsapp_group', $settings['whatsapp_group']) }}" 
                                               placeholder="Masukkan ID grup WhatsApp admin">
                                        <button class="btn btn-outline-success" type="button" id="testWhatsAppBtn">
                                            <i class="bi bi-send"></i> Test
                                        </button>
                                    </div>
                                    @error('whatsapp_group')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="bi bi-info-circle"></i> Untuk notifikasi real-time ke grup admin
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="alert alert-success">
                                        <h6><i class="bi bi-check-circle"></i> Notifikasi Aktif</h6>
                                        <ul class="mb-0">
                                            <li><i class="bi bi-box-arrow-in-right"></i> Absen masuk</li>
                                            <li><i class="bi bi-box-arrow-right"></i> Absen pulang</li>
                                            <li><i class="bi bi-clock-history"></i> Keterlambatan</li>
                                            <li><i class="bi bi-calendar-x"></i> Tidak hadir (alpha)</li>
                                            <li><i class="bi bi-exclamation-triangle"></i> Absensi manual</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning">
                                <h6><i class="bi bi-exclamation-triangle"></i> Peringatan</h6>
                                <p class="mb-0">
                                    Pastikan ID grup WhatsApp sudah benar dan bot sudah ditambahkan ke grup.
                                    Test notifikasi untuk memastikan koneksi berjalan dengan baik.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
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

.alert {
    border-radius: 0.5rem;
}

.form-text {
    color: #6c757d;
}

.text-danger {
    color: #e74a3b !important;
}

.text-warning {
    color: #f6c23e !important;
}

.text-success {
    color: #1cc88a !important;
}

.text-info {
    color: #36b9cc !important;
}

.img-fluid {
    max-width: 100%;
    height: auto;
}

#logoPreview {
    transition: all 0.3s ease;
}

#logoPreview:hover {
    transform: scale(1.05);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

#map {
    border-radius: 0.375rem;
    transition: all 0.3s ease;
}

#map:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>

<script>
// Image preview
document.addEventListener('DOMContentLoaded', function() {
    const companyLogoInput = document.getElementById('company_logo');
    const logoPreview = document.getElementById('logoPreview');
    
    if (companyLogoInput && logoPreview) {
        companyLogoInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    logoPreview.src = event.target.result;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    }
    
    // Get location button
    const getLocationBtn = document.getElementById('getLocationBtn');
    if (getLocationBtn) {
        getLocationBtn.addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('Geolocation tidak didukung oleh browser Anda.');
                return;
            }

            // Tampilkan loading
            const originalText = getLocationBtn.innerHTML;
            getLocationBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Mendapatkan lokasi...';
            getLocationBtn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude.toFixed(6);
                    const lng = position.coords.longitude.toFixed(6);
                    const locationInput = document.getElementById('office_location');
                    locationInput.value = `${lat},${lng}`;

                    // Tampilkan alert sukses
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show mt-2';
                    alertDiv.innerHTML = `
                        <i class="bi bi-check-circle"></i> Lokasi berhasil didapatkan: <strong>${lat}, ${lng}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.getElementById('location').insertBefore(alertDiv, document.getElementById('location').firstChild);

                    // Reset tombol
                    getLocationBtn.innerHTML = originalText;
                    getLocationBtn.disabled = false;
                },
                function (error) {
                    let message = '';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            message = 'Pengguna menolak permintaan lokasi.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message = 'Informasi lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            message = 'Permintaan lokasi timeout.';
                            break;
                        default:
                            message = 'Gagal mendapatkan lokasi: ' + error.message;
                    }

                    alert(message);

                    // Reset tombol
                    getLocationBtn.innerHTML = originalText;
                    getLocationBtn.disabled = false;
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 60000
                }
            );
        });
    }

    
    // Test WhatsApp button
    const testWhatsAppBtn = document.getElementById('testWhatsAppBtn');
    if (testWhatsAppBtn) {
        testWhatsAppBtn.addEventListener('click', function() {
            const whatsappGroup = document.getElementById('whatsapp_group').value;
            if (whatsappGroup) {
                // Simulate WhatsApp test
                alert('Test notifikasi WhatsApp dikirim ke grup dengan ID: ' + whatsappGroup);
            } else {
                alert('Masukkan ID grup WhatsApp terlebih dahulu.');
            }
        });
    }
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            if (alert.classList.contains('alert-dismissible')) {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }
        });
    }, 5000);
});
</script>
@endsection