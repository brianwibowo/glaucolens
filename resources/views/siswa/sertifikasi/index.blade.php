@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sertifikasi Kompetensi</h1>
        <a href="{{ route('siswa-sertifikasi-status') }}" class="btn btn-info shadow-sm">
            <i class="fas fa-eye fa-sm text-white-50"></i> Lihat Status Pendaftaran
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Bagian Daftar Sertifikasi yang Tersedia --}}
    <div class="card shadow-sm mb-5">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Sertifikasi yang Tersedia</h6>
        </div>
        <div class="card-body">
            @if ($availableExams->isEmpty())
                <p>Belum ada sertifikasi yang ditawarkan saat ini.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTableAvailableExams">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Sertifikasi</th>
                                <th>Deskripsi</th>
                                <th>Kompetensi Terkait</th>
                                <th>Dibuat Oleh</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($availableExams as $index => $exam)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $exam->nama_ujian }}</td>
                                    <td>{{ Str::limit($exam->deskripsi, 100) }}</td>
                                    <td>{{ $exam->kompetensi_terkait ?? '-' }}</td>
                                    <td>{{ $exam->pembuat->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('siswa-sertifikasi-register', $exam->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-file-upload"></i> Daftar & Upload Dokumen
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

    {{-- Bagian Pendaftaran Sertifikasi Saya --}}
    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pendaftaran Sertifikasi Saya</h6>
        </div>
        <div class="card-body">
            @if ($myRegistrations->isEmpty())
                <p>Anda belum mendaftar untuk sertifikasi apapun. Silakan daftar dari daftar di atas.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTableMyRegistrations">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Sertifikasi</th>
                                <th>Kompetensi</th>
                                <th>Dibuat Oleh</th>
                                <th>Status</th>
                                <th>Nilai</th>
                                <th class="text-center">Sertifikat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($myRegistrations as $index => $registration)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $registration->exam->nama_ujian ?? 'N/A' }}</td>
                                    <td>{{ $registration->kompetensi ?? '-' }}</td>
                                    <td>{{ $registration->perusahaan->name ?? $registration->lsp->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{
                                            $registration->status_pendaftaran_ujian == 'terdaftar' ? 'bg-primary' :
                                            ($registration->status_pendaftaran_ujian == 'lulus' ? 'bg-success' :
                                            ($registration->status_pendaftaran_ujian == 'tidak_lulus' ? 'bg-danger' : 'bg-secondary'))
                                        }}">
                                            {{ ucfirst(str_replace('_', ' ', $registration->status_pendaftaran_ujian)) }}
                                        </span>
                                    </td>
                                    <td>{{ $registration->nilai ?? '-' }}</td>
                                    <td class="text-center">
                                        @if ($registration->status_pendaftaran_ujian == 'lulus' && $registration->sertifikat_kelulusan)
                                            <a href="{{ route('siswa-sertifikasi-download_certificate', $registration->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-download"></i> Unduh
                                            </a>
                                        @else
                                            Belum Tersedia
                                        @endif
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

@push('scripts')
{{-- Jika kamu menggunakan DataTables, pastikan skripnya ada di sini --}}
<script>
    $(document).ready(function() {
        $('#dataTableAvailableExams').DataTable();
        $('#dataTableMyRegistrations').DataTable();
    });
</script>
@endpush