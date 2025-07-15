@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 h4">Ajukan Ruang PKL</h1>

    <form action="{{ route('perusahaan-pkl-store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="nama" class="form-label">Judul</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
            <input type="date"
                   name="tanggal_mulai"
                   id="tanggal_mulai"
                   class="form-control @error('tanggal_mulai') is-invalid @enderror"
                   min="{{ date('Y-m-d') }}"
                   required>
        </div>

        <div class="mb-3">
            <label for="tanggal_selesai" class="form-label">Tanggal Target Selesai</label>
            <input type="date"
                   name="tanggal_selesai"
                   id="tanggal_selesai"
                   class="form-control @error('tanggal_selesai') is-invalid @enderror"
                   min="{{ date('Y-m-d') }}"
                   required>
        </div>

        <button type="submit" class="btn btn-primary w-10">Ajukan</button>
    </form>
</div>
@endsection
