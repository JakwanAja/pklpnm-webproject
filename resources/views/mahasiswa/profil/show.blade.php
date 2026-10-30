@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Profil</li>
@endsection

@section('content')
<div class="py-4">
    <div class="row g-4">

        {{-- Kolom kiri: foto & info singkat --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-5">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:90px;height:90px;">
                        <iconify-icon icon="solar:user-circle-linear" class="text-primary" style="font-size:2.8rem;"></iconify-icon>
                    </div>
                    <h5 class="fw-bold mb-1">{{ Auth::user()->nama }}</h5>
                    <p class="text-muted mb-3 fs-3">{{ Auth::user()->email }}</p>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-4">
                        {{ \App\Helpers\PeranHelper::label(Auth::user()->peran) }}
                    </span>

                    @if($profil->exists)
                    <div class="d-flex flex-column gap-2 mt-2 text-start">
                        <div class="d-flex justify-content-between align-items-center bg-light rounded px-3 py-2">
                            <span class="text-muted small">Status Aktif</span>
                            @if($profil->status_aktif)
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Tidak Aktif</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center bg-light rounded px-3 py-2">
                            <span class="text-muted small">IPK</span>
                            <span class="fw-semibold small">
                                {{ $profil->ipk !== null ? number_format((float) $profil->ipk, 2) : 'Belum diisi' }}
                            </span>
                        </div>
                    </div>
                    <p class="text-muted small mt-3 mb-0">
                        <iconify-icon icon="solar:info-circle-linear" class="me-1"></iconify-icon>
                        Status dan IPK hanya dapat diubah oleh Admin.
                    </p>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('mahasiswa.profil.edit') }}" class="btn btn-primary btn-sm px-4">
                            <iconify-icon icon="solar:pen-linear" class="me-1"></iconify-icon>
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom kanan: detail profil --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold">
                        <iconify-icon icon="solar:user-id-linear" class="me-2"></iconify-icon>
                        Data Diri
                    </h6>
                    <a href="{{ route('mahasiswa.profil.edit') }}" class="btn btn-sm btn-outline-primary">
                        <iconify-icon icon="solar:pen-linear" class="me-1"></iconify-icon>
                        Edit
                    </a>
                </div>
                <div class="card-body">
                    @if(!$profil->exists)
                        <div class="text-center py-5 text-muted">
                            <iconify-icon icon="solar:user-circle-linear" style="font-size:3rem;" class="mb-3 d-block mx-auto"></iconify-icon>
                            <p class="mb-3">Profil Anda belum diisi.</p>
                            <a href="{{ route('mahasiswa.profil.edit') }}" class="btn btn-primary px-4">
                                Lengkapi Profil Sekarang
                            </a>
                        </div>
                    @else
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-1">Nama Lengkap</label>
                            <p class="fw-semibold mb-0">{{ Auth::user()->nama }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-1">NPM</label>
                            <p class="fw-semibold mb-0">{{ $profil->npm }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-1">Jenis Kelamin</label>
                            <p class="fw-semibold mb-0">{{ $profil->jenis_kelamin }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-1">Program Studi</label>
                            <p class="fw-semibold mb-0">{{ $profil->program_studi }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-1">Kelas</label>
                            <p class="fw-semibold mb-0">{{ $profil->kelas }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-1">Nomor HP</label>
                            <p class="fw-semibold mb-0">{{ $profil->no_hp }}</p>
                        </div>
                        <div class="col-12">
                            <hr class="my-1">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-1">Alamat sesuai KTP</label>
                            <p class="fw-semibold mb-0">{{ $profil->alamat_ktp }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted small mb-1">Alamat Domisili</label>
                            <p class="fw-semibold mb-0">{{ $profil->alamat_domisili }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection