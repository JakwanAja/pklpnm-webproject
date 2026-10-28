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

    <title>@yield('title', 'Masuk') — {{ config('app.name') }}</title>

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

    <div id="main-wrapper" class="auth-customizer-none">
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100">
            <div class="position-relative z-index-5">
                <div class="row">

                    {{-- Panel kiri: form autentikasi --}}
                    <div class="col-xl-5 col-xxl-4">
                        <div class="authentication-login min-vh-100 bg-body row justify-content-center">

                            {{-- Logo --}}
                            <div class="col-12">
                                <a href="{{ url('/') }}"
                                   class="text-nowrap logo-img d-flex align-items-center gap-2 px-4 py-9 w-100">
                                    <b class="logo-icon">
                                        <img src="{{ asset('assets/images/logos/logo-icon.svg') }}"
                                             alt="Logo PKL"
                                             class="dark-logo" />
                                        <img src="{{ asset('assets/images/logos/logo-light-icon.svg') }}"
                                             alt="Logo PKL"
                                             class="light-logo" />
                                    </b>
                                    <span class="logo-text">
                                        <span class="dark-logo ps-2 fw-bold fs-5 text-dark">
                                            Sistem Informasi PKL/Magang
                                        </span>
                                        <span class="light-logo ps-2 fw-bold fs-5 text-dark">
                                            Sistem Informasi PKL/Magang
                                        </span>
                                    </span>
                                </a>
                            </div>

                            {{-- Konten form --}}
                            <div class="auth-max-width col-sm-8 col-md-6 col-xl-7 px-4">

                                {{-- Flash messages --}}
                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                                    </div>
                                @endif

                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                                    </div>
                                @endif

                                {{-- Konten halaman auth --}}
                                @yield('content')

                            </div>{{-- /.auth-max-width --}}
                        </div>
                    </div>{{-- /.col-xl-5 --}}

                    {{-- Panel kanan: ilustrasi (tampil hanya di layar besar) --}}
                    <div class="col-xl-7 col-xxl-8 d-none d-xl-block">
                        <div class="d-none d-xl-flex align-items-center justify-content-center h-100">
                            <img src="{{ asset('assets/images/backgrounds/user-login.png') }}"
                                 alt="Ilustrasi Login"
                                 class="img-fluid" />
                        </div>
                    </div>

                </div>{{-- /.row --}}
            </div>{{-- /.position-relative --}}
        </div>
    </div>{{-- /#main-wrapper --}}

    {{-- Bootstrap Bundle --}}
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    {{-- Tema MaterialPro --}}
    <script src="{{ asset('assets/js/theme/app.init.js') }}"></script>
    <script src="{{ asset('assets/js/theme/theme.js') }}"></script>
    <script src="{{ asset('assets/js/theme/app.min.js') }}"></script>
    {{-- Iconify --}}
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

    @stack('scripts')

</body>
</html>
