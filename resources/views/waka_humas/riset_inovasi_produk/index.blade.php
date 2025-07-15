@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Daftar Riset/Inovasi Produk</h1>
        <a href="{{ route('waka-humas-riset-create') }}" class="btn btn-primary">
            + Ajukan Riset
        </a>
    </div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>Topik</th>
                <th>Deskripsi</th>
                <th>Anggota</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($risets as $riset)
                <tr>
                    <td>{{ $riset->topik }}</td>
                    <td>{{ $riset->deskripsi }}</td>
                    <td>
                        @foreach($riset->anggota as $anggota)
                            <span class="badge bg-secondary">{{ $anggota->user->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('waka-humas-riset-show', $riset) }}" class="btn btn-sm btn-info">Lihat</a>
                        <a href="{{ route('waka-humas-riset-edit', $riset) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('waka-humas-riset-destroy', $riset) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada data riset</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($risets->hasPages())
        <div class="mt-3">
            {{ $risets->links() }}
        </div>
    @endif
</div>
@endsection
