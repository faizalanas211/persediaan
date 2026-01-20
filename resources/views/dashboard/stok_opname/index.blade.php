@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active fw-semibold">
    Stok Opname
</li>
@endsection

@section('content')

<div class="card shadow-sm rounded-4">

    {{-- HEADER --}}
    <div class="card-header border-0 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">Stok Opname</h4>
            <small class="text-muted">Rekap stok akhir bulan</small>
        </div>

        <a href="{{ route('stok-opname.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Buat Stok Opname
        </a>
    </div>

    {{-- BODY --}}
    <div class="card-body">

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
                                    <span class="badge bg-warning rounded-pill px-3">Draft</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3">Final</span>
                                @endif
                            </td>

                            <td>
                                {{ $item->pencatat->name ?? '-' }}
                            </td>

                            <td class="text-center">
                                <a href="{{ route('stok-opname.show', $item->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> Detail
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

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
            <div class="text-muted fs-7">
                Menampilkan {{ $stokOpnames->count() }} dari {{ $stokOpnames->total() }} data
            </div>

            @if ($stokOpnames->hasPages())
                {{ $stokOpnames->links() }}
            @endif
        </div>

    </div>

</div>

@endsection
