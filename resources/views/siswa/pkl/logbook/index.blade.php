@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-0">Logbook PKL {{ $pkl }}</h1>
            <p class="text-muted mb-0">Jumlah logbook: {{ $logbooks->count() }}/50</p>
        </div>
        <a href="{{ route('siswa-logbook-create') }}" class="btn btn-success {{ $logbooks->count() >= 50 ? 'disabled' : '' }}">
            <i class="bi bi-plus-lg"></i> Tambah Logbook
        </a>
    </div>

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
    @endif    {{-- Final Report Status Card --}}
    @php
        $user = auth()->user();
        $userPkl = $user->pklSiswa;
        
        function getStatusBadge($status) {
            switch ($status) {
                case 'disetujui': return '<span class="badge bg-success">Disetujui</span>';
                case 'ditolak': return '<span class="badge bg-danger">Ditolak</span>';
                default: return '<span class="badge bg-warning text-dark">Proses</span>';
            }
        }
        
        function getStatusIcon($status) {
            switch ($status) {
                case 'disetujui': return '<i class="bi bi-check-circle-fill text-success"></i>';
                case 'ditolak': return '<i class="bi bi-exclamation-circle-fill text-danger"></i>';
                default: return '<i class="bi bi-clock-history text-warning"></i>';
            }
        }
    @endphp
    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">Status Validasi Laporan Akhir PKL</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        <div class="me-3 fs-3">
                            {!! getStatusIcon($userPkl ? $userPkl->status_pembimbing : 'proses') !!}
                        </div>
                        <div>
                            <h6 class="mb-1">Guru Pembimbing</h6>
                            <div>{!! getStatusBadge($userPkl ? $userPkl->status_pembimbing : 'proses') !!}</div>
                            @if($userPkl && $userPkl->catatan_pembimbing)
                                <div class="mt-2 small text-muted">
                                    <strong>Komentar:</strong> {{ $userPkl->catatan_pembimbing }}
                                </div>
                            @endif
                            @if($userPkl && $userPkl->tanggal_validasi_pembimbing)
                                <div class="mt-1 small text-muted">
                                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($userPkl->tanggal_validasi_pembimbing)->format('d M Y, H:i') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="me-3 fs-3">
                            {!! getStatusIcon($userPkl ? $userPkl->status_waka_humas : 'proses') !!}
                        </div>
                        <div>
                            <h6 class="mb-1">Waka Humas</h6>
                            <div>{!! getStatusBadge($userPkl ? $userPkl->status_waka_humas : 'proses') !!}</div>
                            @if($userPkl && $userPkl->catatan_waka_humas)
                                <div class="mt-2 small text-muted">
                                    <strong>Komentar:</strong> {{ $userPkl->catatan_waka_humas }}
                                </div>
                            @endif
                            @if($userPkl && $userPkl->tanggal_validasi_waka_humas)
                                <div class="mt-1 small text-muted">
                                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($userPkl->tanggal_validasi_waka_humas)->format('d M Y, H:i') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">                   
                     <thead class="table-light">                       
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Nama Kegiatan</th>
                            <th scope="col">Detail</th>
                            <th scope="col" class="text-center">Dokumentasi</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logbooks as $logbookEntry) {{-- Mengubah nama variabel agar lebih jelas --}}
                            <tr>
                                <td class="text-center">{{ $loop->iteration + ($logbooks->currentPage() - 1) * $logbooks->perPage() }}</td>
                                <td>{{ \Carbon\Carbon::parse($logbookEntry->tanggal)->format('d M Y') }}</td>
                                <td>{{ Str::limit($logbookEntry->nama ?? '-', 50) }}</td>
                                <td>{{ Str::limit($logbookEntry->detail ?? '-', 70) }}</td>
                                <td class="text-center">
                                    @if($logbookEntry->dokumentasi)
                                        <a href="{{ asset('storage/' . $logbookEntry->dokumentasi) }}" target="_blank" class="btn btn-sm btn-outline-info" title="Lihat Dokumentasi">
                                            <i class="bi bi-file-earmark-image"></i>
                                        </a>
                                    @else
                                        <span class="text-muted small">Tidak ada</span>
                                    @endif                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('siswa-logbook-edit', $logbookEntry->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $logbookEntry->id }})" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="deleteForm{{ $logbookEntry->id }}" action="{{ route('siswa-logbook-destroy', $logbookEntry->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="mb-2">
                                        <i class="bi bi-journal-text" style="font-size: 3rem; color: #6c757d;"></i>
                                    </div>
                                    <p class="text-muted mb-0">Belum ada entri logbook yang ditambahkan.</p>
                                    <p class="text-muted small">Mulai tambahkan kegiatan PKL Anda sekarang!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $logbooks->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(logbookId) {
        if (confirm('Anda yakin ingin menghapus entri logbook ini?')) {
            document.getElementById('deleteForm' + logbookId).submit();
        }
    }
</script>

<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    .table th, .table td {
        white-space: nowrap; /* Prevent text wrapping in table cells */
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .table td:nth-child(3), .table td:nth-child(4) { /* Nama Kegiatan, Detail */
        max-width: 150px; /* Limit width for text-truncate */
    }
</style>
@endsection