@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 h4">Ajukan Kurikulum</h1>

    <form action="{{ route('perusahaan-kurikulum-store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Kurikulum</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="tahun" class="form-label">Tahun Ajaran</label>
            <input type="text" name="tahun" id="tahun" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" required></textarea>
        </div>

        <div class="mb-4">
            <label for="file" class="form-label">File Kurikulum</label>
            <input type="file" name="file" id="file" class="form-control" required>
          </div>

        <button type="submit" class="btn btn-primary w-10">Ajukan</button>
        <a href="{{ route('perusahaan-kurikulum-list-diajukan') }}" class="btn btn-secondary w-10">Batal</a>
    </form>
</div>
@endsection
