@extends('layouts.layout')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h1 class="h4 mb-1">Daftar Kurikulum Diajukan</h1>
                                <p class="text-muted mb-0">Kelola kurikulum yang telah diajukan oleh Anda</p>
                            </div>
                            <a href="{{ route('perusahaan-kurikulum-create') }}" class="btn btn-success d-flex align-items-center">
                                + Ajukan Kurikulum
                            </a>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('info'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="bi bi-info-circle me-2"></i>
                                {{ session('info') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                    <input type="date" id="filter-date" class="form-control" placeholder="Filter berdasarkan tanggal pengajuan">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-tag"></i>
                                    </span>
                                    <select id="filter-status" class="form-control">
                                        <option value="">Semua Status Validasi Sekolah</option>
                                        <option value="proses">Menunggu</option>
                                        <option value="disetujui">Disetujui</option>
                                        <option value="tidak_disetujui">Ditolak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" id="search-title" class="form-control" placeholder="Cari berdasarkan nama kurikulum...">
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="kurikulum-table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">Nama Kurikulum</th>
                                        <th class="border-0">Tahun Ajaran</th>
                                        <th class="border-0">File</th>
                                        <th class="border-0">Tanggal Pengajuan</th>
                                        <th class="border-0">Status Validasi Sekolah</th> {{-- Hanya menampilkan validasi_sekolah --}}
                                        <th class="border-0">Aksi</th>
                                        <th class="border-0">Komentar Sekolah</th> {{-- Ubah nama kolom --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kurikulums as $kurikulum)
                                        <tr>
                                            <td>{{ $kurikulum->nama_kurikulum }}</td>
                                            <td>{{ $kurikulum->tahun_ajaran }}</td>
                                            <td>
                                                <a href="{{ asset('storage/'.$kurikulum->file_kurikulum)}}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download me-1"></i> Unduh
                                                </a>
                                            </td>
                                            <td class="created-date">{{ \Carbon\Carbon::parse($kurikulum->created_at)->format('Y-m-d') }}</td> {{-- Tanggal pengajuan --}}
                                            <td>
                                                @if($kurikulum->validasi_sekolah == 'disetujui')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @elseif($kurikulum->validasi_sekolah == 'tidak_disetujui')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    {{-- Tombol Edit selalu ada karena bisa direset statusnya --}}
                                                    <a href="{{ route('perusahaan-kurikulum-edit', ['kurikulum' => $kurikulum->id]) }}"
                                                    class="btn btn-sm btn-outline-warning"
                                                    data-bs-toggle="tooltip"
                                                    title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    {{-- Tombol Hapus selalu ada --}}
                                                    <form action="{{ route('perusahaan-kurikulum-destroy', ['kurikulum' => $kurikulum->id]) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kurikulum ini?')"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger"
                                                                data-bs-toggle="tooltip"
                                                                title="Hapus">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                    {{-- Tombol Batalkan Persetujuan (Cancel) hanya jika validasi perusahaan adalah 'disetujui' --}}
                                                    @if($kurikulum->validasi_perusahaan == 'disetujui')
                                                        <form action="{{ route('perusahaan-kurikulum-cancel-approval', ['kurikulum' => $kurikulum->id]) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan kurikulum ini? Status akan direset untuk validasi ulang.')"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                    class="btn btn-sm btn-outline-info"
                                                                    data-bs-toggle="tooltip"
                                                                    title="Batalkan Pengajuan">
                                                                <i class="bi bi-arrow-counterclockwise"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($kurikulum->komentar)
                                                    <span class="text-muted">{{ Str::limit($kurikulum->komentar, 50) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4"> {{-- colspan disesuaikan --}}
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                    Belum ada kurikulum yang diajukan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* ... (Style tetap sama) ... */
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            const dateFilter = document.getElementById('filter-date');
            const statusFilter = document.getElementById('filter-status');
            const searchInput = document.getElementById('search-title');

            function filterTable() {
                const selectedDate = dateFilter.value.toLowerCase();
                const selectedStatus = statusFilter.value.toLowerCase();
                const searchKeyword = searchInput.value.toLowerCase();

                const rows = document.querySelectorAll('#kurikulum-table tbody tr');

                rows.forEach(row => {
                    // Pastikan sel yang diakses benar setelah perubahan kolom
                    const createdDate = row.querySelector('.created-date').textContent.trim().toLowerCase();
                    const statusSekolahText = row.querySelector('td:nth-child(5)').textContent.trim().toLowerCase(); // Ambil status validasi sekolah
                    const titleText = row.querySelector('td:nth-child(1)').textContent.trim().toLowerCase();

                    // Logika filter status hanya berdasarkan validasi_sekolah
                    const matchStatus = !selectedStatus || statusSekolahText.includes(selectedStatus);

                    const matchDate = !selectedDate || createdDate.startsWith(selectedDate);
                    const matchKeyword = !searchKeyword || titleText.includes(searchKeyword);

                    row.style.display = matchDate && matchStatus && matchKeyword ? '' : 'none';
                });
            }

            dateFilter.addEventListener('input', filterTable);
            statusFilter.addEventListener('change', filterTable);
            searchInput.addEventListener('input', filterTable);
        });
    </script>
@endsection