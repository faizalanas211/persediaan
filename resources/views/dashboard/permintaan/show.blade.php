@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('permintaan.index') }}">Permintaan ATK</a>
</li>
<li class="breadcrumb-item active text-primary fw-semibold">
    Detail Permintaan ATK
</li>
@endsection

@section('content')

<div class="card shadow-sm rounded-4">

    {{-- HEADER --}}
    <div class="card-header border-0">
        <h4 class="fw-bold mb-1">Detail Permintaan ATK</h4>
        <small class="text-muted">
            Tanggal: {{ \Carbon\Carbon::parse($permintaan->tanggal_permintaan)->format('d M Y') }}
        </small>
    </div>

    {{-- INFO --}}
    <div class="card-body border-bottom">
        <div class="row g-3">

            <div class="col-md-6">
                <div class="small text-muted">Nama Pemohon</div>
                <div class="fw-semibold">
                    {{ $permintaan->nama_pemohon }}
                </div>
            </div>

            <div class="col-md-6">
                <div class="small text-muted">Bagian</div>
                <div class="fw-semibold">
                    {{ $permintaan->bagian_pemohon ?? '-' }}
                </div>
            </div>

            @if($permintaan->nip_pemohon)
            <div class="col-md-6">
                <div class="small text-muted">NIP</div>
                <div class="fw-semibold">
                    {{ $permintaan->nip_pemohon }}
                </div>
            </div>
            @endif

            <div class="col-md-6">
                <div class="small text-muted">Dicatat oleh</div>
                <div class="fw-semibold">
                    {{ $permintaan->pencatat->name }}
                </div>
            </div>

            <div class="col-12">
                <div class="small text-muted">Keperluan</div>
                <div class="fw-semibold">
                    {{ $permintaan->keperluan }}
                </div>
            </div>

            @if($permintaan->keterangan)
            <div class="col-12">
                <div class="small text-muted">Keterangan</div>
                <div class="fst-italic text-muted">
                    {{ $permintaan->keterangan }}
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- DETAIL BARANG --}}
    <div class="card-body">

        <h6 class="fw-bold mb-3">Daftar Barang Diminta</h6>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th class="text-center">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permintaan->detail as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $item->barang->nama_barang }}</td>
                        <td>{{ $item->barang->satuan }}</td>
                        <td class="text-center fw-bold">
                            {{ $item->jumlah }} 
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    {{-- FOOTER --}}
    <div class="card-footer border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">

        {{-- STATUS DENGAN BADGE SOFT --}}
        <div>
            @if($permintaan->status === 'draft')
                <span class="badge border-warning-soft" style="background-color: rgba(255, 193, 7, 0.25); color: #b58900; border-color: #ffc107; font-size: 0.9rem; padding: 0.35em 1.2em; border-width: 1px; border-style: solid;">
                    Draft
                </span>
            @elseif($permintaan->status === 'diproses')
                <span class="badge border-success-soft" style="background-color: rgba(40, 167, 69, 0.25); color: #1e7e34; border-color: #28a745; font-size: 0.9rem; padding: 0.35em 1.2em; border-width: 1px; border-style: solid;">
                    Diproses
                </span>
            @else
                <span class="badge border-secondary-soft" style="background-color: rgba(108, 117, 125, 0.25); color: #495057; border-color: #6c757d; font-size: 0.9rem; padding: 0.35em 1.2em; border-width: 1px; border-style: solid;">
                    Dibatalkan
                </span>
            @endif
        </div>

        {{-- AKSI --}}
        <div class="d-flex gap-2">

            {{-- KEMBALI --}}
            <a href="{{ route('permintaan.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>

            @if($permintaan->status === 'draft')

                {{-- EDIT --}}
                <a href="{{ route('permintaan.edit', $permintaan->id) }}"
                class="btn btn-warning">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </a>

                {{-- PROSES --}}
                <form action="{{ route('permintaan.proses', $permintaan->id) }}"
                    method="POST"
                    onsubmit="return confirm('Proses permintaan ini? Stok akan berkurang dan tidak bisa diubah.')">
                    @csrf
                    <button class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Proses
                    </button>
                </form>

                {{-- HAPUS --}}
                <form action="{{ route('permintaan.destroy', $permintaan->id) }}"
                    method="POST"
                    onsubmit="return confirm('Yakin hapus permintaan ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Hapus
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
        padding: 0.35em 1.2em;
        font-size: 0.9rem;
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
        padding: 0.35em 1.2em;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(40, 167, 69, 0.15);
        border-radius: 0.375rem !important;
    }
    
    .badge.border-secondary-soft {
        background-color: rgba(108, 117, 125, 0.25) !important;
        color: #495057 !important;
        border-color: #6c757d !important;
        border-width: 1px;
        border-style: solid;
        padding: 0.35em 1.2em;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(108, 117, 125, 0.15);
        border-radius: 0.375rem !important;
    }
</style>
@endsection