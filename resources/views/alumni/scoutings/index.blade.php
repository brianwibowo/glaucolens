@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Peluang Talent Scouting</h1>
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

    <div class="row">
        @forelse ($batches as $batch)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-left-primary">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <h5 class="card-title font-weight-bold text-primary">{{ $batch->batch }}</h5>
                            {{-- Tampilkan nama perusahaan jika ada relasinya --}}
                            @if($batch->perusahaan)
                                <h6 class="card-subtitle mb-2 text-muted">{{ $batch->perusahaan->nama_perusahaan }}</h6>
                            @endif
                        </div>
                        
                        <p class="card-text small">
                            <i class="fas fa-users fa-fw me-2"></i> {{ $batch->pelamars_count }} Pendaftar
                        </p>
                        <p class="card-text small">
                            <i class="fas fa-calendar-times fa-fw me-2"></i> Batas Pendaftaran: {{ \Carbon\Carbon::parse($batch->tanggal_selesai)->format('d F Y') }}
                        </p>

                        {{-- Bagian Aksi (Tombol) --}}
                        <div class="mt-auto text-end">
                            @if (in_array($batch->id, $appliedBatchIds))
                                {{-- Jika user sudah mendaftar --}}
                                <button class="btn btn-success" disabled>
                                    <i class="fas fa-check"></i> Sudah Mendaftar
                                </button>
                            @else
                               <a href="{{ route('alumni-scouting-register', ['scouting' => $batch->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Daftar Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-search-minus fa-3x text-gray-400 mb-3"></i>
                        <p class="card-text">Saat ini belum ada peluang talent scouting yang tersedia.</p>
                        <p class="text-muted">Silakan periksa kembali di lain waktu.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection