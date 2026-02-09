@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('dashboard') }}" class="text-muted text-decoration-none">
        Dashboard
    </a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('profil.show', auth()->user()->id) }}" class="text-muted text-decoration-none">
        Profil Saya
    </a>
</li>
<li class="breadcrumb-item active text-primary fw-semibold">
    Edit Profil
</li>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12 mx-auto">

        <div class="card card-flush shadow-sm rounded-4">
            <div class="card-header border-0 pt-6 pb-4">
                <div>
                    <h3 class="fw-bold mb-1">Edit Profil</h3>
                    <p class="text-muted mb-0 fs-7">
                        Perbarui informasi akun Anda
                    </p>
                </div>
            </div>

            <div class="card-body pt-0">
                <form action="{{ route('profil.update', auth()->user()->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- NAMA --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', auth()->user()->name) }}"
                               placeholder="Masukkan nama lengkap">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email"
                               class="form-control"
                               value="{{ auth()->user()->email }}"
                               disabled>
                        <small class="text-muted">
                            Email tidak dapat diubah
                        </small>
                    </div>

                    {{-- JENIS KELAMIN --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki"
                                {{ old('jenis_kelamin', auth()->user()->pegawai?->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki
                            </option>
                            <option value="Perempuan"
                                {{ old('jenis_kelamin', auth()->user()->pegawai?->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>
                        @error('jenis_kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TEMPAT LAHIR --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tempat Lahir</label>
                        <input type="text"
                        name="tempat_lahir"
                        class="form-control"
                        value="{{ old('tempat_lahir', auth()->user()->pegawai?->tempat_lahir) }}">

                        @error('tempat_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TANGGAL LAHIR --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Lahir</label>
                        <input type="date"
                            name="tanggal_lahir"
                            class="form-control"
                            value="{{ old('tanggal_lahir', auth()->user()->pegawai?->tanggal_lahir) }}">

                        @error('tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- JABATAN --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jabatan</label>
                        <input type="text"
                            name="jabatan"
                            class="form-control"
                            value="{{ old('jabatan', auth()->user()->pegawai?->jabatan) }}">

                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PANGKAT GOLONGAN --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pangkat Golongan</label>
                        <input type="text"
                            name="pangkat_golongan"
                            class="form-control"
                            value="{{ old('pangkat_golongan', auth()->user()->pegawai?->pangkat_golongan) }}">

                        @error('pangkat_golongan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- FOTO --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Foto</label>
                        @if(optional(auth()->user()->pegawai)->foto)
                            <img src="{{ asset('storage/'.auth()->user()->pegawai->foto) }}"
                                class="rounded-circle mb-2"
                                width="80" height="80">
                        @endif

                        <input type="file" name="foto" class="form-control">

                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ACTION --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-light">
                            Batal
                        </a>
                        <button class="btn btn-primary px-4">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
