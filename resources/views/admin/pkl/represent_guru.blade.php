@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="bi bi-info-circle-fill me-2"></i>
        <div>
            <span class="fw-bold">Mode Admin sebagai Guru:</span> 
            Anda saat ini bertindak atas nama guru <strong>{{ $guru->name }}</strong>
        </div>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Program PKL Yang Dibimbing</h1>
        <a href="{{ route('admin-pkl-select-guru') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Ganti Guru
        </a>
    </div>
    
    <div class="row">
        @forelse ($pkls as $pkl)
            @php
                $progress = $pkl->calculateProgress();
                $progressPercentage = $progress['percentage'];
                $progressStatus = $progress['status'];
                
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
            
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light">
                        <h5 class="card-title mb-0">{{ $pkl->nama }}</h5>
                        
                        @if($pkl->status_pembimbing === 'disetujui')
                            <span class="badge bg-success">Laporan Disetujui</span>
                        @elseif($pkl->status_pembimbing === 'revisi')
                            <span class="badge bg-warning text-dark">Perlu Revisi</span>
                        @else
                            <span class="badge bg-secondary">Belum Divalidasi</span>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between text-muted small mb-1">
                                <span>Progress PKL</span>
                                <span>{{ $progressPercentage }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar {{ $progressBarColor }}" role="progressbar" 
                                    style="width: {{ $progressPercentage }}%;" aria-valuenow="{{ $progressPercentage }}" 
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <i class="bi bi-calendar-range me-1"></i> Periode
                                </div>
                                <p class="small text-muted">
                                    {{ $pkl->tanggal_mulai->format('d M Y') }} s/d {{ $pkl->tanggal_selesai->format('d M Y') }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <i class="bi bi-building me-1"></i> Perusahaan
                                </div>
                                <p class="small text-muted">{{ $pkl->perusahaan->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <i class="bi bi-people me-1"></i> Jumlah Siswa
                                </div>
                                <p class="small text-muted">{{ $pkl->siswas->count() }} siswa</p>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <i class="bi bi-check-circle me-1"></i> Status PKL
                                </div>
                                <p class="small">
                                    @if($pkl->status == 'proses')
                                        <span class="badge bg-warning text-dark">Proses</span>
                                    @elseif($pkl->status == 'berjalan')
                                        <span class="badge bg-primary">Berjalan</span>
                                    @elseif($pkl->status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h6>Daftar Siswa:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pkl->siswas as $siswa)
                                            <tr>
                                                <td>{{ $siswa->name }}</td>
                                                <td>
                                                    @if($siswa->pkl_status == 'disetujui')
                                                        <span class="badge bg-success">Aktif</span>
                                                    @elseif($siswa->pkl_status == 'proses')
                                                        <span class="badge bg-warning text-dark">Proses</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $siswa->pkl_status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin-pkl-siswa-logbook', ['guru' => $guru->id, 'siswa' => $siswa->id]) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-journal-text"></i> Logbook
                                                    </a>
                                                    
                                                    @if($siswa->laporan_pkl)
                                                        <button type="button" class="btn btn-sm btn-outline-success ms-1" data-bs-toggle="modal" data-bs-target="#validateModal{{ $siswa->id }}">
                                                            <i class="bi bi-check-circle"></i> Validasi
                                                        </button>
                                                        
                                                        <!-- Modal Validasi Laporan -->
                                                        <div class="modal fade" id="validateModal{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Validasi Laporan PKL - {{ $siswa->name }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <form action="{{ route('admin-pkl-validate-report-for-guru', ['guru' => $guru->id, 'siswa' => $siswa->id]) }}" method="POST">
                                                                        @csrf
                                                                        <div class="modal-body">
                                                                            <div class="mb-3">
                                                                                <label for="status" class="form-label">Status Validasi</label>
                                                                                <select class="form-select" id="status" name="status" required>
                                                                                    <option value="disetujui">Disetujui</option>
                                                                                    <option value="revisi">Perlu Revisi</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="catatan" class="form-label">Catatan (opsional)</label>
                                                                                <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                            <button type="submit" class="btn btn-primary">Simpan Validasi</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">Belum ada siswa yang terdaftar</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-clipboard-x text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-3 mb-0">Guru ini belum ditugaskan sebagai pembimbing program PKL.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
