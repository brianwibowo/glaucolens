@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0">Daftar Pelamar PKL</h1>
            <button id="toggleArchiveView" class="btn btn-secondary">
                <i class="bi bi-archive me-2"></i> <span id="toggleText">Lihat Pendaftar yang Diarsipkan</span>
            </button>
        </div>

        <p class="text-muted">Total: <strong class="text-primary">{{ $siswas->count() }}</strong> pelamar</p>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col">Nama Pelamar</th>
                                <th scope="col">Email</th>
                                <th scope="col">Program PKl</th>
                                <th scope="col">Pembimbing</th>
                                <th scope="col" class="text-center">Status Aplikasi</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswas as $siswa)
                                @php
                                    $statusAplikasi = $siswa->pkl_status;
                                    $badgeClass = '';
                                    switch ($statusAplikasi) {
                                        case 'disetujui':
                                            $badgeClass = 'bg-success';
                                            break;
                                        case 'tidak_disetujui':
                                            $badgeClass = 'bg-danger';
                                            break;
                                        case 'proses':
                                        default:
                                            $badgeClass = 'bg-warning text-dark';
                                            break;
                                    }
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $siswa->name ?? '-' }}</td>
                                    <td>{{ $siswa->email ?? '-' }}</td>
                                    <td>{{ $siswa->pklSiswa->nama ?? 'Belum Terdaftar' }}</td>
                                    <td>{{ $siswa->pklSiswa && $siswa->pklSiswa->pembimbing ? $siswa->pklSiswa->pembimbing->name : 'Belum Ditentukan' }}
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $statusAplikasi)) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            @if ($siswa->is_archived)
                                                {{-- Aksi untuk pendaftar yang diarsipkan: Hanya Pulihkan --}}
                                                <form action="{{ route('perusahaan-pkl-archive-applicant', $siswa->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-info"
                                                        title="Pulihkan dari Arsip"
                                                        onclick="return confirm('Apakah Anda yakin ingin memulihkan pelamar ini dari arsip (menampilkan kembali)?')">
                                                        <i class="bi bi-arrow-counterclockwise"></i> Pulihkan
                                                    </button>
                                                </form>
                                            @else
                                                {{-- Aksi untuk pendaftar aktif --}}
                                                @if ($statusAplikasi === 'proses')
                                                    <form action="{{ route('perusahaan-pkl-terima', $siswa->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            title="Terima Pelamar">
                                                            <i class="bi bi-check-circle"></i> Terima
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('perusahaan-pkl-tolak', $siswa->id) }}"
                                                        method="POST" class="d-inline ms-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            title="Tolak Pelamar"
                                                            onclick="return confirm('Apakah Anda yakin ingin menolak pelamar ini? Ini akan menghapusnya dari daftar.')">
                                                            <i class="bi bi-x-circle"></i> Tolak
                                                        </button>
                                                    </form>
                                                @else
                                                    {{-- Tombol Arsipkan untuk status yang sudah diputuskan --}}
                                                    <form
                                                        action="{{ route('perusahaan-pkl-archive-applicant', $siswa->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-secondary"
                                                            title="Arsipkan Pelamar"
                                                            onclick="return confirm('Apakah Anda yakin ingin mengarsipkan pelamar ini? Ini akan menyembunyikannya dari daftar aktif.')">
                                                            <i class="bi bi-archive"></i> Arsip
                                                        </button>
                                                    </form>
                                                @endif
                                                {{-- Tombol Hapus Permanen (untuk semua status aktif) --}}
                                                <form action="{{ route('perusahaan-pkl-remove-applicant', $siswa->id) }}"
                                                    method="POST" class="d-inline ms-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        title="Hapus Pelamar Permanen"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pelamar ini secara permanen dari daftar? Tindakan ini tidak dapat dibatalkan.')">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="mb-2">
                                            <i class="bi bi-people" style="font-size: 3rem; color: #6c757d;"></i>
                                        </div>
                                        <p class="text-muted mb-0" id="emptyMessage">Belum ada pelamar yang terdaftar.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Jika $siswas adalah hasil pagination, tambahkan link pagination di sini --}}
                @if (isset($siswas) && method_exists($siswas, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $siswas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .table th,
        .table td {
            white-space: nowrap;
            /* Prevent text wrapping in table cells */
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table td:nth-child(2),
        .table td:nth-child(3) {
            /* Nama Pelamar, Email */
            max-width: 150px;
            /* Limit width for text-truncate */
        }

        .table td:nth-child(4) {
            /* Perusahaan PKL */
            max-width: 120px;
            /* Limit width */
        }

        .table td:nth-child(6) .btn-group {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleArchiveViewBtn = document.getElementById('toggleArchiveView');
            const toggleText = document.getElementById('toggleText');
            const emptyMessage = document.getElementById('emptyMessage');
            let showArchived = false; // State untuk toggle

            toggleArchiveViewBtn.addEventListener('click', function() {
                showArchived = !showArchived;
                const currentUrl = new URL(window.location.href);

                if (showArchived) {
                    currentUrl.searchParams.set('archived', 'true');
                    toggleText.textContent = 'Lihat Pendaftar Aktif';
                    // emptyMessage.textContent akan di-set oleh PHP, tapi ini untuk default JS
                    // emptyMessage.textContent = 'Tidak ada pelamar yang diarsipkan.';
                } else {
                    currentUrl.searchParams.delete('archived');
                    toggleText.textContent = 'Lihat Pendaftar yang Diarsipkan';
                    // emptyMessage.textContent = 'Belum ada pelamar yang terdaftar.';
                }
                window.location.href = currentUrl.toString(); // Refresh halaman dengan parameter baru
            });

            // Set initial button state based on URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('archived') === 'true') {
                showArchived = true;
                toggleText.textContent = 'Lihat Pendaftar Aktif';
                emptyMessage.textContent = 'Tidak ada pelamar yang diarsipkan.'; // Set pesan sesuai status arsip
            } else {
                emptyMessage.textContent = 'Belum ada pelamar yang terdaftar.'; // Set pesan sesuai status aktif
            }
        });

        // Fungsi konfirmasi hapus (tetap ada di sini karena dipanggil dari luar DOMContentLoaded)
        function confirmDelete(id) {
            if (confirm('Anda yakin ingin menghapus data PKL ini secara permanen?')) {
                document.getElementById('deleteForm' + id).submit(); // Ini adalah form untuk delete PKL, bukan pelamar
            }
        }
    </script>
@endsection
