@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    {{-- Tombol "Ajukan Riset" telah dihapus --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Daftar Pengajuan Riset/Inovasi Produk</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 20%;">Topik</th>
                    <th style="width: 30%;">Deskripsi</th>
                    <th style="width: 15%;">Anggota</th>
                    <th style="width: 15%;">File Proposal</th> {{-- Kolom baru --}}
                    <th style="width: 15%;">Status</th>
                    <th style="width: 20%;">Aksi</th> {{-- Kolom aksi diubah --}}
                </tr>
            </thead>
            <tbody>
                @forelse($risets as $riset)
                    <tr>
                        <td>{{ $riset->topik }}</td>
                        <td>{{ Str::limit($riset->deskripsi, 100) }}</td>
                        <td>
                            @foreach($riset->anggota as $anggota)
                                <span class="badge bg-secondary mb-1">{{ $anggota->user->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            {{-- Tombol untuk melihat/mengunduh proposal --}}
                            @if($riset->file_proposal)
                                <a href="{{ Storage::url($riset->file_proposal) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-pdf me-1"></i> Lihat File
                                </a>
                            @else
                                <span class="text-muted">Tidak ada file</span>
                            @endif
                        </td>
                        <td>
                            @if($riset->status === 'proses')
                                <span class="badge bg-warning">Menunggu</span>
                            @elseif($riset->status === 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($riset->status === 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            {{-- Tombol Aksi "Terima" dan "Tolak" --}}
                            <div class="d-flex gap-2">
                                <form action="{{ route('perusahaan-riset-terima', $riset->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENERIMA riset ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success {{ $riset->status !== 'proses' ? 'disabled' : '' }}">
                                        <i class="fas fa-check me-1"></i> Terima
                                    </button>
                                </form>
                                <form action="{{ route('perusahaan-riset-tolak', $riset->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENOLAK riset ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-danger {{ $riset->status !== 'proses' ? 'disabled' : '' }}">
                                        <i class="fas fa-times me-1"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        {{-- Colspan disesuaikan menjadi 5 --}}
                        <td colspan="5" class="text-center">Belum ada pengajuan riset baru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($risets->hasPages())
        <div class="mt-3">
            {{ $risets->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
{{-- Jika Anda menggunakan Font Awesome, pastikan sudah di-load di layout utama --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush