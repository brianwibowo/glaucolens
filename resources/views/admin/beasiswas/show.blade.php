@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Detail Batch Beasiswa</h1>
                <p class="mb-0 text-muted">{{ $beasiswa->batch }}</p>
            </div>
            <a href="{{ route('admin-beasiswa-index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar
            </a>
        </div>

        {{-- Informasi Batch --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Batch</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">Nama Batch</p>
                        <p class="font-weight-bold h5">{{ $beasiswa->batch }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">Status</p>
                        @if (\Carbon\Carbon::now()->lt($beasiswa->tanggal_selesai))
                            <span class="badge bg-success p-2">Aktif</span>
                        @else
                            <span class="badge bg-secondary p-2">Selesai</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">Periode Pendaftaran</p>
                        <p class="font-weight-bold">
                            {{ \Carbon\Carbon::parse($beasiswa->tanggal_mulai)->format('d F Y') }} -
                            {{ \Carbon\Carbon::parse($beasiswa->tanggal_selesai)->format('d F Y') }}
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="text-muted mb-1">Jumlah Pendaftar</p>
                        <p class="font-weight-bold">{{ $pendaftar->count() }} Orang</p>
                    </div>
                </div>

                <hr>
                <div class="text-end">
                    <a href="{{ route('admin-beasiswa-edit', $beasiswa->id) }}" class="btn btn-warning shadow-sm">
                        <i class="fas fa-edit fa-sm"></i> Edit Batch
                    </a>
                </div>
            </div>
        </div>

        {{-- Daftar Pendaftar --}}
        {{-- MENUNGGU REVIEW --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">Menunggu Review</div>
            <div class="card-body">
                @php $review = $pendaftar->where('status', 'proses'); @endphp
                @if ($review->isEmpty())
                    <p class="text-center text-muted">Tidak ada pendaftar yang menunggu review.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Status Seleksi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($review as $i => $siswa)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $siswa->nama_siswa }}</td>
                                        <td class="text-center"><span class="badge bg-primary">Direkomendasikan</span></td>
                                        <td class="text-center">
                                            <a href="{{ route('admin-beasiswa-siswa', ['beasiswa' => $beasiswa->id, 'user' => $siswa->user_id]) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- LOLOS --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">Lolos Seleksi</div>
            <div class="card-body">
                @php $lolos = $pendaftar->where('status', 'lolos'); @endphp
                @if ($lolos->isEmpty())
                    <p class="text-center text-muted">Belum ada yang lolos seleksi.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Status Seleksi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lolos as $i => $siswa)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $siswa->nama_siswa }}</td>
                                        <td class="text-center"><span class="badge bg-success">Diterima</span></td>
                                        <td class="text-center">
                                            <a href="{{ route('admin-beasiswa-siswa', ['beasiswa' => $beasiswa->id, 'user' => $siswa->user_id]) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- TIDAK LOLOS --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-danger text-white">Tidak Lolos</div>
            <div class="card-body">
                @php $gagal = $pendaftar->where('status', 'tidak lolos'); @endphp
                @if ($gagal->isEmpty())
                    <p class="text-center text-muted">Tidak ada siswa yang ditolak.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Status Seleksi</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gagal as $i => $siswa)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $siswa->nama_siswa }}</td>
                                        <td class="text-center"><span class="badge bg-danger">Ditolak</span></td>
                                        <td class="text-center">
                                            <a href="{{ route('admin-beasiswa-siswa', ['beasiswa' => $beasiswa->id, 'user' => $siswa->user_id]) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
