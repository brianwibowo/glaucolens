@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Edit Entri Logbook</h1>
        <a href="{{ route('siswa-logbook-index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Logbook
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            {{-- Form untuk mengedit Logbook --}}
            <form action="{{ route('siswa-logbook-update', $logbook->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- Penting: Gunakan method PUT untuk update --}}

                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', \Carbon\Carbon::parse($logbook->tanggal)->format('Y-m-d')) }}" required>
                    @error('tanggal')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $logbook->nama) }}" placeholder="Contoh: Belajar Laravel Dasar" required>
                    @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>                <div class="mb-3">
                    <label for="detail" class="form-label">Detail Kegiatan <span class="text-danger">*</span></label>
                    <textarea name="detail" id="detail" rows="5" class="form-control @error('detail') is-invalid @enderror" placeholder="Jelaskan secara rinci kegiatan yang Anda lakukan hari ini." required>{{ old('detail', $logbook->detail) }}</textarea>
                    @error('detail')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>                <div class="mb-3">
                    <label for="dokumentasi" class="form-label">Dokumentasi (Opsional)</label>
                    <input type="file" name="dokumentasi" id="dokumentasi" class="form-control @error('dokumentasi') is-invalid @enderror" accept="image/png, image/jpeg, image/jpg">
                    <div class="form-text">
                        Unggah foto terkait kegiatan (hanya format JPG dan PNG, maks 2MB).
                        @if($logbook->dokumentasi)
                            <br>Dokumen saat ini: <a href="{{ asset('storage/' . $logbook->dokumentasi) }}" target="_blank">{{ basename($logbook->dokumentasi) }}</a>
                            <p class="small text-muted mb-0">Biarkan kosong jika tidak ingin mengubah dokumentasi.</p>
                        @else
                            <br>Belum ada dokumentasi diunggah.
                        @endif
                    </div>
                    @error('dokumentasi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary me-2">Perbarui Logbook</button>
                <a href="{{ route('siswa-logbook-index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection