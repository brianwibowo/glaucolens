@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="h3 mb-0 text-gray-800">Buat Nama Sertifikasi/Batch Baru</h1>

    <div class="card shadow-sm mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Pembuatan Sertifikasi</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('lsp-sertifikasi-store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama_ujian" class="form-label">Nama Ujian/Sertifikasi</label>
                    <input type="text" class="form-control @error('nama_ujian') is-invalid @enderror" id="nama_ujian" name="nama_ujian" value="{{ old('nama_ujian') }}" required>
                    @error('nama_ujian')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="kompetensi_terkait" class="form-label">Kompetensi Terkait (Opsional)</label>
                    <input type="text" class="form-control @error('kompetensi_terkait') is-invalid @enderror" id="kompetensi_terkait" name="kompetensi_terkait" value="{{ old('kompetensi_terkait') }}">
                    @error('kompetensi_terkait')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan Sertifikasi</button>
                <a href="{{ route('lsp-sertifikasi-index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection