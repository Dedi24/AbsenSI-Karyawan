@extends('layouts.app')

@section('title', 'System Logs')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">📝 System Logs</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button class="btn btn-outline-danger" onclick="confirmClearLogs()">
                <i class="bi bi-trash"></i> Clear All Logs
            </button>
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

<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-file-earmark-text"></i> Log Files
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
                                    <th>Filename</th>
                                    <th class="text-center" width="15%">Size</th>
                                    <th class="text-center" width="20%">Last Modified</th>
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
                                            <a href="{{ route('admin.logs.show', $logFile['name']) }}" 
                                               class="btn btn-outline-primary" data-bs-toggle="tooltip" title="View Log">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.logs.show', $logFile['name']) }}" 
                                               class="btn btn-outline-info" data-bs-toggle="tooltip" title="Download Log">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="confirmDeleteLog('{{ $logFile['name'] }}')" 
                                                    data-bs-toggle="tooltip" title="Delete Log">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Hidden form for delete -->
                                        <form id="delete-log-form-{{ $index }}" 
                                              action="{{ route('admin.logs.destroy', $logFile['name']) }}" 
                                              method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
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

<!-- Log Viewer Modal -->
<div class="modal fade" id="logViewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-code"></i> Log Viewer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="logContent" style="max-height: 60vh; overflow-y: auto; font-family: monospace; font-size: 0.875rem; white-space: pre-wrap;">
                    <!-- Log content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left"></i> Tutup
                </button>
                <button type="button" class="btn btn-primary" onclick="downloadLog()">
                    <i class="bi bi-download"></i> Download
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for clear logs -->
<form id="clear-logs-form" 
      action="{{ route('admin.logs.clear') }}" 
      method="POST" style="display: none;">
    @csrf
    @method('POST')
</form>
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

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.avatar-sm {
    width: 40px;
    height: 40px;
}

.avatar-content {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    font-size: 0.875rem;
}

.log-row {
    transition: all 0.2s ease;
}

.log-row.filtered-out {
    display: none;
}
</style>

<script>
// Delete log confirmation
function confirmDeleteLog(filename) {
    if (confirm(`⚠️ PERINGATAN!\n\nApakah Anda yakin ingin MENGHAPUS file log "${filename}"?\n\nTINDAKAN INI TIDAK DAPAT DIBATALKAN dan akan menghapus semua data log terkait.`)) {
        document.getElementById(`delete-log-form-${filename}`).submit();
    }
}

// Clear all logs confirmation
function confirmClearLogs() {
    if (confirm(`⚠️ PERINGATAN!\n\nApakah Anda yakin ingin MENGHAPUS SEMUA file log?\n\nTINDAKAN INI TIDAK DAPAT DIBATALKAN dan akan menghapus semua data log sistem.`)) {
        document.getElementById('clear-logs-form').submit();
    }
}

// View log content
function viewLog(filename) {
    fetch(`/admin/logs/${filename}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('logContent').textContent = data;
            const logViewerModal = new bootstrap.Modal(document.getElementById('logViewerModal'));
            logViewerModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat konten log.');
        });
}

// Download log
function downloadLog() {
    // Implement download logic here
    alert('Fitur download akan tersedia di versi berikutnya.');
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection