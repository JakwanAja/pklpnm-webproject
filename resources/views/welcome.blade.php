<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistem Informasi PKL/Magang — Politeknik Negeri Madiun</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />
</head>
<body data-bs-theme="light" data-color-theme="Blue_Theme">

    <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100 d-flex align-items-center justify-content-center">
        <div class="text-center px-4" style="max-width: 520px;">

            <img src="{{ asset('assets/images/logos/logo-icon.svg') }}"
                 alt="Logo PKL"
                 width="80"
                 class="mb-4" />

            <h1 class="fw-bold fs-8 mb-2">Sistem Informasi PKL/Magang</h1>
            <p class="text-muted mb-1 fs-4">Politeknik Negeri Madiun</p>
            <p class="text-muted mb-6 fs-3">
                Platform pengelolaan magang/PKL dari pengajuan hingga penilaian akhir.
            </p>

            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 rounded-pill">
                <iconify-icon icon="solar:login-2-linear" class="me-2"></iconify-icon>
                Masuk ke Sistem
            </a>

            {{-- Indikator status infrastruktur (tampil hanya di development) --}}
            @if(config('app.debug'))
            <div class="mt-5 p-4 bg-light rounded-3 text-start">
                <p class="fw-semibold mb-2 fs-3 text-dark">
                    <iconify-icon icon="solar:check-circle-linear" class="text-success me-1"></iconify-icon>
                    Phase 0 — Infrastruktur
                </p>
                <ul class="list-unstyled mb-0 fs-2 text-muted">
                    <li>✓ Laravel {{ app()->version() }}</li>
                    <li>✓ Database PostgreSQL terhubung</li>
                    <li>✓ MaterialPro Bootstrap 5 v10 terintegrasi</li>
                    <li>✓ DomPDF + Laravel Excel terinstall</li>
                    <li>✓ Seeder: pengaturan & akun default tersedia</li>
                </ul>
            </div>
            @endif

        </div>
    </div>

    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme/app.init.js') }}"></script>
    <script src="{{ asset('assets/js/theme/theme.js') }}"></script>
    <script src="{{ asset('assets/js/theme/app.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>
