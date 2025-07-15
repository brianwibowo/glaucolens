@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Penugasan Pembimbing PKL</h1>
        <a href="{{ route('admin-pkl-index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Daftar Program PKL</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Program PKL</th>
                            <th>Perusahaan</th>
                            <th>Periode</th>
                            <th>Pembimbing</th>
                            <th>Jumlah Siswa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pkls as $index => $pkl)
                            <tr>
                                <td>{{ $index + 1 + ($pkls->currentPage() - 1) * $pkls->perPage() }}</td>
                                <td>{{ $pkl->nama }}</td>
                                <td>{{ $pkl->perusahaan->name ?? 'N/A' }}</td>
                                <td>{{ $pkl->tanggal_mulai->format('d/m/Y') }} - {{ $pkl->tanggal_selesai->format('d/m/Y') }}</td>
                                <td>
                                    @if($pkl->pembimbing)
                                        <span class="badge bg-success">{{ $pkl->pembimbing->name }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Ditugaskan</span>
                                    @endif
                                </td>
                                <td>{{ $pkl->siswas->count() }} siswa</td>
                                <td>
                                    <a href="{{ route('admin-pkl-assign-pembimbing-form', $pkl->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-person-plus"></i> {{ $pkl->pembimbing ? 'Ubah Pembimbing' : 'Tugaskan Pembimbing' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada program PKL yang tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $pkls->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
