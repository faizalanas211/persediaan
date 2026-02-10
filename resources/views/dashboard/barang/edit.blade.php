@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('barang.index') }}" class="text-muted text-decoration-none">
        Data Barang
    </a>
</li>
<li class="breadcrumb-item active fw-semibold text-primary">
    Edit Barang ATK
</li>
@endsection

@section('content')

<div class="card card-flush shadow-sm rounded-4">

    {{-- HEADER --}}
    <div class="card-header border-0 pt-6 pb-4">
        <h4 class="fw-bold mb-1 text-primary">Edit Data Barang ATK</h4>
        <p class="text-muted mb-0 fs-7">Perbarui informasi master barang</p>
    </div>

    <div class="card-body pt-0">

        <form action="{{ route('barang.update', $barang->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- NAMA BARANG --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Nama Barang</label>
                <input type="text"
                       name="nama_barang"
                       value="{{ old('nama_barang', $barang->nama_barang) }}"
                       class="form-control @error('nama_barang') is-invalid @enderror"
                       placeholder="Contoh: Kertas HVS A4 80 gsm">
            </div>

            {{-- SATUAN --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Satuan Terkecil</label>
                <select name="satuan"
                        id="satuan"
                        class="form-select">
                    <option value="">-- Pilih Satuan --</option>
                    <option value="pcs" {{ old('satuan', $barang->satuan)=='pcs'?'selected':'' }}>Pcs</option>
                    <option value="lembar" {{ old('satuan', $barang->satuan)=='lembar'?'selected':'' }}>Lembar</option>
                    <option value="lainnya" {{ old('satuan', $barang->satuan)=='lainnya'?'selected':'' }}>Lainnya</option>
                </select>
            </div>

            {{-- SATUAN LAINNYA --}}
            <div class="mb-4 {{ old('satuan', $barang->satuan)=='lainnya' ? '' : 'd-none' }}"
                 id="satuanLainnyaWrapper">
                <label class="form-label fw-semibold">Satuan Lainnya</label>
                <input type="text"
                       name="satuan_lainnya"
                       value="{{ old('satuan_lainnya', $barang->satuan_lainnya ?? '') }}"
                       class="form-control">
            </div>

            {{-- BUTTON --}}
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('barang.index') }}" class="btn btn-light rounded-pill px-4">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>

<style>
.btn-primary{
    background: linear-gradient(135deg,#6366f1,#a855f7) !important;
    border:none !important;
}

.text-primary{
    color:#6366f1 !important;
}

.card-header{
    background: linear-gradient(180deg, rgba(99,102,241,.05), rgba(168,85,247,.03));
}

.form-control:focus,
.form-select:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 .2rem rgba(99,102,241,.15);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const satuanSelect = document.getElementById('satuan');
    const lainnyaWrap  = document.getElementById('satuanLainnyaWrapper');

    function toggleSatuanLainnya(){
        lainnyaWrap.classList.toggle('d-none', satuanSelect.value !== 'lainnya');
    }

    toggleSatuanLainnya();
    satuanSelect.addEventListener('change', toggleSatuanLainnya);
});
</script>

@endsection
