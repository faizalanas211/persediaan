@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active fw-semibold">
    Stok Opname
</li>
@endsection

@section('content')

<div class="card shadow-sm rounded-4">

    {{-- HEADER --}}
    <div class="card-header border-0 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">Stok Opname</h4>
            <small class="text-muted">Rekap stok akhir bulan</small>
        </div>

        <a href="{{ route('stok-opname.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Buat Stok Opname
        </a>
    </div>

    {{-- BODY --}}
    <div class="card-body">

        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Periode</th>
                        <th>Tanggal Opname</th>
                        <th>Status</th>
                        <th>Dicatat Oleh</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($stokOpnames as $item)
                        <tr>
                            <td>{{ $stokOpnames->firstItem() + $loop->index }}</td>

                            <td class="fw-semibold">
                                {{ \Carbon\Carbon::parse($item->periode_bulan)->translatedFormat('F Y') }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal_opname)->format('d M Y') }}
                            </td>

                            <td>
                                {{-- BADGE STATUS DENGAN WARNA SOFT TAPI LEBIH MENCOLOK --}}
                                @if ($item->status === 'draft')
                                    <span class="badge border-warning-soft" style="background-color: rgba(255, 193, 7, 0.25); color: #b58900; border-color: #ffc107; font-size: 0.8rem; padding: 0.25em 0.8em; border-width: 1px; border-style: solid;">
                                        Draft
                                    </span>
                                @else
                                    <span class="badge border-success-soft" style="background-color: rgba(40, 167, 69, 0.25); color: #1e7e34; border-color: #28a745; font-size: 0.8rem; padding: 0.25em 0.8em; border-width: 1px; border-style: solid;">
                                        Final
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $item->pencatat->name ?? '-' }}
                            </td>

                            <td class="text-center">
                                <a href="{{ route('stok-opname.show', $item->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada data stok opname
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    {{-- ================= FOOTER + PAGINATION ================= --}}
    <!-- Pagination Improved -->
    @if($stokOpnames->hasPages())
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">
        <div class="mb-3 mb-md-0 text-muted">
            <span class="fw-medium">Menampilkan</span>
            <span class="fw-medium">{{ $stokOpnames->firstItem() ?? 0 }}</span>
            <span class="fw-medium">sampai</span>
            <span class="fw-medium">{{ $stokOpnames->lastItem() ?? 0 }}</span>
            <span class="fw-medium">dari</span>
            <span class="fw-medium">{{ $stokOpnames->total() }}</span>
            <span class="fw-medium">data stok opname</span>
        </div>
        
        <nav aria-label="Page navigation">
            <ul class="pagination mb-0">
                <!-- First Page Link -->
                @if(!$stokOpnames->onFirstPage())
                <li class="page-item">
                    <a class="page-link" href="{{ $stokOpnames->url(1) }}" aria-label="First">
                        <i class="bx bx-chevrons-left"></i>
                    </a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="bx bx-chevrons-left"></i></span>
                </li>
                @endif

                <!-- Previous Page Link -->
                @if($stokOpnames->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link"><i class="bx bx-chevron-left"></i></span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $stokOpnames->previousPageUrl() }}" aria-label="Previous">
                        <i class="bx bx-chevron-left"></i>
                    </a>
                </li>
                @endif

                <!-- Page Numbers -->
                @foreach($stokOpnames->getUrlRange(max(1, $stokOpnames->currentPage() - 2), min($stokOpnames->lastPage(), $stokOpnames->currentPage() + 2)) as $page => $url)
                <li class="page-item {{ $page == $stokOpnames->currentPage() ? 'active' : '' }}">
                    @if($page == $stokOpnames->currentPage())
                    <span class="page-link">{{ $page }}</span>
                    @else
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    @endif
                </li>
                @endforeach

                <!-- Next Page Link -->
                @if($stokOpnames->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $stokOpnames->nextPageUrl() }}" aria-label="Next">
                        <i class="bx bx-chevron-right"></i>
                    </a>
                </li>
                @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="bx bx-chevron-right"></i></span>
                </li>
                @endif

                <!-- Last Page Link -->
                @if($stokOpnames->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $stokOpnames->url($stokOpnames->lastPage()) }}" aria-label="Last">
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
    @elseif($stokOpnames->total() > 0)
    <div class="mt-4 pt-3 border-top">
        <div class="text-center text-muted">
            Menampilkan semua {{ $stokOpnames->total() }} data stok opname
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
        padding: 0.25em 0.8em;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(255, 193, 7, 0.1);
    }
    
    .badge.border-success-soft {
        background-color: rgba(40, 167, 69, 0.25) !important;
        color: #1e7e34 !important;
        border-color: #28a745 !important;
        border-width: 1px;
        border-style: solid;
        padding: 0.25em 0.8em;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(40, 167, 69, 0.1);
    }
    
    /* Menghapus rounded-pill default */
    .badge.border-warning-soft,
    .badge.border-success-soft {
        border-radius: 0.375rem !important;
    }
</style>
@endsection