@extends('layouts.layout')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-0">Detail PKL: {{ $pkl->nama }}</h1>
            <p class="text-muted mb-0">
                <i class="bi bi-building"></i> {{ $pkl->perusahaan->name ?? 'Nama Perusahaan' }}
            </p>
        </div>
        <a href="{{ route('guru-pkl-index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @php
        // Mendapatkan progress PKL
        $progress = $pkl->calculateProgress();
        $progressPercentage = $progress['percentage'];
        $progressStatus = $progress['status'];

        // Set warna progress bar berdasarkan persentase
        $progressBarColor = 'bg-info';
        if ($progressPercentage >= 100) {
            $progressBarColor = 'bg-success';
        } elseif ($progressPercentage >= 75) {
            $progressBarColor = 'bg-info';
        } elseif ($progressPercentage >= 50) {
            $progressBarColor = 'bg-primary';
        } elseif ($progressPercentage >= 25) {
            $progressBarColor = 'bg-warning';
        } else {
            $progressBarColor = 'bg-danger';
        }

        // Logika untuk status utama PKL
        $status = $pkl->status;
        $badgeClass = '';
        $statusText = '';
        switch ($status) {
            case 'proses':
                $badgeClass = 'alert-warning';
                $statusText = 'Proses';
                break;
            case 'berjalan':
                $badgeClass = 'alert-primary';
                $statusText = 'Sedang Berjalan';
                break;
            case 'selesai':
                $badgeClass = 'alert-success';
                $statusText = 'Selesai';
                break;
        }
    @endphp

    <div class="alert {{ $badgeClass }} d-inline-block mb-2" role="alert">
        Status PKL Saat Ini: <strong>{{ $statusText }}</strong>
    </div>
    
    <div class="mb-4">
        <p class="mb-1"><strong>Progress PKL:</strong> <span class="badge bg-secondary">{{ $progressPercentage }}%</span></p>
        <div class="progress" style="height: 15px;">
            <div class="progress-bar {{ $progressBarColor }}" role="progressbar" 
                 style="width: {{ $progressPercentage }}%;" 
                 aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                {{ $progressPercentage }}%
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle-fill text-primary"></i> Detail Informasi PKL</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Nama Program</th>
                            <td>{{ $pkl->nama }}</td>
                        </tr>
                        <tr>
                            <th>Perusahaan</th>
                            <td>{{ $pkl->perusahaan->name ?? 'Tidak ada' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Mulai</th>
                            <td>{{ $pkl->tanggal_mulai->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Selesai</th>
                            <td>{{ $pkl->tanggal_selesai->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Durasi</th>
                            <td>{{ $pkl->tanggal_mulai->diffInDays($pkl->tanggal_selesai) + 1 }} hari</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($pkl->siswas->isNotEmpty() && $pkl->siswas->first()->laporan_pkl)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text text-primary"></i> Laporan Akhir PKL</h5>
                    <div>
                        @php
                            $validationClass = 'secondary';
                            $validationText = 'Belum Divalidasi';
                            
                            if ($pkl->status_pembimbing === 'disetujui') {
                                $validationClass = 'success';
                                $validationText = 'Disetujui';
                            } elseif ($pkl->status_pembimbing === 'revisi') {
                                $validationClass = 'warning';
                                $validationText = 'Revisi';
                            }
                        @endphp
                        <span class="badge bg-{{ $validationClass }}">{{ $validationText }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <p class="mb-0">Laporan akhir telah diunggah oleh siswa</p>
                        <a href="{{ asset('storage/' . $pkl->siswas->first()->laporan_pkl) }}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="bi bi-download"></i> Unduh Laporan
                        </a>
                    </div>

                    <form action="{{ route('guru-pkl-validate-report', $pkl->siswas->first()->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Validasi</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="disetujui" {{ $pkl->status_pembimbing === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="revisi" {{ $pkl->status_pembimbing === 'revisi' ? 'selected' : '' }}>Revisi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan untuk Siswa</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3">{{ $pkl->catatan_pembimbing }}</textarea>
                            <div class="form-text">Berikan catatan atau masukan untuk laporan akhir (opsional).</div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan Validasi</button>
                        </div>
                    </form>

                    @if($pkl->tanggal_validasi_pembimbing)
                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted">Terakhir divalidasi pada: {{ $pkl->tanggal_validasi_pembimbing->format('d F Y, H:i') }}</small>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <i class="bi bi-file-earmark-x" style="font-size: 3rem; color: #ccc;"></i>
                    </div>
                    <h5 class="text-muted">Belum Ada Laporan Akhir</h5>
                    <p class="text-muted mb-0">
                        Siswa belum mengunggah laporan akhir PKL. Validasi hanya bisa dilakukan setelah laporan diunggah.
                    </p>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-people-fill text-primary"></i> Siswa PKL ({{ $pkl->siswas->count() }})</h5>
                    <a href="{{ route('guru-pkl-siswa-list', $pkl->id) }}" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-list"></i> Lihat Semua Siswa
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($pkl->siswas->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($pkl->siswas->take(3) as $siswa)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $siswa->name }}</span>                            <div>
                                <a href="{{ route('guru-pkl-siswa-logbook', $siswa->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-journal-text"></i> Logbook
                                </a>
                            </div>
                        </li>
                        @endforeach
                        @if($pkl->siswas->count() > 3)
                        <li class="list-group-item text-center">
                            <a href="{{ route('guru-pkl-siswa-list', $pkl->id) }}" class="text-decoration-none">
                                Lihat {{ $pkl->siswas->count() - 3 }} siswa lainnya
                            </a>
                        </li>
                        @endif
                    </ul>
                    @else
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">Belum ada siswa yang terdaftar dalam program PKL ini</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-check-circle-fill text-primary"></i> Status Validasi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-1 fw-bold">Pembimbing</p>
                            @php
                                $statusPembimbingClass = 'secondary';
                                if ($pkl->status_pembimbing === 'disetujui') {
                                    $statusPembimbingClass = 'success';
                                } elseif ($pkl->status_pembimbing === 'revisi') {
                                    $statusPembimbingClass = 'warning text-dark';
                                }
                            @endphp
                            <span class="badge bg-{{ $statusPembimbingClass }}">{{ ucfirst($pkl->status_pembimbing) }}</span>
                        </div>
                        <div class="col-6">
                            <p class="mb-1 fw-bold">Waka Humas</p>
                            @php
                                $statusWakaClass = 'secondary';
                                if ($pkl->status_waka_humas === 'disetujui') {
                                    $statusWakaClass = 'success';
                                } elseif ($pkl->status_waka_humas === 'ditolak') {
                                    $statusWakaClass = 'danger';
                                }
                            @endphp
                            <span class="badge bg-{{ $statusWakaClass }}">{{ ucfirst($pkl->status_waka_humas) }}</span>
                        </div>
                    </div>

                    @if($pkl->catatan_waka_humas)
                    <div class="mt-3 pt-3 border-top">
                        <p class="mb-1 fw-bold">Catatan dari Waka Humas:</p>
                        <p class="mb-0 bg-light p-2 rounded">{{ $pkl->catatan_waka_humas }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
