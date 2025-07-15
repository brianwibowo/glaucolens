@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Sertifikasi: {{ $certificationExam->nama_ujian }}</h1>

        <div class="card shadow-sm mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Pendaftaran</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('siswa-sertifikasi-store_registration', $certificationExam->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_sertifikasi" class="form-label">Nama Sertifikasi</label>
                        <input type="text" class="form-control" id="nama_sertifikasi"
                            value="{{ $certificationExam->nama_ujian }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" rows="3" readonly>{{ $certificationExam->deskripsi }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="kompetensi" class="form-label">Kompetensi yang Diminati</label>
                        <input type="text" class="form-control @error('kompetensi') is-invalid @enderror" id="kompetensi"
                            name="kompetensi" value="{{ old('kompetensi', $certificationExam->kompetensi_terkait ?? '') }}"
                            required>
                        {{-- Menggunakan ?? '' untuk memastikan tidak ada null --}}
                        @error('kompetensi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="dokumen_persyaratan" class="form-label">Upload Dokumen Persyaratan (PDF, max
                            5MB)</label>
                        <input type="file" class="form-control @error('dokumen_persyaratan') is-invalid @enderror"
                            id="dokumen_persyaratan" name="dokumen_persyaratan" accept="application/pdf" required>
                        @error('dokumen_persyaratan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Daftar Sertifikasi</button>
                    <a href="{{ route('siswa-sertifikasi-index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
