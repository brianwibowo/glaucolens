@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4">Statistik Seleksi Beasiswa</h3>

        @forelse ($batches as $batch)
            @php
                $total = $batch->beasiswas->count();
                $direkom = $batch->beasiswas->where('direkomendasikan', true)->count();
                $diterima = $batch->beasiswas->where('status', 'lolos')->count();
                $ditolak = $batch->beasiswas->where('status', 'tidak lolos')->count();
            @endphp


            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <strong>{{ $batch->batch }}</strong> – {{ $batch->perusahaan->nama_perusahaan ?? '-' }}
                </div>
                <div class="card-body">
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total Pendaftar</span>
                            <span><strong>{{ $total }}</strong></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Sudah Direkomendasikan</span>
                            <span class="text-primary"><strong>{{ $direkom }}</strong></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Diterima Perusahaan</span>
                            <span class="text-success"><strong>{{ $diterima }}</strong></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Ditolak Perusahaan</span>
                            <span class="text-danger"><strong>{{ $ditolak }}</strong></span>
                        </li>
                    </ul>

                    <div class="text-end">
                        <a href="{{ route('waka_kurikulum.beasiswas.hasil.detail', $batch->id) }}"
                            class="btn btn-sm btn-outline-success">
                            <i class="fas fa-eye"></i> Lihat Status Beasiswa Siswa
                        </a>                        
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-warning text-center">
                Tidak ada data pendaftaran dari sekolah Anda untuk batch manapun.
            </div>
        @endforelse
    </div>
@endsection
