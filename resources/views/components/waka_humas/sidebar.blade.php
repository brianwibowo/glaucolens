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
            <ul class="nav nav-secondary">                <li class="nav-item {{ Route::currentRouteName() == 'waka-humas-dashboard' ? 'active' : '' }}">
                    <a href="{{ route('waka-humas-dashboard') }}" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('waka-humas-guru-tamu-*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#guruTamuMenu" aria-expanded="{{ request()->routeIs('waka-humas-guru-tamu-*') ? 'true' : 'false' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Guru Tamu</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('waka-humas-guru-tamu-*') ? 'show' : '' }}" id="guruTamuMenu">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('waka-humas-guru-tamu-index') ? 'active' : '' }}">
                                <a href="{{ route('waka-humas-guru-tamu-index') }}">
                                    <span class="sub-item">Daftar Guru Tamu</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>                <li class="nav-item {{ request()->routeIs('waka-humas-pkl-*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#pklMenu" aria-expanded="{{ request()->routeIs('waka-humas-pkl-*') ? 'true' : 'false' }}">
                        <i class="fas fa-briefcase"></i>
                        <p>PKL</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('waka-humas-pkl-*') ? 'show' : '' }}" id="pklMenu">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('waka-humas-pkl-index') && !request()->routeIs('waka-humas-pkl-assign-*') ? 'active' : '' }}">
                                <a href="{{ route('waka-humas-pkl-index') }}">
                                    <span class="sub-item">Daftar PKL</span>
                                </a>
                            </li>                            <li class="{{ request()->routeIs('waka-humas-pkl-assign-*') ? 'active' : '' }}">
                                <a href="{{ route('waka-humas-pkl-assign-index') }}">
                                    <span class="sub-item">Penugasan Pembimbing</span>
                                </a>
                            </li>                            <li class="{{ request()->is('waka-humas/pkl/logbook*') ? 'active' : '' }}">
                                <a href="{{ route('waka-humas-pkl-logbook-validation-index') }}">
                                    <span class="sub-item">Lihat Logbook</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->routeIs('waka-humas-riset-*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#risetInovasi" aria-expanded="{{ request()->routeIs('waka-humas-riset-*') ? 'true' : 'false' }}">
                        <i class="fas fa-flask"></i>
                        <p>Riset & Inovasi Produk</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('waka-humas-riset-*') ? 'show' : '' }}" id="risetInovasi">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('waka-humas-riset-index') || request()->routeIs('waka-humas-riset-show') ? 'active' : '' }}">
                                <a href="{{ route('waka-humas-riset-index') }}">
                                    <span class="sub-item">Daftar Riset</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('waka-humas-riset-create') ? 'active' : '' }}">
                                <a href="{{ route('waka-humas-riset-create') }}">
                                    <span class="sub-item">Tambah Riset Baru</span>
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
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle untuk menu Riset & Inovasi Produk
        document.querySelector('a[href="#chart"]').addEventListener('click', function (e) {
            e.preventDefault();
            const submenu = document.getElementById('submenu');
            submenu.classList.toggle('show');
        });

        // Toggle untuk menu Beasiswa Talent Scout
        document.querySelector('a[href="#char"]').addEventListener('click', function (e) {
            e.preventDefault();
            const subnav2 = document.getElementById('subnav2');
            subnav2.classList.toggle('show');
        });
    });
</script>

