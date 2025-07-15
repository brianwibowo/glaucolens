@extends('layouts.layout')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Logbook PKL: {{ $siswa->name }}</h1>
            <p class="text-muted mb-0">Program: {{ $pkl->nama }}</p>
        </div>
        <a href="{{ route('guru-pkl-siswa-list', $pkl->id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Siswa
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informasi Siswa</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted mb-1">Nama Siswa</label>
                        <p class="mb-2 fw-bold">{{ $siswa->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted mb-1">Email</label>
                        <p class="mb-2 fw-bold">{{ $siswa->email }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted mb-1">Status PKL</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $siswa->pkl_status == 'disetujui' ? 'success' : ($siswa->pkl_status == 'tidak_disetujui' ? 'danger' : 'warning text-dark') }}">
                                {{ ucfirst($siswa->pkl_status) }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted mb-1">Program PKL</label>
                        <p class="mb-2 fw-bold">{{ $pkl->nama }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted mb-1">Periode PKL</label>
                        <p class="mb-2 fw-bold">{{ $pkl->tanggal_mulai->format('d M Y') }} - {{ $pkl->tanggal_selesai->format('d M Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted mb-1">Perusahaan</label>
                        <p class="mb-0 fw-bold">{{ $pkl->perusahaan->name ?? 'Tidak tersedia' }}</p>
                    </div>
                      @if($siswa->laporan_pkl)
                    <div class="mt-4">
                        <a href="{{ asset('storage/' . $siswa->laporan_pkl) }}" target="_blank" class="btn btn-primary w-100">
                            <i class="bi bi-file-earmark-text"></i> Unduh Laporan Akhir
                        </a>
                    </div>
                    @else
                    <div class="mt-4">
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Siswa belum mengupload laporan akhir
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-9">              <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Logbook Siswa</h5>
                    <span class="badge bg-info">{{ $logbookContents->total() }} Entri</span>
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
                                        <th width="15%">Dokumentasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logbookContents as $content)
                                    <tr>
                                        <td>{{ $content->tanggal->format('d/m/Y') }}</td>
                                        <td>{{ $content->nama }}</td>
                                        <td>{{ $content->detail }}</td>                                        <td class="text-center">
                                            @if($content->dokumentasi)
                                                @php
                                                    $extension = pathinfo('storage/' . $content->dokumentasi, PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                                                @endphp
                                                
                                                <a href="{{ asset('storage/' . $content->dokumentasi) }}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-{{ $isImage ? 'image' : 'file-earmark' }}"></i> Lihat
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
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $logbookContents->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-journal-x" style="font-size: 3rem; color: #ccc;"></i>
                            </div>
                            <h5 class="text-muted">Belum Ada Entri Logbook</h5>
                            <p class="text-muted mb-0">
                                Siswa belum menambahkan entri apapun dalam logbook PKL.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="alert alert-info mt-4">
        <h5><i class="bi bi-info-circle"></i> Informasi Penting:</h5>
        <p class="mb-0">
            Sebagai guru pembimbing, Anda dapat melihat semua entri logbook siswa. Anda tidak perlu memvalidasi setiap entri logbook, validasi hanya diperlukan untuk laporan akhir PKL yang dapat diakses dari menu PKL.
        </p>
    </div>
</div>
@endsection
