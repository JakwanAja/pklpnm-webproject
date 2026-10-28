{{-- Topbar / Navbar atas --}}
<header class="topbar">
    <div class="with-vertical">
        <nav class="navbar navbar-expand-lg px-lg-0 px-3 py-0">

            {{-- Logo (tampil di sidebar, diulang di navbar untuk mobile) --}}
            <div class="d-none d-lg-block">
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="{{ route(Auth::user()->peran . '.dashboard') }}"
                       class="text-nowrap logo-img d-flex align-items-center gap-2">
                        <b class="logo-icon">
                            <img src="{{ asset('assets/images/logos/logo-light-icon.svg') }}"
                                 alt="Logo PKL" class="dark-logo" />
                            <img src="{{ asset('assets/images/logos/logo-light-icon.svg') }}"
                                 alt="Logo PKL" class="light-logo" />
                        </b>
                        <span class="logo-text">
                            <span class="dark-logo ps-2 fw-bold text-white">PKL/Magang</span>
                            <span class="light-logo ps-2 fw-bold text-white">PKL/Magang</span>
                        </span>
                    </a>
                </div>
            </div>

            <ul class="navbar-nav gap-2">
                {{-- Toggle sidebar --}}
                <li class="nav-item nav-icon-hover-bg rounded-circle">
                    <a class="nav-link nav-icon-hover sidebartoggler"
                       id="headerCollapse"
                       href="javascript:void(0)">
                        <iconify-icon icon="solar:list-bold"></iconify-icon>
                    </a>
                </li>
            </ul>

            {{-- Kanan navbar: notifikasi & profil --}}
            <div class="ms-auto d-flex align-items-center gap-2">

                {{-- Notifikasi --}}
                <li class="nav-item dropdown nav-icon-hover-bg rounded-circle list-unstyled">
                    <a class="nav-link nav-icon-hover" href="javascript:void(0)"
                       id="dropNotifHeader"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <iconify-icon icon="solar:bell-linear"></iconify-icon>
                        @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
                        @if($unreadCount > 0)
                            <span class="badge rounded-pill bg-danger fs-2 position-absolute top-0 end-0">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up p-0"
                         aria-labelledby="dropNotifHeader"
                         style="min-width: 320px;">
                        <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
                            <h5 class="mb-0 fs-5 fw-semibold">Notifikasi</h5>
                            @if($unreadCount > 0)
                                <a href="{{ route('notifikasi.tandai-semua') }}"
                                   class="text-primary fs-3">Tandai semua dibaca</a>
                            @endif
                        </div>
                        <div style="max-height: 320px; overflow-y: auto;">
                            @forelse(Auth::user()->notifications->take(10) as $notif)
                                @php $data = $notif->data; @endphp
                                <a href="{{ $data['url'] ?? '#' }}"
                                   class="dropdown-item d-flex gap-2 py-2 px-4 border-bottom {{ is_null($notif->read_at) ? 'bg-light-subtle' : '' }}">
                                    <div class="flex-shrink-0">
                                        <iconify-icon icon="solar:bell-bing-linear" class="fs-5 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <p class="mb-0 fs-3 fw-medium text-dark">{{ $data['judul'] ?? 'Notifikasi' }}</p>
                                        <span class="fs-2 text-muted">{{ $data['pesan'] ?? '' }}</span>
                                    </div>
                                </a>
                            @empty
                                <p class="text-center text-muted py-4 mb-0 fs-3">Tidak ada notifikasi.</p>
                            @endforelse
                        </div>
                    </div>
                </li>

                {{-- Profil dropdown --}}
                <li class="nav-item dropdown nav-icon-hover-bg rounded-circle list-unstyled">
                    <a class="nav-link pe-0" href="javascript:void(0)"
                       id="dropProfileHeader"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <img src="{{ asset('assets/images/profile/user-1.jpg') }}"
                             alt="Foto Profil"
                             class="rounded-circle"
                             width="35"
                             height="35" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                         aria-labelledby="dropProfileHeader">
                        <div class="py-3 px-4 border-bottom">
                            <p class="mb-0 fw-semibold">{{ Auth::user()->nama }}</p>
                            <span class="fs-2 text-muted">{{ Auth::user()->email }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                <iconify-icon icon="solar:logout-2-linear" class="fs-5"></iconify-icon>
                                Keluar
                            </button>
                        </form>
                    </div>
                </li>

            </div>
        </nav>
    </div>
</header>
