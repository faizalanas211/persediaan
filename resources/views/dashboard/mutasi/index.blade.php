@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active text-primary fw-semibold">
    Mutasi Stok
</li>
@endsection

@section('content')

<div class="card card-flush shadow-sm rounded-4">
    {{-- ================= HEADER ================= --}}
    <div class="card-header border-0 pt-6 pb-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">Riwayat Mutasi Stok</h4>
            <p class="text-muted mb-0 fs-7">
                Catatan seluruh pergerakan stok barang ATK
            </p>
        </div>

        <a href="{{ route('mutasi.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Data Mutasi
        </a>
    </div>

    <div class="card-body pt-0">
        {{-- ================= FILTER UTAMA ================= --}}
        <form method="GET" id="filterForm" class="mb-4">
            {{-- ===== BARIS 1: Jenis Mutasi + Periode ===== --}}
            <div class="row g-3 align-items-end mb-4 pb-3 border-bottom">
                {{-- Jenis Mutasi --}}
                <div class="col-lg-7 col-md-6">
                    <label class="form-label small fw-semibold mb-2">Jenis Mutasi</label>
                    <div class="d-flex flex-wrap gap-1">
                        @php $jenis = request('jenis', 'all'); @endphp

                        <a href="{{ request()->fullUrlWithQuery(['jenis' => 'all']) }}"
                           class="btn btn-sm px-3 py-2 btn-outline-secondary {{ $jenis == 'all' ? 'active' : '' }}" style="font-size: 0.8rem;">
                            <i class="bx bx-layer me-1 fs-6"></i> Semua
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['jenis' => 'masuk']) }}"
                           class="btn btn-sm px-3 py-2 btn-outline-success {{ $jenis == 'masuk' ? 'active' : '' }}" style="font-size: 0.8rem;">
                            <i class="bx bx-down-arrow-alt me-1 fs-6"></i> Masuk
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['jenis' => 'keluar']) }}"
                           class="btn btn-sm px-3 py-2 btn-outline-warning {{ $jenis == 'keluar' ? 'active' : '' }}" style="font-size: 0.8rem;">
                            <i class="bx bx-up-arrow-alt me-1 fs-6"></i> Keluar
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['jenis' => 'penyesuaian']) }}"
                           class="btn btn-sm px-3 py-2 btn-outline-info {{ $jenis == 'penyesuaian' ? 'active' : '' }}" style="font-size: 0.8rem;">
                            <i class="bx bx-adjust me-1 fs-6"></i> Penyesuaian
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
                    <button type="submit" class="btn btn-primary btn-sm w-100 py-2" style="font-size: 0.8rem;">
                        Filter
                    </button>
                </div>
            </div>
        </form>

        {{-- ================= TABLE ================= --}}
        <div class="table-responsive border rounded-3 mt-4">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-light">
                    <tr class="text-uppercase text-muted fs-8">
                        <th width="5%" class="ps-4">#</th>
                        <th width="15%">Tanggal</th>
                        <th width="25%">Barang</th>
                        <th width="10%" class="text-center">Jenis</th>
                        <th width="10%" class="text-center">Jumlah</th>
                        <th width="20%">Keterangan</th>
                        <th width="15%">Dicatat Oleh</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($mutasi as $item)
                        <tr style="font-size: 0.85rem;">
                            <td class="ps-4">{{ $mutasi->firstItem() + $loop->index }}</td>

                            <td>
                                <div class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                                </div>
                                <div class="text-muted fs-9">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-semibold">{{ $item->barang->nama_barang }}</div>
                            </td>

                            {{-- JENIS MUTASI - BADGE DIPERBAIKI (FONT DIPERKECIL) --}}
                            <td class="text-center">
                                @if ($item->jenis_mutasi === 'masuk')
                                    <span class="badge border border-success" style="background-color: rgba(25, 135, 84, 0.1); color: #198754; font-size: 0.7rem; padding: 0.2em 0.4em;">
                                        <i class="bx bx-down-arrow-alt me-1 fs-7"></i> Masuk
                                    </span>
                                @elseif ($item->jenis_mutasi === 'keluar')
                                    <span class="badge border border-danger" style="background-color: rgba(220, 53, 69, 0.1); color: #dc3545; font-size: 0.7rem; padding: 0.2em 0.4em;">
                                        <i class="bx bx-up-arrow-alt me-1 fs-7"></i> Keluar
                                    </span>
                                @else
                                    <span class="badge border border-warning" style="background-color: rgba(255, 193, 7, 0.1); color: #ffc107; font-size: 0.7rem; padding: 0.2em 0.4em;">
                                        <i class="bx bx-adjust me-1 fs-7"></i> Penyesuaian
                                    </span>
                                @endif
                            </td>

                            {{-- JUMLAH --}}
                            <td class="text-center">
                                <span class="fw-bold {{ $item->jenis_mutasi === 'masuk' ? 'text-success' : 'text-danger' }}" style="font-size: 0.85rem;">
                                    {{ $item->jenis_mutasi === 'masuk' ? '+' : '-' }}{{ number_format($item->jumlah) }}
                                </span>
                            </td>

                            <td>
                                @if($item->keterangan)
                                    <div class="text-truncate" style="max-width: 200px;" 
                                         title="{{ $item->keterangan }}">
                                        {{ $item->keterangan }}
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-semibold" style="font-size: 0.85rem;">{{ $item->user->name }}</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-center">
                                    <div class="mb-3">
                                        <i class="bx bx-package fs-1 text-muted"></i>
                                    </div>
                                    <h5 class="text-muted mb-2">Belum ada data mutasi stok</h5>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ================= FOOTER + PAGINATION ================= --}}
        @if($mutasi->hasPages())
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">
            <div class="mb-3 mb-md-0">
                <span class="text-muted" style="font-size: 0.85rem;">
                    Menampilkan 
                    <span class="fw-semibold">{{ $mutasi->firstItem() }}</span> 
                    sampai 
                    <span class="fw-semibold">{{ $mutasi->lastItem() }}</span> 
                    dari 
                    <span class="fw-semibold">{{ $mutasi->total() }}</span> 
                    data mutasi
                </span>
            </div>
            
            <nav aria-label="Page navigation">
                <ul class="pagination mb-0" style="font-size: 0.85rem;">
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
                    @php
                        $current = $mutasi->currentPage();
                        $last = $mutasi->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                        
                        if($end - $start < 4 && $last > 5) {
                            if($current <= 3) {
                                $end = min(5, $last);
                            } elseif($current >= $last - 2) {
                                $start = max(1, $last - 4);
                            }
                        }
                    @endphp
                    
                    @for ($page = $start; $page <= $end; $page++)
                        <li class="page-item {{ $page == $current ? 'active' : '' }}">
                            @if($page == $current)
                            <span class="page-link">{{ $page }}</span>
                            @else
                            <a class="page-link" href="{{ $mutasi->url($page) }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endfor

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
            <div class="text-center text-muted" style="font-size: 0.85rem;">
                Menampilkan semua {{ $mutasi->total() }} data mutasi
            </div>
        </div>
        @endif

    </div>
