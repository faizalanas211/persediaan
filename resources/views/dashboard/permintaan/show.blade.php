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

    <div class="card-header border-0">
        <h4 class="fw-bold mb-1 text-primary">Detail Permintaan ATK</h4>
        <small class="text-muted">
            Tanggal: {{ \Carbon\Carbon::parse($permintaan->tanggal_permintaan)->format('d M Y') }}
        </small>
    </div>

    <div class="card-body border-bottom">
        <div class="row g-3">

            <div class="col-md-6">
                <div class="small text-muted">Nama Pemohon</div>
                <div class="fw-semibold">{{ $permintaan->nama_pemohon }}</div>
            </div>

            <div class="col-md-6">
                <div class="small text-muted">Bagian</div>
                <div class="fw-semibold">{{ $permintaan->bagian_pemohon ?? '-' }}</div>
            </div>

            @if($permintaan->nip_pemohon)
            <div class="col-md-6">
                <div class="small text-muted">NIP</div>
                <div class="fw-semibold">{{ $permintaan->nip_pemohon }}</div>
            </div>
            @endif

            <div class="col-md-6">
                <div class="small text-muted">Dicatat oleh</div>
                <div class="fw-semibold">{{ $permintaan->pencatat->name }}</div>
            </div>

            <div class="col-12">
                <div class="small text-muted">Keperluan</div>
                <div class="fw-semibold">{{ $permintaan->keperluan }}</div>
            </div>

        </div>
    </div>

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
                        <td class="text-center fw-bold">{{ $item->jumlah }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <div class="card-footer border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">

        <span class="badge border-warning-soft">DRAFT</span>

        <div class="d-flex gap-2">

            <a href="{{ route('permintaan.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>

            @if($permintaan->status === 'draft')

                <a href="{{ route('permintaan.edit', $permintaan->id) }}"
                class="btn btn-warning shadow-sm">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </a>

                <form action="{{ route('permintaan.proses', $permintaan->id) }}"
                      method="POST">
                    @csrf
                    <button class="btn btn-success shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> Proses
                    </button>
                </form>

                <form action="{{ route('permintaan.destroy', $permintaan->id) }}"
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger shadow-sm">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </form>

            @endif
        </div>
    </div>
</div>

<style>
.text-primary{
    color:#6366f1 !important;
}

.badge.border-warning-soft{
    background: rgba(255,193,7,.2);
    color:#a16207;
    border:1px solid #ffc107;
    padding:.35em 1.2em;
}
</style>

@endsection
