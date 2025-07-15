@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="h3 mb-0 text-gray-800">Input Nilai & Sertifikat untuk Siswa: {{ $registration->siswa->name ?? 'N/A' }}</h1>
    <p class="mb-4">Sertifikasi: <strong>{{ $registration->exam->nama_ujian ?? 'N/A' }}</strong></p>

    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Penilaian & Sertifikat</h6>
        </div>
        <div class="card-body">
            {{-- Bagian ini dikosongkan karena nilai otomatis dari kuis tidak ada lagi --}}
            {{-- @if ($latestAttempt ?? '')
                <div class="alert alert-info">
                    Nilai terakhir dari upaya ujian siswa (otomatis): <strong>{{ $latestAttempt ?? ''->nilai ?? 'Belum ada nilai' }}</strong>
                </div>
            @else
                <div class="alert alert-warning">
                    Siswa ini belum mengerjakan ujian ini atau belum ada nilai upaya yang tercatat.
                </div>
            @endif --}}

            <form action="{{ route('lsp-sertifikasi-results.store_certificate', $registration->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="nilai" class="form-label">Nilai Akhir (0-100)</label>
                    <input type="number" class="form-control @error('nilai') is-invalid @enderror" id="nilai" name="nilai" value="{{ old('nilai', $registration->nilai) }}" required min="0" max="100">
                    @error('nilai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status Kelulusan</label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="">Pilih Status</option>
                        <option value="lulus" {{ old('status', $registration->status_pendaftaran_ujian) == 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="tidak_lulus" {{ old('status', $registration->status_pendaftaran_ujian) == 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="sertifikat" class="form-label">Upload Sertifikat Kelulusan (PDF, Opsional)</label>
                    <input type="file" class="form-control @error('sertifikat') is-invalid @enderror" id="sertifikat" name="sertifikat" accept=".pdf">
                    @error('sertifikat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if ($registration->sertifikat_kelulusan)
                        <small class="form-text text-muted">Sertifikat saat ini: <a href="{{ Storage::url($registration->sertifikat_kelulusan) }}" target="_blank">Lihat Sertifikat</a></small>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('lsp-sertifikasi-results') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection