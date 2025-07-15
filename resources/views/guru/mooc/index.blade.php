@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    {{-- Judul Halaman --}}
    <div class="mb-4">
        <h1 class="h3">Daftar Kelas Pelatihan</h1>
        <p class="text-muted">Pilih dan daftar ke kelas yang Anda minati di platform MOOC kami.</p>
    </div>

    {{-- Mengecek apakah ada kelas yang tersedia --}}
    @if($moocs && $moocs->count() > 0)
        <div class="row">
            {{-- Looping untuk setiap kelas dari variabel $moocs --}}
            @foreach($moocs as $mooc)
                <div class="col-md-6 col-lg-4 mb-4">
                    {{-- Card untuk setiap kelas, dibuat sama tinggi dengan h-100 --}}
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            {{-- Judul Pelatihan --}}
                            <h5 class="card-title">{{ $mooc->judul_pelatihan }}</h5>
                            
                            {{-- Deskripsi, dibatasi 120 karakter agar rapi --}}
                            <p class="card-text text-muted flex-grow-1">
                                {{ Str::limit($mooc->deskripsi, 120) }}
                            </p>
                            
                                <a href="{{ route('guru-mooc-show', $mooc->id) }}" type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-box-arrow-in-right"></i> Lihat Kelas
                                </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Tampilan jika tidak ada kelas yang tersedia --}}
        <div class="text-center p-5 border rounded bg-light">
            <h4 class="text-muted">Belum Ada Kelas yang Tersedia</h4>
            <p>Saat ini belum ada kelas pelatihan yang dibuka. Silakan periksa kembali nanti.</p>
        </div>
    @endif

</div>
@endsection