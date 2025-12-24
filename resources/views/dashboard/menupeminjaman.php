<!-- @extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active text-primary fw-semibold">
    Dashboard Peminjaman Barang
</li>
@endsection

@section('content')

<div class="card card-flush shadow-sm rounded-4 mb-4">

    {{-- HEADER --}}
    <div class="card-header border-0 pt-6 pb-4">
        <h3 class="fw-bold mb-1">Dashboard Peminjaman Barang</h3>
        <p class="text-muted mb-0 fs-7">Monitoring ketersediaan barang</p>
    </div>

    <div class="card-body pt-0">

        {{-- ================= CARD BARANG ================= --}}
        <div class="row g-4 mb-5">

            @forelse($dataBarang as $type => $item)
                @php
                    $progress = $item['total'] > 0
                        ? ($item['ready'] / $item['total']) * 100
                        : 0;
                @endphp

                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="inventory-card">

                        <h6>{{ ucfirst($type) }}</h6>
                        <h2>{{ $item['ready'] }}</h2>

                        <div class="progress mb-2">
                            <div class="progress-bar"
                                 role="progressbar"
                                 style="width: {{ $progress }}%">
                            </div>
                        </div>

                        <small class="text-muted">
                            {{ $item['dipinjam'] }} dipinjam dari {{ $item['total'] }}
                        </small>

                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Belum ada data barang
                    </div>
                </div>
            @endforelse

        </div>

        {{-- ================= CHART ================= --}}
        <div class="row">
            <div class="col-12">
                <div class="chart-card">
                    <h5 class="fw-semibold mb-3">Statistik Ketersediaan Barang</h5>
                    <canvas id="barangChart" height="110"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ================= STYLE ================= --}}
<style>
.inventory-card{
    background:#ffffff;
    border-radius:20px;
    padding:22px;
    height:100%;
    box-shadow:0 8px 22px rgba(0,0,0,.06);
    border:1px solid #eef0f4;
}
.inventory-card h6{
    font-size:14px;
    font-weight:600;
    color:#6b7280;
}
.inventory-card h2{
    font-size:36px;
    font-weight:700;
    margin:6px 0 14px;
    color:#111827;
}
.progress{
    height:8px;
    background:#e5e7eb;
    border-radius:6px;
}
.progress-bar{
    background:#4f46e5;
}
.chart-card{
    background:#ffffff;
    border-radius:20px;
    padding:24px;
    box-shadow:0 8px 22px rgba(0,0,0,.06);
    border:1px solid #eef0f4;
}
</style>

{{-- ================= SCRIPT ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('barangChart');

const dataBarang = @json($dataBarang);

const labels = Object.keys(dataBarang);
const tersedia = labels.map(l => dataBarang[l].ready);
const dipinjam = labels.map(l => dataBarang[l].dipinjam);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Tersedia',
                data: tersedia,
                backgroundColor: '#4f46e5',
                borderRadius: 8
            },
            {
                label: 'Dipinjam',
                data: dipinjam,
                backgroundColor: '#f59e0b',
                borderRadius: 8
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
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
</script>

@endsection
-->
