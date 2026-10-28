{{--
    Script utama MaterialPro Bootstrap 5 v10.
    Di-include di akhir <body> pada layouts/app.blade.php.
    Halaman individual dapat menambahkan script tambahan
    via @stack('scripts').
--}}

{{-- Bootstrap Bundle (termasuk Popper) --}}
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

{{-- SimpleBar (custom scrollbar sidebar) --}}
<script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>

{{-- Tema MaterialPro --}}
<script src="{{ asset('assets/js/theme/app.init.js') }}"></script>
<script src="{{ asset('assets/js/theme/theme.js') }}"></script>
<script src="{{ asset('assets/js/theme/app.min.js') }}"></script>
<script src="{{ asset('assets/js/theme/feather.min.js') }}"></script>

{{-- Iconify (Solar icons) --}}
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

{{-- Slot untuk script tambahan per halaman --}}
@stack('scripts')
