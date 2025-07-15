@extends('layouts.layout')

@section('content')
<div class="container">
    <h2>Detail Ujian Sertifikasi: {{ $certificationExam->nama_ujian }}</h2> 

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">Informasi Ujian</div>
        <div class="card-body">
            <p><strong>Deskripsi:</strong> {{ $certificationExam->deskripsi ?? '-' }}</p> 
            <p><strong>Kompetensi Terkait:</strong> {{ $certificationExam->kompetensi_terkait ?? '-' }}</p> 
            <a href="{{ route('perusahaan-sertifikasi-edit', $certificationExam->id) }}" class="btn btn-warning btn-sm">Edit Ujian</a>
            <a href="{{ route('perusahaan-sertifikasi-index') }}" class="btn btn-secondary btn-sm">Kembali ke Daftar Ujian</a>
        </div>
    </div>

    {{-- Daftar Pendaftar Ujian --}}
    <div class="card">
        <div class="card-header">Daftar Pendaftar Ujian ini</div>
        <div class="card-body">
            @if ($registrations->isEmpty())
                <p>Belum ada siswa yang mendaftar untuk ujian ini.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Siswa</th>
                                <th>Status Pendaftaran</th>
                                <th>Nilai Akhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registrations as $reg)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $reg->siswa->name }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $reg->status_pendaftaran_ujian)) }}</td>
                                    <td>{{ $reg->nilai ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('perusahaan-sertifikasi-results.give_certificate_form', $reg->id) }}" class="btn btn-sm btn-primary">Beri Nilai & Sertifikat</a>
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