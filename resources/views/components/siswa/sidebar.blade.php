<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header d-flex align-items-center justify-content-between px-3" data-background-color="dark">
            {{-- Tulisan besar SIMILA --}}
            <a href="{{ route('waka-kurikulum-dashboard') }}" class="text-white text-decoration-none">
                <h3 class="m-0 fw-bold text-uppercase" style="letter-spacing: 1px;">SIMILA</h3>
            </a>
            <div class="nav-toggle d-flex align-items-center">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{ Route::is('siswa-dashboard') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('siswa-pkl*') || Route::is('siswa-logbook*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#base">
                        <i class="fas fa-layer-group"></i>
                        <p>PKL</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="base">
                        <ul class="nav nav-collapse">
                            <li class="{{ Route::is('siswa-pkl-index') ? 'active' : '' }}">
                                <a href="{{ route('siswa-pkl-index') }}">
                                    <span class="sub-item">Pendaftaran</span>
                                </a>
                            </li>
                            <li class="{{ Route::is('siswa-pkl-show') ? 'active' : '' }}">
                                <a href="{{ route('siswa-pkl-show') }}">
                                    <span class="sub-item">Berjalan</span>
                                </a>
                            </li>
                            <li class="{{ Route::is('siswa-logbook*') ? 'active' : '' }}">
                                <a href="{{ route('siswa-logbook-index') }}">
                                    <span class="sub-item">LogBook</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Sertifikasi Kompetensi (BARU) --}}
                <li class="nav-item {{ request()->routeIs('siswa-sertifikasi*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#sertifikasiSiswaCollapse"
                        aria-expanded="{{ request()->routeIs('siswa-sertifikasi*') ? 'true' : 'false' }}">
                        <i class="fas fa-certificate"></i>
                        <p>Sertifikasi Kompetensi</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('siswa-sertifikasi*') ? 'show' : '' }}"
                        id="sertifikasiSiswaCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ Route::currentRouteName() == 'siswa-sertifikasi-index' ? 'active' : '' }}">
                                <a href="{{ route('siswa-sertifikasi-index') }}">
                                    <span class="sub-item">Daftar & Daftar Sertifikasi</span>
                                </a>
                            </li>
                            <li class="{{ Route::currentRouteName() == 'siswa-sertifikasi-status' ? 'active' : '' }}">
                                <a href="{{ route('siswa-sertifikasi-status') }}">
                                    <span class="sub-item">Status Sertifikasi Saya</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ Route::is('siswa-beasiswa-*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#tsBeasiswa">
                        <i class="fas fa-pen-square"></i>
                        <p>Talent Scouting</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse {{ Route::is('siswa-beasiswa-*') ? 'show' : '' }}" id="tsBeasiswa">
                        <ul class="nav nav-collapse">
                            <li class="{{ Route::is('siswa-beasiswa-index') ? 'active' : '' }}">
                                <a href="{{ route('siswa-beasiswa-index') }}">
                                    <span class="sub-item">Daftar Beasiswa</span>
                                </a>
                            </li>
                            <li class="{{ Route::is('siswa-beasiswa-status') ? 'active' : '' }}">
                                <a href="{{ route('siswa-beasiswa-status') }}">
                                    <span class="sub-item">Status / Riwayat</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle untuk menu Riset & Inovasi Produk
        document.querySelector('a[href="#chart"]').addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = document.getElementById('submenu');
            submenu.classList.toggle('show');
        });

        // Toggle untuk menu Beasiswa Talent Scout
        document.querySelector('a[href="#char"]').addEventListener('click', function(e) {
            e.preventDefault();
            const subnav2 = document.getElementById('subnav2');
            subnav2.classList.toggle('show');
        });
    });
</script>
