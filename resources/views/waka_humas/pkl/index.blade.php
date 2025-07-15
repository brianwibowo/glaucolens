@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 h4">Daftar Laporan PKL</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Pembimbing</th>
                            <th>Perusahaan</th>
                            <th>Status Pembimbing</th>
                            <th>Status Waka Humas</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pkls as $pkl)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pkl->siswas->first()->name ?? '-' }}</td>
                            <td>{{ $pkl->pembimbing->name ?? '-' }}</td>
                            <td>{{ $pkl->perusahaan->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $pkl->status_pembimbing == 'disetujui' ? 'success' : 'warning' }}">
                                    {{ ucfirst($pkl->status_pembimbing) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $pkl->status_waka_humas == 'disetujui' ? 'success' : ($pkl->status_waka_humas == 'ditolak' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($pkl->status_waka_humas) }}
                                </span>
                            </td>
                            <td>{{ $pkl->nilai ?? '-' }}</td>
                            <td>                                <a href="{{ route('waka-humas-pkl-show', $pkl) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data laporan PKL</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $pkls->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
