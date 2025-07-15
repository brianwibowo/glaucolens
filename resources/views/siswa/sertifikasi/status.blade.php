@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Status Pendaftaran Sertifikasi Saya</h1>
        <a href="{{ route('siswa-sertifikasi-index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-list fa-sm text-white-50"></i> Kembali ke Daftar Sertifikasi
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

    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Pendaftaran</h6>
        </div>
        <div class="card-body">
            @if ($myRegistrations->isEmpty())
                <p>Anda belum mendaftar untuk sertifikasi apapun.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTableStatus">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Sertifikasi</th>
                                <th>Kompetensi</th>
                                <th>Dibuat Oleh</th>
                                <th>Status Pendaftaran</th>
                                <th>Nilai Akhir</th>
                                <th>Sertifikat</th>
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
                                            ($registration->status_pendaftaran_ujian == 'selesai_ujian' ? 'bg-info' : // Jika ingin ada status 'selesai_ujian'
                                            ($registration->status_pendaftaran_ujian == 'lulus' ? 'bg-success' :
                                            ($registration->status_pendaftaran_ujian == 'tidak_lulus' ? 'bg-danger' : 'bg-secondary')))
                                        }}">
                                            {{ ucfirst(str_replace('_', ' ', $registration->status_pendaftaran_ujian)) }}
                                        </span>
                                    </td>
                                    <td>{{ $registration->nilai ?? '-' }}</td>
                                    <td>
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
        $('#dataTableStatus').DataTable();
    });
</script>
@endpush