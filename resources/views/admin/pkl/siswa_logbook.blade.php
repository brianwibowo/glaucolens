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
        <h1 class="h4 mb-0">Logbook PKL {{ $siswa->name }}</h1>
        <a href="{{ route('admin-pkl-represent-guru', ['guru_id' => $guru->id]) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Informasi PKL</h5>
            <span class="badge bg-primary">Program: {{ $pkl->nama }}</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Siswa:</strong> {{ $siswa->name }}</p>
                    <p class="mb-1"><strong>Periode PKL:</strong> {{ $pkl->tanggal_mulai->format('d M Y') }} - {{ $pkl->tanggal_selesai->format('d M Y') }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Perusahaan:</strong> {{ $pkl->perusahaan->name ?? 'N/A' }}</p>
                    @php
                        $progress = $pkl->calculateProgress();
                        $progressPercentage = $progress['percentage'];
                    @endphp
                    <p class="mb-1"><strong>Progress:</strong> {{ $progressPercentage }}%</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Entri Logbook ({{ $logbookContents->total() }})</h5>
            
            @if($logbook->status === 'disetujui')
                <span class="badge bg-success">Disetujui</span>
            @elseif($logbook->status === 'revisi')
                <span class="badge bg-warning text-dark">Perlu Revisi</span>
            @else
                <span class="badge bg-secondary">Proses</span>
            @endif
        </div>
        <div class="card-body">
            @if($logbookContents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Judul Kegiatan</th>
                                <th>Detail</th>
                                <th>Dokumentasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logbookContents as $index => $content)
                                <tr>
                                    <td>{{ $index + 1 + ($logbookContents->currentPage() - 1) * $logbookContents->perPage() }}</td>
                                    <td>{{ \Carbon\Carbon::parse($content->tanggal)->format('d M Y') }}</td>
                                    <td>{{ $content->nama }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($content->detail, 50) }}</td>
                                    <td>
                                        @if($content->dokumentasi)
                                            <a href="{{ asset('storage/' . $content->dokumentasi) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-image"></i> Lihat
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($content->status_validasi))
                                            @if($content->status_validasi == 'valid')
                                                <span class="badge bg-success">Valid</span>
                                            @elseif($content->status_validasi == 'revisi')
                                                <span class="badge bg-warning text-dark">Revisi</span>
                                            @else
                                                <span class="badge bg-secondary">Belum divalidasi</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Belum divalidasi</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $logbookContents->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    Belum ada entri logbook yang dibuat oleh siswa.
                </div>
            @endif
        </div>
        <div class="card-footer bg-light">
            <form action="{{ route('admin-pkl-validate-report-for-guru', ['guru' => $guru->id, 'siswa' => $siswa->id]) }}" method="POST">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <select class="form-select" id="status" name="status" required>
                            <option value="">-- Pilih Status Validasi --</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="revisi">Perlu Revisi</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="catatan" placeholder="Catatan (opsional)">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Validasi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
