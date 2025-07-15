@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Status Pendaftaran Talent Scouting</h1>
        <a href="{{ route('alumni-scouting-index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Peluang
        </a>
    </div>

    @if($talents->count())
        @foreach($talents as $talent)
            <div class="card shadow-sm mb-4 border-left-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-2">{{ $talent->batch->batch }}</h5>
                    <p class="mb-1"><strong>Perusahaan:</strong> {{ $talent->batch->perusahaan->nama_perusahaan ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Dikirim pada:</strong> {{ $talent->created_at->format('d F Y') }}</p>

                    <div class="mt-3">
                        <span class="badge fs-6 
                            @if($talent->status_seleksi == 'lolos') bg-success
                            @elseif($talent->status_seleksi == 'ditolak') bg-danger
                            @else bg-warning text-dark @endif">
                            Status: {{ strtoupper($talent->status_seleksi) }}
                        </span>
                    </div>

                    <div class="mt-3">
                        <a href="{{ Storage::url($talent->file_cv) }}" class="btn btn-sm btn-outline-primary me-2" target="_blank">
                            <i class="fas fa-file-alt me-1"></i> CV
                        </a>
                        <a href="{{ Storage::url($talent->file_ijazah) }}" class="btn btn-sm btn-outline-success me-2" target="_blank">
                            <i class="fas fa-file-invoice me-1"></i> Ijazah
                        </a>
                        <a href="{{ Storage::url($talent->file_pernyataan) }}" class="btn btn-sm btn-outline-warning" target="_blank">
                            <i class="fas fa-file-signature me-1"></i> Pernyataan
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="card shadow-sm text-center py-5">
            <div class="card-body">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted">Anda belum mendaftar ke program Talent Scouting manapun.</p>
            </div>
        </div>
    @endif
</div>
@endsection
