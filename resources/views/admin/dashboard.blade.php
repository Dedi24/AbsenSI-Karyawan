@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">📊 Dashboard Admin</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-file-earmark-bar-graph"></i> Laporan
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-gradient-primary text-white py-3 d-flex align-items-center">
                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
                <div>
                    <h6 class="mb-0">Total Karyawan</h6>
                </div>
            </div>
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="fs-3 fw-bold text-primary">{{ number_format($totalKaryawan) }}</div>
                <div class="text-success small">
                    <i class="bi bi-arrow-up"></i> Aktif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-gradient-success text-white py-3 d-flex align-items-center">
                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                    <i class="bi bi-calendar-check fs-4"></i>
                </div>
                <div>
                    <h6 class="mb-0">Hadir Hari Ini</h6>
                </div>
            </div>
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="fs-3 fw-bold text-success">{{ number_format($hadirHariIni) }}</div>
                <div class="text-success small">
                    <i class="bi bi-check-circle"></i> On Time
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-gradient-danger text-white py-3 d-flex align-items-center">
                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                    <i class="bi bi-calendar-x fs-4"></i>
                </div>
                <div>
                    <h6 class="mb-0">Tidak Hadir</h6>
                </div>
            </div>
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="fs-3 fw-bold text-danger">{{ number_format($tidakHadir) }}</div>
                <div class="text-warning small">
                    <i class="bi bi-exclamation-triangle"></i> Perlu Follow-up
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Chart Absensi Mingguan -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
                <i class="bi bi-graph-up me-2 text-primary"></i>
                <h5 class="mb-0">Statistik Absensi Mingguan</h5>
            </div>
            <div class="card-body">
                <canvas id="weeklyAttendanceChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Absensi Hari Ini -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-week text-primary"></i> Hari Ini
                </h5>
                <span class="text-muted small">{{ now()->timezone('Asia/Jakarta')->isoFormat('D MMM') }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Nama</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(App\Models\Absensi::where('date', today())->with('user')->get() as $absensi)
                                <tr>
                                    <td>{{ $absensi->user->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $absensi->status == 'hadir' ? 'success' : ($absensi->status == 'alpha' ? 'danger' : 'warning') }} rounded-pill px-2">
                                            {{ ucfirst($absensi->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">
                                        <i class="bi bi-calendar2-x"></i> Tidak ada data absensi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Data Statistik Absensi (Hari ini - 6 hari ke belakang)
    const labels = [
        @for ($i = 6; $i >= 0; $i--)
            "{{ now()->subDays($i)->timezone('Asia/Jakarta')->isoFormat('ddd') }}"
        @endfor
    ];

    // Ganti ini dengan logika backend Anda untuk menghitung hadir per hari
    const hadirData = [
        @for ($i = 6; $i >= 0; $i--)
            {{ App\Models\Absensi::where('date', now()->subDays($i)->toDateString())
                                   ->where('status', 'hadir')
                                   ->count() }}
        @endfor
    ];

    const alphaData = [
        @for ($i = 6; $i >= 0; $i--)
            {{ App\Models\Absensi::where('date', now()->subDays($i)->toDateString())
                                   ->where('status', 'alpha')
                                   ->count() }}
        @endfor
    ];

    const ctx = document.getElementById('weeklyAttendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Hadir',
                    data: hadirData,
                    backgroundColor: '#1cc88a',
                    borderColor: '#17a673',
                    borderWidth: 1
                },
                {
                    label: 'Alpha',
                    data: alphaData,
                    backgroundColor: '#e74a3b',
                    borderColor: '#be2617',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw} orang`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>

<style>
/* Gradient Backgrounds for Headers */
.bg-gradient-primary {
    background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
}
.bg-gradient-success {
    background: linear-gradient(90deg, #1cc88a 0%, #17a673 100%);
}
.bg-gradient-danger {
    background: linear-gradient(90deg, #e74a3b 0%, #be2617 100%);
}

/* Card Hover Effect */
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    transition: all 0.3s ease;
}

/* Table Styling */
.table th, .table td {
    vertical-align: middle;
    font-size: 0.875rem;
}

/* Badge Styling */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.5em 0.7em;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        align-items: flex-start;
    }
    .card-body {
        text-align: center;
    }
    .fs-3 {
        font-size: 1.75rem !important;
    }
}
</style>
@endsection