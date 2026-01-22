@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active text-primary fw-semibold">
    Permintaan ATK
</li>
@endsection

@section('content')
<div class="card shadow-sm rounded-4">
    {{-- HEADER --}}
    <div class="card-header border-0 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">Permintaan ATK</h4>
            <small class="text-muted">Daftar permintaan ATK yang dicatat admin</small>
        </div>
        <a href="{{ route('permintaan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Permintaan
        </a>
    </div>

    {{-- BODY --}}
    <div class="card-body">
        {{-- FILTER --}}
        <form method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                {{-- Filter Status --}}
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small fw-semibold mb-2">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status')=='draft' ? 'selected' : '' }}>Draft</option>
                        <option value="diproses" {{ request('status')=='diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="dibatalkan" {{ request('status')=='dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                {{-- Filter Periode --}}
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small fw-semibold mb-2">Periode</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-calendar"></i>
                        </span>
                        <input type="month"
                               name="periode"
                               class="form-control"
                               value="{{ request('periode') }}">
                    </div>
                </div>

                {{-- Search --}}
                <div class="col-lg-4 col-md-6">
                    <label class="form-label small fw-semibold mb-2">Cari</label>
                    <div class="input-group input-group-sm">
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Pemohon / Keperluan"
                               value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="col-lg-2 col-md-6">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100 py-2">
                            <i class="bi bi-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('permintaan.index') }}" class="btn btn-outline-secondary btn-sm py-2">
                            Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Pemohon</th>
                        <th>Keperluan</th>
                        <th>Jumlah Item</th>
                        <th>Status</th>
                        <th>Dicatat Oleh</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permintaan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_permintaan)->format('d M Y') }}</td>
                        <td>{{ $item->nama_pemohon ?? '-' }}</td>
                        <td>{{ $item->keperluan }}</td>
                        <td class="text-center">{{ $item->detail->sum('jumlah') }}</td>
                        <td>
                            {{-- BADGE STATUS DENGAN WARNA SOFT --}}
                            @if($item->status === 'draft')
                                <span class="badge border-warning-soft">
                                    Draft
                                </span>
                            @elseif($item->status === 'diproses')
                                <span class="badge border-success-soft">
                                    Diproses
                                </span>
                            @else
                                <span class="badge border-secondary-soft">
                                    Dibatalkan
                                </span>
                            @endif
                        </td>
                        <td>{{ $item->pencatat->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('permintaan.show', $item->id) }}" 
                               class="btn btn-sm btn-outline-primary">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            Belum ada permintaan ATK
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($permintaan->hasPages())
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">
            <div class="mb-3 mb-md-0 text-muted">
                <span class="fw-medium">Menampilkan</span>
                <span class="fw-medium">{{ $permintaan->firstItem() ?? 0 }}</span>
                <span class="fw-medium">sampai</span>
                <span class="fw-medium">{{ $permintaan->lastItem() ?? 0 }}</span>
                <span class="fw-medium">dari</span>
                <span class="fw-medium">{{ $permintaan->total() }}</span>
                <span class="fw-medium">data item</span>
            </div>
            
            <nav aria-label="Page navigation">
                <ul class="pagination mb-0">
                    {{-- First Page --}}
                    @if(!$permintaan->onFirstPage())
                    <li class="page-item">
                        <a class="page-link" href="{{ $permintaan->url(1) }}" aria-label="First">
                            <i class="bx bx-chevrons-left"></i>
                        </a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <span class="page-link"><i class="bx bx-chevrons-left"></i></span>
                    </li>
                    @endif

                    {{-- Previous Page --}}
                    @if($permintaan->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link"><i class="bx bx-chevron-left"></i></span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $permintaan->previousPageUrl() }}" aria-label="Previous">
                            <i class="bx bx-chevron-left"></i>
                        </a>
                    </li>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach($permintaan->getUrlRange(max(1, $permintaan->currentPage() - 2), min($permintaan->lastPage(), $permintaan->currentPage() + 2)) as $page => $url)
                    <li class="page-item {{ $page == $permintaan->currentPage() ? 'active' : '' }}">
                        @if($page == $permintaan->currentPage())
                        <span class="page-link">{{ $page }}</span>
                        @else
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    </li>
                    @endforeach

                    {{-- Next Page --}}
                    @if($permintaan->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $permintaan->nextPageUrl() }}" aria-label="Next">
                            <i class="bx bx-chevron-right"></i>
                        </a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <span class="page-link"><i class="bx bx-chevron-right"></i></span>
                    </li>
                    @endif

                    {{-- Last Page --}}
                    @if($permintaan->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $permintaan->url($permintaan->lastPage()) }}" aria-label="Last">
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
        @elseif($permintaan->total() > 0)
        <div class="mt-4 pt-3 border-top">
            <div class="text-center text-muted">
                Menampilkan semua {{ $permintaan->total() }} data permintaan
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    /* Style untuk badge dengan warna soft yang lebih mencolok */
    .badge.border-warning-soft {
        background-color: rgba(255, 193, 7, 0.25) !important;
        color: #b58900 !important;
        border-color: #ffc107 !important;
        border-width: 1px;
        border-style: solid;
        padding: 0.25em 0.6em;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(255, 193, 7, 0.1);
        border-radius: 0.375rem !important;
    }
    
    .badge.border-success-soft {
        background-color: rgba(40, 167, 69, 0.25) !important;
        color: #1e7e34 !important;
        border-color: #28a745 !important;
        border-width: 1px;
        border-style: solid;
        padding: 0.25em 0.6em;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(40, 167, 69, 0.1);
        border-radius: 0.375rem !important;
    }
    
    .badge.border-secondary-soft {
        background-color: rgba(108, 117, 125, 0.25) !important;
        color: #495057 !important;
        border-color: #6c757d !important;
        border-width: 1px;
        border-style: solid;
        padding: 0.25em 0.6em;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(108, 117, 125, 0.1);
        border-radius: 0.375rem !important;
    }
    
    /* Responsif untuk filter */
    @media (max-width: 768px) {
        .col-lg-3, .col-lg-4, .col-lg-2 {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }
        
        .d-flex.gap-2 {
            justify-content: stretch;
        }
        
        .btn-sm.w-100 {
            width: 100%;
        }
    }
</style>
@endsection