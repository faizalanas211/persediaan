@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active fw-semibold text-primary">
    Permintaan ATK
</li>
@endsection

@section('content')

<div class="card card-flush shadow-sm rounded-4">

    {{-- ================= HEADER ================= --}}
    <div class="card-header border-0 pt-6 pb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

            <div>
                <h4 class="fw-bold mb-1 text-primary">Permintaan ATK</h4>
                <p class="text-muted mb-0">Daftar permintaan ATK yang dicatat admin</p>
            </div>

            <a href="{{ route('permintaan.create') }}"
               class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg me-1"></i> Tambah Permintaan
            </a>
        </div>
    </div>

    {{-- ================= BODY ================= --}}
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
        <form method="GET" class="mb-4">
            <div class="row g-3 align-items-end border-bottom pb-3 mb-3">

                <div class="col-lg-3 col-md-6">
                    <label class="form-label small fw-semibold mb-2">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status')=='draft' ? 'selected' : '' }}>Draft</option>
                        <option value="diproses" {{ request('status')=='diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="dibatalkan" {{ request('status')=='dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label small fw-semibold mb-2">Periode</label>
                    <input type="month" name="periode" class="form-control form-control-sm"
                           value="{{ request('periode') }}">
                </div>

                <div class="col-lg-4 col-md-6">
                    <label class="form-label small fw-semibold mb-2">Cari</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Pemohon / Keperluan"
                           value="{{ request('search') }}">
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                        <a href="{{ route('permintaan.index') }}"
                           class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>
                </div>

            </div>
        </form>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="border-bottom">
                    <tr class="text-uppercase text-muted fs-7">
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pemohon</th>
                        <th>Keperluan</th>
                        <th class="text-center">Jumlah Item</th>
                        <th class="text-center">Status</th>
                        <th>Dicatat Oleh</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($permintaan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_permintaan)->format('d M Y') }}</td>
                        <td>{{ $item->nama_pemohon ?? '-' }}</td>
                        <td>{{ $item->keperluan }}</td>
                        <td class="text-center fw-bold">{{ $item->detail->sum('jumlah') }}</td>

                        <td class="text-center">
                            @if($item->status === 'draft')
                                <span class="badge-status status-warning">Draft</span>
                            @elseif($item->status === 'diproses')
                                <span class="badge-status status-success">Diproses</span>
                            @else
                                <span class="badge-status status-danger">Dibatalkan</span>
                            @endif
                        </td>

                        <td>{{ $item->pencatat->name ?? '-' }}</td>

                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('permintaan.show', $item->id) }}"
                                   class="btn btn-sm btn-light-primary rounded-pill px-3">
                                    Detail
                                </a>

                                {{-- TOMBOL HAPUS (hanya untuk draft) --}}
                                @if($item->status === 'draft')
                                    <button type="button"
                                            class="btn btn-sm btn-light text-danger border-0"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapusPermintaan"
                                            data-id="{{ $item->id }}"
                                            data-pemohon="{{ $item->nama_pemohon }}"
                                            data-keperluan="{{ $item->keperluan }}"
                                            title="Hapus permintaan">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            Belum ada permintaan ATK
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $permintaan->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

{{-- MODAL KONFIRMASI HAPUS PERMINTAAN (ONLY DRAFT) --}}
<div class="modal fade" id="modalHapusPermintaan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Hapus Permintaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formHapusPermintaan" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus permintaan ini?</p>
                    <div class="alert alert-warning">
                        <strong>Konsekuensi:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Data permintaan akan <strong>dihapus permanen</strong></li>
                            <li>Stok barang <strong>tidak berubah</strong> (karena status masih Draft)</li>
                            <li>Aksi ini <strong>tidak dapat dibatalkan</strong></li>
                        </ul>
                    </div>
                    <div class="mt-3">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="fw-semibold">Pemohon</td>
                                <td>: <span id="modalPemohon"></span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Keperluan</td>
                                <td>: <span id="modalKeperluan"></span></td>
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
.text-primary{ color:#6366f1 !important; }

.btn-primary{
    background: linear-gradient(135deg,#6366f1,#a855f7);
    border:none;
}

.card-header{
    background: linear-gradient(180deg, rgba(99,102,241,.05), rgba(168,85,247,.03));
}

/* STATUS BADGE */
.badge-status{
    padding:6px 14px;
    border-radius:999px;
    font-size:.75rem;
    font-weight:600;
}

.status-success{
    background:rgba(25,135,84,.15);
    color:#198754;
}

.status-warning{
    background:rgba(255,159,64,.20);
    color:#d97706;
}

.status-danger{
    background:rgba(220,53,69,.15);
    color:#dc3545;
}

.form-control:focus,
.form-select:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 .2rem rgba(99,102,241,.15);
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
    const modal = document.getElementById('modalHapusPermintaan');
    const form = document.getElementById('formHapusPermintaan');
    
    if (modal && form) {
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const pemohon = button.getAttribute('data-pemohon');
            const keperluan = button.getAttribute('data-keperluan');
            
            // Set action form
            form.action = '/dashboard/permintaan/' + id;
            
            // Set tampilan modal
            document.getElementById('modalPemohon').textContent = pemohon;
            document.getElementById('modalKeperluan').textContent = keperluan;
        });
    }
});
</script>

@endsection