@extends('layouts.app')

@section('title', 'Dashboard PIC Magang')
@section('page_title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="py-4">

    {{-- Sambutan --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body d-flex align-items-center gap-3 py-4">
            <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                <iconify-icon icon="solar:inbox-archive-linear" class="fs-7 text-warning"></iconify-icon>
            </div>
            <div>
                <h4 class="mb-1 fw-semibold">Selamat datang, {{ Auth::user()->nama }}!</h4>
                <span class="badge bg-warning-subtle text-warning rounded-pill">
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
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                            <iconify-icon icon="solar:inbox-archive-linear" class="fs-5 text-primary"></iconify-icon>
                        </div>
                        <span class="fw-semibold text-muted fs-3">Antrean Berkas</span>
                    </div>
                    <h3 class="fw-bold mb-0">—</h3>
                    <small class="text-muted">Akan tersedia di Phase 8</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                            <iconify-icon icon="solar:buildings-2-linear" class="fs-5 text-success"></iconify-icon>
                        </div>
                        <span class="fw-semibold text-muted fs-3">Perusahaan</span>
                    </div>
                    <h3 class="fw-bold mb-0">—</h3>
                    <small class="text-muted">Akan tersedia di Phase 5</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-info bg-opacity-10 p-2 me-3">
                            <iconify-icon icon="solar:eye-linear" class="fs-5 text-info"></iconify-icon>
                        </div>
                        <span class="fw-semibold text-muted fs-3">Pemantauan</span>
                    </div>
                    <h3 class="fw-bold mb-0">—</h3>
                    <small class="text-muted">Akan tersedia di Phase 12</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-2 me-3">
                            <iconify-icon icon="solar:chart-square-linear" class="fs-5 text-danger"></iconify-icon>
                        </div>
                        <span class="fw-semibold text-muted fs-3">Rekap Permohonan</span>
                    </div>
                    <h3 class="fw-bold mb-0">—</h3>
                    <small class="text-muted">Akan tersedia di Phase 17</small>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
