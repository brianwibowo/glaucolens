@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Detail Pelamar</h1>
            {{-- Breadcrumb untuk navigasi yang lebih baik --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin-scouting-index') }}">Daftar Batch</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $pelamar->user->name }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin-scouting-show', $pelamar->batch->id) }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Daftar Pelamar
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profil Pelamar</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($pelamar->user->name) }}&background=random" class="rounded-circle me-3" width="80" height="80" alt="Avatar">
                        <div>
                            <h4 class="mb-0">{{ $pelamar->user->name }}</h4>
                            <p class="text-muted mb-0">{{ $pelamar->user->email }}</p>
                            <p class="text-muted mb-0">Melamar pada: {{ $pelamar->created_at->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Dokumen Terlampir</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        {{-- File CV --}}
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-alt fa-fw me-2 text-primary"></i>
                                Curriculum Vitae (CV)
                            </div>
                            <a href="{{ Storage::url($pelamar->file_cv) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i> Unduh
                            </a>
                        </li>
                        {{-- File Ijazah --}}
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-invoice fa-fw me-2 text-success"></i>
                                Ijazah
                            </div>
                            <a href="{{ Storage::url($pelamar->file_ijazah) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-download me-1"></i> Unduh
                            </a>
                        </li>
                        {{-- File Pernyataan --}}
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-signature fa-fw me-2 text-warning"></i>
                                Pernyataan
                            </div>
                            <a href="{{ Storage::url($pelamar->file_pernyataan) }}" target="_blank" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-download me-1"></i> Unduh
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status & Aksi</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">Status Saat Ini:</p>
                    <div class="text-center mb-3">
                        @if($pelamar->status_seleksi == 'lolos')
                            <span class="badge fs-6 bg-info">LOLOS SELEKSI</span>
                        @elseif($pelamar->status_seleksi == 'ditolak')
                            <span class="badge fs-6 bg-danger">DITOLAK</span>
                        @elseif($pelamar->status_seleksi == 'ditinjau')
                            <span class="badge fs-6 bg-warning">SEDANG DITINJAU</span>
                        @else
                            <span class="badge fs-6 bg-light text-dark">BARU</span>
                        @endif
                    </div>
                    
                    <hr>

                    <p class="mb-2 font-weight-bold">Ubah Status:</p>
                    <form action="{{ route('admin-scouting-seleksi', ['talent'=>$pelamar]) }}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ $pelamar->batch->id }}" name="batch">
                        <div class="mb-3">
                            <select name="status" class="form-select" required>
                                <option value="ditinjau" @if($pelamar->status_seleksi == 'proses') selected @endif>Ditinjau</option>
                                <option value="lolos" @if($pelamar->status_seleksi == 'lolos') selected @endif>Lolos Seleksi</option>
                                <option value="ditolak" @if($pelamar->status_seleksi == 'tidak lolos') selected @endif>Ditolak</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection