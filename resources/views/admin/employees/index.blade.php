@extends('layouts.app')

@section('title', 'Daftar Karyawan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">👥 Daftar Karyawan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.employees.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-person-plus"></i> Tambah Karyawan
            </a>
            <button class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-download"></i> Export
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

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="bi bi-people"></i> Data Karyawan</h5>
            </div>
            <div class="col-md-6">
                <form action="{{ route('admin.employees.index') }}" method="GET" class="d-flex">
                    <input class="form-control me-2" type="search" name="search" placeholder="Cari nama, NIP, email..." aria-label="Search" value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Departemen</th>
                        <th>Status</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr>
                        <td class="text-center">{{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <div class="avatar-content bg-{{ $employee->avatar_color }} text-white rounded-circle">
                                        {{ $employee->initials }}
                                    </div>
                                </div>
                                <div>
                                    <strong>{{ $employee->name }}</strong>
                                    <div class="small text-muted">{{ $employee->nik }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $employee->nip ?? '-' }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->jabatan_singkat }}</td>
                        <td>{{ $employee->departemen_singkat }}</td>
                        <td>
                            <span class="badge bg-{{ $employee->status_badge_color }}">
                                {{ ucfirst($employee->status) }}
                            </span>
                            <span class="badge bg-{{ $employee->role_badge_color }} ms-1">
                                {{ ucfirst($employee->role) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-outline-primary" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('admin.employees.device', $employee->id) }}" class="btn btn-outline-info" title="Lihat Device">
                                    <i class="bi bi-phone"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus" onclick="confirmDelete({{ $employee->id }}, '{{ $employee->name }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-person-x" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">Tidak ada data karyawan ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Menampilkan {{ $employees->firstItem() }} sampai {{ $employees->lastItem() }} dari {{ $employees->total() }} karyawan
            </div>
            <div>
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Form Hapus Tersembunyi -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script>
    function confirmDelete(id, name) {
        if (confirm(`Apakah Anda yakin ingin menghapus karyawan "${name}"?`)) {
            const form = document.getElementById('delete-form');
            form.action = `{{ url('admin/employees') }}/${id}`;
            form.submit();
        }
    }
</script>
@endsection