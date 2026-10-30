<!DOCTYPE html>
<html lang="id" dir="ltr" data-bs-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>403 — Akses Ditolak | {{ config('app.name') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />
</head>

<body>

    <div id="main-wrapper">
        <div class="position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-lg-4 col-md-6 col-10 text-center">

                        {{-- Ikon --}}
                        <div class="mb-4">
                            <iconify-icon
                                icon="solar:shield-cross-linear"
                                class="text-danger"
                                style="font-size: 5rem;"
                            ></iconify-icon>
                        </div>

                        {{-- Kode error --}}
                        <h1 class="fw-bolder text-danger mb-2" style="font-size: 4rem;">403</h1>

                        {{-- Judul --}}
                        <h4 class="fw-semibold mb-3">Akses Ditolak</h4>

                        {{-- Deskripsi --}}
                        <p class="text-muted mb-4">
                            Anda tidak memiliki izin untuk mengakses halaman ini.<br />
                            Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.
                        </p>

                        {{-- Tombol kembali --}}
                        <a href="{{ route('home') }}" class="btn btn-primary px-5 py-2">
                            <iconify-icon icon="solar:home-2-linear" class="me-2"></iconify-icon>
                            Kembali ke Beranda
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

</body>
</html>
