@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3">Detail Program PKL</h1>
                <p class="text-muted mb-0">{{ $pkl->nama }}</p>
            </div>
            <a href="{{ route('waka-humas-pkl-assign-index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informasi Program PKL</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="35%">Nama Program</th>
                            <td width="65%">{{ $pkl->nama }}</td>
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
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                    $statusClass = 'secondary';
                                    switch($pkl->status) {
                                        case 'proses': $statusClass = 'warning'; break;
                                        case 'berjalan': $statusClass = 'primary'; break;
                                        case 'selesai': $statusClass = 'success'; break;
                                    }
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($pkl->status) }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Detail Pembimbing</h5>
                </div>
                <div class="card-body">
                    @if($pkl->pembimbing)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-lg bg-primary text-white rounded-circle">
                                    {{ strtoupper(substr($pkl->pembimbing->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1">{{ $pkl->pembimbing->name }}</h5>
                                <p class="mb-0 text-muted">{{ $pkl->pembimbing->email }}</p>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('waka-humas-pkl-assign-form', $pkl->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i> Ganti Pembimbing
                            </a>
                            <form action="{{ route('waka-humas-pkl-assign-remove', $pkl->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus penugasan pembimbing ini?')">
                                    <i class="bi bi-trash"></i> Hapus Penugasan
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="bi bi-person-x" style="font-size: 3rem; color: #6c757d;"></i>
                            </div>
                            <p class="mb-3">Belum ada pembimbing yang ditugaskan untuk program PKL ini.</p>
                            <a href="{{ route('waka-humas-pkl-assign-form', $pkl->id) }}" class="btn btn-primary">
                                Tugaskan Pembimbing Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Siswa ({{ $pkl->siswas->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    @if($pkl->siswas->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($pkl->siswas as $siswa)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-{{ $siswa->pkl_status == 'disetujui' ? 'success' : ($siswa->pkl_status == 'tidak_disetujui' ? 'danger' : 'warning text-dark') }} me-2">
                                            {{ ucfirst($siswa->pkl_status) }}
                                        </span>
                                        <span>{{ $siswa->name }}</span>
                                    </div>                                    <a href="{{ route('waka-humas-pkl-siswa-logbook', $siswa->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-journal-text"></i> Logbook
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="bi bi-people" style="font-size: 3rem; color: #6c757d;"></i>
                            </div>
                            <p class="mb-0">Belum ada siswa yang terdaftar dalam program PKL ini.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Status Validasi Laporan Akhir</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="card border">
                                <div class="card-body p-3 text-center">
                                    <h6 class="card-title">Pembimbing</h6>
                                    @php
                                        $pembimbingStatusClass = 'secondary';
                                        if ($pkl->status_pembimbing === 'disetujui') {
                                            $pembimbingStatusClass = 'success';
                                        } elseif ($pkl->status_pembimbing === 'revisi') {
                                            $pembimbingStatusClass = 'warning';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $pembimbingStatusClass }}">{{ ucfirst($pkl->status_pembimbing) }}</span>
                                    @if ($pkl->tanggal_validasi_pembimbing)
                                        <div class="text-muted small mt-2">
                                            {{ $pkl->tanggal_validasi_pembimbing->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border">
                                <div class="card-body p-3 text-center">
                                    <h6 class="card-title">Waka Humas</h6>
                                    @php
                                        $wakaStatusClass = 'secondary';
                                        if ($pkl->status_waka_humas === 'disetujui') {
                                            $wakaStatusClass = 'success';
                                        } elseif ($pkl->status_waka_humas === 'ditolak') {
                                            $wakaStatusClass = 'danger';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $wakaStatusClass }}">{{ ucfirst($pkl->status_waka_humas) }}</span>
                                    @if ($pkl->tanggal_validasi_waka_humas)
                                        <div class="text-muted small mt-2">
                                            {{ $pkl->tanggal_validasi_waka_humas->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
