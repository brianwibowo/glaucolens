@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0">Edit Project Mitra</h1>
            <a href="{{ route('admin-project-index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Error!</strong> Ada beberapa masalah dengan input Anda.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin-project-update', $project) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="perusahaan_id" class="form-label">Perusahaan <span class="text-danger">*</span></label>
                        <select name="perusahaan_id" id="perusahaan_id" class="form-control @error('perusahaan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Perusahaan --</option>
                            @foreach($perusahaans as $perusahaan)
                                <option value="{{ $perusahaan->id }}" {{ (old('perusahaan_id', $project->perusahaan_id) == $perusahaan->id) ? 'selected' : '' }}>
                                    {{ $perusahaan->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('perusahaan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Project <span class="text-danger">*</span></label>
                        <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $project->judul) }}" required>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" id="deskripsi" rows="5" class="form-control @error('deskripsi') is-invalid @enderror" required>{{ old('deskripsi', $project->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai', $project->tanggal_mulai) }}" required>
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai', $project->tanggal_selesai) }}" required>
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="file_brief" class="form-label">File Brief</label>
                        <div class="input-group mb-3">
                            <input type="file" name="file_brief" id="file_brief" class="form-control @error('file_brief') is-invalid @enderror">
                            <label class="input-group-text" for="file_brief">Upload</label>
                        </div>
                        <small class="text-muted">Hanya file PDF, DOC, atau DOCX. Maksimal 10MB. Biarkan kosong jika tidak ingin mengubah file brief.</small>
                        
                        @if ($project->file_brief)
                            <div class="mt-2">
                                <span class="d-block">File Brief saat ini:</span>
                                <a href="{{ Storage::url($project->file_brief) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                    <i class="bi bi-file-earmark-text"></i> Lihat Brief
                                </a>
                            </div>
                        @endif

                        @error('file_brief')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-light">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('tanggal_mulai');
            const endDateInput = document.getElementById('tanggal_selesai');

            // Update min date for tanggal_selesai when tanggal_mulai changes
            startDateInput.addEventListener('change', function() {
                if (startDateInput.value) {
                    endDateInput.setAttribute('min', startDateInput.value);
                    
                    // If end date is before start date, reset it
                    if (endDateInput.value && endDateInput.value < startDateInput.value) {
                        endDateInput.value = '';
                    }
                }
            });

            // Set initial min value
            if (startDateInput.value) {
                endDateInput.setAttribute('min', startDateInput.value);
            }
        });
    </script>
@endsection
