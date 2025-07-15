@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Pilih Guru Pembimbing</h1>
        <a href="{{ route('admin-pkl-index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="row">
        @forelse ($gurus as $guru)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">{{ $guru->name }}</h5>
                            <span class="badge bg-info">Guru</span>
                        </div>
                        <p class="card-text text-muted small">
                            <i class="bi bi-envelope"></i> {{ $guru->email }}
                        </p>
                        
                        <form action="{{ route('admin-pkl-represent-guru') }}" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="guru_id" value="{{ $guru->id }}">
                            <button type="submit" class="btn btn-info w-100">
                                <i class="bi bi-box-arrow-in-right"></i> Kelola PKL sebagai Guru Ini
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Tidak ada guru yang tersedia. Silakan tambahkan guru terlebih dahulu.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
