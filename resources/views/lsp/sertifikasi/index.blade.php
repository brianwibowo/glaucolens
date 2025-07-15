@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Sertifikasi Kompetensi (LSP)</h1>
        <a href="{{ route('lsp-sertifikasi-create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Buat Sertifikasi Baru
        </a>
    </div>
    <a href="{{ route('lsp-sertifikasi-results') }}" class="btn btn-info shadow-sm mb-3">
         <i class="fas fa-list fa-sm text-white-50"></i> Lihat Hasil Sertifikasi Siswa
    </a>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Sertifikasi yang Anda Buat</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Sertifikasi</th>
                            <th>Kompetensi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($exams as $index => $exam)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $exam->nama_ujian }}</td>
                                <td>{{ $exam->kompetensi_terkait ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('lsp-sertifikasi-show', $exam->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-info-circle"></i> Detail & Pendaftar
                                    </a>
                                    <a href="{{ route('lsp-sertifikasi-edit', $exam->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('lsp-sertifikasi-destroy', $exam->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus sertifikasi ini? Ini juga akan menghapus semua pendaftaran terkait.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada sertifikasi yang Anda buat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection