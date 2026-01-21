@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('barang.index') }}">Barang ATK</a>
</li>
<li class="breadcrumb-item active fw-semibold">
    Riwayat Stok
</li>
@endsection

@section('content')

<div class="card shadow-sm rounded-4">

    {{-- HEADER --}}
    <div class="card-header border-0">
        <h4 class="fw-bold mb-1">{{ $barang->nama_barang }}</h4>
        <small class="text-muted">
            Riwayat pergerakan stok
        </small>
    </div>

    {{-- BODY --}}
    <div class="card-body">
        {{-- FILTER --}}
        <form method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                {{-- Jenis Mutasi --}}
                <div class="col-lg-7">
                    <label class="form-label small fw-semibold mb-2">Jenis Mutasi</label>
                    <div class="d-flex flex-wrap gap-1">
                        @php
                            $jenis = request('jenis', 'all');
                        @endphp

                        <a href="?jenis=all&bulan={{ request('bulan') }}"
                           class="btn btn-sm px-3 py-2 btn-outline-secondary {{ $jenis=='all' ? 'active' : '' }}">
                            Semua
                        </a>

                        <a href="?jenis=masuk&bulan={{ request('bulan') }}"
                           class="btn btn-sm px-3 py-2 btn-outline-success {{ $jenis=='masuk' ? 'active' : '' }}">
                            Masuk
                        </a>

                        <a href="?jenis=keluar&bulan={{ request('bulan') }}"
                           class="btn btn-sm px-3 py-2 btn-outline-warning {{ $jenis=='keluar' ? 'active' : '' }}">
                            Keluar
                        </a>

                        <a href="?jenis=penyesuaian&bulan={{ request('bulan') }}"
                           class="btn btn-sm px-3 py-2 btn-outline-info {{ $jenis=='penyesuaian' ? 'active' : '' }}">
                            Penyesuaian
                        </a>
                    </div>
                </div>

                {{-- Periode --}}
                <div class="col-lg-4 col-md-6">
                    <label class="form-label small fw-semibold mb-2">Periode</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light">
                            <i class="bx bx-calendar text-muted"></i>
                        </span>
                        <input type="month"
                               name="bulan"
                               value="{{ request('bulan') }}"
                               class="form-control">
                    </div>
                </div>

                {{-- Tombol Filter --}}
                <div class="col-lg-1 col-md-6">
                    <button type="submit" class="btn btn-primary btn-sm w-100 py-2">
                        Filter
                    </button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Stok Awal</th>
                        <th>Stok Akhir</th>
                        <th>Keterangan</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mutasi as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>

                        {{-- BADGE DENGAN WARNA SOFT --}}
                        <td>
                            @if ($item->jenis_mutasi === 'masuk')
                                <span class="badge border border-success" style="background-color: rgba(25, 135, 84, 0.1); color: #198754;">
                                    Masuk
                                </span>
                            @elseif ($item->jenis_mutasi === 'keluar')
                                <span class="badge border border-warning" style="background-color: rgba(255, 193, 7, 0.1); color: #ffc107;">
                                    Keluar
                                </span>
                            @else
                                <span class="badge border border-info" style="background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0;">
                                    Penyesuaian
                                </span>
                            @endif
                        </td>

                        <td class="fw-semibold">
                            @if ($item->jenis_mutasi === 'masuk')
                                +{{ $item->jumlah }}
                            @elseif ($item->jenis_mutasi === 'keluar')
                                -{{ $item->jumlah }}
                            @else
                                {{-- penyesuaian --}}
                                {{ $item->stok_akhir > $item->stok_awal ? '+' : '-' }}{{ $item->jumlah }}
                            @endif
                        </td>

                        <td class="text-center">{{ $item->stok_awal }}</td>
                        <td class="text-center fw-bold">{{ $item->stok_akhir }}</td>

                        <td>{{ $item->keterangan ?? '-' }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Belum ada mutasi stok
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= FOOTER + PAGINATION ================= --}}
    <!-- Pagination Improved -->
    @if($mutasi->hasPages())
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">
        <div class="mb-3 mb-md-0 text-muted">
            <span class="fw-medium">Menampilkan</span>
            <span class="fw-medium">{{ $mutasi->firstItem() ?? 0 }}</span>
            <span class="fw-medium">sampai</span>
            <span class="fw-medium">{{ $mutasi->lastItem() ?? 0 }}</span>
            <span class="fw-medium">dari</span>
            <span class="fw-medium">{{ $mutasi->total() }}</span>
            <span class="fw-medium">data item</span>
        </div>
        
        <nav aria-label="Page navigation">
            <ul class="pagination mb-0">
                <!-- First Page Link -->
                @if(!$mutasi->onFirstPage())
                <li class="page-item">
                    <a class="page-link" href="{{ $mutasi->url(1) }}" aria-label="First">
                        <i class="bx bx-chevrons-left"></i>
                    </a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="bx bx-chevrons-left"></i></span>
                </li>
                @endif

                <!-- Previous Page Link -->
                @if($mutasi->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link"><i class="bx bx-chevron-left"></i></span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $mutasi->previousPageUrl() }}" aria-label="Previous">
                        <i class="bx bx-chevron-left"></i>
                    </a>
                </li>
                @endif

                <!-- Page Numbers -->
                @foreach($mutasi->getUrlRange(max(1, $mutasi->currentPage() - 2), min($mutasi->lastPage(), $mutasi->currentPage() + 2)) as $page => $url)
                <li class="page-item {{ $page == $mutasi->currentPage() ? 'active' : '' }}">
                    @if($page == $mutasi->currentPage())
                    <span class="page-link">{{ $page }}</span>
                    @else
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    @endif
                </li>
                @endforeach

                <!-- Next Page Link -->
                @if($mutasi->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $mutasi->nextPageUrl() }}" aria-label="Next">
                        <i class="bx bx-chevron-right"></i>
                    </a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="bx bx-chevron-right"></i></span>
                </li>
                @endif

                <!-- Last Page Link -->
                @if($mutasi->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $mutasi->url($mutasi->lastPage()) }}" aria-label="Last">
                        <i class="bx bx-chevrons-right"></i>
                    </a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="bx bx-chevrons-right"></i></span>
                </li>
                @endif
            </ul>
        </nav>
    </div>
    @elseif($mutasi->total() > 0)
    <div class="mt-4 pt-3 border-top">
        <div class="text-center text-muted">
            Menampilkan semua {{ $mutasi->total() }} data mutasi
        </div>
    </div>
    @endif

</div>

<style>
    /* Style untuk filter button aktif */
    .btn-outline-secondary.active {
        background-color: #6c757d;
        color: white;
        border-color: #6c757d;
    }
    
    .btn-outline-success.active {
        background-color: #198754;
        color: white;
        border-color: #198754;
    }
    
    .btn-outline-warning.active {
        background-color: #ffc107;
        color: black;
        border-color: #ffc107;
    }
    
    .btn-outline-info.active {
        background-color: #0dcaf0;
        color: white;
        border-color: #0dcaf0;
    }
    
    /* Style untuk badge dengan warna soft */
    .badge.border-success {
        background-color: rgba(25, 135, 84, 0.1) !important;
        color: #198754 !important;
        border-color: #198754 !important;
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
    
    .badge.border-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
        color: #ffc107 !important;
        border-color: #ffc107 !important;
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
    
    .badge.border-info {
        background-color: rgba(13, 202, 240, 0.1) !important;
        color: #0dcaf0 !important;
        border-color: #0dcaf0 !important;
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
    
    /* Responsif untuk filter */
    @media (max-width: 768px) {
        .col-lg-7, .col-lg-4, .col-lg-1 {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }
        
        .d-flex.flex-wrap.gap-1 {
            justify-content: center;
        }
    }
</style>
@endsection