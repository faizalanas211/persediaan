@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('dashboard') }}" class="text-muted text-decoration-none">
        Dashboard
    </a>
</li>
<li class="breadcrumb-item active text-primary fw-semibold">
    Profil Saya
</li>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12 mx-auto">

        <div class="card card-flush shadow-sm rounded-4">
            <div class="card-header border-0 pt-6 pb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">Profil Pengguna</h3>
                    <p class="text-muted mb-0 fs-7">
                        Informasi akun dan data pegawai
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('profil.edit', auth()->id()) }}"
                       class="btn btn-primary btn-sm">
                        <i class="bx bx-edit me-1"></i> Edit Profil
                    </a>

                    <a href="{{ route('password.edit') }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-lock me-1"></i> Ubah Kata Sandi
                    </a>
                </div>
            </div>

            <div class="card-body pt-0">

                {{-- FOTO PROFIL --}}
                <div class="text-center mb-4">
                    @php
                        $pegawai = auth()->user()->pegawai;
                    @endphp

                    @if($pegawai && $pegawai->foto)
                        <img src="{{ asset('storage/'.$pegawai->foto) }}"
                             class="rounded-circle shadow"
                             width="120" height="120"
                             style="object-fit:cover">
                    @else
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center shadow"
                             style="width:120px;height:120px;">
                            <i class="bx bx-user text-secondary" style="font-size:48px"></i>
                        </div>
                    @endif

                    <h5 class="fw-bold mt-3 mb-0">
                        {{ auth()->user()->name }}
                    </h5>
                    <span class="text-muted fs-7">
                        {{ auth()->user()->email }}
                    </span>
                </div>

                <hr>

                {{-- DATA PROFIL --}}
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="text-muted fs-7">Jenis Kelamin</label>
                        <div class="fw-semibold">
                            {{ $pegawai->jenis_kelamin ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted fs-7">Tempat, Tanggal Lahir</label>
                        <div class="fw-semibold">
                            {{ $pegawai->tempat_lahir ?? '-' }},
                            {{ $pegawai && $pegawai->tanggal_lahir
                                ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('d M Y')
                                : '-' }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted fs-7">Jabatan</label>
                        <div class="fw-semibold">
                            {{ $pegawai->jabatan ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted fs-7">Pangkat / Golongan</label>
                        <div class="fw-semibold">
                            {{ $pegawai->pangkat_golongan ?? '-' }}
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

@endsection
