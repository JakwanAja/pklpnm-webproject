@extends('layouts.auth')

@section('title', 'Ganti Kata Sandi')

@section('content')

    <h2 class="mb-1 fs-7 fw-bolder">Ganti Kata Sandi</h2>
    <p class="mb-7 text-muted">Anda wajib mengganti kata sandi sebelum melanjutkan.</p>

    <form method="POST" action="{{ route('ganti-password') }}" novalidate>
        @csrf
        @method('POST')

        {{-- Kata Sandi Baru --}}
        <div class="mb-3">
            <label for="password_baru" class="form-label fw-semibold">Kata Sandi Baru</label>
            <input
                type="password"
                class="form-control @error('password_baru') is-invalid @enderror"
                id="password_baru"
                name="password_baru"
                placeholder="Minimal 8 karakter"
                autocomplete="new-password"
                autofocus
            />
            @error('password_baru')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Konfirmasi Kata Sandi Baru --}}
        <div class="mb-4">
            <label for="password_baru_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi Baru</label>
            <input
                type="password"
                class="form-control @error('password_baru_confirmation') is-invalid @enderror"
                id="password_baru_confirmation"
                name="password_baru_confirmation"
                placeholder="Ulangi kata sandi baru"
                autocomplete="new-password"
            />
            @error('password_baru_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tombol simpan --}}
        <button type="submit" class="btn btn-primary w-100 py-8 mb-4">
            Simpan Kata Sandi Baru
        </button>

        {{-- Opsi logout --}}
        <p class="text-center fs-3 text-muted mb-0">
            Ingin keluar?
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link p-0 fs-3 text-muted align-baseline">
                    Keluar dari sistem
                </button>
            </form>
        </p>

    </form>

@endsection
