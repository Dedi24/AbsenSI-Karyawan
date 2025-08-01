@extends('layouts.app')

@section('title', 'Login - Sistem Absensi Karyawan')

@section('content')
<div class="login-container">
    <div class="login-card row g-0">
        <!-- Kolom Kiri - Keunggulan Sistem -->
        <div class="col-sm-5 login-left">
            <div class="login-header">Sistem Absensi Karyawan</div>
            <div class="login-subtitle">Solusi absensi modern dan efisien</div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="bi bi-fingerprint"></i>
                </div>
                <div class="feature-text">
                    <h4>Fingerprint Scan</h4>
                    <p>Identifikasi karyawan dengan sidik jari untuk keamanan maksimal</p>
                </div>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="bi bi-qr-code"></i>
                </div>
                <div class="feature-text">
                    <h4>QR Code Absensi</h4>
                    <p>Absensi cepat dengan scanning QR code di lokasi</p>
                </div>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div class="feature-text">
                    <h4>Absensi Manual</h4>
                    <p>Input manual absensi untuk kasus khusus dan fleksibilitas</p>
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
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required autocomplete="current-password">
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
        background: linear-gradient(125deg, #b7bdd3 0%, #c3c3c4 100%);
        padding: 2rem;
    }
    .login-card {
        border-radius: 2rem;
        box-shadow: 0 2rem 3rem rgba(31, 30, 30, 0.175);
        overflow: hidden;
        max-width: 900px;
        width: 100%;
    }
    .login-left {
        background: linear-gradient(135deg, #cdd2de 0%, #6f81b5 100%);
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
        margin-bottom: 1.2rem;
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
        font-weight: 500;
        font-size: 1rem;
    }
    .feature-text p {
        margin-bottom: 0;
        opacity: 0.9;
        font-size: 0.85rem;
    }
    .login-header {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .login-subtitle {
        opacity: 0.9;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }
    .login-footer {
        margin-top: 1.5rem;
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