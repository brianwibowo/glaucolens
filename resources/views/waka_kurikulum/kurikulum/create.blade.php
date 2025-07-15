@extends('layouts.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="h4 mb-4">Ajukan Kurikulum</h1>

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('waka-kurikulum-store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Kurikulum <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" name="tahun" id="tahun" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun') }}" placeholder="Contoh: 2025/2026" required>
                            @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="perusahaan_id" class="form-label">Perusahaan Tujuan <span class="text-danger">*</span></label>
                            <select class="form-select @error('perusahaan_id') is-invalid @enderror" id="perusahaan_id" name="perusahaan_id" required>
                                <option value="" disabled selected>-- Pilih Perusahaan --</option>
                                @foreach($perusahaanUsers as $perusahaan)
                                    <option value="{{ $perusahaan->id }}" {{ old('perusahaan_id') == $perusahaan->id ? 'selected' : '' }}>{{ $perusahaan->name }}</option>
                                @endforeach
                            </select>
                            @error('perusahaan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Kurikulum <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="file" class="form-label">File Kurikulum (PDF) <span class="text-danger">*</span></label>
                            <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" required>
                            <small class="text-muted">File harus dalam format PDF (maksimal 5MB)</small>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
          <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('waka-kurikulum-list-diajukan') }}" class="btn btn-light me-2">Batal</a>
            <button type="submit" class="btn btn-primary">Ajukan Kurikulum</button>
        </div>
    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
