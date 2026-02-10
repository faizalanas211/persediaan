@php
    use Carbon\Carbon;

    $today = Carbon::today()->format('Y-m-d');
    $periode = Carbon::today()->subMonth()->startOfMonth()->format('Y-m-d');
@endphp

@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('stok-opname.index') }}">Stok Opname</a>
</li>
<li class="breadcrumb-item active text-primary fw-semibold">Tambah Stok Opname</li>
@endsection

@section('content')

{{-- WARNING AUTO HIDE --}}
@if(session('warning'))
<div id="toastWarning" class="alert alert-warning shadow-sm">
    {{ session('warning') }}
</div>

<script>
setTimeout(function(){
    let el = document.getElementById('toastWarning');
    if(el){
        el.style.transition="opacity .5s";
        el.style.opacity=0;
        setTimeout(()=>el.remove(),500);
    }
},3000);
</script>
@endif

<div class="card shadow-sm rounded-4">

    <div class="card-header border-0 pt-6 pb-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1 text-primary">Stok Opname Akhir Bulan</h4>
            <p class="text-muted mb-0 fs-7">Isi stok fisik sesuai kondisi gudang</p>
        </div>

        <button class="btn btn-outline-success btn-sm shadow-sm"
                data-bs-toggle="modal"
                data-bs-target="#modalImportStokOpname">
            <i class="bx bx-upload me-1"></i> Import Excel
        </button>
    </div>

    <form action="{{ route('stok-opname.store') }}" method="POST">
        @csrf

        <div class="card-body">

            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Periode Bulan</label>
                    <input type="date"
                           name="periode_bulan"
                           class="form-control"
                           value="{{ old('periode_bulan',$periode) }}"
                           required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Opname</label>
                    <input type="date"
                           name="tanggal_opname"
                           class="form-control"
                           value="{{ old('tanggal_opname',$today) }}"
                           required>
                </div>
            </div>

            <h6 class="fw-bold mb-3 text-primary">Detail Barang</h6>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light-purple">
                        <tr>
                            <th>Barang</th>
                            <th width="150">Stok Sistem</th>
                            <th width="150">Stok Fisik</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($barangs as $i => $barang)
                        <tr>
                            <td>
                                <input type="hidden" name="barang_id[]" value="{{ $barang->id }}">
                                <strong>{{ $barang->nama_barang }}</strong><br>
                                <small class="text-muted">{{ $barang->satuan }}</small>
                            </td>

                            <td class="text-center fw-bold text-primary">
                                {{ $barang->stok }}
                            </td>

                            <td>
                                <input type="number"
                                       name="stok_fisik[]"
                                       class="form-control"
                                       min="0"
                                       value="{{ old('stok_fisik.'.$i) }}"
                                       required>
                            </td>

                            <td>
                                <input type="text"
                                       name="keterangan_detail[]"
                                       class="form-control"
                                       value="{{ old('keterangan_detail.'.$i) }}"
                                       placeholder="Jika ada selisih">
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <label class="form-label fw-semibold">Catatan Umum</label>
                <textarea name="keterangan"
                          class="form-control"
                          rows="2"
                          placeholder="Contoh: Selisih karena barang rusak / hilang">{{ old('keterangan') }}</textarea>
            </div>

        </div>

        <div class="card-footer border-0 text-end">
            <a href="{{ route('stok-opname.index') }}" class="btn btn-light">
                Batal
            </a>
            <button class="btn btn-primary px-4 shadow-sm">
                Simpan Stok Opname
            </button>
        </div>

    </form>
</div>

<style>
.text-primary{ color:#6366f1 !important; }

.btn-primary{
    background:linear-gradient(135deg,#6366f1,#a855f7);
    border:none;
}

.form-control:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 .2rem rgba(99,102,241,.15);
}

.table-light-purple{
    background:rgba(99,102,241,.06);
}

.table-hover tbody tr:hover{
    background:rgba(99,102,241,.04);
}
</style>

@endsection
