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
                <div class="small text-muted">Pegawai</div>
                <div class="fw-semibold">
                    {{ $permintaan->pegawai->nama }}
                </div>
            </div>

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
    <div class="card-footer border-0 text-end">
        <a href="{{ route('permintaan.index') }}" class="btn btn-light">
            Kembali
        </a>
    </div>

</div>

@endsection
