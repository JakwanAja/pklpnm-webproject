@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')

    <h2 class="mb-1 fs-7 fw-bolder">Sistem Informasi PKL/Magang</h2>
    <p class="mb-7 text-muted">Politeknik Negeri Madiun</p>

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Alamat Email</label>
            <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="nama@contoh.com"
                autocomplete="email"
                autofocus
            />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <label for="password" class="form-label fw-semibold">Kata Sandi</label>
            <input
                type="password"
                class="form-control @error('password') is-invalid @enderror"
                id="password"
                name="password"
                placeholder="Kata sandi Anda"
                autocomplete="current-password"
            />
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tombol masuk --}}
        <button type="submit" class="btn btn-primary w-100 py-8 mb-4">
            Masuk
        </button>

        {{-- Catatan kecil --}}
        <p class="text-center fs-3 text-muted mb-0">
            Akun dikelola oleh administrator sistem.
        </p>

    </form>

@endsection
