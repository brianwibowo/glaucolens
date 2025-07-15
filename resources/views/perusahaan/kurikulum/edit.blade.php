@extends('layouts.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="h4 mb-4">Edit Kurikulum</h1>

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('perusahaan-kurikulum-update', $kurikulum->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Kurikulum <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $kurikulum->nama_kurikulum) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" name="tahun" id="tahun" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun', $kurikulum->tahun_ajaran) }}" placeholder="Contoh: 2025/2026" required>
                            @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Kurikulum <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" required>{{ old('deskripsi', $kurikulum->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="file" class="form-label">Ganti File Kurikulum (Opsional)</label>
                            <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror">
                            <small class="text-muted">Unggah file PDF baru jika ingin mengganti file yang ada</small>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning mb-4"> {{-- Pesan peringatan tetap ada --}}
                            <i class="bi bi-exclamation-triangle me-2"></i> <strong>Perhatian:</strong> Mengedit kurikulum akan me-reset status validasi menjadi "Menunggu Validasi" dan Pihak Sekolah harus memvalidasi ulang.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('perusahaan-kurikulum-list-diajukan') }}" class="btn btn-light me-2">Batal</a>
                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection