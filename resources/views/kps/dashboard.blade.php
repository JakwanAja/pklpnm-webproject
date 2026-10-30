@extends('layouts.app')

@section('title', 'Dashboard KPS')
@section('page_title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="py-4">

    {{-- Sambutan --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body d-flex align-items-center gap-3 py-4">
            <div class="rounded-circle bg-success bg-opacity-10 p-3">
                <iconify-icon icon="solar:check-square-linear" class="fs-7 text-success"></iconify-icon>
            </div>
            <div>
                <h4 class="mb-1 fw-semibold">Selamat datang, {{ Auth::user()->nama }}!</h4>
                <span class="badge bg-success-subtle text-success rounded-pill">
                    {{ \App\Helpers\PeranHelper::label(Auth::user()->peran) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Info card placeholder --}}
    <div class="row g-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                            <iconify-icon icon="solar:check-square-linear" class="fs-5 text-primary"></iconify-icon>
                        </div>
                        <span class="fw-semibold text-muted fs-3">Menunggu Persetujuan</span>
                    </div>
                    <h3 class="fw-bold mb-0">—</h3>
                    <small class="text-muted">Akan tersedia di Phase 9</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                            <iconify-icon icon="solar:diploma-linear" class="fs-5 text-success"></iconify-icon>
                        </div>
                        <span class="fw-semibold text-muted fs-3">Laporan & Penyelesaian</span>
                    </div>
                    <h3 class="fw-bold mb-0">—</h3>
                    <small class="text-muted">Akan tersedia di Phase 15</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-info bg-opacity-10 p-2 me-3">
                            <iconify-icon icon="solar:chart-square-linear" class="fs-5 text-info"></iconify-icon>
                        </div>
                        <span class="fw-semibold text-muted fs-3">Rekap Nilai</span>
                    </div>
                    <h3 class="fw-bold mb-0">—</h3>
                    <small class="text-muted">Akan tersedia di Phase 17</small>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
