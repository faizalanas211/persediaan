@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active fw-semibold">Permintaan ATK</li>
@endsection

@section('content')

<div class="card shadow-sm rounded-4">
    <div class="card-header border-0">
        <h4 class="fw-bold mb-0">Formulir Permintaan ATK</h4>
        <small class="text-muted">Dicatat langsung oleh admin</small>
    </div>

    <div class="card-body">

        <form action="{{ route('permintaan.store') }}" method="POST">
            @csrf

            {{-- PEGAWAI --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Pegawai</label>
                <select name="pegawai_id" class="form-select" required>
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach ($pegawais as $pegawai)
                        <option value="{{ $pegawai->id }}">
                            {{ $pegawai->nama }} ({{ $pegawai->nip }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- TANGGAL --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Permintaan</label>
                <input type="date" name="tanggal_permintaan"
                       class="form-control"
                       value="{{ date('Y-m-d') }}" required>
            </div>

            {{-- KEPERLUAN --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Keperluan</label>
                <input type="text" name="keperluan"
                       class="form-control"
                       placeholder="Contoh: Kegiatan rapat" required>
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
                                <input type="number" name="jumlah[]"
                                       class="form-control"
                                       min="1" required>
                            </td>
                            <td class="text-center">
                                <button type="button"
                                        class="btn btn-sm btn-light-danger"
                                        onclick="this.closest('tr').remove()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </td>
                        </tr>
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
                <textarea name="keterangan" rows="2"
                          class="form-control"
                          placeholder="Opsional"></textarea>
            </div>

            {{-- BUTTON --}}
            <div class="text-end">
                <a href="{{ route('permintaan.index') }}" class="btn btn-light">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    Simpan Permintaan
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
                <button type="button" class="btn btn-sm btn-light-danger"
                        onclick="this.closest('tr').remove()">
                    <i class="bi bi-x"></i>
                </button>
            </td>
        </tr>
    `);
}
</script>

@endsection
