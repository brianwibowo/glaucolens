@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Batch Beasiswa Talent Scouting</h1>
        <a href="{{ route('perusahaan-beasiswa-create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Ajukan Batch Baru
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Batch Beasiswa</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Batch</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($batches as $index => $b)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $b->batch }}</td>
                                <td>{{ \Carbon\Carbon::parse($b->tanggal_mulai)->translatedFormat('d F Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($b->tanggal_selesai)->translatedFormat('d F Y') }}</td>
                                <td>
                                    @if (\Carbon\Carbon::now()->lt($b->tanggal_selesai))
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Selesai</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('perusahaan-beasiswa-show', $b->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                    <a href="{{ route('perusahaan-beasiswa-edit', $b->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('perusahaan-beasiswa-destroy', $b->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus batch ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada batch beasiswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
