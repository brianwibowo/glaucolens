@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Detail Siswa PKL: {{ $user->name ?? 'Siswa Tidak Ditemukan' }}</h1>
        {{-- Pastikan rute ini mengarah ke daftar pelamar yang benar untuk perusahaan --}}
        <a href="{{ route('perusahaan-pkl-show', $user->pkl_id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali 
        </a>
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
        <div class="col-md-4">
            {{-- Informasi Siswa Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    Informasi Siswa
                </div>                <div class="card-body">
                    <p class="mb-2"><strong>Nama:</strong> {{ $user->name ?? '-' }}</p>
                    <p class="mb-2"><strong>Email:</strong> {{ $user->email ?? '-' }}</p>                    
                    <p class="mb-2"><strong>Terdaftar PKL:</strong> {{ $user->pklSiswa->nama ?? 'Belum Terdaftar' }}</p>
                    <p class="mb-0"><strong>Pembimbing Lapangan:</strong> {{ $user->pklSiswa && $user->pklSiswa->pembimbing ? $user->pklSiswa->pembimbing->name : 'Belum Ditentukan' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">            {{-- Laporan Akhir PKL Siswa Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span>Laporan Akhir PKL Siswa</span>
                    @if($user->pklSiswa)
                        @php
                            $statusClass = 'secondary';
                            $statusText = 'Proses';
                            
                            if ($user->pklSiswa->status_waka_humas === 'disetujui') {
                                $statusClass = 'success';
                                $statusText = 'Disetujui';
                            } elseif ($user->pklSiswa->status_waka_humas === 'ditolak') {
                                $statusClass = 'danger';
                                $statusText = 'Ditolak';
                            }
                        @endphp
                        <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                    @endif
                </div>                
                <div class="card-body">
                    @if($user->laporan_pkl)
                        <div class="row">
                            <div class="col-md-8">
                                <p class="mb-3">Laporan telah diunggah.</p>
                                <a href="{{ asset('storage/' . $user->laporan_pkl) }}" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="bi bi-download"></i> Unduh Laporan Akhir
                                </a>
                            </div>
                            <div class="col-md-4">
                                <div class="border-start ps-3">
                                    <p class="mb-1"><strong>Status Validasi:</strong></p>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <span class="badge bg-{{ $user->pklSiswa && $user->pklSiswa->status_pembimbing === 'disetujui' ? 'success' : ($user->pklSiswa && $user->pklSiswa->status_pembimbing === 'revisi' ? 'warning' : 'secondary') }} me-1">
                                                {{ $user->pklSiswa ? ucfirst($user->pklSiswa->status_pembimbing) : 'Proses' }}
                                            </span>
                                            <small>Pembimbing</small>
                                        </li>
                                        <li>
                                            <span class="badge bg-{{ $user->pklSiswa && $user->pklSiswa->status_waka_humas === 'disetujui' ? 'success' : ($user->pklSiswa && $user->pklSiswa->status_waka_humas === 'ditolak' ? 'danger' : 'secondary') }} me-1">
                                                {{ $user->pklSiswa ? ucfirst($user->pklSiswa->status_waka_humas) : 'Proses' }}
                                            </span>
                                            <small>Waka Humas</small>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">Siswa ini belum mengunggah laporan akhir PKL.</p>
                    @endif
                </div>
            </div>

            {{-- Input Nilai Siswa Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    Input Nilai Siswa
                </div>
                <div class="card-body">
                    @if ($user->nilai_pkl !== null) 
                        <p class="mb-0"><strong>Nilai Akhir PKL:</strong> <span class="fs-4 text-primary">{{ $user->nilai_pkl }}</span></p>
                    @else
                        <form action="{{ route('perusahaan-pkl-nilai', $user->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nilai" class="form-label">Masukkan Nilai (Skala 0-100)</label>
                                <input type="number" name="nilai" id="nilai" class="form-control @error('nilai') is-invalid @enderror" min="0" max="100" placeholder="Contoh: 85" required>
                                @error('nilai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Entri Logbook Siswa Card --}}
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            Entri Logbook Siswa
        </div>
        <div class="card-body">
            @if ($logbooks->isEmpty()) {{-- Cukup periksa logbooks->isEmpty() karena itu sudah paginated collection --}}
                <div class="text-center py-4">
                    <div class="mb-2">
                        <i class="bi bi-journal-text" style="font-size: 3rem; color: #6c757d;"></i>
                    </div>
                    <p class="text-muted mb-0">Siswa ini belum menambahkan entri logbook.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Nama Kegiatan</th>
                                <th scope="col">Detail</th>
                                <th scope="col" class="text-center">Dokumentasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logbooks as $logbookEntry)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($logbooks->currentPage() - 1) * $logbooks->perPage() }}</td>
                                    <td>{{ \Carbon\Carbon::parse($logbookEntry->tanggal)->format('d M Y') }}</td>
                                    <td>{{ Str::limit($logbookEntry->nama ?? '-', 50) }}</td>
                                    <td>{{ Str::limit($logbookEntry->detail ?? '-', 70) }}</td>
                                    <td class="text-center">
                                        @if($logbookEntry->dokumentasi)
                                            <a href="{{ asset('storage/' . $logbookEntry->dokumentasi) }}" target="_blank" class="btn btn-sm btn-outline-info" title="Lihat Dokumentasi">
                                                <i class="bi bi-file-earmark-image"></i>
                                            </a>
                                        @else
                                            <span class="text-muted small">Tidak ada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $logbooks->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    .table th, .table td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .table td:nth-child(3), .table td:nth-child(4) { /* Nama Kegiatan, Detail */
        max-width: 150px;
    }
</style>
@endsection