@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active fw-semibold">
    Permintaan ATK
</li>
@endsection

@section('content')

<div class="card shadow-sm rounded-4">

    {{-- HEADER --}}
    <div class="card-header border-0 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1">Permintaan ATK</h4>
            <small class="text-muted">Daftar permintaan ATK yang dicatat admin</small>
        </div>

        <a href="{{ route('permintaan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Permintaan
        </a>
    </div>

    {{-- BODY --}}
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Pegawai</th>
                        <th>Keperluan</th>
                        <th>Jumlah Item</th>
                        <th>Dicatat Oleh</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permintaan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_permintaan)->format('d M Y') }}</td>
                        <td>{{ $item->pegawai->nama ?? '-' }}</td>
                        <td>{{ $item->keperluan }}</td>
                        <td class="text-center">
                            {{ $item->detail->sum('jumlah') }}
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
                        <td colspan="7" class="text-center text-muted py-4">
                            Belum ada permintaan ATK
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
