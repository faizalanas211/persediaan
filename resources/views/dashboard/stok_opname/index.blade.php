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
                        <th width="15%" class="text-center">Aksi</th>
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
                                    <span class="badge badge-draft">Draft</span>
                                @else
                                    <span class="badge badge-final">Final</span>
                                @endif
                            </td>

                            <td>{{ $item->pencatat->name ?? '-' }}</td>

                            <td class="text-center">
                                <a href="{{ route('stok-opname.show', $item->id) }}"
                                   class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    Detail
                                </a>
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

</style>

@endsection
