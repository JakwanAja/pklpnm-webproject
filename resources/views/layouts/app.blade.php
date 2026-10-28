<!DOCTYPE html>
<html lang="id"
      dir="ltr"
      data-bs-theme="light"
      data-color-theme="Blue_Theme"
      data-layout="vertical">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />

    {{-- MaterialPro Core CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />

    {{-- Slot CSS tambahan per halaman --}}
    @stack('styles')
</head>

<body>

    {{-- Preloader --}}
    <div class="preloader">
        <img src="{{ asset('assets/images/logos/logo-icon.svg') }}"
             alt="Memuat..."
             class="lds-ripple img-fluid" />
    </div>

    <div id="main-wrapper">

        {{-- Sidebar --}}
        @include('partials._sidebar')

        <div class="page-wrapper">

            {{-- Navbar / Topbar --}}
            @include('partials._navbar')

            {{-- Breadcrumb & Judul Halaman --}}
            @hasSection('breadcrumb')
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-0 px-3 py-2">
                <div class="breadcrumb-title pe-3 text-dark fw-semibold">
                    @yield('page_title', '')
                </div>
                @hasSection('breadcrumb')
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
                @endif
            </div>
            @endif

            {{-- Konten Utama --}}
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        {{-- Flash messages --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                <iconify-icon icon="solar:check-circle-linear" class="me-2 fs-5"></iconify-icon>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <iconify-icon icon="solar:danger-circle-linear" class="me-2 fs-5"></iconify-icon>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                            </div>
                        @endif

                        @if(session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                                <iconify-icon icon="solar:info-circle-linear" class="me-2 fs-5"></iconify-icon>
                                {{ session('warning') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <iconify-icon icon="solar:danger-triangle-linear" class="me-2 fs-5"></iconify-icon>
                                <strong>Terdapat kesalahan pada input:</strong>
                                <ul class="mb-0 mt-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                            </div>
                        @endif

                        {{-- Konten halaman --}}
                        @yield('content')

                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="py-4 px-4 border-top">
                <p class="mb-0 fs-3 text-muted text-center">
                    &copy; {{ date('Y') }} Sistem Informasi PKL/Magang — Politeknik Negeri Madiun
                </p>
            </div>

        </div>{{-- /.page-wrapper --}}

    </div>{{-- /#main-wrapper --}}

    <div class="dark-transparent sidebartoggler"></div>

    {{-- Script utama MaterialPro + slot script per halaman --}}
    @include('partials._scripts')

</body>
</html>
