@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Formulir Pendaftaran: {{ $scouting->batch }}</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info small">
                        <strong>Perhatian:</strong> Pastikan semua file dalam format PDF, DOC, atau DOCX dengan ukuran maksimal 2MB per file.
                    </div>

                    {{-- Formulir harus memiliki enctype untuk upload file --}}
                    <form action="{{ route('alumni-scouting-apply', $scouting->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="nama" class="form-label font-weight-bold">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" value="{{ Auth::user()->name }}" readonly>
                            <div class="form-text">Nama diambil dari data akun Anda dan tidak dapat diubah.</div>
                        </div>

                        <div class="mb-4">
                            <label for="cv" class="form-label font-weight-bold">File CV <span class="text-danger">*</span></label>
                            <input class="form-control @error('cv') is-invalid @enderror" type="file" id="cv" name="cv" required>
                            @error('cv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="ijazah" class="form-label font-weight-bold">File Ijazah Terakhir <span class="text-danger">*</span></label>
                            <input class="form-control @error('ijazah') is-invalid @enderror" type="file" id="ijazah" name="ijazah" required>
                             @error('ijazah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="pernyataan" class="form-label font-weight-bold">File Pernyataan <span class="text-danger">*</span></label>
                            <input class="form-control @error('pernyataan') is-invalid @enderror" type="file" id="pernyataan" name="pernyataan" required>
                             @error('pernyataan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('alumni-scouting-index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Kirim Lamaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection