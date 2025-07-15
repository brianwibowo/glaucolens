@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Pengajuan Guru Tamu</h4>
                </div>
                
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('perusahaan-guru-tamu-update', $guruTamu->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $guruTamu->nama_karyawan) }}" required>
        </div>

        <div class="mb-3">
            <label for="jabatan" class="form-label">Jabatan</label>
            <input type="text" name="jabatan" id="jabatan" class="form-control" value="{{ old('jabatan', $guruTamu->jabatan) }}" required>
        </div>

        <div class="mb-3">
            <label for="keahlian" class="form-label">Keahlian</label>
            <input type="text" name="keahlian" id="keahlian" class="form-control" value="{{ old('keahlian', $guruTamu->keahlian) }}" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" required>{{ old('deskripsi', $guruTamu->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="jadwal" class="form-label">Jadwal</label>
            <input type="date" name="jadwal" id="jadwal" class="form-control" value="{{ old('jadwal', \Carbon\Carbon::parse($guruTamu->jadwal)->format('Y-m-d')) }}" required>

        </div>

        <div class="mb-4">
            <label for="file_cv" class="form-label">Ganti File CV (opsional)</label>
            <input type="file" name="file_cv" id="file_cv" class="form-control">
            @if($guruTamu->file_cv)
                <small class="text-muted">CV saat ini: <a href="{{ asset('storage/'.$guruTamu->file_cv) }}" target="_blank">Lihat</a></small>
            @endif
        </div>

        <div class="mb-4">
            <label for="file_materi" class="form-label">Ganti File Materi (pdf)</label>
            <input type="file" name="file_materi" id="file_materi" class="form-control">
            @if($guruTamu->file_materi)
                <small class="text-muted">Materi saat ini: <a href="{{ asset('storage/'.$guruTamu->file_materi) }}" target="_blank">Lihat</a></small>
            @endif        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('perusahaan-guru-tamu-index') }}" class="btn btn-secondary me-2">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
