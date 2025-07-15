@php
use App\Models\User;
@endphp

@extends('layouts.layout')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h1 class="h4 mb-1">Daftar Kurikulum Diajukan</h1>
                                <p class="text-muted mb-0">Kelola kurikulum yang telah diajukan</p>
                            </div>
                            <a href="{{ route('waka-kurikulum-create') }}" class="btn btn-success d-flex align-items-center">
                                + Ajukan Kurikulum
                            </a>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                    <input type="date" id="filter-date" class="form-control" placeholder="Filter berdasarkan tanggal mulai">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-tag"></i>
                                    </span>
                                    <select id="filter-status" class="form-control">
                                        <option value="">Semua Status</option>
                                        <option value="menunggu">Menunggu</option>
                                        <option value="disetujui">Disetujui</option>
                                        <option value="ditolak">Ditolak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" id="search-title" class="form-control" placeholder="Cari berdasarkan judul project...">
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="kurikulum-table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">Nama Kurikulum</th>
                                        <th class="border-0">Perusahaan Tujuan</th>
                                        <th class="border-0">Tahun Ajaran</th>
                                        <th class="border-0">File</th>
                                        <th class="border-0">Tanggal Pengajuan</th>
                                        <th class="border-0">Status Validasi</th>
                                        <th class="border-0">Aksi</th>
                                        <th class="border-0">Komentar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kurikulums as $kurikulum)
                                        <tr>
                                            <td>{{ $kurikulum->nama_kurikulum }}</td>
                                            <td>
                                                @if($kurikulum->perusahaan_id)
                                                    @php 
                                                        $perusahaan = User::find($kurikulum->perusahaan_id);
                                                    @endphp
                                                    @if($perusahaan)
                                                        {{ $perusahaan->name }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $kurikulum->tahun_ajaran }}</td>
                                            <td>
                                                <a href="{{ asset('storage/'.$kurikulum->file_kurikulum) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download me-1"></i> Unduh
                                                </a>
                                            </td>
                                            <td class="created-date">{{ \Carbon\Carbon::parse($kurikulum->created_at)->format('Y-m-d') }}</td>
                                            <td>
                                                @if($kurikulum->validasi_perusahaan == 'disetujui')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @elseif($kurikulum->validasi_perusahaan == 'tidak_disetujui')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($kurikulum->validasi_sekolah == 'disetujui' && $kurikulum->validasi_perusahaan == 'disetujui')
                                                    <span class="text-muted">Tidak dapat diubah</span>
                                                @else
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('waka-kurikulum-edit', $kurikulum) }}" 
                                                        class="btn btn-sm btn-outline-warning" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('waka-kurikulum-destroy', $kurikulum) }}" 
                                                            method="POST" 
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kurikulum ini?')"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-outline-danger" 
                                                                    data-bs-toggle="tooltip" 
                                                                    title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($kurikulum->komentar)
                                                    <span class="text-muted">{{ Str::limit($kurikulum->komentar, 50) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                    Belum ada kurikulum yang diajukan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table > :not(caption) > * > * {
            padding: 1rem;
        }
        .btn-group .btn {
            padding: 0.375rem 0.75rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            const dateFilter = document.getElementById('filter-date');
            const statusFilter = document.getElementById('filter-status');
            const searchInput = document.getElementById('search-title');
            
            function filterTable() {
                const selectedDate = dateFilter.value.toLowerCase();
                const selectedStatus = statusFilter.value.toLowerCase();
                const searchKeyword = searchInput.value.toLowerCase();

                const rows = document.querySelectorAll('#kurikulum-table tbody tr');

                rows.forEach(row => {
                    if (row.querySelector('td[colspan="8"]')) {
                        return; // Skip the "no data" row
                    }
                    
                    const createdDate = row.querySelector('.created-date').textContent.trim().toLowerCase();
                    const statusElement = row.querySelector('td:nth-child(6) .badge');
                    const statusText = statusElement ? statusElement.textContent.trim().toLowerCase() : '';
                    const titleText = row.querySelector('td:nth-child(1)').textContent.trim().toLowerCase();

                    const matchDate = !selectedDate || createdDate.includes(selectedDate);
                    const matchStatus = !selectedStatus || 
                                        (selectedStatus === 'menunggu' && statusText.includes('menunggu')) ||
                                        (selectedStatus === 'disetujui' && statusText.includes('disetujui')) ||
                                        (selectedStatus === 'ditolak' && statusText.includes('ditolak'));
                    const matchKeyword = !searchKeyword || titleText.includes(searchKeyword);

                    row.style.display = matchDate && matchStatus && matchKeyword ? '' : 'none';
                });
            }

            dateFilter.addEventListener('input', filterTable);
            statusFilter.addEventListener('change', filterTable);
            searchInput.addEventListener('input', filterTable);
        });
    </script>
@endsection
