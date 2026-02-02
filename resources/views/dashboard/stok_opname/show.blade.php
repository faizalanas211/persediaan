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
            <h4 class="fw-bold mb-1">Detail Stok Opname</h4>
            <small class="text-muted">
                Periode:
                {{ \Carbon\Carbon::parse($stokOpname->periode_bulan)->translatedFormat('F Y') }}
            </small>
        </div>

        {{-- STATUS --}}
        <div>
            @if($stokOpname->status === 'draft')
                <span class="badge border-warning-soft">Draft</span>
            @else
                <span class="badge border-success-soft">Final</span>
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

        <h6 class="fw-bold mb-3">Detail Stok Barang</h6>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
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

    {{-- ================= FOOTER / AKSI ================= --}}
    <div class="card-footer border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">

        <a href="{{ route('stok-opname.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>

        <div class="d-flex gap-2">

            {{-- EXPORT EXCEL (HANYA FINAL) --}}
            @if($stokOpname->status === 'final')
                <a href="{{ route('stok-opname.export-excel', $stokOpname->id) }}"
                   class="btn btn-success">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
                </a>
            @endif

            {{-- AKSI DRAFT --}}
            @if($stokOpname->status === 'draft')

                <a href="{{ route('stok-opname.edit', $stokOpname->id) }}"
                   class="btn btn-warning">
                    <i class="bi bi-pencil-square me-1"></i>
                    Edit
                </a>

                <form action="{{ route('stok-opname.final', $stokOpname->id) }}"
                      method="POST"
                      onsubmit="return confirm(
                        'Finalisasi stok opname?\n\n' +
                        '• Data tidak dapat diubah\n' +
                        '• Stok sistem akan disesuaikan\n\n' +
                        'Lanjutkan?'
                      )">
                    @csrf
                    <button class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>
                        Finalisasi
                    </button>
                </form>

            @endif

        </div>
    </div>

</div>

{{-- ================= STYLE ================= --}}
<style>
.badge.border-warning-soft{
    background-color: rgba(255, 193, 7, 0.25);
    color: #b58900;
    border:1px solid #ffc107;
    padding:0.3em 1em;
    font-size:0.85rem;
    font-weight:600;
    border-radius:0.375rem;
}

.badge.border-success-soft{
    background-color: rgba(40, 167, 69, 0.25);
    color:#1e7e34;
    border:1px solid #28a745;
    padding:0.3em 1em;
    font-size:0.85rem;
    font-weight:600;
    border-radius:0.375rem;
}
</style>

@endsection
