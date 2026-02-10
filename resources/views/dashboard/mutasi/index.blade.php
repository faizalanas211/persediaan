@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active text-primary fw-semibold">
    Mutasi Stok
</li>
@endsection

@section('content')

<div class="card card-flush shadow-sm rounded-4">

    {{-- HEADER --}}
    <div class="card-header border-0 pt-6 pb-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1 text-primary">Riwayat Mutasi Stok</h4>
            <p class="text-muted mb-0 fs-7">Catatan seluruh pergerakan stok barang ATK</p>
        </div>

        <a href="{{ route('mutasi.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Tambah Data Mutasi
        </a>
    </div>

    <div class="card-body pt-0">

        {{-- FILTER --}}
        <form method="GET" class="mb-4">
            <div class="row g-3 align-items-end mb-4 pb-3 border-bottom">

                <div class="col-lg-7 col-md-6">
                    <label class="form-label small fw-semibold mb-2">Jenis Mutasi</label>
                    <div class="d-flex flex-wrap gap-1">
                        @php $jenis = request('jenis','all'); @endphp

                        <a href="{{ request()->fullUrlWithQuery(['jenis'=>'all']) }}"
                           class="btn btn-sm btn-outline-secondary {{ $jenis=='all' ? 'active' : '' }}">
                           Semua
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['jenis'=>'masuk']) }}"
                           class="btn btn-sm btn-outline-success {{ $jenis=='masuk' ? 'active' : '' }}">
                           Masuk
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['jenis'=>'keluar']) }}"
                           class="btn btn-sm btn-outline-warning {{ $jenis=='keluar' ? 'active' : '' }}">
                           Keluar
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['jenis'=>'penyesuaian']) }}"
                           class="btn btn-sm btn-outline-info {{ $jenis=='penyesuaian' ? 'active' : '' }}">
                           Penyesuaian
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <label class="form-label small fw-semibold mb-2">Periode</label>
                    <input type="month" name="bulan"
                           value="{{ request('bulan') }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-lg-1 col-md-6">
                    <button class="btn btn-primary btn-sm w-100">Filter</button>
                </div>

            </div>
        </form>

        {{-- TABLE --}}
        <div class="table-responsive border rounded-3">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-uppercase text-muted fs-7">
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-center">Jumlah</th>
                        <th>Keterangan</th>
                        <th>Petugas</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($mutasi as $item)
                    <tr>
                        <td>{{ $mutasi->firstItem() + $loop->index }}</td>

                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td class="fw-semibold">{{ $item->barang->nama_barang }}</td>

                        <td class="text-center">
                            @if($item->jenis_mutasi=='masuk')
                                <span class="badge badge-soft-success">Masuk</span>
                            @elseif($item->jenis_mutasi=='keluar')
                                <span class="badge badge-soft-danger">Keluar</span>
                            @else
                                <span class="badge badge-soft-warning">Penyesuaian</span>
                            @endif
                        </td>

                        <td class="text-center fw-bold">
                            {{ $item->jumlah }}
                        </td>

                        <td>{{ $item->keterangan ?? '-' }}</td>
                        <td>{{ $item->user->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Belum ada data mutasi stok
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $mutasi->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

<style>
.text-primary{ color:#6366f1 !important; }

.btn-primary{
    background: linear-gradient(135deg,#6366f1,#a855f7);
    border:none;
}

.form-control:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 .2rem rgba(99,102,241,.15);
}

.badge-soft-success{
    background: rgba(25,135,84,.15);
    color:#198754;
    padding:5px 10px;
    border-radius:8px;
}

.badge-soft-danger{
    background: rgba(220,53,69,.15);
    color:#dc3545;
    padding:5px 10px;
    border-radius:8px;
}

.badge-soft-warning{
    background: rgba(255,193,7,.25);
    color:#b58900;
    padding:5px 10px;
    border-radius:8px;
}
</style>

@endsection
