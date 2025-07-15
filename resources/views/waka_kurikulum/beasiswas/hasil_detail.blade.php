@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Status Seleksi Beasiswa – {{ $batch->batch }}</h3>
    <p class="text-muted">Perusahaan: {{ $batch->perusahaan->nama_perusahaan ?? '-' }}</p>

    @if ($beasiswas->count())
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Status Seleksi</th>
                                <th>Catatan Waka</th>
                                <th>Tanggal Rekomendasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($beasiswas as $index => $siswa)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $siswa->nama_siswa }}</td>
                                    <td>
                                        @switch($siswa->status)
                                            @case('lolos')
                                                <span class="badge bg-success">Diterima</span>
                                                @break
                                            @case('tidak lolos')
                                                <span class="badge bg-danger">Ditolak</span>
                                                @break
                                            @default
                                                <span class="badge bg-warning text-dark">Proses</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $siswa->catatan ?? '-' }}</td>
                                    <td>
                                        {{ $siswa->tanggal_rekomendasi 
                                            ? \Carbon\Carbon::parse($siswa->tanggal_rekomendasi)->format('d M Y H:i') 
                                            : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center">
            Belum ada siswa dari sekolah Anda yang mendaftar pada batch ini.
        </div>
    @endif
</div>
@endsection
