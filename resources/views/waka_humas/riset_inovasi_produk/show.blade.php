@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 h4">Detail Riset/Inovasi Produk</h1>
    <div class="mb-3">
        <a href="{{ route('waka-humas-riset-index') }}" class="btn btn-secondary btn-sm">Kembali ke Daftar</a>
        <a href="{{ route('waka-humas-riset-edit', $riset) }}" class="btn btn-warning btn-sm">Edit</a>
        <form action="{{ route('waka-humas-riset-destroy', $riset) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus riset ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
        </form>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Topik: {{ $riset->topik }}</h5>
            <p class="card-text"><strong>Deskripsi:</strong><br>{{ $riset->deskripsi }}</p>
            <p class="card-text"><strong>Anggota Tim:</strong><br>
                @forelse($riset->anggota as $anggota)
                    <span class="badge bg-secondary">{{ $anggota->user->name }}</span>
                @empty
                    <span class="text-muted">Belum ada anggota tim</span>
                @endforelse
            </p>
            <p class="card-text"><strong>File Proposal:</strong>
                @if($riset->file_proposal)
                    <a href="{{ Storage::url($riset->file_proposal) }}" target="_blank">Unduh Proposal</a>
                @else
                    <span class="text-muted">Tidak ada file</span>
                @endif
            </p>
            @if($riset->status === 'disetujui')
                <p class="card-text"><strong>Dokumentasi:</strong>
                    @if($riset->dokumentasi)
                        <a href="{{ Storage::url($riset->dokumentasi) }}" target="_blank">Lihat Dokumentasi</a>
                    @else
                        <span class="text-muted">Tidak ada dokumentasi</span>
                        <form action="{{ route('waka-humas-riset-dokumentasi', $riset) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input class = "form-control" type="file" name="dokumentasi" id="dokumentasi" required accept="image/png, image/jpeg, image/jpg">
                            <button type="submit" class="btn btn-primary btn-sm mt-2">Unggah Dokumentasi</button>
                        </form>
                    @endif
                </p>
            @else
                <p class="card-text"><strong>Status:</strong> {{ $riset->status }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
