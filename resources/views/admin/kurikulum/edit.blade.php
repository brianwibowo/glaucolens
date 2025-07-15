@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 h4">Edit Kurikulum</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin-kurikulum-update', $kurikulum) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Kurikulum</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $kurikulum->nama_kurikulum) }}" required>
        </div>

        <div class="mb-3">
            <label for="tahun" class="form-label">Tahun Ajaran</label>
            <input type="text" name="tahun" id="tahun" class="form-control" value="{{ old('tahun', $kurikulum->tahun_ajaran) }}" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" required>{{ old('deskripsi', $kurikulum->deskripsi) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="file" class="form-label">File Kurikulum (PDF)</label>
            <input type="file" name="file" id="file" class="form-control">
            <small class="text-muted">File harus dalam format PDF, kosongkan jika tidak ingin mengubah file</small>
            @if($kurikulum->file_kurikulum)
                <div class="mt-2">
                    <p>File saat ini: <a href="{{ asset('storage/'.$kurikulum->file_kurikulum) }}" target="_blank">Lihat File</a></p>
                </div>
            @endif
        </div>        
        <div class="alert alert-warning mb-4">
            <p class="mb-0"><i class="fas fa-exclamation-triangle"></i> <strong>Perhatian:</strong> Mengedit kurikulum akan me-reset status validasi menjadi "Menunggu Validasi" dan Perusahaan harus memvalidasi ulang.</p>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin-kurikulum-list-diajukan') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
