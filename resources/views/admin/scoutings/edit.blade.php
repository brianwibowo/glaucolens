@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Batch: <span class="text-primary">{{ $scouting->nama }}</span></h1>
        <a href="{{ route('admin-scouting-index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Batal dan Kembali
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Perubahan Data Batch</h6>
        </div>
        <div class="card-body">
            {{-- Form mengarah ke route 'update' dengan method 'PUT' --}}
            <form action="{{ route('admin-scouting-update', ['scouting'=>$scouting->id] ) }}" method="POST">
                @csrf
                @method('PUT') {{-- Penting untuk memberitahu Laravel ini adalah request UPDATE --}}
                
                <div class="mb-4">
                    <label for="nama_batch" class="form-label font-weight-bold">Nama Batch</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                        {{-- 'old()' digunakan untuk menjaga input pengguna jika validasi gagal --}}
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama_batch" name="nama" value="{{ old('nama', $scouting->batch) }}" placeholder="Contoh: Rekrutmen Frontend Developer Q3 2025" required>
                    </div>
                    @error('nama')
                        <div class="mt-2 text-sm text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tanggal_mulai" class="form-label font-weight-bold">Tanggal Mulai</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                        {{-- Tampilkan tanggal mulai yang sudah ada, tidak bisa diubah --}}
                        <input type="text" class="form-control" id="tanggal_mulai" value="{{ \Carbon\Carbon::parse($scouting->tanggal_mulai)->format('d M Y') }}" readonly>
                    </div>
                    <div class="form-text">Tanggal mulai tidak dapat diubah.</div>
                </div>

                <div class="mb-4">
                    <label for="tanggal_selesai" class="form-label font-weight-bold">Tanggal Selesai Pendaftaran</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-times"></i></span>
                        {{-- Input type 'date' memerlukan format Y-m-d untuk 'value' --}}
                        <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', \Carbon\Carbon::parse($scouting->tanggal_selesai)->format('Y-m-d')) }}" required min="{{ date('Y-m-d') }}">
                    </div>
                     @error('tanggal_selesai')
                        <div class="mt-2 text-sm text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="form-text">Anda dapat memperpanjang atau mempersingkat masa pendaftaran.</div>
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