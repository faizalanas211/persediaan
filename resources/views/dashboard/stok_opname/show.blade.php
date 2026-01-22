@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('stok-opname.index') }}">Stok Opname</a>
</li>
<li class="breadcrumb-item active text-primary fw-semibold">Detail Stok Opname</li>
@endsection

@section('content')

<div class="card shadow-sm rounded-4">

    {{-- HEADER --}}
    <div class="card-header border-0 d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">Detail Stok Opname</h4>
            <small class="text-muted">
                Periode: {{ \Carbon\Carbon::parse($stokOpname->periode_bulan)->translatedFormat('F Y') }}
            </small>
        </div>

        {{-- STATUS DENGAN BADGE SOFT --}}
        <div>
            @if($stokOpname->status === 'draft')
                <span class="badge border-warning-soft" style="background-color: rgba(255, 193, 7, 0.25); color: #b58900; border-color: #ffc107; font-size: 0.85rem; padding: 0.3em 1em; border-width: 1px; border-style: solid;">
                    Draft
                </span>
            @else
                <span class="badge border-success-soft" style="background-color: rgba(40, 167, 69, 0.25); color: #1e7e34; border-color: #28a745; font-size: 0.85rem; padding: 0.3em 1em; border-width: 1px; border-style: solid;">
                    Final
                </span>
            @endif
        </div>
    </div>

    {{-- INFO --}}
    <div class="card-body border-bottom">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="small text-muted">Tanggal Opname</div>
                <div class="fw-semibold">
                    {{ \Carbon\Carbon::parse($stokOpname->tanggal_opname)->format('d M Y') }}
                </div>
            </div>

            <div class="col-md-4">
                <div class="small text-muted">Dicatat oleh</div>
                <div class="fw-semibold">
                    {{ $stokOpname->pencatat->name }}
                </div>
            </div>

            @if($stokOpname->keterangan)
            <div class="col-12">
                <div class="small text-muted">Keterangan Umum</div>
                <div class="fst-italic text-muted">
                    {{ $stokOpname->keterangan }}
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- DETAIL BARANG --}}
    <div class="card-body">

        <h6 class="fw-bold mb-3">Detail Stok Barang</h6>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th class="text-center">Stok Sistem</th>
                        <th class="text-center">Stok Fisik</th>
                        <th class="text-center">Selisih</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stokOpname->detail as $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $detail->barang->nama_barang }}</td>
                        <td>{{ $detail->barang->satuan }}</td>
                        <td class="text-center">{{ $detail->stok_sistem }}</td>
                        <td class="text-center">{{ $detail->stok_fisik }}</td>
                        <td class="text-center fw-bold">
                            @if ($detail->selisih > 0)
                                <span class="text-success">+{{ $detail->selisih }}</span>
                            @elseif ($detail->selisih < 0)
                                <span class="text-danger">{{ $detail->selisih }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td class="text-muted fst-italic">
                            {{ $detail->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    {{-- FOOTER / AKSI --}}
    <div class="card-footer border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">

        <a href="{{ route('stok-opname.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>

        <div class="d-flex gap-2">

            @if($stokOpname->status === 'draft')

                {{-- EDIT --}}
                <a href="{{ route('stok-opname.edit', $stokOpname->id) }}"
                   class="btn btn-warning">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </a>

                {{-- PROSES / FINAL --}}
                <form action="{{ route('stok-opname.final', $stokOpname->id) }}"
                      method="POST"
                      onsubmit="return confirm('Finalisasi stok opname? Stok akan disesuaikan dan tidak bisa diubah.')">
                    @csrf
                    <button class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Finalisasi
                    </button>
                </form>

            @endif

        </div>
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
        padding: 0.3em 1em;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(255, 193, 7, 0.15);
        border-radius: 0.375rem !important;
    }
    
    .badge.border-success-soft {
        background-color: rgba(40, 167, 69, 0.25) !important;
        color: #1e7e34 !important;
        border-color: #28a745 !important;
        border-width: 1px;
        border-style: solid;
        padding: 0.3em 1em;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(40, 167, 69, 0.15);
        border-radius: 0.375rem !important;
    }
</style>
@endsection