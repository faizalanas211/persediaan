@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('barang.index') }}">Barang ATK</a>
</li>
<li class="breadcrumb-item active text-primary fw-semibold">
    Riwayat Stok
</li>
@endsection

@section('content')

<div class="card shadow-sm rounded-4">

    {{-- HEADER --}}
    <div class="card-header border-0 pt-6 pb-4">
        <h4 class="fw-bold mb-1 text-primary">{{ $barang->nama_barang }}</h4>
        <small class="text-muted">Riwayat pergerakan stok</small>
    </div>

    {{-- BODY --}}
    <div class="card-body">

        {{-- FILTER --}}
        <form method="GET" class="mb-4">
            <div class="row g-3 align-items-end">

                <div class="col-lg-7">
                    <label class="form-label small fw-semibold mb-2">Jenis Mutasi</label>
                    <div class="d-flex flex-wrap gap-1">
                        @php $jenis = request('jenis','all'); @endphp

                        <a href="?jenis=all&bulan={{ request('bulan') }}"
                           class="btn btn-sm btn-outline-secondary {{ $jenis=='all' ? 'active-purple' : '' }}">
                            Semua
                        </a>

                        <a href="?jenis=masuk&bulan={{ request('bulan') }}"
                           class="btn btn-sm btn-outline-secondary {{ $jenis=='masuk' ? 'active-purple' : '' }}">
                            Masuk
                        </a>

                        <a href="?jenis=keluar&bulan={{ request('bulan') }}"
                           class="btn btn-sm btn-outline-secondary {{ $jenis=='keluar' ? 'active-purple' : '' }}">
                            Keluar
                        </a>

                        <a href="?jenis=penyesuaian&bulan={{ request('bulan') }}"
                           class="btn btn-sm btn-outline-secondary {{ $jenis=='penyesuaian' ? 'active-purple' : '' }}">
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
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Stok Awal</th>
                        <th>Stok Akhir</th>
                        <th>Keterangan</th>
                        <th>Petugas</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($mutasi as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>

                        <td>
                            @if ($item->jenis_mutasi=='masuk')
                                <span class="badge badge-soft-success">Masuk</span>
                            @elseif ($item->jenis_mutasi=='keluar')
                                <span class="badge badge-soft-warning">Keluar</span>
                            @else
                                <span class="badge badge-soft-info">Penyesuaian</span>
                            @endif
                        </td>

                        <td class="fw-semibold">
                            {{ $item->jumlah }}
                        </td>

                        <td class="text-center">{{ $item->stok_awal }}</td>
                        <td class="text-center fw-bold">{{ $item->stok_akhir }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Belum ada mutasi stok
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
.text-primary{
    color:#6366f1 !important;
}

.btn-primary{
    background: linear-gradient(135deg,#6366f1,#a855f7);
    border:none;
}

.active-purple{
    background: linear-gradient(135deg,#6366f1,#a855f7);
    color:white !important;
    border:none !important;
}

.form-control:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 .2rem rgba(99,102,241,.15);
}

.badge-soft-success{
    background: rgba(25,135,84,.15);
    color:#198754;
    border-radius:8px;
    padding:5px 10px;
}

.badge-soft-warning{
    background: rgba(255,193,7,.2);
    color:#b58900;
    border-radius:8px;
    padding:5px 10px;
}

.badge-soft-info{
    background: rgba(13,202,240,.2);
    color:#0dcaf0;
    border-radius:8px;
    padding:5px 10px;
}
</style>

@endsection
