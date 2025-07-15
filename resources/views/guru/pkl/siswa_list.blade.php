@extends('layouts.layout')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-0">Daftar Siswa PKL</h1>
            <p class="text-muted mb-0">{{ $pkl->nama }}</p>
        </div>
        <a href="{{ route('guru-pkl-show', $pkl->id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if($pkl->siswas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Siswa</th>
                            <th>Email</th>
                            <th>Status PKL</th>
                            <th>Laporan Akhir</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pkl->siswas as $index => $siswa)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $siswa->name }}</td>
                            <td>{{ $siswa->email }}</td>
                            <td>
                                <span class="badge bg-{{ $siswa->pkl_status == 'disetujui' ? 'success' : ($siswa->pkl_status == 'tidak_disetujui' ? 'danger' : 'warning text-dark') }}">
                                    {{ ucfirst($siswa->pkl_status) }}
                                </span>
                            </td>
                            <td>
                                @if($siswa->laporan_pkl)
                                <a href="{{ asset('storage/' . $siswa->laporan_pkl) }}" target="_blank" class="btn btn-sm btn-success">
                                    <i class="bi bi-file-earmark-text"></i> Lihat
                                </a>
                                @else
                                <span class="badge bg-secondary">Belum ada</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('guru-pkl-siswa-logbook', $siswa->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-journal-text"></i> Logbook
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-people" style="font-size: 3rem; color: #ccc;"></i>
                </div>
                <h5 class="text-muted">Belum Ada Siswa</h5>
                <p class="text-muted mb-0">
                    Belum ada siswa yang terdaftar dalam program PKL ini.
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
