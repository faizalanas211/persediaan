@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('stok-opname.index') }}">Stok Opname</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('stok-opname.show', $stokOpname->id) }}">Detail Stok Opname</a>
</li>
<li class="breadcrumb-item active text-primary fw-semibold">Edit Stok Opname</li>
@endsection

@section('content')

<div class="card shadow-sm rounded-4">
    <div class="card-header border-0">
        <h4 class="fw-bold mb-0">Edit Stok Opname</h4>
        <small class="text-muted">Perbarui data stok opname</small>
    </div>

    <form action="{{ route('stok-opname.update', $stokOpname->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            {{-- HEADER --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Periode Bulan</label>
                    <input type="date"
                           name="periode_bulan"
                           class="form-control"
                           value="{{ $stokOpname->periode_bulan }}"
                           required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Opname</label>
                    <input type="date"
                           name="tanggal_opname"
                           class="form-control"
                           value="{{ $stokOpname->tanggal_opname }}"
                           required>
                </div>
            </div>

            {{-- DETAIL --}}
            <h6 class="fw-bold mb-3">Detail Barang</h6>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Barang</th>
                            <th width="150">Stok Sistem</th>
                            <th width="150">Stok Fisik</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($details as $detail)
                        <tr>
                            <td>
                                <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">
                                <input type="hidden" name="barang_id[]" value="{{ $detail->barang_id }}">

                                <strong>{{ $detail->barang->nama_barang }}</strong><br>
                                <small class="text-muted">{{ $detail->barang->satuan }}</small>
                            </td>

                            <td class="text-center fw-bold">
                                {{ $detail->stok_sistem }}
                            </td>

                            <td>
                                <input type="number"
                                       name="stok_fisik[]"
                                       class="form-control"
                                       min="0"
                                       value="{{ $detail->stok_fisik }}"
                                       required>
                            </td>

                            <td>
                                <input type="text"
                                       name="keterangan_detail[]"
                                       class="form-control"
                                       value="{{ $detail->keterangan }}">
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{-- KETERANGAN --}}
            <div class="mt-3">
                <label class="form-label fw-semibold">Catatan Umum</label>
                <textarea name="keterangan"
                          class="form-control"
                          rows="2"
                          placeholder="Catatan tambahan">{{ $stokOpname->keterangan }}</textarea>
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="card-footer border-0 text-end">
            <a href="{{ route('stok-opname.index') }}" class="btn btn-light">
                Batal
            </a>
            <button class="btn btn-primary px-4">
                Update Stok Opname
            </button>
        </div>

    </form>
</div>

@endsection
