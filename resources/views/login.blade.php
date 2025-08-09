@extends('layouts.app')

@section('title', 'Login - Sistem Absensi Karyawan')

@section('content')
<div class="login-container">
    <div class="login-card row g-0">
        <!-- Kolom Kiri - Keunggulan Sistem -->
        <div class="col-sm-5 login-left">
            <div class="text-center mb-4">
                <img src="{{ asset('storage/logos/company-logo.png') }}" alt="Logo" class="img-fluid mb-3" style="max-width: 100px;">
                <div class="login-header">Sistem Absensi Karyawan</div>
                <div class="login-subtitle">Solusi absensi modern dan efisien</div>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="bi bi-fingerprint"></i>
                </div>
                <div class="feature-text">
                    <h4>Fingerprint Scan</h4>
                </div>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="bi bi-qr-code"></i>
                </div>
                <div class="feature-text">
                    <h4>QR Code Absensi</h4>
                </div>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div class="feature-text">
                    <h4>Absensi Manual</h4>
                </div>
            </div>
        </div>
        
        <!-- Kolom Kanan - Form Login -->
        <div class="col-sm-7 login-right">
            <div class="text-center mb-4">
                <i class="bi bi-calendar-check text-primary" style="font-size: 3rem;"></i>
                <h3 class="mt-3">Selamat Datang</h3>
                <p>Masuk ke akun Anda untuk melanjutkan</p>
            </div>
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required autocomplete="current-password">
                    <button type="button" class="btn btn-outline-secondary btn-sm position-absolute" 
                            style="right: 10px; top: 33px; z-index: 10;" 
                            onclick="togglePasswordVisibility()" id="togglePasswordBtn">
                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Ingat Saya</label>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-2 mb-3">Login</button>
                
                <div class="text-center">
                    <a href="#" class="text-decoration-none text-primary" data-bs-toggle="modal" data-bs-target="#pwaInstructionsModal">
                        <i class="bi bi-info-circle"></i> Panduan Install Aplikasi
                    </a>
                </div>
            </form>
            
            <div class="login-footer mt-4">
                <p class="small text-center mb-0">&copy; {{ date('Y') }} Sistem Absensi Karyawan. All rights reserved.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #1598d4 0%, #179c55 100%);
        padding: 20px;
    }
    .login-card {
        border-radius: 1rem;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        overflow: hidden;
        max-width: 900px;
        width: 100%;
    }
    .login-left {
        background: linear-gradient(135deg, #1598d4 0%, #179c55 100%);
        color: white;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .login-right {
        padding: 2rem;
        background: white;
    }
    .feature-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }
    .feature-icon {
        background: rgba(255, 255, 255, 0.2);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    .feature-text h4 {
        margin-bottom: 0.3rem;
        font-weight: 600;
        font-size: 1rem;
    }
    .feature-text p {
        margin-bottom: 0;
        opacity: 0.9;
        font-size: 0.85rem;
    }
    .login-header {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .login-subtitle {
        opacity: 0.9;
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }
    .login-footer {
        margin-top: 1.5rem;
    }
    .login-footer p {
        margin-bottom: 0;
    }
    @media (max-width: 991.98px) {
        .login-card {
            flex-direction: column;
        }
        .login-left, .login-right {
            width: 100%;
        }
        .login-left {
            padding: 1.5rem;
        }
        .login-right {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }
    
    // Validasi password saat input
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const loginForm = document.getElementById('loginForm');
        
        // Validasi saat input password berubah
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let isValid = true;
            let errorMessage = '';
            
            // Validasi panjang password (minimal 6 karakter)
            if (password.length < 6) {
                isValid = false;
                errorMessage = 'Password harus minimal 6 karakter';
            }
            
            // Validasi karakter
            if (!/[A-Za-z]/.test(password)) {
                isValid = false;
                errorMessage = 'Password harus mengandung huruf';
            }
            
            if (!/[0-9]/.test(password)) {
                isValid = false;
                errorMessage = 'Password harus mengandung angka';
            }
            
            // Tampilkan atau sembunyikan error
            const invalidFeedback = this.parentNode.querySelector('.invalid-feedback');
            if (invalidFeedback) {
                invalidFeedback.textContent = errorMessage;
                if (!isValid) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            }
        });
    })
        
    // Validasi saat submit form
    loginForm.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        let isValid = true;
        
        // Validasi panjang password
        if (password.length < 6) {
            isValid = false;
            passwordInput.classList.add('is-invalid');
        } else {
            passwordInput.classList.remove('is-invalid');
        }
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Check if app is already installed (standalone mode)
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || 
                            window.navigator.standalone === true;
        
        // Check if user has seen the modal before
        const hasSeenModal = localStorage.getItem('pwa_instructions_seen');
        
        // Hide PWA install button if app is already installed
        const installPWABtn = document.getElementById('installPWABtn');
        if (isStandalone || hasSeenModal) {
            if (installPWABtn) {
                installPWABtn.classList.add('d-none');
            }
        }
        
        // Also hide the modal trigger if already installed
        const pwaInstructionsModal = document.getElementById('pwaInstructionsModal');
        if (isStandalone && pwaInstructionsModal) {
            // Remove the modal trigger from login page
            const modalTrigger = document.querySelector('[data-bs-target="#pwaInstructionsModal"]');
            if (modalTrigger) {
                modalTrigger.style.display = 'none';
            }
        }
    });
</script>
@endpush