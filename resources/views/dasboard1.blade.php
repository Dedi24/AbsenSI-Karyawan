<!DOCTYPE html>
<html lang="id" dir="ltr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Absensi Karyawan') - {{ config('app.name', 'Absensi Karyawan') }}</title>
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#4e73df">
    <meta name="description" content="Aplikasi absensi karyawan berbasis Progressive Web App">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Absensi">
    <meta name="application-name" content="Absensi Karyawan">
    <meta name="msapplication-TileColor" content="#4e73df">
    <meta name="msapplication-TileImage" content="/icons/icon-144x144.png">
    <!-- PWA Icons -->
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/icons/icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/icon-180x180.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/icons/icon-167x167.png">
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <!-- PWA Splash Screens (iOS) -->
    <link rel="apple-touch-startup-image" href="/icons/splash-640x1136.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
    <link rel="apple-touch-startup-image" href="/icons/splash-750x1334.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
    <link rel="apple-touch-startup-image" href="/icons/splash-1242x2208.png" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
    <link rel="apple-touch-startup-image" href="/icons/splash-1125x2436.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
    <link rel="apple-touch-startup-image" href="/icons/splash-828x1792.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
    <link rel="apple-touch-startup-image" href="/icons/splash-1242x2688.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
    <link rel="apple-touch-startup-image" href="/icons/splash-1536x2048.png" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
    <link rel="apple-touch-startup-image" href="/icons/splash-1668x2224.png" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
    <link rel="apple-touch-startup-image" href="/icons/splash-1668x2388.png" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
    <link rel="apple-touch-startup-image" href="/icons/splash-2048x2732.png" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
    <!-- Bootstrap CSS -->
     <!-- Bootstrap core CSS -->
    <link href="/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            /* Warna Utama */
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
            /* Warna Sidebar */
            --sidebar-bg:  #1598d4 0%, #179c55 100%;
            --sidebar-color: rgba(255, 255, 255, 0.8);
            --sidebar-hover-bg: rgba(255, 255, 255, 0.1);
            --sidebar-active-bg: rgba(255, 255, 255, 0.2);
            --sidebar-border-color: rgba(255, 255, 255, 0.15);
            /* Warna Topbar */
            --topbar-bg: hsl(0, 10%, 94%);
            --topbar-color: var(--dark-color);
            --topbar-border-color: rgba(0, 0, 0, 0.1);
            /* Shadow dan Transition */
            --shadow-sm: 0 .125rem .25rem 0 rgba(58, 59, 69, .2);
            --shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, .175);
            /* Transition */
            --transition-fast: all 0.2s ease-in-out;
            --transition-normal: all 0.3s ease;
            --transition-slow: all 0.5s ease;
        }
        /* Dark Theme Variables */
        [data-bs-theme="dark"] {
            --sidebar-bg: #1a1d20;
            --sidebar-color: rgba(131, 128, 128, 0.85);
            --sidebar-hover-bg: rgba(255, 255, 255, 0.1);
            --sidebar-active-bg: rgba(255, 255, 255, 0.2);
            --sidebar-border-color: rgba(255, 255, 255, 0.1);
            --topbar-bg: #2b3035;
            --topbar-color: rgba(235, 232, 232, 0.85);
            --topbar-border-color: rgba(255, 255, 255, 0.1);
            --light-color: #1e2227;
            --dark-color: rgba(32, 30, 30, 0.85);
        }
        body {
            font-size: 0.875rem;
            background-color: var(--light-color);
            color: var(--dark-color);
            transition: var(--transition-normal);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        /* Topbar Styling */
        .topbar {
            height: 60px;
            background-color: var(--topbar-bg);
            color: var(--topbar-color);
            border-bottom: 1px solid var(--topbar-border-color);
            box-shadow: var(--shadow);
            z-index: 1030;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            transition: var(--transition-normal);
        }
        .topbar .navbar-nav .nav-link {
            height: 60px;
            display: flex;
            align-items: center;
            padding: 0 1rem;
            color: var(--topbar-color);
            transition: var(--transition-fast);
        }
        .topbar .navbar-nav .nav-link:hover,
        .topbar .navbar-nav .nav-link:focus {
            background-color: rgba(0, 0, 0, 0.03);
            color: var(--topbar-color);
        }
        [data-bs-theme="dark"] .topbar .navbar-nav .nav-link:hover,
        [data-bs-theme="dark"] .topbar .navbar-nav .nav-link:focus {
            background-color: rgba(255, 255, 255, 0.05);
        }
        .topbar .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
            transition: var(--transition-fast);
        }
        .topbar .navbar-brand:hover {
            color: var(--primary-color);
            transform: scale(1.02);
        }
        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #224abe 100%);
            color: var(--sidebar-color);
            min-height: calc(100vh - 60px);
            transition: var(--transition-normal);
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, 0.15);
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            z-index: 1020;
            overflow-y: auto;
            padding-bottom: 70px;
        }
        .sidebar .nav-link {
            color: var(--sidebar-color);
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: var(--transition-fast);
            position: relative;
            font-size: 0.9rem;
            border-radius: 0;
        }
        .sidebar .nav-link i {
            font-size: 1.1rem;
            margin-right: 0.75rem;
            width: 1.25rem;
            text-align: center;
            transition: var(--transition-fast);
        }
        .sidebar .nav-link:hover {
            color: white;
            background-color: var(--sidebar-hover-bg);
        }
        .sidebar .nav-link.active {
            color: white;
            background-color: var(--sidebar-active-bg);
        }
        .sidebar .nav-link.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: #fff;
        }
        .sidebar-heading {
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.15rem;
            opacity: 0.8;
            transition: var(--transition-fast);
        }
        .sidebar .user-info {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--sidebar-color);
            text-decoration: none;
            border-top: 1px solid var(--sidebar-border-color);
            margin-top: var(--sidebar-active-bg);
            transition: var(--transition-fast);
            position: absolute;
            bottom: -42%;
            width: 100%;
            left: 0;
        }
        .sidebar .user-info:hover {
            background-color: var(--sidebar-hover-bg);
            color: white;
            text-decoration: none;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #fff;
            color: var(--sidebar-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.8rem;
            margin-right: 0.75rem;
            transition: var(--transition-fast);
        }
        [data-bs-theme="dark"] .user-avatar {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--sidebar-color);
        }
        .sidebar .user-info:hover .user-avatar {
            transform: scale(1.1);
        }
        /* Main Content Styling */
        .main-content {
            transition: var(--transition-normal);
            background-color: var(--light-color);
            color: var(--dark-color);
            min-height: calc(100vh - 60px);
            padding-top: 60px;
            padding-left: 250px;
            flex: 1;
        }
        /* Collapsed Sidebar State */
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar.collapsed ~ .main-content {
            padding-left: 70px;
        }
        .sidebar.collapsed .sidebar-heading,
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .user-info-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
            display: inline-block;
            transition: var(--transition-fast);
        }
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            transition: var(--transition-fast);
        }
        /* Responsive Adjustments */
        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                padding-left: 0;
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1010;
            }
            .sidebar-overlay.show {
                display: block;
            }
        }
        @media (min-width: 768px) {
            .sidebar {
                width: 250px;
            }
            .sidebar-overlay {
                display: none !important;
            }
        }
        /* Theme Toggle Button */
        .theme-toggle {
            background: none;
            border: 1px solid var(--topbar-border-color);
            color: var(--topbar-color);
            transition: var(--transition-fast);
        }
        .theme-toggle:hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: var(--topbar-color);
        }
        [data-bs-theme="dark"] .theme-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        /* Cards */
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: var(--shadow);
            transition: var(--transition-normal);
        }
        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }
        .card-header {
            background-color: var(--light-color);
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0.5rem 0.5rem 0 0 !important;
            transition: var(--transition-fast);
        }
        [data-bs-theme="dark"] .card-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.125);
        }
        /* Stats Cards */
        .border-left-primary {
            border-left: 0.25rem solid var(--primary-color) !important;
        }
        .border-left-success {
            border-left: 0.25rem solid var(--success-color) !important;
        }
        .border-left-warning {
            border-left: 0.25rem solid var(--warning-color) !important;
        }
        .border-left-danger {
            border-left: 0.25rem solid var(--danger-color) !important;
        }
        .border-left-info {
            border-left: 0.25rem solid var(--info-color) !important;
        }
        /* Badges */
        .badge {
            transition: var(--transition-fast);
        }
        .badge:hover {
            transform: scale(1.1);
        }
        /* Buttons */
        .btn {
            transition: var(--transition-fast);
            border-radius: 0.35rem;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .btn:active {
            transform: translateY(0);
        }
        /* Tables */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        [data-bs-theme="dark"] .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }
        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        [data-bs-theme="dark"] .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        [data-bs-theme="dark"] .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }
        [data-bs-theme="dark"] .sidebar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 20px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        .fade-in-up {
            animation: fadeInUp 0.3s ease forwards;
        }
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(78, 115, 223, 0.7); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(78, 115, 223, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(78, 115, 223, 0); }
        }
        .pulse {
            animation: pulse 2s infinite;
        }
        /* Utility Classes */
        .text-primary { color: var(--primary-color) !important; }
        .text-success { color: var(--success-color) !important; }
        .text-warning { color: var(--warning-color) !important; }
        .text-danger { color: var(--danger-color) !important; }
        .text-info { color: var(--info-color) !important; }
        .text-dark { color: var(--dark-color) !important; }
        .text-light { color: var(--light-color) !important; }
        .bg-primary { background-color: var(--primary-color) !important; }
        .bg-success { background-color: var(--success-color) !important; }
        .bg-warning { background-color: var(--warning-color) !important; }
        .bg-danger { background-color: var(--danger-color) !important; }
        .bg-info { background-color: var(--info-color) !important; }
        .bg-light { background-color: var(--light-color) !important; }
        .bg-dark { background-color: var(--dark-color) !important; }
        /* Avatar */
        .avatar {
            position: relative;
            display: inline-block;
        }
        .avatar-content {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            font-size: 0.875rem;
        }
        .avatar-sm {
            width: 40px;
            height: 40px;
        }
        .avatar-xl {
            width: 80px;
            height: 80px;
        }
        /* Divider */
        .divider {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }
        [data-bs-theme="dark"] .divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        /* Soft transition for sidebar items */
        .sidebar .nav-link,
        .sidebar .sidebar-heading,
        .sidebar .user-info {
            transition: all 0.3s ease;
        }
        /* Login Page Styles */
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #a9b5d8 0%, #f1f3fc 100%);
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
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-right {
            padding: 3rem;
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
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .feature-text p {
            margin-bottom: 0;
            opacity: 0.9;
        }
        .login-header {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .login-subtitle {
            opacity: 0.9;
            margin-bottom: 2rem;
        }
        .login-footer {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.875rem;
            opacity: 0.7;
        }
        /* Modal Styles */
        .modal-backdrop {
            backdrop-filter: blur(5px);
        }
        .modal-content {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.3);
        }
        .modal-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            border-radius: 1rem 1rem 0 0 !important;
        }
        .modal-body {
            padding: 2rem;
        }
        .modal-footer {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }
        .modal-title {
            font-weight: 600;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Main Content - Only shown when logged in -->
    @auth
        <!-- Topbar -->
        <nav class="topbar navbar navbar-expand navbar-light bg-white shadow-sm fixed-top">
            <div class="container-fluid">
                <!-- Sidebar Toggle (Topbar - Visible only on mobile) -->
                <button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop">
                    <i class="bi bi-list"></i>
                </button>
                <!-- Topbar Brand -->
                <a class="navbar-brand d-none d-md-block" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-calendar-check text-primary"></i> {{ config('app.name', 'Absensi Karyawan') }}
                </a>
                <!-- Topbar Search -->
                <form class="d-none d-sm-inline-block form-inline me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" 
                               placeholder="Cari..." aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <!-- Topbar Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                    <li class="nav-item dropdown no-arrow d-sm-none">
                        <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bi bi-search"></i>
                        </a>
                        <!-- Dropdown - Messages -->
                        <div class="dropdown-menu dropdown-menu-end p-3 shadow animated--grow-in"
                            aria-labelledby="searchDropdown">
                            <form class="form-inline me-auto w-100 navbar-search">
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light border-0 small"
                                        placeholder="Cari..." aria-label="Search">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>
                    <!-- Nav Item - Alerts -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                            <!-- Counter - Alerts -->
                            <span class="badge bg-danger badge-counter">3+</span>
                        </a>
                        <!-- Dropdown - Alerts -->
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                            aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">Pusat Notifikasi</h6>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="bi bi-file-earmark-text text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">12 April 2024</div>
                                    <span class="font-weight-bold">Laporan bulanan tersedia!</span>
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-success">
                                        <i class="bi bi-check-circle text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">7 April 2024</div>
                                    Backup sistem berhasil
                                </div>
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-warning">
                                        <i class="bi bi-exclamation-triangle text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">2 April 2024</div>
                                    Peringatan: 5 karyawan belum absen
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-gray-500" href="#">Lihat semua notifikasi</a>
                        </div>
                    </li>
                    <div class="topbar-divider d-none d-sm-block"></div>
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link user-dropdown" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-2">
                                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                                </div>
                                <div class="d-none d-lg-inline-block">
                                    <span class="d-block small text-gray-500">
                                        {{ Auth::user()->role === 'admin' ? 'Admin' : 'Karyawan' }}
                                    </span>
                                </div>
                            </div>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('admin.profile.show') }}">
                                <i class="bi bi-person-circle me-2"></i> Profil
                            </a>
                            <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                                <i class="bi bi-gear me-2"></i> Pengaturan
                            </a>
                            <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                    <!-- Theme Toggle -->
                    <li class="nav-item ms-2">
                        <button class="btn btn-sm theme-toggle" id="themeToggle" title="Toggle Dark/Light Mode">
                            <i class="bi bi-moon-stars" id="themeIcon"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <!-- Menu khusus untuk karyawan -->
                    @if(Auth::user()->role === 'karyawan')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.absensis.*') ? 'active' : '' }}" 
                               href="{{ route('admin.absensis.index') }}">
                                <i class="bi bi-calendar-check"></i> <span>Absensi</span>
                            </a>
                        </li>
                    @else
                        <!-- Menu untuk admin -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}" 
                               href="{{ route('admin.employees.index') }}">
                               <i class="bi bi-people"></i> <span>Karyawan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.absensis.*') ? 'active' : '' }}" 
                                href="{{ route('admin.absensis.index') }}">
                                <i class="bi bi-calendar-check"></i> <span>Absensi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" 
                                href="{{ route('admin.reports.index') }}">
                                <i class="bi bi-file-earmark-bar-graph"></i> <span>Laporan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" 
                                href="{{ route('admin.settings.index') }}">
                                <i class="bi bi-gear"></i> <span>Pengaturan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pwa.offline') }}">
                                <i class="bi bi-wifi-off"></i> <span>Offline Mode</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}" 
                                href="{{ route('admin.logs.index') }}">
                                <i class="bi bi-journal-text"></i> <span>Log Aktivitas</span>
                            </a>
                        </li>
                    @endif
                </ul>
                
                <!-- User Info at Bottom of Sidebar -->

                <div class="user-info">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                        {{-- <div class="sticky-sm-bottom user-info">
                        <div class="user-avatar"> --}}
                        <strong>{{ Auth::user()->name ?? 'User' }}</strong>
                    </a>
                </div>
            </div>
        </nav>
        <!-- Main Content -->
        <div class="main-content">
            <div class="container-fluid pt-4 px-4">
                @yield('content')
            </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-white mt-auto">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; {{ \App\Models\Setting::getCompanyName('Perusahaan Kita') }} {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
        </div>
        
        <!-- PWA Install Alert -->
        <div class="install-pwa d-none" id="installPWABtn">
            <button class="btn btn-success btn-lg pulse" id="installPWA">
                <i class="bi bi-download"></i> Install App
            </button>
        </div>
    @else
        <!-- Login Page - Shown when NOT logged in -->
        @yield('content')
    @endauth
    
    <!-- Updated PWA Instructions Modal -->
    <div class="modal fade" id="pwaInstructionsModal" tabindex="-1" aria-labelledby="pwaInstructionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pwaInstructionsModalLabel">
                        <i class="bi bi-info-circle me-2"></i> Cara Install Aplikasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-phone-lg text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <div class="mb-3">
                        <h6><i class="bi bi-android"></i> Untuk Android</h6>
                        <ol class="ps-3">
                            <li>Buka browser Chrome</li>
                            <li>Klik ikon menu (<i class="bi bi-three-dots-vertical"></i>) di pojok kanan atas</li>
                            <li>Pilih "Install App"</li>
                        </ol>
                    </div>
                    <div class="mb-3">
                        <h6><i class="bi bi-windows"></i> Untuk Windows</h6>
                        <ol class="ps-3">
                            <li>Buka browser Edge</li>
                            <li>Klik ikon menu (<i class="bi bi-three-dots-vertical"></i>) di pojok kanan atas</li>
                            <li>Pilih "Install App"</li>
                        </ol>
                    </div>
                    <div class="mb-3">
                        <h6><i class="bi bi-apple"></i> Untuk iOS</h6>
                        <ol class="ps-3">
                            <li>Buka Safari</li>
                            <li>Klik ikon bagikan (<i class="bi bi-share"></i>) di pojok kanan bawah</li>
                            <li>Pilih "Tambah ke Layar Beranda"</li>
                        </ol>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Setelah diinstal, aplikasi dapat diakses tanpa koneksi internet.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="/docs/5.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (Optional) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar toggle functionality
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const sidebarToggleTop = document.getElementById('sidebarToggleTop');
            // Theme toggle functionality
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            
            // --- Theme Toggle ---
            // Check for saved theme or respect system preference
            const savedTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const initialTheme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', initialTheme);
            updateThemeIcon(initialTheme);
            themeToggle.addEventListener('click', () => {
                const currentTheme = document.documentElement.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
            });
            function updateThemeIcon(theme) {
                if (theme === 'dark') {
                    themeIcon.classList.remove('bi-moon-stars');
                    themeIcon.classList.add('bi-brightness-high');
                    themeIcon.title = 'Switch to Light Mode';
                } else {
                    themeIcon.classList.remove('bi-brightness-high');
                    themeIcon.classList.add('bi-moon-stars');
                    themeIcon.title = 'Switch to Dark Mode';
                }
            }
            
            // --- Sidebar Toggle (Mobile) ---
            if (sidebarToggleTop && sidebar) {
                sidebarToggleTop.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.toggle('show');
                    }
                });
            }
            
            // Close sidebar when clicking overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function () {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }
            
            // --- Auto-hide alerts ---
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(() => {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                }, 5000); // Hide after 5 seconds
            });
            
            // --- PWA Integration ---
            let deferredPrompt;
            const installPWAButton = document.getElementById('installPWA');
            const installPWABtn = document.getElementById('installPWABtn');
            const pwaInstructionsModal = document.getElementById('pwaInstructionsModal');
            
            // Check if user has seen the modal before (using localStorage)
            const hasSeenModal = localStorage.getItem('pwa_instructions_seen');
            
            // Check if app is already installed (standalone mode)
            const isStandalone = window.matchMedia('(display-mode: standalone)').matches || 
                                window.navigator.standalone === true;
            
            // Register Service Worker
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/sw.js')
                        .then(function(registration) {
                            console.log('Service Worker registered: ', registration);
                        })
                        .catch(function(registrationError) {
                            console.log('Service Worker registration failed: ', registrationError);
                        });
                });
            }
            
            // PWA Install Prompt
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                
                // Only show install button if not already installed and user hasn't seen modal
                if (!isStandalone && !hasSeenModal) {
                    installPWABtn.classList.remove('d-none');
                }
            });
            
            installPWAButton.addEventListener('click', (e) => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User accepted the A2HS prompt');
                            // Set flag that user has seen the modal
                            localStorage.setItem('pwa_instructions_seen', 'true');
                        } else {
                            console.log('User dismissed the A2HS prompt');
                        }
                        deferredPrompt = null;
                    });
                }
            });
            
            window.addEventListener('appinstalled', (evt) => {
                console.log('PWA was installed');
                installPWABtn.classList.add('d-none');
                deferredPrompt = null;
                // Set flag that user has installed the app
                localStorage.setItem('pwa_installed', 'true');
                localStorage.setItem('pwa_instructions_seen', 'true');
            });
            
            // Show PWA instructions modal only if not installed and not seen before
            const showPWAInstructions = () => {
                const modal = new bootstrap.Modal(document.getElementById('pwaInstructionsModal'));
                modal.show();
                localStorage.setItem('pwa_instructions_seen', 'true');
            };
            
            // Add click event to install button
            if (installPWAButton) {
                installPWAButton.addEventListener('click', showPWAInstructions);
            }
            
            // Check if we're on a mobile device
            const isMobile = /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            if (!isMobile && !hasSeenModal && !isStandalone) {
                // Show PWA instructions for desktop users after a delay
                setTimeout(() => {
                    if (installPWABtn.classList.contains('d-none')) {
                        showPWAInstructions();
                    }
                }, 3000);
            }
            
            // Check if app is running in standalone mode and hide install button
            if (isStandalone) {
                installPWABtn.classList.add('d-none');
            }
        });
        // <!-- Tambahkan ini di bagian script sebelum closing -->
        @push('scripts')
        <script>
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
    </script>
    @yield('scripts')
</body>
</html>