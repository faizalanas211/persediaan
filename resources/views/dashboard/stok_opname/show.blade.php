@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('stok-opname.index') }}">Stok Opname</a>
</li>
<li class="breadcrumb-item active text-primary fw-semibold">
    Detail Stok Opname
</li>
@endsection

@section('content')

<div class="card shadow-sm rounded-4">

    {{-- ================= HEADER ================= --}}
    <div class="card-header border-0 d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1 text-primary">Detail Stok Opname</h4>
            <small class="text-muted">
                Periode:
                {{ \Carbon\Carbon::parse($stokOpname->periode_bulan)->translatedFormat('F Y') }}
            </small>
        </div>

        {{-- STATUS --}}
        <div>
            @if($stokOpname->status === 'draft')
                <span class="badge badge-draft">Draft</span>
            @else
                <span class="badge badge-final">Final</span>
            @endif
        </div>
    </div>

    {{-- ================= INFO ================= --}}
    <div class="card-body border-bottom">
        <div class="row g-3">

            <div class="col-md-4">
                <div class="small text-muted">Tanggal Opname</div>
                <div class="fw-semibold">
                    {{ \Carbon\Carbon::parse($stokOpname->tanggal_opname)->translatedFormat('d F Y') }}
                </div>
            </div>

            <div class="col-md-4">
                <div class="small text-muted">Dicatat oleh</div>
                <div class="fw-semibold">
                    {{ $stokOpname->pencatat->name }}
                </div>
            </div>

            @if($stokOpname->keterangan)
            <div class="col-12">
                <div class="small text-muted">Keterangan Umum</div>
                <div class="fst-italic text-muted">
                    {{ $stokOpname->keterangan }}
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- ================= DETAIL BARANG ================= --}}
    <div class="card-body">

        <h6 class="fw-bold mb-3 text-primary">Detail Stok Barang</h6>

        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light-purple">
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th class="text-center">Masuk</th>
                        <th class="text-center">Keluar</th>
                        <th class="text-center">Stok Sistem</th>
                        <th class="text-center">Stok Fisik</th>
                        <th class="text-center">Selisih</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stokOpname->detail as $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $detail->barang->nama_barang }}</td>
                        <td>{{ $detail->barang->satuan }}</td>
                        <td class="text-center text-success fw-semibold">
                            {{ $detail->total_masuk }}
                        </td>
                        <td class="text-center text-danger fw-semibold">
                            {{ $detail->total_keluar }}
                        </td>
                        <td class="text-center">{{ $detail->stok_sistem }}</td>
                        <td class="text-center">{{ $detail->stok_fisik }}</td>
                        <td class="text-center fw-bold">
                            @if ($detail->selisih > 0)
                                <span class="text-success">+{{ $detail->selisih }}</span>
                            @elseif ($detail->selisih < 0)
                                <span class="text-danger">{{ $detail->selisih }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td class="text-muted fst-italic">
                            {{ $detail->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    {{-- ================= FOOTER ================= --}}
    <div class="card-footer border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">

        <a href="{{ route('stok-opname.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>

        <div class="d-flex gap-2">

            @if($stokOpname->status === 'final')
                <a href="{{ route('stok-opname.export-excel', $stokOpname->id) }}"
                   class="btn btn-success">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
                </a>
            @endif

            @if($stokOpname->status === 'draft')

                <a href="{{ route('stok-opname.edit', $stokOpname->id) }}"
                   class="btn btn-warning">
                    <i class="bi bi-pencil-square me-1"></i>
                    Edit
                </a>

                <form action="{{ route('stok-opname.final', $stokOpname->id) }}"
                      method="POST">
                    @csrf
                    <button class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>
                        Finalisasi
                    </button>
                </form>

            @endif

        </div>
    </div>

</div>

<style>
.text-primary{ color:#6366f1 !important; }

.table-light-purple{
    background:rgba(99,102,241,.06);
}

.btn-primary{
    background:linear-gradient(135deg,#6366f1,#a855f7);
    border:none;
}

.table-hover tbody tr:hover{
    background:rgba(99,102,241,.03);
}

/* STATUS */
.badge-draft{
    background: rgba(255,193,7,.2);
    color:#a16207;
    border:1px solid #ffc107;
    padding:.3em 1em;
    font-weight:600;
    border-radius:6px;
}

.badge-final{
    background: rgba(25,135,84,.18);
    color:#198754;
    border:1px solid #198754;
    padding:.3em 1em;
    font-weight:600;
    border-radius:6px;
}
</style>

@endsection
