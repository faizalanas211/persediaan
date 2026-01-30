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

<div class="card shadow-sm rounded-4">
    <!-- <div class="card-header border-0">
        <h4 class="fw-bold mb-0">Stok Opname Akhir Bulan</h4>
        <small class="text-muted">Isi stok fisik sesuai kondisi gudang</small>
    </div> -->
    <div class="card-header border-0 pt-6 pb-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">Stok Opname Akhir Bulan</h4>
            <p class="text-muted mb-0 fs-7">Isi stok fisik sesuai kondisi gudang</p>
        </div>

        {{-- TOMBOL IMPORT KECIL --}}
        <button class="btn btn-outline-success btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#modalImportStokOpname">
            <i class="bx bx-upload me-1"></i> Import Excel
        </button>
    </div>

    <form action="{{ route('stok-opname.store') }}" method="POST">
        @csrf

        <div class="card-body">

            {{-- HEADER --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Periode Bulan</label>
                    <input type="date"
                           name="periode_bulan"
                           class="form-control"
                           value="{{ $periode }}"
                           required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Opname</label>
                    <input type="date"
                           name="tanggal_opname"
                           class="form-control"
                           value="{{ $tanggal }}"
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

                        @foreach ($barangs as $barang)
                        <tr>
                            <td>
                                <input type="hidden" name="barang_id[]" value="{{ $barang->id }}">
                                <strong>{{ $barang->nama_barang }}</strong><br>
                                <small class="text-muted">{{ $barang->satuan }}</small>
                            </td>

                            <td class="text-center fw-bold">
                                {{ $barang->stok }}
                            </td>

                            <td>
                                <input type="number"
                                       name="stok_fisik[]"
                                       class="form-control"
                                       min="0"
                                       required>
                            </td>

                            <td>
                                <input type="text"
                                       name="keterangan_detail[]"
                                       class="form-control"
                                       placeholder="Jika ada selisih">
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
                          placeholder="Contoh: Selisih karena barang rusak / hilang"></textarea>
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="card-footer border-0 text-end">
            <a href="{{ route('stok-opname.index') }}" class="btn btn-light">
                Batal
            </a>
            <button class="btn btn-primary px-4">
                Simpan Stok Opname
            </button>
        </div>

    </form>
</div>
{{-- ================= MODAL IMPORT EXCEL ================= --}}
<div class="modal fade" id="modalImportStokOpname" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Impor Data Stok Opname</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('stok-opname.import') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                    {{-- PERIODE & TANGGAL --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Periode Bulan</label>
                            <input type="date"
                                name="periode_bulan"
                                class="form-control"
                                value="{{ $periode }}"
                                required>
                            <small class="text-muted">Otomatis bulan sebelumnya</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Opname</label>
                            <input type="date"
                                name="tanggal_opname"
                                class="form-control"
                                value="{{ $today }}"
                                required>
                        </div>
                    </div>

                    {{-- FILE --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel</label>
                        <input type="file"
                               name="file"
                               class="form-control"
                               accept=".xls,.xlsx"
                               required>
                        <small class="text-muted">
                            Format .xls / .xlsx • Maks 2 MB
                        </small>
                    </div>

                    <a href="{{ asset('template/stok_opname.xlsx') }}"
                       class="text-primary fw-semibold text-decoration-none">
                        <i class="bx bx-download me-1"></i> Download Template Excel
                    </a>
                </div>

                <div class="modal-footer border-0">
                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button class="btn btn-success px-4">
                        Import
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
