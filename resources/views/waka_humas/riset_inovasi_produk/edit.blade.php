@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 h4">Edit Riset/Inovasi Produk</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('waka-humas-riset-update', $riset) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="topik" class="form-label">Topik Riset</label>
            <input type="text" name="topik" id="topik" class="form-control" value="{{ old('topik', $riset->topik) }}" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" required>{{ old('deskripsi', $riset->deskripsi) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="tim_riset" class="form-label">Anggota Tim</label>
            <select name="tim_riset[]" id="tim_riset" class="form-select" multiple required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ in_array($user->id, old('tim_riset', $riset->anggota->pluck('user_id')->toArray())) ? 'selected' : '' }}>{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
            <small class="text-muted">Gunakan Ctrl+Klik (Windows) atau Cmd+Klik (Mac) untuk memilih lebih dari satu anggota</small>
        </div>
        <div class="mb-3">
            <label for="file_proposal" class="form-label">File Proposal (PDF, maks. 10MB)</label>
            @if($riset->file_proposal)
                <div class="mb-2">
                    <a href="{{ Storage::url($riset->file_proposal) }}" target="_blank">Lihat File Proposal</a>
                </div>
            @endif
            <input type="file" name="file_proposal" id="file_proposal" class="form-control" accept=".pdf">
        </div>
        <div class="mb-3">
            <label for="dokumentasi" class="form-label">Dokumentasi (Gambar, maks. 2MB)</label>
            @if($riset->dokumentasi)
                <div class="mb-2">
                    <a href="{{ Storage::url($riset->dokumentasi) }}" target="_blank">Lihat Dokumentasi</a>
                </div>
            @endif
            <input type="file" name="dokumentasi" id="dokumentasi" class="form-control" accept="image/*">
        </div>
        <a href="{{ route('waka-humas-riset-index', $riset) }}" class="btn btn-success">Simpan Perubahan</a>
        <a href="{{ route('waka-humas-riset-index', $riset) }}" class="btn btn-secondary ms-2">Batal</a>
    </form>
</div>
@endsection
