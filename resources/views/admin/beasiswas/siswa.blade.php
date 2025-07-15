@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Detail Pendaftar Beasiswa</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin-beasiswa-index') }}">Daftar Batch</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $pendaftar->nama_siswa }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin-beasiswa-show', $beasiswa->id) }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Batch
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            {{-- Kiri: Data Pelamar dan Dokumen --}}
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Identitas Pendaftar</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($pendaftar->nama_siswa) }}&background=random"
                                class="rounded-circle me-3" width="80" height="80" alt="Avatar">
                            <div>
                                <h4 class="mb-0">{{ $pendaftar->nama_siswa }}</h4>
                                <p class="text-muted mb-0">Status Saat Ini:
                                    @if ($pendaftar->status == 'lolos')
                                        <span class="badge bg-success">DITERIMA</span>
                                    @elseif($pendaftar->status == 'tidak lolos')
                                        <span class="badge bg-danger">DITOLAK</span>
                                    @else
                                        <span class="badge bg-warning text-dark">MENUNGGU REVIEW</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dokumen --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Dokumen Terlampir</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div><i class="fas fa-file-alt text-primary me-2"></i>Rapor</div>
                                <a href="{{ Storage::url($pendaftar->raport) }}" class="btn btn-sm btn-outline-primary"
                                    target="_blank">
                                    <i class="fas fa-download me-1"></i>Lihat
                                </a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div><i class="fas fa-file-signature text-success me-2"></i>Surat Rekomendasi</div>
                                <a href="{{ Storage::url($pendaftar->surat_rekomendasi) }}"
                                    class="btn btn-sm btn-outline-success" target="_blank">
                                    <i class="fas fa-download me-1"></i>Lihat
                                </a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div><i class="fas fa-file-signature text-warning me-2"></i>Surat Motivasi</div>
                                <a href="{{ Storage::url($pendaftar->surat_motivasi) }}"
                                    class="btn btn-sm btn-outline-warning" target="_blank">
                                    <i class="fas fa-download me-1"></i>Lihat
                                </a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div><i class="fas fa-briefcase text-info me-2"></i>Portofolio</div>
                                <a href="{{ Storage::url($pendaftar->portofolio) }}" class="btn btn-sm btn-outline-info"
                                    target="_blank">
                                    <i class="fas fa-download me-1"></i>Lihat
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Kanan: Form Seleksi --}}
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Seleksi & Aksi</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">Ubah Status Seleksi:</p>

                        <form
                            action="{{ route('admin-beasiswa-seleksi', [
                                'beasiswa' => $beasiswa->id,
                                'pendaftar' => $pendaftar->id,
                            ]) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="batch" value="{{ $beasiswa->id }}">
                            <div class="mb-3">
                                <select name="status" class="form-select" required>
                                    @foreach (\App\Models\Beasiswa::getStatusOptions() as $key => $label)
                                        <option value="{{ $key }}"
                                            @if ($pendaftar->status == $key) selected @endif>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
