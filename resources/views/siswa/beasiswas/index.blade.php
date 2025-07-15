@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="h3 mb-4 text-gray-800">Peluang Beasiswa dari Perusahaan</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        @forelse ($batches as $batch)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100 border-left-primary">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary">{{ $batch->batch }}</h5>
                        <p class="text-muted mb-2">{{ $batch->perusahaan->nama_perusahaan ?? 'Perusahaan' }}</p>
                        <p class="mb-2 small">
                            <i class="fas fa-calendar-times me-1"></i>
                            Batas Daftar: {{ \Carbon\Carbon::parse($batch->tanggal_selesai)->translatedFormat('d F Y') }}
                        </p>
                        <p class="small"><i class="fas fa-users me-1"></i>{{ $batch->beasiswas_count ?? 0 }} Pendaftar</p>

                        <div class="mt-auto text-end">
                            @if (in_array($batch->id, $appliedBatchIds))
                                <button class="btn btn-success btn-sm" disabled><i class="fas fa-check"></i> Sudah Mendaftar</button>
                            @else
                                <a href="{{ route('siswa-beasiswa-register', $batch->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-paper-plane"></i> Daftar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center">Belum ada batch beasiswa yang tersedia.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
