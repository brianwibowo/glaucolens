@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 h4">Pengajuan Guru Tamu Baru</h1>

    <form action="{{ route('admin-guru-tamu-store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="perusahaan_id" class="form-label">Perusahaan</label>
            <select name="perusahaan_id" id="perusahaan_id" class="form-control @error('perusahaan_id') is-invalid @enderror" required>
                <option value="">Pilih Perusahaan</option>
                @foreach($perusahaans as $perusahaan)
                <option value="{{ $perusahaan->id }}" {{ old('perusahaan_id') == $perusahaan->id ? 'selected' : '' }}>{{ $perusahaan->name }}</option>
                @endforeach
            </select>
            @error('perusahaan_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Karyawan</label>
            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
            @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="jabatan" class="form-label">Jabatan</label>
            <input type="text" name="jabatan" id="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan') }}" required>
            @error('jabatan')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="keahlian" class="form-label">Keahlian</label>
            <input type="text" name="keahlian" id="keahlian" class="form-control @error('keahlian') is-invalid @enderror" value="{{ old('keahlian') }}" required>
            @error('keahlian')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" required>{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="jadwal" class="form-label">Jadwal</label>
            <input type="date" name="jadwal" id="jadwal" class="form-control @error('jadwal') is-invalid @enderror" value="{{ old('jadwal') }}" required>
            @error('jadwal')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="file_cv" class="form-label">File CV (opsional, format pdf)</label>
            <input type="file" name="file_cv" id="file_cv" class="form-control @error('file_cv') is-invalid @enderror">
            @error('file_cv')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="file_materi" class="form-label">File Materi (pdf)*</label>
            <input type="file" name="file_materi" id="file_materi" class="form-control @error('file_materi') is-invalid @enderror" required>
            @error('file_materi')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('admin-guru-tamu-index') }}" class="btn btn-secondary me-md-2">Batal</a>
            <button type="submit" class="btn btn-primary">Ajukan</button>
        </div>
    </form>
</div>
@endsection
