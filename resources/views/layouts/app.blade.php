<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Absensi Karyawan')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
        }
        .nav-link.active {
            background-color: #0d6efd;
            color: white !important;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @auth
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                   href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}"
                                   href="{{ route('admin.employees.index') }}">
                                    <i class="bi bi-people"></i> Karyawan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.absensis.*') ? 'active' : '' }}"
                                   href="{{ route('admin.absensis.index') }}">
                                    <i class="bi bi-calendar-check"></i> Absensi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                                   href="{{ route('admin.reports.index') }}">
                                    <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                                   href="{{ route('admin.settings.index') }}">
                                    <i class="bi bi-gear"></i> Pengaturan
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}"
                                   href="{{ route('karyawan.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('karyawan.absensi.index') ? 'active' : '' }}"
                                   href="{{ route('karyawan.absensi.index') }}">
                                    <i class="bi bi-list"></i> Riwayat Absensi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('karyawan.absensi.qr-code') ? 'active' : '' }}"
                                   href="{{ route('karyawan.absensi.qr-code') }}">
                                    <i class="bi bi-qr-code-scan"></i> Absen QR Code
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('karyawan.absensi.fingerprint') ? 'active' : '' }}"
                                   href="{{ route('karyawan.absensi.fingerprint') }}">
                                    <i class="bi bi-fingerprint"></i> Absen Fingerprint
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
            @endauth

            <main class="{{ auth()->check() ? 'col-md-9 ms-sm-auto col-lg-10 px-md-4' : 'col-12' }}">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>
