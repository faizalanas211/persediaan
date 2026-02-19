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

@if(session('warning'))
<div id="toastWarning" class="alert alert-warning shadow-sm">
    {{ session('warning') }}
</div>
@endif

<div class="card shadow-sm rounded-4">

    <div class="card-header border-0 pt-6 pb-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1 text-primary">Stok Opname Akhir Bulan</h4>
            <p class="text-muted mb-0 fs-7">Isi stok fisik sesuai kondisi gudang</p>
        </div>

        {{-- BUTTON IMPORT --}}
        <button class="btn btn-outline-success btn-sm rounded-pill"
                data-bs-toggle="modal"
                data-bs-target="#modalImportStokOpname">
            <i class="bx bx-upload me-1"></i> Import Excel
        </button>
    </div>

    {{-- ================= FORM MANUAL ================= --}}
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
            <a href="{{ route('stok-opname.index') }}" class="btn btn-light rounded-pill px-4">
                Batal
            </a>
            <button class="btn btn-primary rounded-pill px-4">
                Simpan Stok Opname
            </button>
        </div>

    </form>
</div>

{{-- ================= MODAL IMPORT ================= --}}
<div class="modal fade" id="modalImportStokOpname" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-primary">
                    Import Stok Opname Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- FORM IMPORT --}}
            <form action="{{ route('stok-opname.import') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="importForm">
                @csrf

                {{-- IMPORTANT: HARUS DI DALAM FORM --}}
                <input type="hidden"
                       name="periode_bulan"
                       id="import_periode_bulan">

                <div class="modal-body">
                    <input type="file"
                           name="file"
                           class="form-control"
                           accept=".xls,.xlsx"
                           required>

                    <small class="text-muted">
                        Format .xls / .xlsx • Maks 2 MB
                    </small>

                    <a href="{{ route('stok-opname.template') }}"
                       class="text-primary fw-semibold text-decoration-none d-block mt-3">
                        <i class="bx bx-download me-1"></i> Download Template Excel
                    </a>
                </div>

                <div class="modal-footer border-0">
                    <button type="button"
                            class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button class="btn btn-success rounded-pill px-4">
                        Import
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ================= JS SYNC PERIODE ================= --}}
<script>
document.addEventListener("DOMContentLoaded", function(){

    const periodeInput = document.querySelector("input[name='periode_bulan']");
    const importPeriode = document.getElementById("import_periode_bulan");

    function syncPeriode(){
        importPeriode.value = periodeInput.value;
    }

    syncPeriode();
    periodeInput.addEventListener("change", syncPeriode);
});
</script>

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
</style>

@endsection
