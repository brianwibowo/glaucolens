<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header d-flex align-items-center justify-content-between px-3" data-background-color="dark">
            {{-- Tulisan besar SIMILA, href mengarah ke admin-dashboard --}}
            <a href="{{ route('admin-dashboard') }}" class="text-white text-decoration-none">
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
                <li class="nav-item {{ Route::currentRouteName() == 'admin-dashboard' ? 'active' : '' }}">
                    <a href="{{ route('admin-dashboard') }}" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- PKL Admin --}}
                <li class="nav-item {{ request()->routeIs('admin-pkl*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#pklAdminCollapse"
                        aria-expanded="{{ request()->routeIs('admin-pkl*') ? 'true' : 'false' }}">
                        <i class="fas fa-briefcase"></i>
                        <p>Praktik Kerja Lapangan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin-pkl*') ? 'show' : '' }}"
                        id="pklAdminCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ Route::currentRouteName() == 'admin-pkl-index' ? 'active' : '' }}">
                                <a href="{{ route('admin-pkl-index') }}">
                                    <span class="sub-item">Dashboard PKL</span>
                                </a>
                            </li>
                            <li class="{{ Route::currentRouteName() == 'admin-pkl-select-company' ? 'active' : '' }}">
                                <a href="{{ route('admin-pkl-select-company') }}">
                                    <span class="sub-item">Mewakili Perusahaan</span>
                                </a>
                            </li>
                            <li class="{{ Route::currentRouteName() == 'admin-pkl-select-guru' ? 'active' : '' }}">
                                <a href="{{ route('admin-pkl-select-guru') }}">
                                    <span class="sub-item">Mewakili Guru</span>
                                </a>
                            </li>
                            <li class="{{ Route::currentRouteName() == 'admin-pkl-assign-pembimbing-list' ? 'active' : '' }}">
                                <a href="{{ route('admin-pkl-assign-pembimbing-list') }}">
                                    <span class="sub-item">Penugasan Pembimbing</span>
                                </a>
                            </li>
                            {{-- Jika ada halaman khusus penilaian PKL, sesuaikan rutenya --}}
                            {{-- <li class="{{ Route::currentRouteName() == 'admin-pkl-penilaian' ? 'active' : '' }}">
                                <a href="{{ route('admin-pkl-penilaian') }}">
                                    <span class="sub-item">Penilaian</span>
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                </li>

                {{-- Kurikulum Bersama --}}
                <li class="nav-item {{ request()->routeIs('admin-kurikulum*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#kurikulumAdminCollapse"
                        aria-expanded="{{ request()->routeIs('admin-kurikulum*') ? 'true' : 'false' }}">
                        <i class="fas fa-layer-group"></i>
                        <p>Kurikulum Bersama</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin-kurikulum*') ? 'show' : '' }}"
                        id="kurikulumAdminCollapse">
                        <ul class="nav nav-collapse">
                            <li
                                class="{{ Route::currentRouteName() == 'admin-kurikulum-list-diajukan' ? 'active' : '' }}">
                                <a href="{{ route('admin-kurikulum-list-diajukan') }}">
                                    <span class="sub-item">Kurikulum Diajukan</span>
                                </a>
                            </li>
                            <li
                                class="{{ Route::currentRouteName() == 'admin-kurikulum-list-validasi' ? 'active' : '' }}">
                                <a href="{{ route('admin-kurikulum-list-validasi') }}">
                                    <span class="sub-item">Validasi Kurikulum Perusahaan</span>
                                </a>
                            </li>
                            <li
                                class="{{ Route::currentRouteName() == 'admin-kurikulum-list-validasi-sekolah' ? 'active' : '' }}">
                                <a href="{{ route('admin-kurikulum-list-validasi-sekolah') }}">
                                    <span class="sub-item">Validasi Kurikulum Sekolah</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Project Mitra --}}
                <li class="nav-item {{ request()->routeIs('admin-project*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#projectAdminCollapse"
                        aria-expanded="{{ request()->routeIs('admin-project*') ? 'true' : 'false' }}">
                        <i class="fas fa-th-list"></i>
                        <p>Project Mitra</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin-project*') ? 'show' : '' }}"
                        id="projectAdminCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ Route::currentRouteName() == 'admin-project-index' ? 'active' : '' }}">
                                <a href="{{ route('admin-project-index') }}">
                                    <span class="sub-item">Manajemen Project</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Guru Tamu --}}
                <li class="nav-item {{ request()->routeIs('admin-guru-tamu*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#guruTamuAdminCollapse"
                        aria-expanded="{{ request()->routeIs('admin-guru-tamu*') ? 'true' : 'false' }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Guru Tamu</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin-guru-tamu*') ? 'show' : '' }}"
                        id="guruTamuAdminCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ Route::currentRouteName() == 'admin-guru-tamu-index' ? 'active' : '' }}">
                                <a href="{{ route('admin-guru-tamu-index') }}">
                                    <span class="sub-item">Manajemen Guru Tamu</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Talent Scouting (Admin) --}}
                <li class="nav-item {{ request()->routeIs('admin-scouting*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#scoutingAdminNewCollapse"
                        aria-expanded="{{ request()->routeIs('admin-scouting*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-friends"></i>
                        <p>Talent Scouting</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin-scouting*') ? 'show' : '' }}"
                        id="scoutingAdminNewCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ Route::currentRouteName() == 'admin-scouting-index' ? 'active' : '' }}">
                                <a href="{{ route('admin-scouting-index') }}">
                                    <span class="sub-item">Manajemen Batch</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- MOOC (Admin) --}}
                <li class="nav-item {{ request()->routeIs('admin-mooc*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#moocAdminCollapse"
                        aria-expanded="{{ request()->routeIs('admin-mooc*') ? 'true' : 'false' }}">
                        <i class="fas fa-graduation-cap"></i>
                        <p>MOOC</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin-mooc*') ? 'show' : '' }}" id="moocAdminCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ Route::currentRouteName() == 'admin-mooc-index' ? 'active' : '' }}">
                                <a href="{{ route('admin-mooc-index') }}">
                                    <span class="sub-item">Manajemen Pelatihan</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Riset & Inovasi Produk (Admin) --}}
                <li class="nav-item {{ request()->routeIs('admin-riset*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#risetAdminCollapse"
                        aria-expanded="{{ request()->routeIs('admin-riset*') ? 'true' : 'false' }}">
                        <i class="fas fa-flask"></i>
                        <p>Riset & Inovasi Produk</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin-riset*') ? 'show' : '' }}"
                        id="risetAdminCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ Route::currentRouteName() == 'admin-riset-index' ? 'active' : '' }}">
                                <a href="{{ route('admin-riset-index') }}">
                                    <span class="sub-item">Daftar Riset</span>
                                </a>
                            </li>
                            <li class="{{ Route::currentRouteName() == 'admin-riset-create' ? 'active' : '' }}">
                                <a href="{{ route('admin-riset-create') }}">
                                    <span class="sub-item">Ajukan Riset Baru</span>
                                </a>
                            </li>
                            <li class="{{ Route::currentRouteName() == 'admin-riset-results' ? 'active' : '' }}">
                                <a href="{{ route('admin-riset-results') }}">
                                    <span class="sub-item">Hasil Riset</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Beasiswa Talent Scout (Admin) --}}
                <li class="nav-item {{ request()->routeIs('admin-beasiswa*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#beasiswaAdminCollapse"
                        aria-expanded="{{ request()->routeIs('admin-beasiswa*') ? 'true' : 'false' }}">
                        <i class="fas fa-medal"></i>
                        <p>Beasiswa Talent Scout</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin-beasiswa*') ? 'show' : '' }}"
                        id="beasiswaAdminCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('admin-beasiswa-index') ? 'active' : '' }}">
                                <a href="{{ route('admin-beasiswa-index') }}">
                                    <span class="sub-item">Daftar Batch Beasiswa</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Sertifikasi Kompetensi --}}
                <li class="nav-item {{ request()->routeIs('admin-sertifikasi*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#sertifikasiAdminCollapse"
                        aria-expanded="{{ request()->routeIs('admin-sertifikasi*') ? 'true' : 'false' }}">
                        <i class="fas fa-certificate"></i>
                        <p>Sertifikasi Kompetensi</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin-sertifikasi*') ? 'show' : '' }}"
                        id="sertifikasiAdminCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ Route::currentRouteName() == 'admin-sertifikasi-index' ? 'active' : '' }}">
                                <a href="{{ route('admin-sertifikasi-index') }}">
                                    <span class="sub-item">Manajemen Sertifikasi</span>
                                </a>
                            </li>
                            <li
                                class="{{ Route::currentRouteName() == 'admin-sertifikasi-results' ? 'active' : '' }}">
                                <a href="{{ route('admin-sertifikasi-results') }}">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ini adalah toggle untuk sidebar global (gg-menu-right/left)
        const toggleSidebarBtn = document.querySelector('.toggle-sidebar');
        const sidenavTogglerBtn = document.querySelector('.sidenav-toggler');
        const topbarTogglerBtn = document.querySelector('.topbar-toggler');
        const sidebar = document.querySelector('.sidebar');
        const wrapper = document.querySelector('.wrapper'); // Asumsi ada wrapper di layout utama

        if (toggleSidebarBtn) {
            toggleSidebarBtn.addEventListener('click', function() {
                if (sidebar.classList.contains('toggled')) {
                    sidebar.classList.remove('toggled');
                    wrapper.classList.remove('sidebar_minimize');
                } else {
                    sidebar.classList.add('toggled');
                    wrapper.classList.add('sidebar_minimize');
                }
            });
        }

        if (sidenavTogglerBtn) {
            sidenavTogglerBtn.addEventListener('click', function() {
                // Logika untuk menampilkan/menyembunyikan sidebar di layar kecil
                sidebar.classList.toggle('toggled'); // Atau kelas lain yang relevan
            });
        }

        if (topbarTogglerBtn) {
            topbarTogglerBtn.addEventListener('click', function() {
                // Logika untuk topbar toggler (biasanya untuk menu mobile)
                // Kamu bisa menambahkan logika yang sesuai di sini
            });
        }

        // Logic for sub-menu collapses is now handled by Bootstrap's data-bs-toggle="collapse"
        // and Laravel's request()->routeIs() for active/show classes.
        // No custom JS needed for sub-menu collapse behavior.
    });
</script>