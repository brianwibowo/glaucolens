@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Formulir Pendaftaran Beasiswa: {{ $beasiswaBatch->batch ?? '-' }}</h3>

    <div class="alert alert-info small">
        <strong>Catatan:</strong> Semua file wajib dalam format PDF/DOC/DOCX, maksimal 2MB.
    </div>

    <form action="{{ route('siswa-beasiswa-apply', $beasiswaBatch->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold">Nama Lengkap</label>
            <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Upload Rapor <span class="text-danger">*</span></label>
            <input type="file" class="form-control @error('raport') is-invalid @enderror" name="raport" required>
            @error('raport')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Upload Surat Rekomendasi <span class="text-danger">*</span></label>
            <input type="file" class="form-control @error('surat_rekomendasi') is-invalid @enderror" name="surat_rekomendasi" required>
            @error('surat_rekomendasi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Upload Surat Motivasi <span class="text-danger">*</span></label>
            <input type="file" class="form-control @error('surat_motivasi') is-invalid @enderror" name="surat_motivasi" required>
            @error('surat_motivasi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Upload Portofolio <span class="text-danger">*</span></label>
            <input type="file" class="form-control @error('portofolio') is-invalid @enderror" name="portofolio" required>
            @error('portofolio')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{ route('siswa-beasiswa-index') }}" class="btn btn-secondary me-2">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Kirim Lamaran
            </button>
        </div>
    </form>
</div>
@endsection
