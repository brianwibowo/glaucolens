@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ajukan Batch Talent Scouting Baru</h1>
        <a href="{{ route('admin-scouting-index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Batch Baru</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin-scouting-store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="nama_batch" class="form-label font-weight-bold">Nama Batch</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama_batch" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Rekrutmen Frontend Developer Q3 2025" required>
                    </div>
                    @error('nama')
                        <div class="mt-2 text-sm text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                    <div id="namaHelp" class="form-text">Berikan nama yang deskriptif untuk batch rekrutmen ini.</div>
                </div>

                <div class="mb-4">
                    <label for="tanggal_mulai" class="form-label font-weight-bold">Tanggal Mulai</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                        <input type="text" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ date('Y-m-d') }}" readonly>
                    </div>
                    <div class="form-text">Tanggal mulai diatur otomatis ke hari ini dan tidak dapat diubah.</div>
                </div>

                <div class="mb-4">
                    <label for="tanggal_selesai" class="form-label font-weight-bold">Tanggal Selesai Pendaftaran</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-times"></i></span>
                        <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required min="{{ date('Y-m-d') }}">
                    </div>
                     @error('tanggal_selesai')
                        <div class="mt-2 text-sm text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="form-text">Tentukan batas akhir pendaftaran untuk batch ini.</div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="fas fa-save fa-sm text-white-50"></i> Simpan dan Buat Batch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection