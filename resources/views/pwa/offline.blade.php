<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline - Sistem Absensi Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .offline-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            padding: 2rem;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .offline-icon {
            font-size: 4rem;
            color: #4e73df;
            margin-bottom: 1rem;
        }
        .offline-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #333;
        }
        .offline-message {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .offline-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .btn-refresh {
            background: #4e73df;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        .btn-refresh:hover {
            background: #3a5bc7;
            transform: translateY(-2px);
        }
        .btn-home {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        .offline-status {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 1rem;
            color: #dc3545;
        }
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #dc3545;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="offline-card">
        <div class="offline-status">
            <div class="status-indicator"></div>
            <span>Anda sedang offline</span>
        </div>
        <i class="bi bi-wifi-off offline-icon"></i>
        <h2 class="offline-title">Tidak Ada Koneksi Internet</h2>
        <p class="offline-message">
            Sistem ini dapat bekerja offline setelah diinstal. 
            Silakan periksa koneksi internet Anda atau gunakan aplikasi yang sudah diinstal.
        </p>
        
        <div class="offline-actions">
            <button class="btn btn-refresh" onclick="location.reload()">
                <i class="bi bi-arrow-repeat me-2"></i> Muat Ulang
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-home">
                <i class="bi bi-house-door me-2"></i> Halaman Utama
            </a>
        </div>
        
        <div class="mt-4">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Aplikasi ini dapat diinstal untuk digunakan offline
            </small>
        </div>
    </div>

    <script>
        // Cek status koneksi secara real-time
        function checkConnection() {
            const statusIndicator = document.querySelector('.status-indicator');
            const statusText = document.querySelector('.offline-status span');
            
            if (navigator.onLine) {
                statusIndicator.style.background = '#28a745';
                statusText.textContent = 'Koneksi aktif';
                statusText.style.color = '#28a745';
            } else {
                statusIndicator.style.background = '#dc3545';
                statusText.textContent = 'Anda sedang offline';
                statusText.style.color = '#dc3545';
            }
        }
        
        // Periksa koneksi saat halaman dimuat
        window.addEventListener('load', checkConnection);
        
        // Periksa koneksi saat status berubah
        window.addEventListener('online', checkConnection);
        window.addEventListener('offline', checkConnection);
        
        // Periksa koneksi setiap 5 detik
        setInterval(checkConnection, 5000);
    </script>
</body>
</html>