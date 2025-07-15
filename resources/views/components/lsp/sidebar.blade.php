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
    <div class="sidebar" data-background-color="dark">
        {{-- ... bagian logo dan toggle --}}
        <div class="sidebar-logo">
            <div class="logo-header d-flex align-items-center justify-content-between px-3"
                data-background-color="dark">
                <a href="{{ route('lsp-dashboard') }}" class="text-white text-decoration-none">
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
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">
                <ul class="nav nav-secondary">
                    <li class="nav-item {{ Route::currentRouteName() == 'lsp-dashboard' ? 'active' : '' }}">
                        <a href="{{ route('lsp-dashboard') }}" class="collapsed" aria-expanded="false">
                            <i class="fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    {{-- Sertifikasi Kompetensi (BARU UNTUK LSP) --}}
                    <li class="nav-item {{ request()->routeIs('lsp-sertifikasi*') ? 'active' : '' }}">
                        <a data-bs-toggle="collapse" href="#sertifikasiLspCollapse"
                            aria-expanded="{{ request()->routeIs('lsp-sertifikasi*') ? 'true' : 'false' }}">
                            <i class="fas fa-certificate"></i>
                            <p>Sertifikasi Kompetensi</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{ request()->routeIs('lsp-sertifikasi*') ? 'show' : '' }}"
                            id="sertifikasiLspCollapse">
                            <ul class="nav nav-collapse">
                                <li class="{{ Route::currentRouteName() == 'lsp-sertifikasi-index' ? 'active' : '' }}">
                                    <a href="{{ route('lsp-sertifikasi-index') }}">
                                        <span class="sub-item">Manajemen Sertifikasi</span>
                                    </a>
                                </li>
                                <li
                                    class="{{ Route::currentRouteName() == 'lsp-sertifikasi-results' ? 'active' : '' }}">
                                    <a href="{{ route('lsp-sertifikasi-results') }}">
                                        <span class="sub-item">Hasil Sertifikasi Siswa</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
