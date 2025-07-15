{{-- Asumsi Anda menggunakan layout utama bernama 'layouts.app' --}}
{{-- Sesuaikan nama file layout jika berbeda, contoh: 'layouts.perusahaan' --}}
@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h1 class="h4 mb-0 fw-bold">Hasil Riset</h1>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Topik Riset</th>
                                    <th scope="col">Deskripsi Singkat</th>
                                    <th scope="col">Tim Riset</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Dokumentasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Gunakan @forelse untuk menangani kasus jika data riset kosong --}}
                                @forelse ($risets as $riset)
                                    <tr>
                                        {{-- Nomor urut yang berkelanjutan meskipun berganti halaman paginasi --}}
                                        <th scope="row">{{ $loop->iteration + $risets->firstItem() - 1 }}</th>
                                        <td>{{ $riset->topik }}</td>
                                        {{-- Batasi panjang deskripsi agar tabel tidak terlalu lebar --}}
                                        <td>{{ Str::limit($riset->deskripsi, 75, '...') }}</td>
                                        <td>
                                            {{-- Loop untuk menampilkan anggota tim --}}
                                            @if($riset->anggota->isNotEmpty())
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($riset->anggota as $anggota)
                                                        <li>
                                                            <span class="badge bg-secondary fw-normal">{{ $anggota->user->name ?? 'User tidak ditemukan' }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">Belum ada anggota</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- Badge untuk status dengan warna yang berbeda --}}
                                            @if($riset->status == 'disetujui')
                                                <span class="badge bg-success">{{ ucfirst($riset->status) }}</span>
                                            @elseif($riset->status == 'proses')
                                                <span class="badge bg-warning text-dark">{{ ucfirst($riset->status) }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($riset->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- Cek apakah ada file dokumentasi --}}
                                            @if($riset->dokumentasi)
                                                <a href="{{ Storage::url($riset->dokumentasi) }}" target="_blank" class="btn btn-outline-info btn-sm">
                                                    Lihat File
                                                </a>
                                            @else
                                                <span class="text-muted">Tidak ada</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Pesan jika tidak ada data riset sama sekali --}}
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Tidak ada data hasil riset yang ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{-- Render link paginasi --}}
                    <div class="d-flex justify-content-center">
                        {{ $risets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection