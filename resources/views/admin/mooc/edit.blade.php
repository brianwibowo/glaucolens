@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 h4">Edit Pelatihan MOOC: {{ $mooc->judul_pelatihan }}</h1>

    {{-- Form untuk mengedit MOOC --}}
    <form action="{{ route('admin-mooc-update', $mooc->id) }}" method="POST" enctype="multipart/form-data">
        @csrf {{-- Token CSRF untuk keamanan --}}
        @method('PUT') {{-- Metode PUT untuk update resource --}}

        <div class="mb-3">
            <label for="judul_pelatihan" class="form-label">Judul Pelatihan</label>
            <input type="text" name="judul_pelatihan" id="judul_pelatihan" class="form-control @error('judul_pelatihan') is-invalid @enderror" value="{{ old('judul_pelatihan', $mooc->judul_pelatihan) }}" required>
            @error('judul_pelatihan')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi Pelatihan</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" required>{{ old('deskripsi', $mooc->deskripsi) }}</textarea>
            @error('deskripsi')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="link_materi" class="form-label">Link Materi (Opsional)</label>
            <input type="url" name="link_materi" id="link_materi" class="form-control @error('link_materi') is-invalid @enderror" value="{{ old('link_materi', $mooc->link_materi) }}" placeholder="Contoh: https://youtube.com/link-video">
            @error('link_materi')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="dokumen_materi" class="form-label">Dokumen Materi (PDF, Doc, dll.)</label>
            <input type="file" name="dokumen_materi" id="dokumen_materi" class="form-control @error('dokumen_materi') is-invalid @enderror">
            <div class="form-text">Biarkan kosong jika tidak ingin mengubah dokumen. Maksimal ukuran file: 2MB</div>
            @error('dokumen_materi')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            {{-- Tampilkan dokumen yang sudah ada jika ada --}}
            @if($mooc->dokumen_materi)
                <p class="mt-2">Dokumen saat ini: <a href="{{ asset('storage/'.$mooc->dokumen_materi) }}" target="_blank">{{ basename($mooc->dokumen_materi) }}</a></p>
            @else
                <p class="mt-2 text-muted">Belum ada dokumen yang diunggah.</p>
            @endif
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary">Perbarui Pelatihan</button>
            <a href="{{ route('admin-mooc-index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection