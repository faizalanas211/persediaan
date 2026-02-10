@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('mutasi.index') }}" class="text-muted text-decoration-none">
        Mutasi Stok
    </a>
</li>
<li class="breadcrumb-item active text-primary fw-semibold">
    Tambah Mutasi Stok
</li>
@endsection

@section('content')

<div class="card shadow-sm rounded-4">

    {{-- HEADER --}}
    <div class="card-header border-0 pt-6 pb-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1 text-primary">Input Mutasi Stok</h4>
            <p class="text-muted mb-0 fs-7">Catat barang masuk, keluar, atau penyesuaian stok</p>
        </div>

        {{-- IMPORT --}}
        <button class="btn btn-outline-success btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#modalImportMutasi">
            <i class="bx bx-upload me-1"></i> Import Excel
        </button>
    </div>

    <div class="card-body pt-0">

        <form action="{{ route('mutasi.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="form-label fw-semibold">Barang</label>
                <select name="barang_id"
                        class="form-select @error('barang_id') is-invalid @enderror">
                    <option value="">-- Pilih Barang --</option>
                    @foreach ($barangs as $barang)
                        <option value="{{ $barang->id }}"
                            {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                            {{ $barang->nama_barang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Jenis Mutasi</label>
                <select name="jenis_mutasi"
                        class="form-select @error('jenis_mutasi') is-invalid @enderror">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="masuk">Barang Masuk</option>
                    <option value="keluar">Barang Keluar</option>
                    <option value="penyesuaian">Penyesuaian Stok</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Jumlah</label>
                <input type="number"
                       name="jumlah"
                       min="1"
                       value="{{ old('jumlah') }}"
                       class="form-control">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Tanggal</label>
                <input type="date"
                       name="tanggal"
                       value="{{ old('tanggal', date('Y-m-d')) }}"
                       class="form-control">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan"
                          rows="3"
                          class="form-control"
                          placeholder="Opsional">{{ old('keterangan') }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('mutasi.index') }}" class="btn btn-light px-4">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    Simpan Mutasi
                </button>
            </div>

        </form>

    </div>
</div>

{{-- MODAL IMPORT --}}
<div class="modal fade" id="modalImportMutasi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Impor Data Mutasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('mutasi.import') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel</label>
                        <input type="file" name="file" class="form-control">
                    </div>

                    <a href="{{ asset('template/mutasi.xlsx') }}"
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
                    <button class="btn btn-success">
                        Import
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
.text-primary{ color:#6366f1 !important; }

.btn-primary{
    background: linear-gradient(135deg,#6366f1,#a855f7);
    border:none;
}

.form-control:focus,
.form-select:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 .2rem rgba(99,102,241,.15);
}
</style>

@endsection
