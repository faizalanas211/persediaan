@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active fw-semibold text-primary">
    Data Barang ATK
</li>
@endsection

@section('content')

<div class="card card-flush shadow-sm rounded-4">

    {{-- ================= HEADER ================= --}}
    <div class="card-header border-0 pt-6 pb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

            {{-- JUDUL --}}
            <div>
                <h4 class="fw-bold mb-1 text-primary">Data Barang ATK</h4>
                <p class="text-muted mb-0">Daftar seluruh barang inventaris</p>
            </div>

            {{-- SEARCH + TOMBOL TAMBAH --}}
            <div class="d-flex align-items-center gap-2 flex-wrap" style="min-width: 300px;">
                <div class="position-relative flex-grow-1">
                    <i class="bx bx-search position-absolute top-50 start-0 translate-middle-y ms-3 text-primary fs-5"></i>
                    <input type="text"
                           id="searchBarang"
                           class="form-control rounded-pill ps-5 shadow-sm"
                           placeholder="Cari nama barang ..."
                           autocomplete="off"
                           value="{{ request('search') }}">
                </div>

                <button type="button"
                        id="resetSearch"
                        class="btn btn-outline-secondary rounded-pill px-3 py-2 d-none">
                    <i class="bx bx-x fs-5"></i>
                </button>

                <a href="{{ route('barang.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Data
                </a>
            </div>
        </div>
    </div>

    <div class="card-body pt-0">

        {{-- ================= TABLE ================= --}}
        <div class="table-responsive">
            <table class="table table-borderless align-middle table-hover">
                <thead class="border-bottom">
                    <tr class="text-uppercase text-muted fs-7">
                        <th width="5%">#</th>
                        <th class="sortable" data-sort="nama_barang">Nama Barang <i class="bx bx-sort-alt-2 ms-1"></i></th>
                        <th>Satuan</th>
                        <th class="sortable text-center" data-sort="stok">Stok <i class="bx bx-sort-alt-2 ms-1"></i></th>
                        <th class="text-center">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="border-top" id="barangTable">
                    @foreach ($barangs as $barang)
                    <tr>
                        <td>{{ $barangs->firstItem() + $loop->index }}</td>
                        <td class="fw-semibold">{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->satuan }}</td>
                        <td class="text-center fw-bold">{{ $barang->stok }}</td>

                        <td class="text-center">
                            @if ($barang->stok == 0)
                                <span class="badge border-danger-soft">Habis</span>
                            @elseif ($barang->stok <= 5)
                                <span class="badge border-warning-soft">Menipis</span>
                            @else
                                <span class="badge border-success-soft">Aman</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <a href="{{ route('barang.riwayat', $barang->id) }}"
                               class="btn btn-sm btn-light-info rounded-pill px-2">
                                <i class="bx bx-history"></i>
                            </a>
                            <a href="{{ route('barang.edit', $barang->id) }}"
                               class="btn btn-sm btn-light-primary rounded-pill px-2">
                                <i class="bx bx-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $barangs->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

<style>

/* ======== PRIMARY UNGU ======== */
.btn-primary{
    background: linear-gradient(135deg,#6366f1,#a855f7) !important;
    border:none !important;
}

.page-item.active .page-link{
    background: linear-gradient(135deg,#6366f1,#a855f7) !important;
    border:none !important;
}

.text-primary{
    color:#6366f1 !important;
}

/* header soft ungu */
.card-header{
    background: linear-gradient(180deg, rgba(99,102,241,.05), rgba(168,85,247,.03));
}

/* focus input */
#searchBarang:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 .2rem rgba(99,102,241,.15);
}

/* sortable hover */
.sortable:hover{
    color:#6366f1;
    cursor:pointer;
}

</style>

@endsection
