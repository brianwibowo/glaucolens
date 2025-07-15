@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Logbook Siswa PKL</h1>
        <a href="{{ route('waka-humas-pkl-index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar PKL
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Daftar Logbook Siswa</h5>
        </div>
        <div class="card-body">
            @if($logbooks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Siswa</th>
                                <th scope="col">Program PKL</th>
                                <th scope="col">Perusahaan</th>
                                <th scope="col">Total Entri</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logbooks as $index => $logbook)
                                <tr>
                                    <td>{{ $index + 1 + ($logbooks->currentPage() - 1) * $logbooks->perPage() }}</td>
                                    <td>
                                        {{ $logbook->siswa->name ?? 'Siswa tidak ditemukan' }}
                                    </td>
                                    <td>
                                        {{ $logbook->pkl->nama ?? 'PKL tidak ditemukan' }}
                                    </td>
                                    <td>
                                        {{ $logbook->pkl->perusahaan->name ?? 'Perusahaan tidak ditemukan' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $logbook->logbookContents->count() }} entri</span>
                                    </td>
                                    <td class="text-center">                                        <a href="{{ route('waka-humas-pkl-siswa-logbook', $logbook->siswa_id) }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-journal-text"></i> Lihat Logbook
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $logbooks->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <div class="mb-3">
                        <i class="bi bi-journal-x" style="font-size: 3rem; color: #6c757d;"></i>
                    </div>
                    <p class="text-muted mb-0">Tidak ada logbook yang tersedia untuk validasi.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="alert alert-info mt-4">
        <h5><i class="bi bi-info-circle"></i> Catatan Penting:</h5>
        <p class="mb-0">Anda dapat melihat logbook siswa, namun tidak perlu melakukan validasi pada logbook. Validasi hanya dilakukan pada laporan akhir PKL.</p>
    </div>
</div>
@endsection
