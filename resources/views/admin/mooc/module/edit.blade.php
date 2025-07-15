@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h1 class="h4 mb-0">Edit Modul: {{ $module->module_name }}</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('admin-module-update', ['mooc' => $mooc->id, 'module' => $module->id]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3">Detail Modul</h5>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="module_name" class="form-label">Nama Modul <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('module_name') is-invalid @enderror"
                                id="module_name" name="module_name" value="{{ old('module_name', $module->module_name) }}"
                                required>
                            @error('module_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="deskripsi_modul" class="form-label">Deskripsi Modul</label>
                            <textarea class="form-control @error('deskripsi_modul') is-invalid @enderror" id="deskripsi_modul"
                                name="deskripsi_modul" rows="3">{{ old('deskripsi_modul', $module->deskripsi_modul) }}</textarea>
                            @error('deskripsi_modul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="link_materi" class="form-label">Link Materi (Opsional)</label>
                            <input type="url" class="form-control @error('link_materi') is-invalid @enderror"
                                id="link_materi" name="link_materi" value="{{ old('link_materi', $module->link_materi) }}"
                                placeholder="https://contoh.com/materi">
                            @error('link_materi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="dokumen_materi" class="form-label">Ganti Dokumen Materi (PDF, DOC, PPT,
                                Opsional)</label>
                            <input type="file" class="form-control @error('dokumen_materi') is-invalid @enderror"
                                id="dokumen_materi" name="dokumen_materi" accept=".pdf,.doc,.docx,.ppt,.pptx">
                            <div class="form-text">Biarkan kosong jika tidak ingin mengubah dokumen. Maksimal ukuran file:
                                10MB.</div>
                            @error('dokumen_materi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($module->dokumen_materi)
                                <p class="mt-2">Dokumen saat ini: <a href="{{ Storage::url($module->dokumen_materi) }}"
                                        target="_blank">{{ basename($module->dokumen_materi) }}</a></p>
                            @else
                                <p class="mt-2 text-muted">Belum ada dokumen yang diunggah.</p>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin-mooc-show', $mooc->id) }}" class="btn btn-secondary me-2">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
