@extends('layouts.layout')

@section('content')
<div class="container my-4">

    @php
        // Helper untuk status persetujuan
        function getStatusBadge($status) {
            switch ($status) {
                case 'disetujui': return '<span class="badge bg-success">Disetujui</span>';
                case 'revisi': return '<span class="badge bg-danger">Revisi</span>';
                case 'proses': return '<span class="badge bg-warning text-dark">Proses</span>';
                default: return '<span class="badge bg-secondary">N/A</span>';
            }
        }

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

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap: 10px;">
        <div>
            <h1 class="h3 mb-0">{{ $pkl->nama }}</h1>
            <p class="mb-0 text-muted">
                <i class="bi bi-building"></i> {{ $pkl->perusahaan->nama ?? 'Nama Perusahaan' }}
            </p>
        </div>
        <div class="d-flex align-items-center">
             <a href="{{ route('perusahaan-pkl-index') }}" class="btn btn-secondary btn-sm me-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('perusahaan-pkl-edit', $pkl->id) }}" class="btn btn-warning btn-sm me-2">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $pkl->id }})">
                <i class="bi bi-trash"></i> Hapus
            </button>
            <form id="deleteForm{{ $pkl->id }}" action="{{ route('perusahaan-pkl-destroy', $pkl->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
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

    <hr>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle-fill text-primary"></i> Detail Informasi PKL</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-muted">INFORMASI UTAMA</h6>
                    <dl class="row mb-4">                        <dt class="col-sm-4"><i class="bi bi-person-check-fill text-muted me-1"></i> Pembimbing</dt>
                        <dd class="col-sm-8">{{ $pkl->pembimbing ? $pkl->pembimbing->name : 'Belum ditentukan' }}</dd>

                        <dt class="col-sm-4"><i class="bi bi-calendar-event text-muted me-1"></i> Tanggal Mulai</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($pkl->tanggal_mulai)->translatedFormat('d F Y') }}</dd>

                        <dt class="col-sm-4"><i class="bi bi-calendar-check text-muted me-1"></i> Tanggal Selesai</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($pkl->tanggal_selesai)->translatedFormat('d F Y') }}</dd>
                        
                        <dt class="col-sm-4"><i class="bi bi-clock-history text-muted me-1"></i> Durasi</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($pkl->tanggal_mulai)->diffInMonths(\Carbon\Carbon::parse($pkl->tanggal_selesai)) }} Bulan</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                 <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-people-fill text-primary"></i> Anggota Kelompok ({{ $pkl->siswas()->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    @if($pkl->siswas && $pkl->siswas->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($pkl->siswas as $index => $anggota)
                            <li class="list-group-item d-flex justify-content-between align-items-start px-3 py-2">
                                <div class="ms-2 me-auto d-flex gap-2">
                                    @if($anggota->pkl_status === 'disetujui')
                                        <span class="badge bg-success rounded-pill">diterima</span>
                                    @elseif($anggota->pkl_status === 'ditolak')
                                        <span class="badge bg-danger rounded-pill">ditolak</span>
                                    @else
                                        <span class="badge bg-warning rounded-pill">menunggu validasi</span>
                                    @endif
                                    <div class="fw-bold">{{ $anggota->name }}</div>
                                </div>
                                <a href="{{ route('perusahaan-pkl-siswa', ['user' => $anggota->id]) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                            </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="p-3">
                        <p class="text-muted">Tidak ada anggota dalam kelompok ini.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@if ($pkl->status_pembimbing == 'disetujui' && $pkl->status_waka_humas == 'disetujui')
<div class="modal fade" id="nilaiModal" tabindex="-1" aria-labelledby="nilaiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nilaiModalLabel">Input Nilai Akhir PKL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('perusahaan-pkl-nilai', $pkl->siswas->first()) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Berikan nilai akhir untuk kelompok <strong>{{ $pkl->nama }}</strong>.</p>
                    <div class="mb-3">
                        <label for="nilai" class="form-label">Nilai Akhir (Skala 0-100)</label>
                        <input type="number" class="form-control" id="nilai" name="nilai" 
                               min="0" max="100" 
                               value="{{ $pkl->nilai ?? 0 }}" 
                               required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif


<script>
// Fungsi konfirmasi hapus
function confirmDelete(id) {
    if (confirm('Anda yakin ingin menghapus data PKL ini secara permanen?')) {
        document.getElementById('deleteForm' + id).submit();
    }
}
</script>
@endsection