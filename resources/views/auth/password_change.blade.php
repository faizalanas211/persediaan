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
    Ubah Kata Sandi
</li>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12 mx-auto">

        <div class="card card-flush shadow-sm rounded-4">
            <div class="card-header border-0 pt-6 pb-4">
                <div>
                    <h3 class="fw-bold mb-1">Ubah Kata Sandi</h3>
                    <p class="text-muted mb-0 fs-7">
                        Perbarui kata sandi Anda
                    </p>
                </div>
            </div>

            <div class="card-body pt-0">
                <form action="{{ route('password.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- PASSWORD LAMA --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Lama</label>
                        <input type="password"
                            name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            placeholder="Masukkan password lama">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PASSWORD BARU --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Password Baru 
                        </label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Masukkan kata sandi baru">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- KONFIRMASI PASSWORD --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Konfirmasi Password</label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="Ulangi password baru">
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
