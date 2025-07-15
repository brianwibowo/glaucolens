@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Detail Batch Scouting</h1>
            <p class="mb-0 text-muted">{{ $scouting->batch }}</p>
        </div>
        <a href="{{ route('admin-scouting-index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Batch</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <p class="text-muted mb-1">Nama Batch</p>
                    <p class="font-weight-bold h5">{{ $scouting->batch }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <p class="text-muted mb-1">Status</p>
                    @if (\Carbon\Carbon::now()->lt($scouting->tanggal_selesai))
                        <span class="badge bg-success p-2">Aktif</span>
                    @else
                        <span class="badge bg-secondary p-2">Selesai</span>
                    @endif
                </div>
                <div class="col-md-6 mb-3">
                    <p class="text-muted mb-1">Periode Pendaftaran</p>
                    <p class="font-weight-bold">
                        {{ \Carbon\Carbon::parse($scouting->tanggal_mulai)->format('d F Y') }} - {{ \Carbon\Carbon::parse($scouting->tanggal_selesai)->format('d F Y') }}
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <p class="text-muted mb-1">Jumlah Pelamar</p>
                    <p class="font-weight-bold">{{ $scouting->talents()->count() }} Orang</p>
                </div>
                {{-- Anda bisa menambahkan field lain seperti deskripsi jika ada --}}
                {{-- <div class="col-12">
                    <p class="text-muted mb-1">Deskripsi</p>
                    <p>{{ $scouting->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                </div> --}}
            </div>
            <hr>
            <div class="text-end">
                <a href="{{ route('admin-scouting-edit', $scouting->id) }}" class="btn btn-warning shadow-sm">
                    <i class="fas fa-edit fa-sm"></i> Edit Batch
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pelamar</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Pelamar</th>
                            <th>Email</th>
                            <th>Tanggal Melamar</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($talents as $index => $pelamar)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                {{-- Asumsi ada relasi ke model User --}}
                                <td>{{ $pelamar->user->name ?? 'N/A' }}</td>
                                <td>{{ $pelamar->user->email ?? 'N/A' }}</td>
                                <td>{{ $pelamar->created_at->format('d M Y, H:i') }}</td>
                                <td class="text-center">
                                    {{-- Contoh logika status pelamar --}}
                                    @if($pelamar->status_seleksi == 'lolos')
                                        <span class="badge bg-info">Diterima</span>
                                    @elseif($pelamar->status_seleksi == 'tidak lolos')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @elseif($pelamar->status_seleksi == 'proses')
                                        <span class="badge bg-warning">Ditinjau</span>
                                    @else
                                        <span class="badge bg-light text-dark">Baru</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href={{ route('admin-scouting-siswa', ['scouting' => $scouting->id, 'user' => $pelamar->user_id]) }} class="btn btn-primary btn-sm" title="Lihat Detail Pelamar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Belum ada pelamar untuk batch ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection