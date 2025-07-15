@extends('layouts.layout')

@section('content')
<div class="container">
    <h2>Hasil Ujian Sertifikasi Siswa</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('perusahaan-sertifikasi-index') }}" class="btn btn-secondary mb-3">Kembali ke Manajemen Ujian</a>

    <div class="card">
        <div class="card-header">Daftar Pendaftar Ujian</div>
        <div class="card-body">
            @if ($registrations->isEmpty())
                <p>Belum ada siswa yang mendaftar atau menyelesaikan ujian yang dibuat oleh perusahaan Anda.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Siswa</th>
                                <th>Ujian Diikuti</th>
                                <th>Nilai Akhir (Diinput)</th>
                                <th>Status Kelulusan</th>
                                <th>Sertifikat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registrations as $reg)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
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
                                    <td>
                                        <a href="{{ route('perusahaan-sertifikasi-results.give_certificate_form', $reg->id) }}" class="btn btn-sm btn-primary">Input Nilai & Sertifikat</a>
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