</div>

@endsection

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
    
    /* Tombol filter jenis lebih kompak */
    .btn-sm.px-3.py-2 {
        padding: 0.35rem 0.6rem !important;
        font-size: 0.8rem !important;
    }
    
    /* Hover effect untuk table rows */
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.03);
    }
    
    /* Badge dengan background opacity tapi teks solid */
    .badge.border-success,
    .badge.border-danger,
    .badge.border-warning {
        font-weight: 500;
        padding: 0.2em 0.4em !important;
        font-size: 0.7rem !important;
    }
    
    /* Styling untuk form filter */
    .border-bottom {
        border-color: #e9ecef !important;
    }
    
    /* Input group styling */
    .input-group-sm .input-group-text {
        padding: 0.35rem 0.6rem;
        font-size: 0.8rem;
    }
    
    .input-group-sm .form-control {
        font-size: 0.8rem;
        padding: 0.35rem 0.6rem;
    }
    
    /* Responsif untuk filter jenis */
    @media (max-width: 768px) {
        .btn-sm.px-3.py-2 {
            flex: 1;
            min-width: 110px;
            justify-content: center;
        }
        
        .d-flex.flex-wrap.gap-1,
        .d-flex.flex-wrap.gap-2 {
            gap: 0.4rem !important;
        }
        
        .border-bottom {
            padding-bottom: 0.8rem;
        }
    }
    
    @media (max-width: 576px) {
        .btn-sm.px-3.py-2 {
            min-width: 45%;
            margin-bottom: 0.2rem;
            font-size: 0.75rem !important;
            padding: 0.3rem 0.5rem !important;
        }
        
        .col-lg-7, .col-lg-5,
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .d-flex.gap-2 {
            flex-direction: column;
            gap: 0.4rem !important;
        }
        
        .d-flex.gap-2 .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>