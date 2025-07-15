@extends('layouts.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h1 class="h4 mb-4">Ajukan Kurikulum Atas Nama Perusahaan</h1>
                    
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Sebagai admin, Anda membantu perusahaan untuk mengajukan kurikulum ke sekolah.
                    </div>
                    
                    <form action="{{ route('admin-kurikulum-store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="submission_type" value="company">
                        
                        <div class="mb-3">
                            <label for="company_submitter_id" class="form-label">Perusahaan Pengaju <span class="text-danger">*</span></label>
                            <select class="form-select @error('company_submitter_id') is-invalid @enderror" id="company_submitter_id" name="company_submitter_id" required>
                                <option value="" disabled selected>-- Pilih Perusahaan --</option>
                                @foreach($perusahaanUsers as $perusahaan)
                                    <option value="{{ $perusahaan->id }}" {{ old('company_submitter_id') == $perusahaan->id ? 'selected' : '' }}>{{ $perusahaan->name }}</option>
                                @endforeach
                            </select>
                            @error('company_submitter_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Kurikulum <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="tahun" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tahun') is-invalid @enderror" id="tahun" name="tahun" value="{{ old('tahun') }}" placeholder="Contoh: 2025/2026" required>
                            @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Kurikulum <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="file" class="form-label">File Kurikulum (PDF) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".pdf" required>
                            <small class="text-muted">Unggah dokumen kurikulum dalam format PDF (maksimal 5MB)</small>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin-kurikulum-list-diajukan') }}" class="btn btn-light me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Ajukan Kurikulum</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
