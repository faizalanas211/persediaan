@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active text-primary fw-semibold">
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
                <h4 class="fw-bold mb-1">Data Barang ATK</h4>
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
                <a href="{{ route('barang.create') }}" class="btn btn-primary">
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
                        <th class="sortable" data-sort="nama_barang">
                            Nama Barang
                            <i class="bx bx-sort-alt-2 ms-1 text-muted"></i>
                        </th>
                        <th>Satuan</th>
                        <th class="sortable text-center" data-sort="stok">
                            Stok
                            <i class="bx bx-sort-alt-2 ms-1 text-muted"></i>
                        </th>
                        <th class="text-center">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="border-top" id="barangTable">
                    @forelse ($barangs as $barang)
                        <tr>
                            <td>{{ $barangs->firstItem() + $loop->index }}</td>

                            <td class="fw-semibold">{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->satuan }}</td>

                            <td class="text-center fw-bold">
                                {{ $barang->stok }}
                            </td>

                            {{-- BADGE STATUS DENGAN WARNA SOFT --}}
                            <td class="text-center">
                                @if ($barang->stok == 0)
                                    <span class="badge border-danger-soft">
                                        Habis
                                    </span>
                                @elseif ($barang->stok <= 5)
                                    <span class="badge border-warning-soft">
                                        Menipis
                                    </span>
                                @else
                                    <span class="badge border-success-soft">
                                        Aman
                                    </span>
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
                                    @if ($barang->can_be_deleted)
                                        <button type="button"
                                                class="btn btn-sm btn-light-danger rounded-pill px-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalHapus{{ $barang->id }}"
                                                title="Hapus Barang">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    @else
                                        <button type="button"
                                                class="btn btn-sm btn-light-secondary rounded-pill px-2"
                                                disabled
                                                title="Barang sudah digunakan atau memiliki riwayat">
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
        @if($barangs->hasPages())
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top">
            <div class="mb-3 mb-md-0 text-muted">
                <span class="fw-medium">Menampilkan</span>
                <span class="fw-medium">{{ $barangs->firstItem() ?? 0 }}</span>
                <span class="fw-medium">sampai</span>
                <span class="fw-medium">{{ $barangs->lastItem() ?? 0 }}</span>
                <span class="fw-medium">dari</span>
                <span class="fw-medium">{{ $barangs->total() }}</span>
                <span class="fw-medium">data barang ATK</span>
            </div>
            
            <nav aria-label="Page navigation">
                <ul class="pagination mb-0">
                    <!-- First Page Link -->
                    @if(!$barangs->onFirstPage())
                    <li class="page-item">
                        <a class="page-link" href="{{ $barangs->url(1) }}" aria-label="First">
                            <i class="bx bx-chevrons-left"></i>
                        </a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <span class="page-link"><i class="bx bx-chevrons-left"></i></span>
                    </li>
                    @endif

                    <!-- Previous Page Link -->
                    @if($barangs->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link"><i class="bx bx-chevron-left"></i></span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $barangs->previousPageUrl() }}" aria-label="Previous">
                            <i class="bx bx-chevron-left"></i>
                        </a>
                    </li>
                    @endif

                    <!-- Page Numbers -->
                    @foreach($barangs->getUrlRange(max(1, $barangs->currentPage() - 2), min($barangs->lastPage(), $barangs->currentPage() + 2)) as $page => $url)
                    <li class="page-item {{ $page == $barangs->currentPage() ? 'active' : '' }}">
                        @if($page == $barangs->currentPage())
                        <span class="page-link">{{ $page }}</span>
                        @else
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    </li>
                    @endforeach

                    <!-- Next Page Link -->
                    @if($barangs->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $barangs->nextPageUrl() }}" aria-label="Next">
                            <i class="bx bx-chevron-right"></i>
                        </a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <span class="page-link"><i class="bx bx-chevron-right"></i></span>
                    </li>
                    @endif

                    <!-- Last Page Link -->
                    @if($barangs->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $barangs->url($barangs->lastPage()) }}" aria-label="Last">
                            <i class="bx bx-chevrons-right"></i>
                        </a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <span class="page-link"><i class="bx bx-chevrons-right"></i></span>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
        @elseif($barangs->total() > 0)
        <div class="mt-4 pt-3 border-top">
            <div class="text-center text-muted">
                Menampilkan semua {{ $barangs->total() }} data barang ATK
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchBarang');
    const resetButton = document.getElementById('resetSearch');
    const tableBody   = document.getElementById('barangTable');
    const sortableHeaders = document.querySelectorAll('.sortable');

    let delayTimer;
    let currentSort = 'nama_barang';
    let currentDirection = 'asc';

    // Tampilkan/menyembunyikan tombol reset berdasarkan input
    function toggleResetButton() {
        if (searchInput.value.trim() !== '') {
            resetButton.classList.remove('d-none');
        } else {
            resetButton.classList.add('d-none');
        }
    }

    // Load data dengan parameter search
    function loadData() {
        const keyword = searchInput.value.trim();

        fetch(`{{ route('barang.search') }}?q=${encodeURIComponent(keyword)}&sort=${currentSort}&direction=${currentDirection}`)
            .then(res => res.json())
            .then(data => {
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Data tidak ditemukan
                            </td>
                        </tr>`;
                    return;
                }

                data.forEach((item, index) => {
                    let statusBadge = '';
                    if (item.stok == 0) {
                        statusBadge = '<span class="badge border-danger-soft">Habis</span>';
                    } else if (item.stok <= 5) {
                        statusBadge = '<span class="badge border-warning-soft">Menipis</span>';
                    } else {
                        statusBadge = '<span class="badge border-success-soft">Aman</span>';
                    }

                    tableBody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td class="fw-semibold">${item.nama_barang}</td>
                            <td>${item.satuan}</td>
                            <td class="text-center fw-bold">${item.stok}</td>
                            <td class="text-center">${statusBadge}</td>
                            <td class="text-center">
                                <a href="/dashboard/barang/${item.id}/edit"
                                   class="btn btn-sm btn-light-primary rounded-pill px-2">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <a href="/dashboard/barang/${item.id}/riwayat"
                                   class="btn btn-sm btn-light-info rounded-pill px-2">
                                    <i class="bx bx-history"></i>
                                </a>
                            </td>
                        </tr>`;
                });
            });
    }

    // 🔍 SEARCH dengan debounce
    searchInput.addEventListener('keyup', function () {
        clearTimeout(delayTimer);
        toggleResetButton();
        delayTimer = setTimeout(loadData, 300);
    });

    // 🔄 RESET SEARCH
    resetButton.addEventListener('click', function () {
        window.location.href = "{{ route('barang.index') }}";
    });

    // 🔃 SORT
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function () {
            const sortField = this.dataset.sort;

            if (currentSort === sortField) {
                currentDirection = currentDirection === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = sortField;
                currentDirection = 'asc';
            }

            sortableHeaders.forEach(h =>
                h.querySelector('i').className = 'bx bx-sort-alt-2 ms-1 text-muted'
            );

            this.querySelector('i').className =
                currentDirection === 'asc'
                ? 'bx bx-sort-up ms-1'
                : 'bx bx-sort-down ms-1';

            loadData();
        });
    });

    // Inisialisasi tampilan tombol reset
    toggleResetButton();

});
</script>

<style>
.sortable {
    cursor: pointer;
}
.sortable:hover {
    color: #0d6efd;
}

/* Style untuk badge dengan warna soft */
.badge.border-danger-soft {
    background-color: rgba(220, 53, 69, 0.25) !important;
    color: #bd2130 !important;
    border-color: #dc3545 !important;
    border-width: 1px;
    border-style: solid;
    padding: 0.25em 0.6em;
    font-size: 0.8rem;
    font-weight: 600;
    box-shadow: 0 1px 2px rgba(220, 53, 69, 0.1);
    border-radius: 0.375rem !important;
}

.badge.border-warning-soft {
    background-color: rgba(255, 193, 7, 0.25) !important;
    color: #b58900 !important;
    border-color: #ffc107 !important;
    border-width: 1px;
    border-style: solid;
    padding: 0.25em 0.6em;
    font-size: 0.8rem;
    font-weight: 600;
    box-shadow: 0 1px 2px rgba(255, 193, 7, 0.1);
    border-radius: 0.375rem !important;
}

.badge.border-success-soft {
    background-color: rgba(40, 167, 69, 0.25) !important;
    color: #1e7e34 !important;
    border-color: #28a745 !important;
    border-width: 1px;
    border-style: solid;
    padding: 0.25em 0.6em;
    font-size: 0.8rem;
    font-weight: 600;
    box-shadow: 0 1px 2px rgba(40, 167, 69, 0.1);
    border-radius: 0.375rem !important;
}

/* Responsif untuk search area */
@media (max-width: 768px) {
    .d-flex.align-items-center.gap-2.flex-wrap {
        width: 100%;
        flex-direction: column;
        gap: 1rem !important;
    }
    
    .position-relative.flex-grow-1 {
        width: 100%;
    }
    
    #resetSearch {
        align-self: flex-end;
        margin-left: auto;
    }
    
    .btn-primary {
        width: 100%;
        justify-content: center;
    }
}

/* Tombol reset styling */
#resetSearch {
    min-width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50% !important;
    padding: 0;
}

#resetSearch:hover {
    background-color: #f8f9fa;
    border-color: #6c757d;
}
</style>
@endsection