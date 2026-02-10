@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active text-primary fw-semibold">
    Permintaan ATK
</li>
@endsection

@section('content')
<div class="card shadow-sm rounded-4">

    {{-- HEADER --}}
    <div class="card-header border-0 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1 text-primary">Permintaan ATK</h4>
            <small class="text-muted">Daftar permintaan ATK yang dicatat admin</small>
        </div>
        <a href="{{ route('permintaan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Permintaan
        </a>
    </div>

    {{-- BODY --}}
    <div class="card-body">

        {{-- FILTER --}}
        <form method="GET" class="mb-4">
            <div class="row g-3 align-items-end">

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
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            Filter
                        </button>
                        <a href="{{ route('permintaan.index') }}"
                           class="btn btn-outline-secondary btn-sm">
                            Reset
                        </a>
                    </div>
                </div>

            </div>
        </form>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pemohon</th>
                        <th>Keperluan</th>
                        <th class="text-center">Jumlah Item</th>
                        <th>Status</th>
                        <th>Dicatat Oleh</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permintaan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_permintaan)->format('d M Y') }}</td>
                        <td>{{ $item->nama_pemohon ?? '-' }}</td>
                        <td>{{ $item->keperluan }}</td>
                        <td class="text-center">{{ $item->detail->sum('jumlah') }}</td>
                        <td>
                            @if($item->status === 'draft')
                                <span class="badge border-warning-soft">Draft</span>
                            @elseif($item->status === 'diproses')
                                <span class="badge border-success-soft">Diproses</span>
                            @else
                                <span class="badge border-secondary-soft">Dibatalkan</span>
                            @endif
                        </td>
                        <td>{{ $item->pencatat->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('permintaan.show', $item->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                Detail
                            </a>
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

        {{ $permintaan->links('pagination::bootstrap-5') }}

    </div>
</div>

<style>
.text-primary{ color:#6366f1 !important; }

.btn-primary{
    background: linear-gradient(135deg,#6366f1,#a855f7);
    border:none;
}

.form-control:focus,
.form-select:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 .2rem rgba(99,102,241,.15);
}
</style>

@endsection
