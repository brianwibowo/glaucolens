@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Status PKL Saya</h1>
    </div>

    @php
        $isPklValidated = false;
        $validationStatusText = 'Menunggu Validasi';
        $validationBadgeClass = 'bg-warning text-dark';

        if (auth()->user()->pkl_status === 'disetujui') {
            $isPklValidated = true;
            $validationStatusText = 'Tervalidasi';
            $validationBadgeClass = 'bg-success';
        } elseif (auth()->user()->pkl_status === 'tidak_disetujui') {
            $validationStatusText = 'Ditolak';
            $validationBadgeClass = 'bg-danger';
        } elseif (auth()->user()->pkl_status === 'proses') {
            $validationStatusText = 'Menunggu Validasi Perusahaan';
            $validationBadgeClass = 'bg-warning text-dark';
        }

    @endphp

    @if ($pkl && !$isPklValidated)
        <div class="card shadow-sm mb-4 border-{{ $validationBadgeClass === 'bg-warning text-dark' ? 'warning' : 'danger' }}">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                Status Pendaftaran PKL
                <span class="badge {{ $validationBadgeClass }}">{{ $validationStatusText }}</span>
            </div>
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    @if($validationStatusText === 'Tervalidasi')
                        <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                    @elseif($validationStatusText === 'Ditolak')
                        <i class="bi bi-x-circle text-danger" style="font-size: 4rem;"></i>
                    @else
                        <i class="bi bi-hourglass-split text-warning" style="font-size: 4rem;"></i>
                    @endif
                </div>
                <h5 class="card-title text-muted">Anda telah mendaftar untuk PKL: <strong>{{ $pkl->nama ?? '-' }}</strong></h5>
                <p class="card-text text-muted">
                    @if($validationStatusText === 'Ditolak')
                        Pendaftaran Anda ditolak. Silakan hubungi admin atau pembimbing untuk informasi lebih lanjut.
                    @else
                        Pendaftaran Anda saat ini **{{ strtolower($validationStatusText) }}**. Silakan tunggu konfirmasi dari pihak berwenang.
                    @endif
                </p>
                <p class="text-muted small mt-3">
                    Perusahaan: <strong>{{ $pkl->perusahaan->name ?? 'N/A' }}</strong><br>
                </p>
            </div>
        </div>
    @elseif ($pkl && $isPklValidated)
        {{-- Tampilan saat PKL sudah tervalidasi --}}
        <div class="card shadow-sm mb-4 border-success">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                Detail PKL Anda
                <span class="badge {{ $validationBadgeClass }}">{{ $validationStatusText }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h5>Judul PKL</h5>
                        <p class="text-muted">{{ $pkl->nama ?? '-' }}</p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <h5>Perusahaan</h5>
                        <p class="text-muted">{{ $pkl->perusahaan->name ?? 'Perusahaan tidak ditemukan' }}</p>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h5>Status Pendaftaran PKL (Umum)</h5>
                        <span class="badge bg-{{ auth()->user()->pkl_status === 'proses' ? 'warning' : 'success' }}">{{ auth()->user()->pkl_status }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h5>Progress PKL</h5>
                        @php
                            $progress = $pkl->calculateProgress();
                            $progressPercentage = round($progress['percentage'], 1);

                            $progressBarColor = 'bg-danger';
                            if ($progressPercentage >= 100) {
                                $progressBarColor = 'bg-success';
                            } elseif ($progressPercentage >= 75) {
                                $progressBarColor = 'bg-info';
                            } elseif ($progressPercentage >= 50) {
                                $progressBarColor = 'bg-primary';
                            } elseif ($progressPercentage >= 25) {
                                $progressBarColor = 'bg-warning';
                            }
                        @endphp

                        <div class="d-flex justify-content-between align-items-center mb-1">
                            @if($progressPercentage >= 100)
                                <span class="badge bg-success">Selesai</span>
                            @elseif($progressPercentage > 0)
                                <span class="badge bg-primary">Berlangsung</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum Dimulai</span>
                            @endif

                            <span class="badge bg-secondary">{{ $progressPercentage }}%</span>
                        </div>

                        <div class="progress" style="height: 12px;">
                            <div class="progress-bar {{ $progressBarColor }}" role="progressbar"
                                style="width: {{ $progressPercentage }}%; transition: width 0.5s;"
                                aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>

                @if (auth()->user()->logbook->logbookContents()->count() < 2)
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h5>Upload Laporan Akhir</h5>
                            <p>Logbook Anda belum lengkap. Silakan upload logbook Anda sebelum melanjutkan. {{ auth()->user()->logbook->logbookContents()->count() }}/50</p>
                            <a href="{{ route('siswa-logbook-index', $pkl->id) }}" class="btn btn-primary">Upload Logbook</a>
                        </div>
                    </div>
                @else
                    @if(auth()->user()->laporan_pkl === null)
                        <form action="{{ route('siswa-pkl-uploadLaporan', $pkl->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h5>Upload Laporan Akhir</h5>
                                    <div class="d-flex gap-2">
                                        <input class="form-control" type="file" name="laporan_akhir" id="laporan_akhir">
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                    </div>
                                </div>
                            </div>
                        </form>                    @else
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h5>Upload Laporan Akhir</h5>
                                <p class="text-muted">Laporan akhir telah diupload.</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ asset('storage/' . auth()->user()->laporan_pkl) }}" class="btn btn-primary" target="_blank">Lihat Laporan</a>
                                    
                                    @if($pkl->status_waka_humas === 'ditolak' || $pkl->status_pembimbing === 'revisi')
                                    <form action="{{ route('siswa-pkl-uploadLaporan', $pkl->id) }}" method="post" enctype="multipart/form-data" class="mt-3 w-100">
                                        @csrf
                                        <div class="alert alert-warning">
                                            <strong>Perhatian!</strong> Laporan Anda perlu direvisi.
                                            @if($pkl->catatan_waka_humas)
                                                <p class="mb-0 mt-1"><strong>Catatan Waka Humas:</strong> {{ $pkl->catatan_waka_humas }}</p>
                                            @endif
                                            @if($pkl->catatan_pembimbing)
                                                <p class="mb-0 mt-1"><strong>Catatan Pembimbing:</strong> {{ $pkl->catatan_pembimbing }}</p>
                                            @endif
                                        </div>
                                        <div class="input-group mt-2">
                                            <input class="form-control" type="file" name="laporan_akhir" id="laporan_akhir" required>
                                            <button type="submit" class="btn btn-warning">Upload Ulang</button>
                                        </div>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                  <h5 class="mt-4">Informasi Validasi & Penempatan</h5>
                <ul class="list-group list-group-flush">                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Status Validasi Laporan Akhir oleh Pembimbing:
                        <span class="badge {{ $pkl->status_pembimbing === 'disetujui' ? 'bg-success' : ($pkl->status_pembimbing === 'revisi' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                            {{ ucfirst($pkl->status_pembimbing ?? 'proses') }}
                        </span>
                    </li>
                    @if($pkl->status_pembimbing != 'proses' && $pkl->catatan_pembimbing)
                    <li class="list-group-item">
                        <div>Catatan Pembimbing:</div>
                        <div class="alert alert-{{ $pkl->status_pembimbing === 'disetujui' ? 'success' : 'warning' }} mt-2">
                            {{ $pkl->catatan_pembimbing }}
                        </div>
                    </li>
                    @endif
                    @if($pkl->tanggal_validasi_pembimbing)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Tanggal Validasi Pembimbing:
                        <span class="text-muted">{{ \Carbon\Carbon::parse($pkl->tanggal_validasi_pembimbing)->format('d F Y, H:i') }}</span>
                    </li>
                    @endif
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Status Validasi Laporan Akhir oleh Waka Humas:
                        <span class="badge {{ $pkl->status_waka_humas === 'disetujui' ? 'bg-success' : ($pkl->status_waka_humas === 'ditolak' ? 'bg-danger' : 'bg-secondary') }}">
                            {{ ucfirst($pkl->status_waka_humas ?? 'proses') }}
                        </span>
                    </li>
                    @if($pkl->status_waka_humas != 'proses' && $pkl->catatan_waka_humas)
                    <li class="list-group-item">
                        <div>Catatan Waka Humas:</div>
                        <div class="alert alert-{{ $pkl->status_waka_humas === 'disetujui' ? 'success' : 'danger' }} mt-2">
                            {{ $pkl->catatan_waka_humas }}
                        </div>
                    </li>
                    @endif
                    @if($pkl->tanggal_validasi_waka_humas)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Tanggal Validasi Waka Humas:
                        <span class="text-muted">{{ \Carbon\Carbon::parse($pkl->tanggal_validasi_waka_humas)->format('d F Y, H:i') }}</span>
                    </li>
                    @endif
                    @if($pkl->pembimbing)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Pembimbing Lapangan:
                        <span class="text-muted">{{ $pkl->pembimbing->name }}</span>
                    </li>
                    @endif
                    {{-- Tambahkan info lain yang hanya relevan setelah tervalidasi --}}
                    {{-- Misal: Alamat lengkap perusahaan, kontak PIC, jadwal bimbingan, dll. --}}
                </ul>

                <hr class="my-4">

                <div class="row text-muted small">
                    <div class="col-md-6">
                        <p class="mb-1">Dibuat pada: {{ $pkl->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-1">Terakhir diperbarui: {{ $pkl->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Tampilan jika $pkl adalah null (misal ada masalah di controller) --}}
        <div class="card shadow-sm mb-4 border-danger">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                </div>
                <h5 class="card-title text-muted">Terjadi kesalahan atau data PKL tidak ditemukan.</h5>
                <p class="card-text text-muted">
                    Mohon kembali ke daftar PKL dan coba lagi, atau hubungi administrator jika masalah berlanjut.
                </p>
            </div>
        </div>
    @endif
</div>
@endsection

<style>
    .card {
        transition: all 0.3s ease;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    .card-footer {
        border-top: 1px solid #dee2e6;
    }

    .card-footer .btn {
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }
    .card-footer .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }
    .card-footer .btn-secondary,
    .card-footer .btn-info {
        cursor: not-allowed;
        opacity: 0.8;
    }
</style>