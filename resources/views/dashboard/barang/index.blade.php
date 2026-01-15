@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active text-primary fw-semibold">
    Data Barang
</li>
@endsection

@section('content')

<div class="card card-flush shadow-sm rounded-4">

    {{-- ================= HEADER ================= --}}
    <div class="card-header border-0 pt-6 pb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-1">Data Barang ATK</h3>
            <p class="text-muted mb-0 fs-7">Daftar seluruh barang inventaris</p>
        </div>
        <a href="{{ route('barang.create') }}"
           class="btn btn-primary px-4 rounded-pill">
            <i class="bx bx-plus me-1"></i> Tambah Data
        </a>
    </div>

    <div class="card-body pt-0">

        {{-- ================= TABLE ================= --}}
        <div class="table-responsive">
            <table class="table table-borderless align-middle table-hover">
                <thead class="border-bottom">
                    <tr class="text-uppercase text-muted fs-7">
                        <th width="5%">#</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="border-top">
                    @forelse ($barangs as $barang)
                        <tr class="
                            {{ $barang->stok == 0 ? 'table-danger' : '' }}
                            {{ $barang->stok > 0 && $barang->stok <= 5 ? 'table-warning' : '' }}
                        ">
                            <td>{{ $barangs->firstItem() + $loop->index }}</td>

                            <td class="fw-semibold">{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->satuan }}</td>

                            <td class="text-center fw-bold">
                                {{ $barang->stok }}
                            </td>

                            <td class="text-center">
                                @if ($barang->stok == 0)
                                    <span class="badge bg-danger rounded-pill">Habis</span>
                                @elseif ($barang->stok <= 5)
                                    <span class="badge bg-warning text-dark rounded-pill">Menipis</span>
                                @else
                                    <span class="badge bg-success rounded-pill">Aman</span>
                                @endif
                            </td>

                            {{-- ===== AKSI ===== --}}
                            <td class="text-center">
                                <div class="d-inline-flex align-items-center gap-1">

                                    {{-- RIWAYAT STOK --}}
                                    <a href="{{ route('barang.riwayat', $barang->id) }}"
                                    class="btn btn-sm btn-light-info rounded-pill px-2"
                                    title="Riwayat Stok">
                                        <i class="bx bx-history"></i>
                                    </a>

                                    {{-- EDIT MASTER BARANG --}}
                                    <a href="{{ route('barang.edit', $barang->id) }}"
                                    class="btn btn-sm btn-light-primary rounded-pill px-2"
                                    title="Edit Barang">
                                        <i class="bx bx-edit"></i>
                                    </a>

                                    {{-- DELETE --}}
                                    @if ($barang->detail_permintaan_exists)
                                        <button type="button"
                                                class="btn btn-sm btn-light-secondary rounded-pill px-2"
                                                title="Barang sudah memiliki riwayat, tidak dapat dihapus"
                                                disabled>
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    @else
                                        <button type="button"
                                                class="btn btn-sm btn-light-danger rounded-pill px-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalHapus{{ $barang->id }}"
                                                title="Hapus Barang">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalHapus{{ $barang->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content border-0 rounded-4 shadow">

                                    {{-- HEADER --}}
                                    <div class="modal-header border-0 justify-content-center pb-0">
                                        <div class="text-center">
                                            <i class="bi bi-exclamation-triangle-fill text-danger fs-1 mb-2"></i>
                                            <h5 class="fw-bold text-danger mb-0">Hapus Barang</h5>
                                        </div>
                                    </div>

                                    {{-- BODY --}}
                                    <div class="modal-body text-center pt-2">
                                        <p class="mb-3">
                                            Yakin ingin menghapus barang berikut?
                                        </p>

                                        <div class="card border border-danger bg-light mb-3">
                                            <div class="card-body py-2">
                                                <div class="fw-bold text-dark">
                                                    {{ $barang->nama_barang }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-warning small p-2 mb-3">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Tindakan ini tidak dapat dibatalkan
                                        </div>

                                        {{-- CHECKBOX KONFIRMASI --}}
                                        <div class="form-check d-flex justify-content-center gap-2">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="confirmDelete{{ $barang->id }}">
                                            <label class="form-check-label small text-muted"
                                                for="confirmDelete{{ $barang->id }}">
                                                Saya yakin ingin menghapus data ini
                                            </label>
                                        </div>
                                    </div>

                                    {{-- FOOTER --}}
                                    <div class="modal-footer border-0 justify-content-center gap-2 pt-0 pb-4">
                                        <button type="button"
                                                class="btn btn-outline-secondary px-4 rounded-pill"
                                                data-bs-dismiss="modal">
                                            Batal
                                        </button>

                                        <form action="{{ route('barang.destroy', $barang->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-danger px-4 rounded-pill"
                                                    id="submitDelete{{ $barang->id }}"
                                                    disabled>
                                                <i class="bi bi-trash me-1"></i>Hapus
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const checkbox = document.getElementById('confirmDelete{{ $barang->id }}');
                            const button   = document.getElementById('submitDelete{{ $barang->id }}');

                            checkbox.addEventListener('change', function () {
                                button.disabled = !this.checked;
                            });
                        });
                        </script>

                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada data barang ATK
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ================= FOOTER + PAGINATION ================= --}}
        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
            <div class="text-muted fs-7">
                Menampilkan {{ $barangs->count() }} dari {{ $barangs->total() }} data barang
            </div>

            <div>
                @if ($barangs->hasPages())
                <nav>
                    <ul class="pagination mb-0">

                        {{-- Previous --}}
                        @if ($barangs->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $barangs->previousPageUrl() }}">&laquo;</a>
                            </li>
                        @endif

                        {{-- Page ±1 --}}
                        @foreach ($barangs->getUrlRange(
                            max($barangs->currentPage()-1,1),
                            min($barangs->currentPage()+1, $barangs->lastPage())
                        ) as $page => $url)
                            @if ($page == $barangs->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if ($barangs->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $barangs->nextPageUrl() }}">&raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                        @endif

                    </ul>
                </nav>
                @endif
            </div>
        </div>

    </div>
</div>
<script>
document.addEventListener('contextmenu', e => e.preventDefault());

document.addEventListener('keydown', function(e) {
    if (
        e.key === 'PrintScreen' ||
        (e.ctrlKey && e.key === 'p') ||
        (e.ctrlKey && e.key === 's')
    ) {
        e.preventDefault();
        alert('Aksi ini tidak diizinkan');
    }
});
</script>

@endsection
