@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="bi bi-info-circle-fill me-2"></i>
        <div>
            <span class="fw-bold">Mode Admin sebagai Perusahaan:</span> 
            Anda saat ini bertindak atas nama perusahaan <strong>{{ $company->name }}</strong>
        </div>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Program PKL {{ $company->name }}</h1>
        <div>
            <a href="{{ route('admin-pkl-select-company') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left"></i> Ganti Perusahaan
            </a>
            <a href="{{ route('admin-pkl-create-for-company', $company->id) }}" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Tambah Program PKL
            </a>
        </div>
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
                        
                        @if($pkl->created_by == auth()->id() && $pkl->admin_representing == $company->id)
                            <span class="badge bg-secondary" title="Dibuat oleh Admin">
                                <i class="bi bi-person-fill-gear"></i> Dibuat Admin
                            </span>
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
                                    <i class="bi bi-people me-1"></i> Peserta
                                </div>
                                <p class="small text-muted">{{ $pkl->siswas->count() }} siswa</p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <i class="bi bi-person-check me-1"></i> Pembimbing
                                </div>
                                <p class="small text-muted">
                                    {{ $pkl->pembimbing->name ?? 'Belum ditugaskan' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <i class="bi bi-check-circle me-1"></i> Status
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
                    </div>
                    
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('admin-pkl-edit-for-company', ['company' => $company->id, 'pkl' => $pkl->id]) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                
                                @if($pkl->siswas->count() == 0)
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePklModal{{ $pkl->id }}">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                @endif
                            </div>
                            
                            <a href="{{ route('admin-pkl-students-for-company', ['company' => $company->id, 'pkl' => $pkl->id]) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-people"></i> Kelola Siswa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal untuk konfirmasi penghapusan -->
            @if($pkl->siswas->count() == 0)
                <div class="modal fade" id="deletePklModal{{ $pkl->id }}" tabindex="-1" aria-labelledby="deletePklModalLabel{{ $pkl->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deletePklModalLabel{{ $pkl->id }}">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Apakah Anda yakin ingin menghapus program PKL "{{ $pkl->nama }}"?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <form action="{{ route('admin-pkl-destroy-for-company', ['company' => $company->id, 'pkl' => $pkl->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-clipboard-x text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-3 mb-0">Belum ada program PKL yang dibuat oleh perusahaan ini.</p>
                        <a href="{{ route('admin-pkl-create-for-company', $company->id) }}" class="btn btn-primary mt-3">
                            <i class="bi bi-plus-lg"></i> Buat Program PKL Baru
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
