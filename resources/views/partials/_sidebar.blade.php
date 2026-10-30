{{-- Sidebar navigasi berdasarkan peran pengguna yang sedang login --}}
<aside class="left-sidebar with-vertical">
    <div>
    <nav class="sidebar-nav scroll-sidebar" data-simplebar>

        {{-- Profil pengguna --}}
        <div class="user-profile position-relative"
             style="background: url({{ asset('assets/images/backgrounds/user-info.jpg') }}) no-repeat;">
            <div class="profile-img">
                <img src="{{ asset('assets/images/profile/user-1.jpg') }}"
                     alt="Foto Profil"
                     class="w-100 rounded-circle overflow-hidden" />
            </div>
            <div class="profile-text hide-menu pt-1 dropdown">
                <a href="javascript:void(0)"
                   class="dropdown-toggle u-dropdown w-100 text-white d-block position-relative"
                   id="dropdownMenuProfil"
                   data-bs-toggle="dropdown"
                   aria-expanded="false">
                    {{ Auth::user()->nama }}
                </a>
                <div class="dropdown-menu animated flipInY" aria-labelledby="dropdownMenuProfil">
                    <span class="dropdown-item-text text-muted fs-2">
                        {{ \App\Helpers\PeranHelper::label(Auth::user()->peran) }}
                    </span>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex gap-2 text-danger">
                            <i data-feather="log-out" class="feather-sm"></i>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <ul id="sidebarnav">

            {{-- ============================== --}}
            {{-- Menu MAHASISWA --}}
            {{-- ============================== --}}
            @if(Auth::user()->peran === 'mahasiswa')

            <li class="nav-small-cap">
                <iconify-icon icon="solar:menu-dots-bold" class="nav-small-cap-icon fs-4"></iconify-icon>
                <span class="hide-menu">Mahasiswa</span>
            </li>
            <li class="sidebar-item {{ request()->routeIs('mahasiswa.dashboard') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('mahasiswa.dashboard') }}">
                    <iconify-icon icon="solar:screencast-2-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('mahasiswa.profil.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('mahasiswa.profil.show') }}">
                    <iconify-icon icon="solar:user-circle-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Profil Saya</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('mahasiswa.pengajuan.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('mahasiswa.pengajuan.index') }}">
                    <iconify-icon icon="solar:document-add-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Pengajuan Magang</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('mahasiswa.jurnal.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('mahasiswa.jurnal.index') }}">
                    <iconify-icon icon="solar:notebook-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Jurnal</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('mahasiswa.bimbingan.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('mahasiswa.bimbingan.index') }}">
                    <iconify-icon icon="solar:chat-square-call-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Bimbingan</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('mahasiswa.laporan.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('mahasiswa.laporan.index') }}">
                    <iconify-icon icon="solar:file-text-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Laporan Akhir</span>
                </a>
            </li>

            {{-- ============================== --}}
            {{-- Menu PIC MAGANG --}}
            {{-- ============================== --}}
            @elseif(Auth::user()->peran === 'pic_magang')

            <li class="nav-small-cap">
                <iconify-icon icon="solar:menu-dots-bold" class="nav-small-cap-icon fs-4"></iconify-icon>
                <span class="hide-menu">PIC Magang</span>
            </li>
            <li class="sidebar-item {{ request()->routeIs('pic-magang.dashboard') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('pic-magang.dashboard') }}">
                    <iconify-icon icon="solar:screencast-2-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('pic-magang.pengajuan.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('pic-magang.pengajuan.index') }}">
                    <iconify-icon icon="solar:inbox-archive-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Antrean Berkas</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('pic-magang.perusahaan.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('pic-magang.perusahaan.index') }}">
                    <iconify-icon icon="solar:buildings-2-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Perusahaan</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('pic-magang.pemantauan.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('pic-magang.pemantauan.index') }}">
                    <iconify-icon icon="solar:eye-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Pemantauan</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('pic-magang.rekap.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('pic-magang.rekap.index') }}">
                    <iconify-icon icon="solar:chart-square-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Rekap Permohonan</span>
                </a>
            </li>

            {{-- ============================== --}}
            {{-- Menu KPS --}}
            {{-- ============================== --}}
            @elseif(Auth::user()->peran === 'kps')

            <li class="nav-small-cap">
                <iconify-icon icon="solar:menu-dots-bold" class="nav-small-cap-icon fs-4"></iconify-icon>
                <span class="hide-menu">KPS</span>
            </li>
            <li class="sidebar-item {{ request()->routeIs('kps.dashboard') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('kps.dashboard') }}">
                    <iconify-icon icon="solar:screencast-2-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('kps.pengajuan.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('kps.pengajuan.index') }}">
                    <iconify-icon icon="solar:check-square-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Persetujuan</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('kps.laporan.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('kps.laporan.index') }}">
                    <iconify-icon icon="solar:diploma-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Laporan & Penyelesaian</span>
                </a>
            </li>

            {{-- ============================== --}}
            {{-- Menu DOSEN --}}
            {{-- ============================== --}}
            @elseif(Auth::user()->peran === 'dosen')

            <li class="nav-small-cap">
                <iconify-icon icon="solar:menu-dots-bold" class="nav-small-cap-icon fs-4"></iconify-icon>
                <span class="hide-menu">Dosen Pembimbing</span>
            </li>
            <li class="sidebar-item {{ request()->routeIs('dosen.dashboard') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('dosen.dashboard') }}">
                    <iconify-icon icon="solar:screencast-2-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('dosen.mahasiswa.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('dosen.mahasiswa.index') }}">
                    <iconify-icon icon="solar:users-group-two-rounded-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Mahasiswa Bimbingan</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('dosen.bimbingan.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('dosen.bimbingan.index') }}">
                    <iconify-icon icon="solar:chat-square-call-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Bimbingan</span>
                </a>
            </li>

            {{-- ============================== --}}
            {{-- Menu PIC PERUSAHAAN --}}
            {{-- ============================== --}}
            @elseif(Auth::user()->peran === 'pic_perusahaan')

            <li class="nav-small-cap">
                <iconify-icon icon="solar:menu-dots-bold" class="nav-small-cap-icon fs-4"></iconify-icon>
                <span class="hide-menu">PIC Perusahaan</span>
            </li>
            <li class="sidebar-item {{ request()->routeIs('pic-perusahaan.dashboard') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('pic-perusahaan.dashboard') }}">
                    <iconify-icon icon="solar:screencast-2-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('pic-perusahaan.jurnal.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('pic-perusahaan.jurnal.index') }}">
                    <iconify-icon icon="solar:notebook-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Jurnal</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('pic-perusahaan.laporan.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('pic-perusahaan.laporan.index') }}">
                    <iconify-icon icon="solar:file-text-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Laporan & Penilaian</span>
                </a>
            </li>

            {{-- ============================== --}}
            {{-- Menu ADMIN --}}
            {{-- ============================== --}}
            @elseif(Auth::user()->peran === 'admin')

            <li class="nav-small-cap">
                <iconify-icon icon="solar:menu-dots-bold" class="nav-small-cap-icon fs-4"></iconify-icon>
                <span class="hide-menu">Admin Sistem</span>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                    <iconify-icon icon="solar:screencast-2-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.pengguna.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.pengguna.index') }}">
                    <iconify-icon icon="solar:users-group-rounded-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Manajemen Pengguna</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.perusahaan.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.perusahaan.index') }}">
                    <iconify-icon icon="solar:buildings-2-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Perusahaan</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.periode.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.periode.index') }}">
                    <iconify-icon icon="solar:calendar-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Periode</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.pengaturan.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.pengaturan.index') }}">
                    <iconify-icon icon="solar:settings-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Pengaturan Sistem</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.log.*') ? 'selected' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.log.index') }}">
                    <iconify-icon icon="solar:clipboard-list-linear" class="aside-icon"></iconify-icon>
                    <span class="hide-menu">Log Aktivitas</span>
                </a>
            </li>

            @endif

        </ul>
    </nav>
    </div>
</aside>
