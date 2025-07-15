<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header d-flex align-items-center justify-content-between px-3" data-background-color="dark">
            <a href="{{ route('perusahaan-dashboard') }}" class="text-white text-decoration-none">
                <h3 class="m-0 fw-bold text-uppercase" style="letter-spacing: 1px;">SIMILA</h3>
            </a>
            {{-- Toggle untuk sidebar, ini biasanya di luar logo-header atau punya parent div sendiri --}}
            {{-- Jika ada elemen toggle untuk sidebar global, pastikan ada di sini atau di layout utama --}}
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
                <li class="nav-item {{ Route::currentRouteName() == 'perusahaan-dashboard' ? 'active' : '' }}">
                    <a href="{{ route('perusahaan-dashboard') }}" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                {{-- Kurikulum Bersama --}}
                <li class="nav-item {{ request()->routeIs('perusahaan-kurikulum*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#kurikulumCollapse"
                        aria-expanded="{{ request()->routeIs('perusahaan-kurikulum*') ? 'true' : 'false' }}">
                        <i class="fas fa-layer-group"></i>
                        <p>Kurikulum Bersama</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('perusahaan-kurikulum*') ? 'show' : '' }}"
                        id="kurikulumCollapse">
                        <ul class="nav nav-collapse">
                            <li
                                class="{{ Route::currentRouteName() == 'perusahaan-kurikulum-list-diajukan' ? 'active' : '' }}">
                                <a href="{{ route('perusahaan-kurikulum-list-diajukan') }}">
                                    <span class="sub-item">Kurikulum diajukan</span>
                                </a>
                            </li>
                            <li
                                class="{{ Route::currentRouteName() == 'perusahaan-kurikulum-list-validasi' ? 'active' : '' }}">
                                <a href="{{ route('perusahaan-kurikulum-list-validasi') }}">
                                    <span class="sub-item">Kurikulum Sekolah</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Project Mitra --}}
                <li class="nav-item {{ request()->routeIs('perusahaan-project*') ? 'active' : '' }} ">
                    <a data-bs-toggle="collapse" href="#projectCollapse"
                        aria-expanded="{{ request()->routeIs('perusahaan-project*') ? 'true' : 'false' }}">
                        <i class="fas fa-th-list"></i>
                        <p>Project Mitra</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('perusahaan-project*') ? 'show' : '' }}"
                        id="projectCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ Route::currentRouteName() == 'perusahaan-project-index' ? 'active' : '' }}">
                                <a href="{{ route('perusahaan-project-index') }}">
                                    <span class="sub-item">List Projek</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Guru Tamu --}}
                <li class="nav-item {{ request()->routeIs('perusahaan-guru-tamu*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#guruTamuCollapse"
                        aria-expanded="{{ request()->routeIs('perusahaan-guru-tamu*') ? 'true' : 'false' }}">
                        <i class="fas fa-pen-square"></i>
                        <p>Guru Tamu</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('perusahaan-guru-tamu*') ? 'show' : '' }}"
                        id="guruTamuCollapse">
                        <ul class="nav nav-collapse">
                            <li
                                class="{{ Route::currentRouteName() == 'perusahaan-guru-tamu-index' ? 'active' : '' }}">
                                <a href="{{ route('perusahaan-guru-tamu-index') }}">
                                    <span class="sub-item">Pengajuan Guru Tamu</span>
                                </a>
                            </li>
                            <li class="{{ Route::currentRouteName() == 'perusahaan-guru-tamu-list' ? 'active' : '' }}">
                                <a href="{{ route('perusahaan-guru-tamu-list') }}">
                                    <span class="sub-item">Guru Tamu</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- PKL --}}
                <li class="nav-item {{ request()->routeIs('perusahaan-pkl*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#pklCollapse"
                        aria-expanded="{{ request()->routeIs('perusahaan-pkl*') ? 'true' : 'false' }}">
                        <i class="fas fa-table"></i>
                        <p>PKL</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('perusahaan-pkl*') ? 'show' : '' }}" id="pklCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ Route::currentRouteName() == 'perusahaan-pkl-index' ? 'active' : '' }}">
                                <a href="{{ route('perusahaan-pkl-index') }}">
                                    <span class="sub-item">PKL</span>
                                </a>
                            </li>
                            <li class="{{ Route::currentRouteName() == 'perusahaan-pkl-list' ? 'active' : '' }}">
                                <a href="{{ route('perusahaan-pkl-list') }}">
                                    <span class="sub-item">Pelamar</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Talent Scouting --}}
                <li class="nav-item {{ request()->routeIs('perusahaan-scouting*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#scoutingCollapse"
                        aria-expanded="{{ request()->routeIs('perusahaan-scouting*') ? 'true' : 'false' }}">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>Talent Scouting</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('perusahaan-scouting*') ? 'show' : '' }}"
                        id="scoutingCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('perusahaan-scouting*') ? 'active' : '' }}">
                                <a href="{{ route('perusahaan-scouting-index') }}">
                                    <span class="sub-item">Talent Scouting</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- MOOC (Pelatihan Guru) --}}
                <li class="nav-item {{ request()->routeIs('perusahaan-mooc*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#moocCollapse"
                        aria-expanded="{{ request()->routeIs('perusahaan-mooc*') ? 'true' : 'false' }}">
                        <i class="fas fa-graduation-cap"></i> {{-- Mengubah ikon MOOC --}}
                        <p>MOOC</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('perusahaan-mooc*') ? 'show' : '' }}"
                        id="moocCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ Route::currentRouteName() == 'perusahaan-mooc-index' ? 'active' : '' }}">
                                <a href="{{ route('perusahaan-mooc-index') }}">
                                    <span class="sub-item">Kelas</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Sertifikasi Kompetensi --}}
                <li class="nav-item {{ request()->routeIs('perusahaan-sertifikasi*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#sertifikasiCollapse"
                        aria-expanded="{{ request()->routeIs('perusahaan-sertifikasi*') ? 'true' : 'false' }}">
                        <i class="fas fa-certificate"></i> {{-- Ikon lebih umum untuk sertifikasi --}}
                        <p>Sertifikasi Kompetensi</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('perusahaan-sertifikasi*') ? 'show' : '' }}"
                        id="sertifikasiCollapse">
                        <ul class="nav nav-collapse">
                            <li
                                class="{{ Route::currentRouteName() == 'perusahaan-sertifikasi-index' ? 'active' : '' }}">
                                <a href="{{ route('perusahaan-sertifikasi-index') }}">
                                    <span class="sub-item">Manajemen Ujian</span>
                                </a>
                            </li>
                            <li
                                class="{{ Route::currentRouteName() == 'perusahaan-sertifikasi-results' ? 'active' : '' }}">
                                <a href="{{ route('perusahaan-sertifikasi-results') }}">
                                    <span class="sub-item">Hasil Sertifikasi Siswa</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Riset & Inovasi Produk --}}
                <li class="nav-item {{ request()->routeIs('perusahaan-riset*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#risetInovasiCollapse"
                        aria-expanded="{{ request()->routeIs('perusahaan-riset*') ? 'true' : 'false' }}">
                        <i class="fas fa-flask"></i> {{-- Mengubah ikon Riset & Inovasi Produk --}}
                        <p>Riset & Inovasi Produk</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('perusahaan-riset*') ? 'show' : '' }}"
                        id="risetInovasiCollapse">
                        <ul class="nav nav-collapse">
                            {{-- Pastikan kamu mendefinisikan rute ini nanti di web.php --}}
                            <li class="{{ Route::currentRouteName() == 'perusahaan-riset-index' ? 'active' : '' }}">
                                <a href="{{ route('perusahaan-riset-index') }}">
                                    <span class="sub-item">List Riset Inovasi</span>
                                </a>
                            </li>
                            {{-- Pastikan kamu mendefinisikan rute ini nanti di web.php --}}
                            <li class="{{ Route::currentRouteName() == 'perusahaan-riset-results' ? 'active' : '' }}">
                                <a href="{{ route('perusahaan-riset-results') }}">
                                    <span class="sub-item">Hasil Inovasi</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Beasiswa Talent Scout --}}
                <li class="nav-item {{ request()->routeIs('perusahaan-beasiswa-*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#beasiswaCollapse"
                        aria-expanded="{{ request()->routeIs('perusahaan-beasiswa-*') ? 'true' : 'false' }}">
                        <i class="fas fa-medal"></i> {{-- Mengganti ikon beasiswa agar lebih relevan --}}
                        <p>Beasiswa Talent Scout</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('perusahaan-beasiswa-*') ? 'show' : '' }}"
                        id="beasiswaCollapse">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('perusahaan-beasiswa-*') ? 'active' : '' }}">
                                <a href="{{ route('perusahaan-beasiswa-index') }}">
                                    <span class="sub-item">Daftar Batch Beasiswa</span>
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
