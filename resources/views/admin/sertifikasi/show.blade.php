@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Sertifikasi: {{ $certificationExam->nama_ujian }}</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4 mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Sertifikasi</h6>
        </div>
        <div class="card-body">
            <p><strong>Deskripsi:</strong> {{ $certificationExam->deskripsi ?? '-' }}</p>
            <p><strong>Kompetensi Terkait:</strong> {{ $certificationExam->kompetensi_terkait ?? '-' }}</p>
            <a href="{{ route('admin-sertifikasi-edit', $certificationExam->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit Sertifikasi
            </a>
            <a href="{{ route('admin-sertifikasi-index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Sertifikasi
            </a>
        </div>
    </div>

    {{-- Daftar Pendaftar Sertifikasi ini --}}
    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pendaftar Sertifikasi ini</h6>
        </div>
        <div class="card-body">
            @if ($registrations->isEmpty())
                <p>Belum ada siswa yang mendaftar untuk sertifikasi ini.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Status Pendaftaran</th>
                                <th>Nilai Akhir</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registrations as $index => $reg)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $reg->siswa->name ?? '-' }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $reg->status_pendaftaran_ujian)) }}</td>
                                    <td>{{ $reg->nilai ?? '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin-sertifikasi-results.give_certificate_form', $reg->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-award"></i> Beri Nilai & Sertifikat
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