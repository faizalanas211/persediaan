@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('permintaan.index') }}">Permintaan ATK</a>
</li>
<li class="breadcrumb-item active text-primary fw-semibold">
    Detail Permintaan ATK
</li>
@endsection

@section('content')

<div class="card shadow-sm rounded-4">

    <div class="card-header border-0">
        <h4 class="fw-bold mb-1 text-primary">Detail Permintaan ATK</h4>
        <small class="text-muted">
            Tanggal: {{ \Carbon\Carbon::parse($permintaan->tanggal_permintaan)->format('d M Y') }}
        </small>
    </div>

    <div class="card-body border-bottom">
        <div class="row g-3">

            <div class="col-md-6">
                <div class="small text-muted">Nama Pemohon</div>
                <div class="fw-semibold">{{ $permintaan->nama_pemohon }}</div>
            </div>

            <div class="col-md-6">
                <div class="small text-muted">Bagian</div>
                <div class="fw-semibold">{{ $permintaan->bagian_pemohon ?? '-' }}</div>
            </div>

            @if($permintaan->nip_pemohon)
            <div class="col-md-6">
                <div class="small text-muted">NIP</div>
                <div class="fw-semibold">{{ $permintaan->nip_pemohon }}</div>
            </div>
            @endif

            <div class="col-md-6">
                <div class="small text-muted">Dicatat oleh</div>
                <div class="fw-semibold">{{ $permintaan->pencatat->name }}</div>
            </div>

            <div class="col-12">
                <div class="small text-muted">Keperluan</div>
                <div class="fw-semibold">{{ $permintaan->keperluan }}</div>
            </div>

        </div>
    </div>

    <div class="card-body">

        <h6 class="fw-bold mb-3">Daftar Barang Diminta</h6>

        {{-- ✅ TAMBAHAN: Alert error dari session (jika stok tidak cukup) --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- ✅ TAMBAHAN: Alert validation error (jika ada error dari controller) --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Gagal memproses permintaan:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th class="text-center">Jumlah</th>
                        {{-- ✅ TAMBAHAN: Kolom Stok Tersedia --}}
                        <th class="text-center">Stok Tersedia</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permintaan->detail as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $item->barang->nama_barang }}</td>
                        <td>{{ $item->barang->satuan }}</td>
                        <td class="text-center fw-bold">{{ $item->jumlah }}</td>
                        
                        {{-- ✅ TAMBAHAN: Stok tersedia --}}
                        <td class="text-center">{{ $item->barang->stok }}</td>
                        
                        {{-- ✅ TAMBAHAN: Status kecukupan stok --}}
                        <td class="text-center">
                            @if($item->barang->stok >= $item->jumlah)
                                <span class="badge bg-success">Cukup</span>
                            @else
                                <span class="badge bg-danger">Stok Kurang</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <div class="card-footer border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">

        <span class="badge border-warning-soft">DRAFT</span>

        <div class="d-flex gap-2">

            <a href="{{ route('permintaan.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>

            @if($permintaan->status === 'draft')
                <a href="{{ route('permintaan.edit', $permintaan->id) }}"
                   class="btn btn-warning shadow-sm">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </a>

                {{-- ✅ TAMBAHAN: Form proses dengan alert konfirmasi & cek stok --}}
                <form action="{{ route('permintaan.proses', $permintaan->id) }}"
                      method="POST"
                      id="formProses">
                    @csrf
                    <button type="submit" class="btn btn-success shadow-sm" id="btnProses">
                        <i class="bi bi-check-circle me-1"></i> Proses
                    </button>
                </form>

                <form action="{{ route('permintaan.destroy', $permintaan->id) }}"
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger shadow-sm">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </form>

            @endif
        </div>
    </div>
</div>

<style>
.text-primary{
    color:#6366f1 !important;
}

.badge.border-warning-soft{
    background: rgba(255,193,7,.2);
    color:#a16207;
    border:1px solid #ffc107;
    padding:.35em 1.2em;
}

/* ✅ TAMBAHAN: Style untuk alert */
.alert {
    border-radius: 0.75rem;
    border-left: 4px solid;
}
.alert-danger {
    border-left-color: #dc2626;
}
</style>

{{-- ✅ TAMBAHAN: JavaScript untuk konfirmasi dan cek stok sebelum submit --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const formProses = document.getElementById('formProses');
    const btnProses = document.getElementById('btnProses');
    
    if (formProses) {
        formProses.addEventListener('submit', function(e) {
            // Cek apakah ada barang dengan stok kurang
            let adaStokKurang = false;
            const statusBadges = document.querySelectorAll('tbody tr .badge');
            
            statusBadges.forEach(function(badge) {
                if (badge.textContent === 'Stok Kurang') {
                    adaStokKurang = true;
                }
            });
            
            if (adaStokKurang) {
                e.preventDefault();
                alert('❌ Tidak dapat memproses permintaan! Ada barang dengan stok tidak mencukupi.\n\nSilakan edit permintaan atau batalkan.');
                return false;
            }
            
            // Konfirmasi sebelum proses
            if (!confirm('Yakin akan memproses permintaan ini?\n\nStok barang akan berkurang sesuai permintaan.')) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>

@endsection