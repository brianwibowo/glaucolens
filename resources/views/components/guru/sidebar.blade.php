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
                <li class="nav-item {{ Route::currentRouteName() == 'guru-dashboard' ? 'active' : '' }}">
                    <a href="{{ route('guru-dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('guru-project-*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#projectMitraMenu" class="{{ request()->routeIs('guru-project-*') ? '' : 'collapsed' }}" aria-expanded="{{ request()->routeIs('guru-project-*') ? 'true' : 'false' }}">
                        <i class="fas fa-th-list"></i>
                        <p>Project Mitra</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('guru-project-*') ? 'show' : '' }}" id="projectMitraMenu">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('guru-project-index') ? 'active' : '' }}">
                                <a href="{{ route('guru-project-index') }}">
                                    <span class="sub-item">Daftar Project</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>                @if(auth()->user()->jenis_guru == 'guru pembimbing')
                <li class="nav-item {{ request()->routeIs('guru-pkl-*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#pklMenu" class="{{ request()->routeIs('guru-pkl-*') ? '' : 'collapsed' }}" aria-expanded="{{ request()->routeIs('guru-pkl-*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-graduate"></i>
                        <p>PKL</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('guru-pkl-*') ? 'show' : '' }}" id="pklMenu">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('guru-pkl-index') ? 'active' : '' }}">
                                <a href="{{ route('guru-pkl-index') }}">
                                    <span class="sub-item">Daftar PKL</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                <li class="nav-item {{ request()->routeIs('guru-mooc-*') ? 'active' : '' }} {{ auth()->user()->jenis_guru == 'guru-produktif' ? '' : 'disable' }}">
                    <a data-bs-toggle="collapse" href="{{ auth()->user()->jenis_guru == 'guru-produktif' ? '#charts' : '#' }}">
                        <i class="far fa-chart-bar"></i>
                        <p>MOOC</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="charts">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('guru-mooc-index') ? 'active' : '' }}">
                                <a href="{{ route('guru-mooc-index') }}">
                                    <span class="sub-item">Kelas</span>
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

