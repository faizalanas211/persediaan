@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('permintaan.index') }}">Permintaan ATK</a>
</li>
<li class="breadcrumb-item active fw-semibold">Edit Permintaan</li>
@endsection

@section('content')

<div class="card shadow-sm rounded-4">
    <div class="card-header border-0">
        <h4 class="fw-bold mb-0">Edit Permintaan ATK</h4>
        <small class="text-muted">Status: Draft</small>
    </div>

    <div class="card-body">

        <form action="{{ route('permintaan.update', $permintaan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nama Pemohon</label>
                    <input type="text"
                        name="nama_pemohon"
                        class="form-control"
                        value="{{ $permintaan->nama_pemohon }}"
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">NIP</label>
                    <input type="text"
                        name="nip_pemohon"
                        class="form-control"
                        value="{{ $permintaan->nip_pemohon }}"
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Bagian</label>
                    <input type="text"
                        name="bagian_pemohon"
                        class="form-control"
                        value="{{ $permintaan->bagian_pemohon }}"
                        required>
                </div>
            </div>

            {{-- TANGGAL --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Permintaan</label>
                <input type="date"
                       name="tanggal_permintaan"
                       class="form-control"
                       value="{{ $permintaan->tanggal_permintaan }}"
                       required>
            </div>

            {{-- KEPERLUAN --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Keperluan</label>
                <input type="text"
                       name="keperluan"
                       class="form-control"
                       value="{{ $permintaan->keperluan }}"
                       required>
            </div>

            {{-- BARANG --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Barang Diminta</label>

                <table class="table table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Barang</th>
                            <th width="120">Jumlah</th>
                            <th width="40"></th>
                        </tr>
                    </thead>
                    <tbody id="barang-wrapper">

                        @foreach ($permintaan->detail as $detail)
                        <tr>
                            <td>
                                <select name="barang_id[]" class="form-select" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach ($barangs as $barang)
                                        <option value="{{ $barang->id }}"
                                            {{ $barang->id == $detail->barang_id ? 'selected' : '' }}>
                                            {{ $barang->nama_barang }} (stok: {{ $barang->stok }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number"
                                       name="jumlah[]"
                                       class="form-control"
                                       min="1"
                                       value="{{ $detail->jumlah }}"
                                       required>
                            </td>
                            <td class="text-center">
                                <button type="button"
                                        class="btn btn-sm btn-light-danger"
                                        onclick="this.closest('tr').remove()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>

                <button type="button"
                        class="btn btn-sm btn-light-primary"
                        onclick="tambahBarang()">
                    + Tambah Barang
                </button>
            </div>

            {{-- KETERANGAN --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan"
                          rows="2"
                          class="form-control">{{ $permintaan->keterangan }}</textarea>
            </div>

            {{-- BUTTON --}}
            <div class="text-end">
                <a href="{{ route('permintaan.show', $permintaan->id) }}"
                   class="btn btn-light">
                    Batal
                </a>
                <button type="submit" class="btn btn-warning px-4">
                    Update Permintaan
                </button>
            </div>

        </form>
    </div>
</div>

<script>
function tambahBarang() {
    const wrapper = document.getElementById('barang-wrapper');
    wrapper.insertAdjacentHTML('beforeend', `
        <tr>
            <td>
                <select name="barang_id[]" class="form-select" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach ($barangs as $barang)
                        <option value="{{ $barang->id }}">
                            {{ $barang->nama_barang }} (stok: {{ $barang->stok }})
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="jumlah[]" class="form-control" min="1" required>
            </td>
            <td class="text-center">
                <button type="button"
                        class="btn btn-sm btn-light-danger"
                        onclick="this.closest('tr').remove()">
                    <i class="bi bi-x"></i>
                </button>
            </td>
        </tr>
    `);
}
</script>

@endsection
