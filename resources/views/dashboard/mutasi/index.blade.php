@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active fw-semibold text-primary">
    Mutasi Stok
</li>
@endsection

@section('content')

<div class="card card-flush shadow-sm rounded-4">

    {{-- ================= HEADER ================= --}}
    <div class="card-header border-0 pt-6 pb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

            {{-- JUDUL --}}
            <div>
                <h4 class="fw-bold mb-1 text-primary">Riwayat Mutasi Stok</h4>
                <p class="text-muted mb-0">Catatan seluruh pergerakan stok barang ATK</p>
            </div>

            {{-- TOMBOL TAMBAH --}}
            <div>
                <a href="{{ route('mutasi.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Data
                </a>
            </div>

        </div>
    </div>

    <div class="card-body pt-0">

        {{-- ALERT SESSION --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                        <th class="text-center">Aksi</th>
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

                        {{-- TOMBOL HAPUS SIMPEL (ICON SAJA) --}}
                        <td class="text-center">
                            <button type="button"
                                    class="btn btn-sm btn-light text-danger border-0"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalHapusMutasi"
                                    data-id="{{ $item->id }}"
                                    data-barang="{{ $item->barang->nama_barang }}"
                                    data-jenis="{{ $item->jenis_mutasi }}"
                                    data-jumlah="{{ $item->jumlah }}"
                                    title="Hapus mutasi">
                                <i class="bi bi-trash fs-5"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
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

{{-- MODAL KONFIRMASI HAPUS MUTASI --}}
<div class="modal fade" id="modalHapusMutasi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Hapus Mutasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formHapusMutasi" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus mutasi ini?</p>
                    <div class="alert alert-warning">
                        <strong>Konsekuensi:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Stok barang akan <strong>dikembalikan</strong> ke jumlah sebelum mutasi</li>
                            <li>Data mutasi akan <strong>dihapus permanen</strong></li>
                            <li>Aksi ini <strong>tidak dapat dibatalkan</strong></li>
                        </ul>
                    </div>
                    <div class="mt-3">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="fw-semibold">Barang</td>
                                <td>: <span id="modalBarang"></span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Jenis Mutasi</td>
                                <td>: <span id="modalJenis"></span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Jumlah</td>
                                <td>: <span id="modalJumlah"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        <i class="bi bi-trash me-1"></i> Ya, Hapus
                    </button>
                </div>
            </form>
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

.alert {
    border-radius: 0.75rem;
    border-left: 4px solid;
}
.alert-success {
    border-left-color: #198754;
}
.alert-danger {
    border-left-color: #dc2626;
}
.alert-warning {
    border-left-color: #f59e0b;
}

/* Hover efek untuk tombol hapus */
.btn-light.text-danger:hover {
    background-color: #fee2e2 !important;
    color: #dc2626 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalHapusMutasi');
    const form = document.getElementById('formHapusMutasi');
    
    if (modal && form) {
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const barang = button.getAttribute('data-barang');
            const jenis = button.getAttribute('data-jenis');
            const jumlah = button.getAttribute('data-jumlah');
            
            // ✅ REVISI: URL lengkap dengan prefix dashboard
            form.action = '/dashboard/mutasi/' + id;
            
            // Set tampilan modal
            document.getElementById('modalBarang').textContent = barang;
            
            let jenisText = '';
            if (jenis === 'masuk') jenisText = 'Masuk (Stok akan berkurang)';
            else if (jenis === 'keluar') jenisText = 'Keluar (Stok akan bertambah)';
            else jenisText = 'Penyesuaian';
            document.getElementById('modalJenis').textContent = jenisText;
            
            document.getElementById('modalJumlah').textContent = jumlah;
        });
    }
});
</script>

@endsection