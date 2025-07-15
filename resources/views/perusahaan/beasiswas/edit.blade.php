@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Batch Beasiswa: <span class="text-primary">{{ $beasiswa->batch }}</span></h1>
        <a href="{{ route('perusahaan-beasiswa-index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Batal dan Kembali
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Perubahan Data Batch</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('perusahaan-beasiswa-update', $beasiswa->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama Batch --}}
                <div class="mb-4">
                    <label for="nama_batch" class="form-label font-weight-bold">Nama Batch</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama_batch" name="nama"
                            value="{{ old('nama', $beasiswa->batch) }}" placeholder="Contoh: Beasiswa Backend Engineer 2025" required>
                    </div>
                    @error('nama')
                        <div class="mt-2 text-sm text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tanggal Mulai (readonly) --}}
                <div class="mb-4">
                    <label for="tanggal_mulai" class="form-label font-weight-bold">Tanggal Mulai</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                        <input type="text" class="form-control" id="tanggal_mulai"
                            value="{{ \Carbon\Carbon::parse($beasiswa->tanggal_mulai)->translatedFormat('d F Y') }}" readonly>
                    </div>
                    <div class="form-text">Tanggal mulai tidak dapat diubah.</div>
                </div>

                {{-- Tanggal Selesai --}}
                <div class="mb-4">
                    <label for="tanggal_selesai" class="form-label font-weight-bold">Tanggal Selesai Pendaftaran</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-times"></i></span>
                        <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai"
                            name="tanggal_selesai"
                            value="{{ old('tanggal_selesai', \Carbon\Carbon::parse($beasiswa->tanggal_selesai)->format('Y-m-d')) }}"
                            required min="{{ now()->format('Y-m-d') }}">
                    </div>
                    @error('tanggal_selesai')
                        <div class="mt-2 text-sm text-danger">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Silakan ubah batas akhir pendaftaran sesuai kebutuhan.</div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="fas fa-save fa-sm text-white-50"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
