@extends('layouts.app')

@section('title', 'View Log - ' . $filename)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">📄 View Log File</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
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
                    <i class="bi bi-file-earmark-code"></i> {{ $filename }}
                </h6>
                <div class="btn-group">
                    <a href="{{ route('admin.logs.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button class="btn btn-sm btn-primary" onclick="downloadLog('{{ $filename }}')">
                        <i class="bi bi-download"></i> Download
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="confirmDeleteLog('{{ $filename }}')">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>
            </div>
            
            <div class="card-body">
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" id="logSearch" placeholder="Cari dalam log...">
                        <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
                
                <div class="log-viewer" id="logViewer" 
                     style="background-color: #1e1e1e; color: #d4d4d4; padding: 15px; border-radius: 0.375rem; 
                            font-family: 'Courier New', Courier, monospace; font-size: 0.875rem; 
                            max-height: 70vh; overflow-y: auto; white-space: pre-wrap;">
                    {{ $logContent }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for delete -->
<form id="delete-log-form" 
      action="{{ route('admin.logs.destroy', $filename) }}" 
      method="POST" style="display: none;">
    @csrf
    @method('DELETE')
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

.input-group-text {
    background-color: #f8f9fc;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.log-viewer {
    line-height: 1.4;
}

.log-viewer .error {
    color: #f48771;
}

.log-viewer .warning {
    color: #cca700;
}

.log-viewer .info {
    color: #75beff;
}

.log-viewer .debug {
    color: #c586c0;
}
</style>

<script>
// Delete log confirmation
function confirmDeleteLog(filename) {
    if (confirm(`⚠️ PERINGATAN!\n\nApakah Anda yakin ingin MENGHAPUS file log "${filename}"?\n\nTINDAKAN INI TIDAK DAPAT DIBATALKAN dan akan menghapus semua data log terkait.`)) {
        document.getElementById('delete-log-form').submit();
    }
}

// Download log
function downloadLog(filename) {
    const logContent = document.getElementById('logViewer').textContent;
    const blob = new Blob([logContent], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

// Search in log
document.addEventListener('DOMContentLoaded', function() {
    const logSearch = document.getElementById('logSearch');
    const logViewer = document.getElementById('logViewer');
    const originalContent = logViewer.textContent;

    logSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        if (searchTerm === '') {
            logViewer.textContent = originalContent;
            return;
        }

        const lines = originalContent.split('\n');
        const filteredLines = lines.filter(line => 
            line.toLowerCase().includes(searchTerm)
        );

        logViewer.textContent = filteredLines.join('\n');
    });
});

// Clear search
function clearSearch() {
    document.getElementById('logSearch').value = '';
    document.getElementById('logViewer').textContent = '{!! addslashes($logContent) !!}';
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