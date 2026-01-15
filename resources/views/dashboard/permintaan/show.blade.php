@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('permintaan.index') }}">Permintaan ATK</a>
</li>
<li class="breadcrumb-item active fw-semibold">
    Detail Permintaan
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

        {{-- STATUS --}}
        <div>
            @if($permintaan->status === 'draft')
                <span class="badge bg-warning rounded-pill px-3">Draft</span>
            @elseif($permintaan->status === 'diproses')
                <span class="badge bg-success rounded-pill px-3">Diproses</span>
            @else
                <span class="badge bg-secondary rounded-pill px-3">Dibatalkan</span>
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

@endsection
