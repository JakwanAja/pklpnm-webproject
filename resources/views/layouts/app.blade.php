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
    <title>@yield('title', 'Dashboard') -- {{ config('app.name') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />
    @stack('styles')
</head>
<body>
    <div class="preloader">
        <img src="{{ asset('assets/images/logos/logo-icon.svg') }}" alt="Memuat..." class="lds-ripple img-fluid" />
    </div>
    <div id="main-wrapper">
        @include('partials._sidebar')
        <div class="page-wrapper">
            @include('partials._navbar')
            <div class="body-wrapper">
                <div class="container-fluid">
                    <div class="font-weight-medium shadow-none position-relative overflow-hidden mb-4">
                        <div class="card-body px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="font-weight-medium mb-0">@yield('page_title', 'Dashboard')</h4>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="{{ route('home') }}">Beranda</a></li>
                                            @yield('breadcrumb')
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                    @endif
                    @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                    @endif
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Terdapat kesalahan:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                    @endif
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <div class="dark-transparent sidebartoggler"></div>
    @include('partials._scripts')
</body>
</html>