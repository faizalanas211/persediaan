@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active text-primary fw-semibold">
    Mutasi Stok
</li>
@endsection

@section('content')

<div class="card card-flush shadow-sm rounded-4">

    {{-- ================= HEADER ================= --}}
    <div class="card-header border-0 pt-6 pb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-1">Riwayat Mutasi Stok</h3>
            <p class="text-muted mb-0 fs-7">
                Catatan seluruh pergerakan stok barang ATK
            </p>
        </div>

        <a href="{{ route('mutasi.create') }}"
           class="btn btn-primary px-4 rounded-pill">
            <i class="bx bx-plus me-1"></i> Input Mutasi
        </a>
    </div>

    <div class="card-body pt-0">
        {{-- ================= TABLE ================= --}}
        <div class="table-responsive">
            <table class="table table-borderless align-middle table-hover">
                <thead class="border-bottom">
                    <tr class="text-uppercase text-muted fs-7">
                        <th width="5%">#</th>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-center">Jumlah</th>
                        <th>Keterangan</th>
                        <th>Dicatat Oleh</th>
                    </tr>
                </thead>

                <tbody class="border-top">
                    @forelse ($mutasi as $item)
                        <tr>
                            <td>{{ $mutasi->firstItem() + $loop->index }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                            </td>

                            <td class="fw-semibold">
                                {{ $item->barang->nama_barang }}
                                <div class="text-muted fs-7">
                                    Satuan: {{ $item->barang->satuan }}
                                </div>
                            </td>

                            {{-- JENIS MUTASI --}}
                            <td class="text-center">
                                @if ($item->jenis_mutasi === 'masuk')
                                    <span class="badge bg-success">Masuk</span>
                                @elseif ($item->jenis_mutasi === 'keluar')
                                    <span class="badge bg-danger">Keluar</span>
                                @else
                                    <span class="badge bg-warning text-dark">Penyesuaian</span>
                                @endif
                            </td>

                            {{-- JUMLAH --}}
                            <td class="text-center fw-bold">
                                {{ $item->jumlah }}
                            </td>

                            <td>
                                {{ $item->keterangan ?? '-' }}
                            </td>

                            <td>
                                {{ $item->user->name }}
                            </td>
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

        {{-- ================= FOOTER + PAGINATION ================= --}}
        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
            <div class="text-muted fs-7">
                Menampilkan {{ $mutasi->count() }} dari {{ $mutasi->total() }} data mutasi
            </div>

            @if ($mutasi->hasPages())
                {{ $mutasi->links() }}
            @endif
        </div>

    </div>
</div>

@endsection
