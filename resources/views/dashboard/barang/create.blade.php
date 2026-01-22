@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('barang.index') }}" class="text-muted text-decoration-none">
        Data Barang ATK
    </a>
</li>
<li class="breadcrumb-item active text-primary fw-semibold">
    Tambah Barang ATK
</li>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12 mx-auto">

        {{-- ================= MANUAL INPUT + IMPORT ================= --}}
        <div class="card card-flush shadow-sm rounded-4">
            <div class="card-header border-0 pt-6 pb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">Tambah Barang</h3>
                    <p class="text-muted mb-0 fs-7">Input manual atau impor dari Excel</p>
                </div>

                {{-- TOMBOL IMPORT KECIL --}}
                <button class="btn btn-outline-success btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modalImportBarang">
                    <i class="bx bx-upload me-1"></i> Import Excel
                </button>
            </div>

            <div class="card-body pt-0">
                <form action="{{ route('barang.store') }}" method="POST">
                    @csrf

                    {{-- NAMA BARANG --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text"
                               name="nama_barang"
                               class="form-control @error('nama_barang') is-invalid @enderror"
                               value="{{ old('nama_barang') }}"
                               placeholder="Contoh: Kertas HVS A4">
                        @error('nama_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Satuan</label>
                        <select name="satuan"
                                id="satuan"
                                class="form-select @error('satuan') is-invalid @enderror"
                                onchange="toggleSatuanLainnya(this.value)">
                            <option value="">-- Pilih Satuan Terkecil --</option>
                            <option value="pcs">Pcs</option>
                            <option value="lembar">Lembar</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                        @error('satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 d-none" id="satuan_lainnya">
                        <label class="form-label fw-semibold">Satuan Lainnya</label>
                        <input type="text"
                            name="satuan_lainnya"
                            class="form-control"
                            placeholder="Contoh: roll, ml, gram">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Stok Awal</label>
                        <input type="number"
                            name="stok"
                            class="form-control @error('stok') is-invalid @enderror"
                            value="{{ old('stok', 0) }}"
                            min="0">
                        @error('stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ACTION --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('barang.index') }}" class="btn btn-light">
                            Batal
                        </a>
                        <button class="btn btn-primary px-4">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL IMPORT EXCEL ================= --}}
<div class="modal fade" id="modalImportBarang" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Impor Data Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('barang.import') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel</label>
                        <input type="file"
                               name="file"
                               class="form-control"
                               accept=".xls,.xlsx">
                        <small class="text-muted">
                            Format .xls / .xlsx • Maks 2 MB
                        </small>
                    </div>

                    <a href="{{ asset('template/persediaan.xlsx') }}"
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

<script>
function toggleSatuanLainnya(value) {
    const field = document.getElementById('satuan_lainnya');
    field.classList.toggle('d-none', value !== 'lainnya');
}
</script>
@endsection
