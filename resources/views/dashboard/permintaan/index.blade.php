@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active fw-semibold">
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
                            {{-- BADGE STATUS DENGAN WARNA SOFT TAPI LEBIH MENCOLOK --}}
                            @if($item->status === 'draft')
                                <span class="badge border-warning-soft" style="background-color: rgba(255, 193, 7, 0.2); color: #d39e00; border-color: #ffc107; font-size: 0.8rem; padding: 0.25em 0.6em;">
                                    Draft
                                </span>
                            @elseif($item->status === 'diproses')
                                <span class="badge border-success-soft" style="background-color: rgba(40, 167, 69, 0.2); color: #218838; border-color: #28a745; font-size: 0.8rem; padding: 0.25em 0.6em;">
                                    Diproses
                                </span>
                            @else
                                <span class="badge border-secondary-soft" style="background-color: rgba(108, 117, 125, 0.2); color: #495057; border-color: #6c757d; font-size: 0.8rem; padding: 0.25em 0.6em;">
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
    }
</style>
@endsection