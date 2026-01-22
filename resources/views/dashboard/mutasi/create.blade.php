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
    <div class="card-header border-0 pt-6 pb-4">
        <h4 class="fw-bold mb-1">Input Mutasi Stok</h4>
        <p class="text-muted mb-0 fs-7">
            Catat barang masuk, keluar, atau penyesuaian stok
        </p>
    </div>

    <div class="card-body pt-0">

        <form action="{{ route('mutasi.store') }}" method="POST">
            @csrf

            {{-- BARANG --}}
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
                @error('barang_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- JENIS MUTASI --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Jenis Mutasi</label>
                <select name="jenis_mutasi"
                        class="form-select @error('jenis_mutasi') is-invalid @enderror">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="masuk" {{ old('jenis_mutasi') == 'masuk' ? 'selected' : '' }}>
                        Barang Masuk
                    </option>
                    <option value="keluar" {{ old('jenis_mutasi') == 'keluar' ? 'selected' : '' }}>
                        Barang Keluar
                    </option>
                    <option value="penyesuaian" {{ old('jenis_mutasi') == 'penyesuaian' ? 'selected' : '' }}>
                        Penyesuaian Stok
                    </option>
                </select>
                @error('jenis_mutasi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- JUMLAH --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Jumlah</label>
                <input type="number"
                       name="jumlah"
                       min="1"
                       value="{{ old('jumlah') }}"
                       class="form-control @error('jumlah') is-invalid @enderror">
                @error('jumlah')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- TANGGAL --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Tanggal</label>
                <input type="date"
                       name="tanggal"
                       value="{{ old('tanggal', date('Y-m-d')) }}"
                       class="form-control @error('tanggal') is-invalid @enderror">
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- KETERANGAN --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan"
                          rows="3"
                          class="form-control @error('keterangan') is-invalid @enderror"
                          placeholder="Opsional">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- BUTTON --}}
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('barang.index') }}" class="btn btn-light px-4">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    Simpan Mutasi
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
