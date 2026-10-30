@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('page_title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="py-4">

    {{-- Banner: profil belum lengkap --}}
    @if(!Auth::user()->profilMahasiswa)
    <div class="alert alert-warning d-flex align-items-center gap-2 mb-4" role="alert">
        <iconify-icon icon="solar:info-circle-linear" class="fs-5"></iconify-icon>
        <div>
            Profil Anda belum lengkap.
            <a href="{{ route('mahasiswa.profil.edit') }}" class="alert-link">Lengkapi sekarang</a>
        </div>
    </div>
    @endif

    {{-- Sambutan --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body d-flex align-items-center gap-3 py-4">
            <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                <iconify-icon icon="solar:user-circle-linear" class="fs-7 text-primary"></iconify-icon>
            </div>
            <div>
                <h4 class="mb-1 fw-semibold">Selamat datang, {{ Auth::user()->nama }}!</h4>
                <span class="badge bg-primary-subtle text-primary rounded-pill">
                    {{ \App\Helpers\PeranHelper::label(Auth::user()->peran) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Info card placeholder --}}
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-3">
                            <iconify-icon icon="solar:document-add-linear" class="fs-5 text-warning"></iconify-icon>
                        </div>
                        <span class="fw-semibold text-muted fs-3">Pengajuan Magang</span>
                    </div>
                    <h3 class="fw-bold mb-0">—</h3>
                    <small class="text-muted">Akan tersedia di Phase 6</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                            <iconify-icon icon="solar:notebook-linear" class="fs-5 text-success"></iconify-icon>
                        </div>
                        <span class="fw-semibold text-muted fs-3">Jurnal</span>
                    </div>
                    <h3 class="fw-bold mb-0">—</h3>
                    <small class="text-muted">Akan tersedia di Phase 11</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-info bg-opacity-10 p-2 me-3">
                            <iconify-icon icon="solar:chat-square-call-linear" class="fs-5 text-info"></iconify-icon>
                        </div>
                        <span class="fw-semibold text-muted fs-3">Bimbingan</span>
                    </div>
                    <h3 class="fw-bold mb-0">—</h3>
                    <small class="text-muted">Akan tersedia di Phase 13</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-2 me-3">
                            <iconify-icon icon="solar:file-text-linear" class="fs-5 text-danger"></iconify-icon>
                        </div>
                        <span class="fw-semibold text-muted fs-3">Laporan Akhir</span>
                    </div>
                    <h3 class="fw-bold mb-0">—</h3>
                    <small class="text-muted">Akan tersedia di Phase 14</small>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
