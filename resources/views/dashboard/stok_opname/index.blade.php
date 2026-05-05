@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active text-primary fw-semibold">
    Stok Opname
</li>
@endsection

@section('content')

<div class="card card-flush shadow-sm rounded-4">

    {{-- ================= HEADER ================= --}}
    <div class="card-header border-0 pt-6 pb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

            <div>
                <h4 class="fw-bold mb-1 text-primary">Stok Opname</h4>
                <p class="text-muted mb-0">Rekap stok akhir bulan</p>
            </div>

            <a href="{{ route('stok-opname.create') }}"
               class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg me-1"></i> Buat Stok Opname
            </a>

        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body pt-0">

        {{-- ALERT SESSION --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- FILTER --}}
        <form method="GET" class="row g-2 align-items-end mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Periode</label>
                <input type="month"
                       name="periode"
                       class="form-control"
                       value="{{ request('periode') }}">
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary px-4">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>

                <a href="{{ route('stok-opname.index') }}"
                   class="btn btn-outline-secondary">
                    Reset
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Periode</th>
                        <th>Tanggal Opname</th>
                        <th>Status</th>
                        <th>Dicatat Oleh</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($stokOpnames as $item)
                        <tr>
                            <td>{{ $stokOpnames->firstItem() + $loop->index }}</td>

                            <td class="fw-semibold">
                                {{ \Carbon\Carbon::parse($item->periode_bulan)->translatedFormat('F Y') }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal_opname)->format('d M Y') }}
                            </td>

                            <td>
                                @if ($item->status === 'draft')
                                    <span class="badge-draft">Draft</span>
                                @else
                                    <span class="badge-final">Final</span>
                                @endif
                            </td>

                            <td>{{ $item->pencatat->name ?? '-' }}</td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('stok-opname.show', $item->id) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Detail
                                    </a>

                                    {{-- TOMBOL HAPUS (hanya untuk status draft) --}}
                                    @if($item->status === 'draft')
                                        <button type="button"
                                                class="btn btn-sm btn-light text-danger border-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalHapusStokOpname"
                                                data-id="{{ $item->id }}"
                                                data-periode="{{ \Carbon\Carbon::parse($item->periode_bulan)->translatedFormat('F Y') }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_opname)->format('d M Y') }}"
                                                title="Hapus stok opname">
                                            <i class="bi bi-trash fs-5"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada data stok opname
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{ $stokOpnames->links('pagination::bootstrap-5') }}

    </div>
</div>

{{-- MODAL KONFIRMASI HAPUS STOK OPNAME (ONLY DRAFT) --}}
<div class="modal fade" id="modalHapusStokOpname" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Hapus Stok Opname
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formHapusStokOpname" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus stok opname ini?</p>
                    <div class="alert alert-warning">
                        <strong>Konsekuensi:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Data stok opname akan <strong>dihapus permanen</strong></li>
                            <li>Stok barang <strong>tidak berubah</strong> (karena status masih Draft)</li>
                            <li>Aksi ini <strong>tidak dapat dibatalkan</strong></li>
                        </ul>
                    </div>
                    <div class="mt-3">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="fw-semibold">Periode</td>
                                <td>: <span id="modalPeriode"></span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Tanggal Opname</td>
                                <td>: <span id="modalTanggal"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        <i class="bi bi-trash me-1"></i> Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>

/* PRIMARY UNGU */
.text-primary{
    color:#6366f1 !important;
}

.btn-primary{
    background: linear-gradient(135deg,#6366f1,#a855f7);
    border:none;
}

.card-header{
    background: linear-gradient(180deg, rgba(99,102,241,.05), rgba(168,85,247,.03));
}

.form-control:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 .2rem rgba(99,102,241,.15);
}

.table-hover tbody tr:hover{
    background:rgba(99,102,241,.03);
}

/* STATUS BADGE */
.badge-draft{
    background: rgba(255,193,7,.2);
    color:#a16207;
    border:1px solid #ffc107;
    padding:.3em .9em;
    border-radius:10px;
    font-weight:600;
}

.badge-final{
    background: rgba(25,135,84,.18);
    color:#198754;
    border:1px solid #198754;
    padding:.3em .9em;
    border-radius:10px;
    font-weight:600;
}

.alert {
    border-radius: 0.75rem;
    border-left: 4px solid;
}
.alert-success {
    border-left-color: #198754;
}
.alert-danger {
    border-left-color: #dc2626;
}
.alert-warning {
    border-left-color: #f59e0b;
}

/* Hover efek untuk tombol hapus */
.btn-light.text-danger:hover {
    background-color: #fee2e2 !important;
    color: #dc2626 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalHapusStokOpname');
    const form = document.getElementById('formHapusStokOpname');
    
    if (modal && form) {
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const periode = button.getAttribute('data-periode');
            const tanggal = button.getAttribute('data-tanggal');
            
            // Set action form
            form.action = '/dashboard/stok-opname/' + id;
            
            // Set tampilan modal
            document.getElementById('modalPeriode').textContent = periode;
            document.getElementById('modalTanggal').textContent = tanggal;
        });
    }
});
</script>

@endsection