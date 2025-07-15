@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4">Logbook PKL - {{ $siswa->name }}</h1>
        <a href="{{ route('waka-humas-pkl-logbook-validation-index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif    <!-- Informasi Siswa Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Informasi Siswa</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama:</strong> {{ $siswa->name }}</p>
                    <p><strong>Email:</strong> {{ $siswa->email }}</p>
                    <p><strong>Program PKL:</strong> {{ $logbook->pkl->nama ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Perusahaan:</strong> {{ $logbook->pkl->perusahaan->name ?? '-' }}</p>
                    <p><strong>Pembimbing:</strong> {{ $logbook->pkl->pembimbing->name ?? '-' }}</p>
                    <p><strong>Jumlah Entri Logbook:</strong> {{ $logbookContents->total() }} entri</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Entri Logbook Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Entri Logbook</h5>
        </div>
        <div class="card-body">
            @if($logbookContents->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th width="12%">Tanggal</th>
                            <th width="20%">Nama Kegiatan</th>
                            <th>Detail</th>
                            <th width="12%">Dokumentasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logbookContents as $content)
                        <tr>
                            <td>{{ $content->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $content->nama ?? $content->kegiatan }}</td>
                            <td>{{ $content->detail ?? '-' }}</td>
                            <td class="text-center">
                                @if($content->dokumentasi)
                                <a href="{{ asset('storage/' . $content->dokumentasi) }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="bi bi-image"></i> Lihat
                                </a>
                                @else
                                <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $logbookContents->links() }}
            </div>
            @else
            <div class="alert alert-info">
                Belum ada entri logbook.
            </div>
            @endif
        </div>
    </div>    <!-- Validation form removed as per requirement - only final reports need validation -->
</div>
@endsection
