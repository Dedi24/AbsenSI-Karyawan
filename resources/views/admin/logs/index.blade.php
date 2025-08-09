@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">📝 Log Aktivitas Sistem</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-outline-danger" onclick="confirmClearLogs()">
                <i class="bi bi-trash"></i> Hapus Semua
            </button>
            <button type="button" class="btn btn-outline-secondary" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Refresh
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
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-journal-text"></i> File Log Sistem
                </h6>
                <div class="text-muted small">
                    Menampilkan {{ count($logFiles) }} file log
                </div>
            </div>

            <div class="card-body">
                @if(count($logFiles) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Nama File</th>
                                    <th class="text-center" width="15%">Ukuran</th>
                                    <th class="text-center" width="20%">Dimodifikasi</th>
                                    <th class="text-center" width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logFiles as $index => $logFile)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <div class="avatar-content bg-primary text-white rounded-circle">
                                                    <i class="bi bi-file-earmark-code"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <strong>{{ $logFile['name'] }}</strong>
                                                <div class="small text-muted">
                                                    {{ basename(dirname($logFile['path'])) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $logFile['size'] > 1048576 ? 'danger' : ($logFile['size'] > 102400 ? 'warning' : 'success') }}">
                                            {{ number_format($logFile['size'] / 1024, 2) }} KB
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="small">
                                            {{ date('d/m/Y H:i:s', $logFile['modified']) }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ \Carbon\Carbon::createFromTimestamp($logFile['modified'])->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" 
                                                    class="btn btn-outline-primary" 
                                                    onclick="viewLog('{{ $logFile['name'] }}')"
                                                    data-bs-toggle="tooltip" 
                                                    title="Lihat Log">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <a href="{{ route('admin.logs.download', $logFile['name']) }}" 
                                               class="btn btn-outline-info"
                                               data-bs-toggle="tooltip" 
                                               title="Download Log">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger" 
                                                    onclick="confirmDeleteLog('{{ $logFile['name'] }}')"
                                                    data-bs-toggle="tooltip" 
                                                    title="Hapus Log">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-file-earmark-x fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada file log</h5>
                        <p class="text-muted">Belum ada log yang di-generate oleh sistem.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal: Lihat Isi Log -->
<div class="modal fade" id="logViewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-code"></i> <span id="modal-log-filename">Log Viewer</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="logContent" style="max-height: 60vh; overflow-y: auto; font-family: monospace; font-size: 0.875rem; white-space: pre-wrap; line-height: 1.4;">
                    <!-- Konten log akan dimuat di sini -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left"></i> Tutup
                </button>
                <button type="button" class="btn btn-primary" onclick="downloadCurrentLog()">
                    <i class="bi bi-download"></i> Download
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Form: Hapus Semua Log -->
<form id="clear-logs-form" action="{{ route('admin.logs.clear') }}" method="POST" style="display: none;">
    @csrf
    @method('POST')
</form>
@endsection

@section('scripts')
<script>
// Lihat isi log
function viewLog(filename) {
    fetch(`{{ url('/admin/logs') }}/${filename}`)
        .then(response => {
            if (!response.ok) throw new Error('Gagal memuat file');
            return response.text();
        })
        .then(data => {
            document.getElementById('logContent').textContent = data;
            document.getElementById('modal-log-filename').textContent = `Log: ${filename}`;
            const modal = new bootstrap.Modal(document.getElementById('logViewerModal'));
            modal.show();
            // Simpan nama file untuk download
            document.getElementById('logViewerModal').dataset.filename = filename;
        })
        .catch(error => {
            alert('Gagal memuat log: ' + error.message);
        });
}

// Hapus satu file log
function confirmDeleteLog(filename) {
    if (confirm(`⚠️ PERINGATAN!\n\nApakah Anda yakin ingin menghapus file log "${filename}"?\n\nTINDAKAN INI TIDAK DAPAT DIBATALKAN.`)) {
        fetch(`{{ url('/admin/logs') }}/${filename}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Berhasil menghapus log: ' + filename);
                location.reload();
            } else {
                alert('Gagal: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
    }
}

// Hapus semua log
function confirmClearLogs() {
    if (confirm(`⚠️ PERINGATAN!\n\nApakah Anda yakin ingin menghapus SEMUA file log?\n\nTINDAKAN INI TIDAK DAPAT DIBATALKAN.`)) {
        document.getElementById('clear-logs-form').submit();
    }
}

// Download log dari modal
function downloadCurrentLog() {
    const modal = document.getElementById('logViewerModal');
    const filename = modal.dataset.filename;
    const content = document.getElementById('logContent').textContent;

    if (!filename || !content) {
        alert('Tidak ada log untuk diunduh.');
        return;
    }

    const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.click();
    URL.revokeObjectURL(url);
}

// Inisialisasi tooltip
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection