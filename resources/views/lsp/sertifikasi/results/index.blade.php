@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="h3 mb-0 text-gray-800">Hasil Sertifikasi Siswa (LSP)</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <a href="{{ route('lsp-sertifikasi-index') }}" class="btn btn-secondary shadow-sm mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Manajemen Sertifikasi
    </a>

    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pendaftar Sertifikasi Anda</h6>
        </div>
        <div class="card-body">
            @if ($registrations->isEmpty())
                <p>Belum ada siswa yang mendaftar untuk sertifikasi yang Anda buat.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Sertifikasi Diikuti</th>
                                <th>Nilai Akhir</th>
                                <th>Status Kelulusan</th>
                                <th>Sertifikat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registrations as $index => $reg)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $reg->siswa->name ?? '-' }}</td>
                                    <td>{{ $reg->exam->nama_ujian ?? '-' }}</td>
                                    <td>{{ $reg->nilai ?? '-' }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $reg->status_pendaftaran_ujian)) }}</td>
                                    <td>
                                        @if ($reg->sertifikat_kelulusan)
                                            <a href="{{ Storage::url($reg->sertifikat_kelulusan) }}" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                        @else
                                            Belum ada
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('lsp-sertifikasi-results.give_certificate_form', $reg->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-award"></i> Input Nilai & Sertifikat
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