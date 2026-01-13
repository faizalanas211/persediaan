@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('barang.index') }}">Barang ATK</a>
</li>
<li class="breadcrumb-item active fw-semibold">
    Riwayat Stok
</li>
@endsection

@section('content')

<div class="card shadow-sm rounded-4">

    {{-- HEADER --}}
    <div class="card-header border-0">
        <h4 class="fw-bold mb-1">{{ $barang->nama_barang }}</h4>
        <small class="text-muted">
            Riwayat pergerakan stok
        </small>
    </div>

    {{-- BODY --}}
    <div class="card-body">
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
                            @if ($item->jenis_mutasi === 'masuk')
                                <span class="badge bg-success">Masuk</span>
                            @elseif ($item->jenis_mutasi === 'keluar')
                                <span class="badge bg-warning text-dark">Keluar</span>
                            @else
                                <span class="badge bg-info">Penyesuaian</span>
                            @endif
                        </td>

                        <td class="fw-semibold">
                            {{ $item->jenis_mutasi === 'masuk' ? '+' : '-' }}
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
    </div>

    {{-- FOOTER --}}
    <div class="card-footer border-0 text-end">
        <a href="{{ route('barang.index') }}" class="btn btn-light">
            Kembali
        </a>
    </div>

</div>

@endsection
