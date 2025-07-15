@extends('layouts.layout')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4">Daftar Program PKL</h1>
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

    @if($pkls->count() > 0)
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Program</th>
                            <th>Perusahaan</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Jumlah Siswa</th>
                            <th class="text-center">Laporan Akhir</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pkls as $index => $pkl)
                        <tr>
                            <td>{{ $index + 1 + ($pkls->currentPage() - 1) * $pkls->perPage() }}</td>
                            <td>{{ $pkl->nama }}</td>
                            <td>{{ $pkl->perusahaan->name ?? 'Tidak ada' }}</td>
                            <td>{{ $pkl->tanggal_mulai->format('d/m/Y') }} - {{ $pkl->tanggal_selesai->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $statusClass = 'secondary';
                                    switch($pkl->status) {
                                        case 'proses': $statusClass = 'warning'; break;
                                        case 'berjalan': $statusClass = 'primary'; break;
                                        case 'selesai': $statusClass = 'success'; break;
                                    }
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($pkl->status) }}</span>
                            </td>
                            <td>{{ $pkl->siswas->count() }} siswa</td>
                            <td class="text-center">
                                @php
                                    $validationClass = 'secondary';
                                    $validationText = 'Belum Divalidasi';
                                    
                                    if ($pkl->status_pembimbing === 'disetujui') {
                                        $validationClass = 'success';
                                        $validationText = 'Disetujui';
                                    } elseif ($pkl->status_pembimbing === 'revisi') {
                                        $validationClass = 'warning';
                                        $validationText = 'Revisi';
                                    }
                                @endphp
                                <span class="badge bg-{{ $validationClass }}">{{ $validationText }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('guru-pkl-show', $pkl->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('guru-pkl-siswa-list', $pkl->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-people"></i> Daftar Siswa
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $pkls->links() }}
            </div>
        </div>
    </div>
    @else
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <i class="fas fa-clipboard-list" style="font-size: 3rem; color: #ccc;"></i>
            </div>
            <h4 class="text-muted">Belum Ada Program PKL</h4>
            <p class="text-muted mb-0">
                Anda belum ditugaskan sebagai pembimbing untuk program PKL manapun.
            </p>
        </div>
    </div>
    @endif

    <div class="alert alert-info mt-4">
        <h5 class="alert-heading"><i class="bi bi-info-circle-fill me-2"></i>Informasi Tugas Pembimbing</h5>
        <p>Sebagai pembimbing PKL, Anda bertanggung jawab untuk:</p>
        <ol>
            <li>Memantau kemajuan siswa melalui logbook yang diisi oleh siswa.</li>
            <li>Memvalidasi laporan akhir PKL siswa (menyetujui atau meminta revisi).</li>
            <li>Memberikan catatan atau masukan terkait laporan akhir siswa.</li>
        </ol>
        <p class="mb-0">Validasi laporan akhir oleh pembimbing diperlukan sebelum laporan dapat diproses oleh Waka Humas.</p>
    </div>
</div>
@endsection
