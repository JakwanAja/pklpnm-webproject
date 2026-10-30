{{-- Topbar — disalin 1:1 dari index.html MaterialPro, hanya konten profil/notifikasi yang disesuaikan --}}
<header class="topbar rounded-0 border-0 bg-primary">
    <div class="with-vertical">
        <nav class="navbar navbar-expand-lg px-lg-0 px-3 py-0">

            {{-- Logo desktop --}}
            <div class="d-none d-lg-block">
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="{{ route('home') }}" class="text-nowrap logo-img d-flex align-items-center gap-2">
                        <b class="logo-icon">
                            <img src="{{ asset('assets/images/logos/logo-light-icon.svg') }}" alt="homepage" class="dark-logo" />
                            <img src="{{ asset('assets/images/logos/logo-light-icon.svg') }}" alt="homepage" class="light-logo" />
                        </b>
                        <span class="logo-text">
                            <img src="{{ asset('assets/images/logos/logo-light-text.svg') }}" alt="PKL" class="dark-logo ps-2" />
                            <img src="{{ asset('assets/images/logos/logo-light-text.svg') }}" class="light-logo ps-2" alt="PKL" />
                        </span>
                    </a>
                </div>
            </div>

            {{-- Kiri navbar: toggle sidebar + search --}}
            <ul class="navbar-nav gap-2">
                <li class="nav-item nav-icon-hover-bg rounded-circle">
                    <a class="nav-link nav-icon-hover sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                        <iconify-icon icon="solar:list-bold"></iconify-icon>
                    </a>
                </li>
                <li class="nav-item d-none d-lg-block search-box nav-icon-hover-bg rounded-circle">
                    <a class="nav-link nav-icon-hover d-none d-md-flex waves-effect waves-dark" href="javascript:void(0)">
                        <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                    </a>
                </li>
            </ul>

            {{-- Logo mobile --}}
            <div class="d-block d-lg-none">
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="{{ route('home') }}" class="text-nowrap logo-img d-flex align-items-center gap-2">
                        <b class="logo-icon">
                            <img src="{{ asset('assets/images/logos/logo-light-icon.svg') }}" alt="homepage" class="dark-logo" />
                            <img src="{{ asset('assets/images/logos/logo-light-icon.svg') }}" alt="homepage" class="light-logo" />
                        </b>
                    </a>
                </div>
            </div>

            {{-- Toggle mobile --}}
            <ul class="navbar-nav flex-row gap-2 align-items-center justify-content-center d-flex d-lg-none">
                <li class="nav-item dropdown nav-icon-hover-bg rounded-circle">
                    <a class="navbar-toggler nav-link text-white nav-icon-hover border-0"
                       href="javascript:void(0)"
                       data-bs-toggle="collapse"
                       data-bs-target="#navbarNav"
                       aria-controls="navbarNav"
                       aria-expanded="false">
                        <span><i class="ti ti-dots fs-7"></i></span>
                    </a>
                </li>
            </ul>

            {{-- Kanan navbar --}}
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center justify-content-between py-2 py-lg-0">

                    <ul class="navbar-nav gap-2 flex-row ms-auto align-items-center justify-content-center">

                        {{-- Dark/Light toggle --}}
                        <li class="nav-item nav-icon-hover-bg rounded-circle">
                            <a class="nav-link nav-icon-hover moon dark-layout" href="javascript:void(0)">
                                <iconify-icon icon="solar:moon-line-duotone" class="moon"></iconify-icon>
                            </a>
                            <a class="nav-link nav-icon-hover sun light-layout" href="javascript:void(0)">
                                <iconify-icon icon="solar:sun-2-line-duotone" class="sun"></iconify-icon>
                            </a>
                        </li>

                        {{-- Notifikasi --}}
                        @php $unread = Auth::user()->unreadNotifications->count(); @endphp
                        <li class="nav-item hover-dd dropdown nav-icon-hover-bg rounded-circle d-none d-lg-block">
                            <a class="nav-link nav-icon-hover waves-effect waves-dark"
                               href="javascript:void(0)"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">
                                <iconify-icon icon="solar:bell-bing-line-duotone"></iconify-icon>
                                @if($unread > 0)
                                    <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                                @endif
                            </a>
                            <div class="dropdown-menu py-0 content-dd dropdown-menu-animate-up overflow-hidden dropdown-menu-end">
                                <div class="py-3 px-4 bg-primary">
                                    <div class="mb-0 fs-6 fw-medium text-white">Notifikasi</div>
                                    <div class="mb-0 fs-2 fw-medium text-white">
                                        {{ $unread > 0 ? 'Anda memiliki '.$unread.' notifikasi baru' : 'Tidak ada notifikasi baru' }}
                                    </div>
                                </div>
                                <div class="message-body" data-simplebar>
                                    @forelse(Auth::user()->notifications->take(6) as $notif)
                                        @php $data = $notif->data; @endphp
                                        <a href="{{ $data['url'] ?? '#' }}"
                                           class="p-3 d-flex align-items-center dropdown-item gap-3 border-bottom {{ is_null($notif->read_at) ? 'bg-light-subtle' : '' }}">
                                            <span class="flex-shrink-0 bg-primary-subtle rounded-circle round-40 d-flex align-items-center justify-content-center fs-6 text-primary">
                                                <iconify-icon icon="solar:bell-bing-line-duotone"></iconify-icon>
                                            </span>
                                            <div class="w-80">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1">{{ $data['judul'] ?? 'Notifikasi' }}</h6>
                                                </div>
                                                <span class="fs-2 d-block text-truncate text-muted">{{ $data['pesan'] ?? '' }}</span>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="p-4 text-center text-muted fs-3">Belum ada notifikasi</div>
                                    @endforelse
                                </div>
                                @if($unread > 0)
                                    <div class="p-3">
                                        <a href="{{ route('notifikasi.tandai-semua') }}"
                                           class="d-flex btn btn-primary align-items-center justify-content-center gap-2">
                                            <span>Tandai semua dibaca</span>
                                            <iconify-icon icon="solar:alt-arrow-right-outline" class="iconify-sm"></iconify-icon>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </li>

                        {{-- Profil dropdown — mengikuti struktur user-dd dari index.html --}}
                        <li class="nav-item hover-dd dropdown">
                            <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('assets/images/profile/user-1.jpg') }}"
                                     alt="user"
                                     class="profile-pic rounded-circle round-30" />
                            </a>
                            <div class="dropdown-menu pt-0 content-dd overflow-hidden dropdown-menu-end user-dd">
                                <div class="profile-dropdown position-relative" data-simplebar>
                                    <div class="py-3 border-bottom">
                                        <div class="d-flex align-items-center px-3">
                                            <img src="{{ asset('assets/images/profile/user-1.jpg') }}"
                                                 class="rounded-circle round-50" alt="" />
                                            <div class="ms-3">
                                                <h5 class="mb-1 fs-4">{{ Auth::user()->nama }}</h5>
                                                <p class="mb-0 fs-2 d-flex align-items-center text-muted">
                                                    {{ Auth::user()->email }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="message-body pb-3">
                                        <div class="px-3 pt-3">
                                            <div class="px-3 py-1 mb-1 rounded-2">
                                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                                    {{ \App\Helpers\PeranHelper::label(Auth::user()->peran) }}
                                                </span>
                                            </div>
                                            @if(Auth::user()->peran === 'mahasiswa')
                                            <div class="h6 mb-0 dropdown-item py-8 px-3 rounded-2 link">
                                                <a href="{{ route('mahasiswa.profil.show') }}" class="d-flex align-items-center text-dark">
                                                    <iconify-icon icon="solar:user-circle-linear" class="me-2 fs-5"></iconify-icon>
                                                    Profil Saya
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                        <hr>
                                        <div class="px-3">
                                            <div class="py-8 px-3 d-flex justify-content-between dropdown-item align-items-center h6 mb-0 rounded-2 link">
                                                <span>Mode Tampilan</span>
                                                <div>
                                                    <a class="moon dark-layout" href="javascript:void(0)">
                                                        <iconify-icon icon="solar:moon-line-duotone" class="moon"></iconify-icon>
                                                    </a>
                                                    <a class="sun light-layout" href="javascript:void(0)">
                                                        <iconify-icon icon="solar:sun-2-line-duotone" class="sun"></iconify-icon>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="h6 mb-0 dropdown-item py-8 px-3 rounded-2 link">
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="d-flex align-items-center w-100 border-0 bg-transparent p-0 text-danger">
                                                        <iconify-icon icon="solar:logout-2-linear" class="me-2 fs-5"></iconify-icon>
                                                        Keluar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>

        </nav>
    </div>
</header>