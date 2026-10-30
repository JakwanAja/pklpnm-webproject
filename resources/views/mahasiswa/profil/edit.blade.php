@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Profil</li>
@endsection

@section('content')
<div class="py-4">
    <div class="row g-4">

        {{-- Kolom kiri: kartu informasi --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-5">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:80px;height:80px;">
                        <iconify-icon icon="solar:user-circle-linear" class="text-primary" style="font-size:2.5rem;"></iconify-icon>
                    </div>
                    <h5 class="fw-semibold mb-1">{{ Auth::user()->nama }}</h5>
                    <span class="badge bg-primary-subtle text-primary rounded-pill mb-3">
                        {{ \App\Helpers\PeranHelper::label(Auth::user()->peran) }}
                    </span>

                    @if($profil->exists)
                        <div class="d-flex flex-column gap-2 mt-3">
                            <div class="d-flex justify-content-between align-items-center border rounded px-3 py-2">
                                <span class="text-muted small">NPM</span>
                                <span class="fw-semibold small">{{ $profil->npm }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center border rounded px-3 py-2">
                                <span class="text-muted small">Program Studi</span>
                                <span class="fw-semibold small text-end" style="max-width:60%">{{ $profil->program_studi }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center border rounded px-3 py-2">
                                <span class="text-muted small">Kelas</span>
                                <span class="fw-semibold small">{{ $profil->kelas }}</span>
                            </div>

                            {{-- Status aktif & IPK — hanya baca, dikelola Admin --}}
                            <div class="d-flex justify-content-between align-items-center border rounded px-3 py-2">
                                <span class="text-muted small">Status</span>
                                @if($profil->status_aktif)
                                    <span class="badge bg-success-subtle text-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Tidak Aktif</span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center border rounded px-3 py-2">
                                <span class="text-muted small">IPK</span>
                                <span class="fw-semibold small">
                                    {{ $profil->ipk !== null ? number_format((float) $profil->ipk, 2) : '—' }}
                                </span>
                            </div>
                        </div>
                        <p class="text-muted small mt-3 mb-0">
                            <iconify-icon icon="solar:info-circle-linear" class="me-1"></iconify-icon>
                            Status dan IPK hanya dapat diubah oleh Admin.
                        </p>
                    @else
                        <p class="text-muted small mt-3">
                            Profil Anda belum diisi. Lengkapi data di sebelah kanan.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom kanan: form --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <iconify-icon icon="solar:pen-linear" class="me-2"></iconify-icon>
                        Edit Profil
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('mahasiswa.profil.update') }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            {{-- Nama Lengkap --}}
                            <div class="col-12">
                                <label for="nama" class="form-label fw-semibold">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control @error('nama') is-invalid @enderror"
                                    id="nama"
                                    name="nama"
                                    value="{{ old('nama', Auth::user()->nama) }}"
                                    placeholder="Nama lengkap sesuai identitas"
                                    autocomplete="name"
                                >
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- NPM --}}
                            <div class="col-sm-6">
                                <label for="npm" class="form-label fw-semibold">
                                    NPM <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control @error('npm') is-invalid @enderror"
                                    id="npm"
                                    name="npm"
                                    value="{{ old('npm', $profil->npm ?? '') }}"
                                    placeholder="Nomor Pokok Mahasiswa"
                                >
                                @error('npm')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div class="col-sm-6">
                                <label for="jenis_kelamin" class="form-label fw-semibold">
                                    Jenis Kelamin <span class="text-danger">*</span>
                                </label>
                                <select
                                    class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                    id="jenis_kelamin"
                                    name="jenis_kelamin"
                                >
                                    <option value="" disabled {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') === '' ? 'selected' : '' }}>-- Pilih --</option>
                                    <option value="Laki-laki"  {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') === 'Laki-laki'  ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan"  {{ old('jenis_kelamin', $profil->jenis_kelamin ?? '') === 'Perempuan'  ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Program Studi --}}
                            <div class="col-sm-6">
                                <label for="program_studi" class="form-label fw-semibold">
                                    Program Studi <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control @error('program_studi') is-invalid @enderror"
                                    id="program_studi"
                                    name="program_studi"
                                    value="{{ old('program_studi', $profil->program_studi ?? '') }}"
                                    placeholder="Contoh: D3 Teknik Informatika"
                                >
                                @error('program_studi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kelas --}}
                            <div class="col-sm-6">
                                <label for="kelas" class="form-label fw-semibold">
                                    Kelas <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control @error('kelas') is-invalid @enderror"
                                    id="kelas"
                                    name="kelas"
                                    value="{{ old('kelas', $profil->kelas ?? '') }}"
                                    placeholder="Contoh: 5A"
                                >
                                @error('kelas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- No HP --}}
                            <div class="col-12">
                                <label for="no_hp" class="form-label fw-semibold">
                                    Nomor HP <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control @error('no_hp') is-invalid @enderror"
                                    id="no_hp"
                                    name="no_hp"
                                    value="{{ old('no_hp', $profil->no_hp ?? '') }}"
                                    placeholder="Contoh: 08123456789"
                                >
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Alamat KTP --}}
                            <div class="col-12">
                                <label for="alamat_ktp" class="form-label fw-semibold">
                                    Alamat sesuai KTP <span class="text-danger">*</span>
                                </label>
                                <textarea
                                    class="form-control @error('alamat_ktp') is-invalid @enderror"
                                    id="alamat_ktp"
                                    name="alamat_ktp"
                                    rows="3"
                                    placeholder="Alamat lengkap sesuai KTP"
                                >{{ old('alamat_ktp', $profil->alamat_ktp ?? '') }}</textarea>
                                @error('alamat_ktp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Alamat Domisili --}}
                            <div class="col-12">
                                <label for="alamat_domisili" class="form-label fw-semibold">
                                    Alamat Domisili <span class="text-danger">*</span>
                                </label>
                                <textarea
                                    class="form-control @error('alamat_domisili') is-invalid @enderror"
                                    id="alamat_domisili"
                                    name="alamat_domisili"
                                    rows="3"
                                    placeholder="Alamat tempat tinggal saat ini"
                                >{{ old('alamat_domisili', $profil->alamat_domisili ?? '') }}</textarea>
                                @error('alamat_domisili')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>{{-- /row --}}

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4">
                                <iconify-icon icon="solar:disk-linear" class="me-1"></iconify-icon>
                                Simpan Profil
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>{{-- /row --}}
</div>
@endsection
