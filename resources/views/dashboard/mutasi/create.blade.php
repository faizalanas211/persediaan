@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('mutasi.index') }}" class="text-muted text-decoration-none">
        Mutasi Stok
    </a>
</li>
<li class="breadcrumb-item active text-primary fw-semibold">
    Tambah Mutasi Stok
</li>
@endsection

@section('content')

<div class="card shadow-sm rounded-4">

    {{-- HEADER --}}
    <div class="card-header border-0 pt-6 pb-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-1 text-primary">Input Mutasi Stok</h4>
            <p class="text-muted mb-0 fs-7">Catat barang masuk, keluar, atau penyesuaian stok</p>
        </div>

        {{-- IMPORT --}}
        <button class="btn btn-outline-success btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#modalImportMutasi">
            <i class="bx bx-upload me-1"></i> Import Excel
        </button>
    </div>

    <div class="card-body pt-0">

        {{-- ✅ TAMBAHAN: Alert error dari session --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- ✅ TAMBAHAN: Alert validation error --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Gagal menyimpan mutasi:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('mutasi.store') }}" method="POST" id="formMutasi">
            @csrf

            <div class="mb-4">
                <label class="form-label fw-semibold">Barang</label>
                <select name="barang_id"
                        id="barang_id"
                        class="form-select @error('barang_id') is-invalid @enderror">
                    <option value="">-- Pilih Barang --</option>
                    @foreach ($barangs as $barang)
                        <option value="{{ $barang->id }}"
                            data-stok="{{ $barang->stok }}"
                            data-satuan="{{ $barang->satuan }}"
                            {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                            {{ $barang->nama_barang }} (Stok: {{ $barang->stok }} {{ $barang->satuan }})
                        </option>
                    @endforeach
                </select>
                
                {{-- ✅ TAMBAHAN: Info stok yang dipilih --}}
                <div id="infoStok" class="mt-2 small d-none">
                    <div class="alert alert-info py-2 px-3 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Stok saat ini: <strong id="stokSaatIni">0</strong> 
                        <span id="satuanBarang"></span>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Jenis Mutasi</label>
                <select name="jenis_mutasi"
                        id="jenis_mutasi"
                        class="form-select @error('jenis_mutasi') is-invalid @enderror">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="masuk" {{ old('jenis_mutasi') == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                    <option value="keluar" {{ old('jenis_mutasi') == 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
                    <option value="penyesuaian" {{ old('jenis_mutasi') == 'penyesuaian' ? 'selected' : '' }}>Penyesuaian Stok</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Jumlah</label>
                <input type="number"
                       name="jumlah"
                       id="jumlah"
                       min="1"
                       value="{{ old('jumlah') }}"
                       class="form-control @error('jumlah') is-invalid @enderror">
                
                {{-- ✅ TAMBAHAN: Pesan peringatan stok --}}
                <div id="warningStok" class="mt-2 small d-none">
                    <div class="alert alert-warning py-2 px-3 mb-0">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <span id="warningText"></span>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Tanggal</label>
                <input type="date"
                       name="tanggal"
                       value="{{ old('tanggal', date('Y-m-d')) }}"
                       class="form-control @error('tanggal') is-invalid @enderror">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan"
                          rows="3"
                          class="form-control"
                          placeholder="Opsional">{{ old('keterangan') }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('mutasi.index') }}" class="btn btn-light px-4">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary px-4" id="btnSimpan">
                    Simpan Mutasi
                </button>
            </div>

        </form>

    </div>
</div>

{{-- MODAL IMPORT --}}
<div class="modal fade" id="modalImportMutasi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Impor Data Mutasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('mutasi.import') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel</label>
                        <input type="file" name="file" class="form-control">
                    </div>

                    <a href="{{ asset('template/mutasi.xlsx') }}"
                       class="text-primary fw-semibold text-decoration-none">
                        <i class="bx bx-download me-1"></i> Download Template Excel
                    </a>
                </div>

                <div class="modal-footer border-0">
                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button class="btn btn-success">
                        Import
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

.form-control:focus,
.form-select:focus{
    border-color:#6366f1;
    box-shadow:0 0 0 .2rem rgba(99,102,241,.15);
}

.alert {
    border-radius: 0.75rem;
    border-left: 4px solid;
}
.alert-danger {
    border-left-color: #dc2626;
}
.alert-warning {
    border-left-color: #f59e0b;
}
.alert-info {
    border-left-color: #6366f1;
}
</style>

{{-- ✅ TAMBAHAN: JavaScript untuk validasi real-time --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const barangSelect = document.getElementById('barang_id');
    const jenisMutasi = document.getElementById('jenis_mutasi');
    const jumlahInput = document.getElementById('jumlah');
    const infoStokDiv = document.getElementById('infoStok');
    const stokSaatIniSpan = document.getElementById('stokSaatIni');
    const satuanBarangSpan = document.getElementById('satuanBarang');
    const warningStokDiv = document.getElementById('warningStok');
    const warningTextSpan = document.getElementById('warningText');
    const formMutasi = document.getElementById('formMutasi');
    const btnSimpan = document.getElementById('btnSimpan');

    let stokTersedia = 0;
    let satuan = '';

    // Fungsi update info stok
    function updateInfoStok() {
        const selectedOption = barangSelect.options[barangSelect.selectedIndex];
        
        if (barangSelect.value && selectedOption) {
            stokTersedia = parseInt(selectedOption.dataset.stok) || 0;
            satuan = selectedOption.dataset.satuan || '';
            
            stokSaatIniSpan.textContent = stokTersedia;
            satuanBarangSpan.textContent = satuan;
            infoStokDiv.classList.remove('d-none');
        } else {
            infoStokDiv.classList.add('d-none');
            stokTersedia = 0;
        }
        
        // Update validasi stok
        validateStok();
    }

    // Fungsi validasi stok untuk mutasi keluar
    function validateStok() {
        const jenis = jenisMutasi.value;
        const jumlah = parseInt(jumlahInput.value) || 0;
        
        warningStokDiv.classList.add('d-none');
        
        if (jenis === 'keluar' && jumlah > 0 && stokTersedia > 0) {
            if (jumlah > stokTersedia) {
                warningTextSpan.innerHTML = `Stok tidak mencukupi! Stok saat ini: ${stokTersedia} ${satuan}. Maksimal pengeluaran: ${stokTersedia} ${satuan}.`;
                warningStokDiv.classList.remove('d-none');
            } else if (jumlah === stokTersedia) {
                warningTextSpan.innerHTML = `Peringatan: Stok akan habis setelah transaksi ini (${stokTersedia} ${satuan}).`;
                warningStokDiv.classList.remove('d-none');
            }
        } else if (jenis === 'keluar' && jumlah > 0 && stokTersedia === 0) {
            warningTextSpan.innerHTML = `Stok sedang kosong (0 ${satuan}). Tidak bisa melakukan transaksi keluar.`;
            warningStokDiv.classList.remove('d-none');
        }
    }

    // Fungsi cek sebelum submit
    function cekSebelumSubmit() {
        const jenis = jenisMutasi.value;
        const jumlah = parseInt(jumlahInput.value) || 0;
        
        // Cek barang dipilih
        if (!barangSelect.value) {
            alert('❌ Silakan pilih barang terlebih dahulu!');
            return false;
        }
        
        // Cek jenis mutasi dipilih
        if (!jenis) {
            alert('❌ Silakan pilih jenis mutasi!');
            return false;
        }
        
        // Cek jumlah
        if (jumlah < 1) {
            alert('❌ Jumlah harus minimal 1!');
            return false;
        }
        
        // Cek stok untuk mutasi keluar
        if (jenis === 'keluar' && jumlah > stokTersedia) {
            alert(`❌ Stok tidak mencukupi! Stok saat ini: ${stokTersedia} ${satuan}.`);
            return false;
        }
        
        // Konfirmasi
        let pesan = '';
        if (jenis === 'masuk') {
            pesan = `Barang akan bertambah ${jumlah} ${satuan}. Lanjutkan?`;
        } else if (jenis === 'keluar') {
            pesan = `Barang akan berkurang ${jumlah} ${satuan}. Stok akhir: ${stokTersedia - jumlah} ${satuan}. Lanjutkan?`;
        } else {
            pesan = `Stok akan disesuaikan menjadi ${jumlah} ${satuan}. Lanjutkan?`;
        }
        
        return confirm(pesan);
    }

    // Event listeners
    barangSelect.addEventListener('change', updateInfoStok);
    jenisMutasi.addEventListener('change', validateStok);
    jumlahInput.addEventListener('input', validateStok);
    formMutasi.addEventListener('submit', function(e) {
        if (!cekSebelumSubmit()) {
            e.preventDefault();
        }
    });
    
    // Trigger awal
    updateInfoStok();
});
</script>

@endsection