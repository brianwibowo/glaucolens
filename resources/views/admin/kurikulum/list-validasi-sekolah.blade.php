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
                                <h1 class="h4 mb-1">Validasi Kurikulum dari Sekolah</h1>
                                <p class="text-muted mb-0">Kelola dan validasi kurikulum yang diajukan oleh sekolah</p>
                            </div>
                        </div>
                        
                        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-info-circle-fill"></i>
                            Pada halaman ini, Anda dapat memvalidasi kurikulum yang diajukan oleh Sekolah dan melihat riwayat validasi.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-calendar3"></i>
                                    </span>
                                    <input type="date" id="filter-date" class="form-control border-start-0" placeholder="Filter berdasarkan tanggal">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" id="search-input" class="form-control border-start-0" placeholder="Cari kurikulum...">
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav-tabs nav-tabs-custom mb-4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="waiting-tab" data-bs-toggle="tab" data-bs-target="#waiting-validation" type="button" role="tab">
                                    <i class="bi bi-clock me-2"></i>Menunggu Validasi
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">
                                    <i class="bi bi-check-circle me-2"></i>Disetujui
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab">
                                    <i class="bi bi-x-circle me-2"></i>Ditolak
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="waiting-validation" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle kurikulum-table">                                        <thead class="bg-light">
                                            <tr>
                                                <th class="border-0">Pengirim</th>
                                                <th class="border-0">Nama Kurikulum</th>
                                                <th class="border-0">Perusahaan Tujuan</th>
                                                <th class="border-0">Tahun Ajaran</th>
                                                <th class="border-0">File</th>
                                                <th class="border-0">Tanggal Pengajuan</th>
                                                <th class="border-0">Status</th>
                                                <th class="border-0">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $hasWaiting = false; @endphp
                                            @foreach ($kurikulums as $kurikulum)
                                                @if($kurikulum->validasi_perusahaan == 'proses')
                                                    @php $hasWaiting = true; @endphp
                                                    <tr>
                                                        <td>{{ $kurikulum->pengirim->name }}</td>
                                                        <td>{{ $kurikulum->nama_kurikulum }}</td>
                                                        <td>
                                                            @if($kurikulum->perusahaan_id)
                                                                @php 
                                                                    $perusahaan = App\Models\User::find($kurikulum->perusahaan_id);
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
                                                        <td><span class="badge bg-warning">Menunggu</span></td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                <form action="{{ route('admin-kurikulum-setuju', $kurikulum) }}" method="POST" class="d-inline" id="approveForm{{ $kurikulum->id }}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="btn btn-sm btn-outline-success me-1" data-bs-toggle="tooltip" title="Setuju">
                                                                        <i class="bi bi-check-lg"></i>
                                                                    </button>
                                                                </form>
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-danger" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#tolakModal"
                                                                        data-kurikulum-id="{{ $kurikulum->id }}"
                                                                        data-bs-toggle="tooltip"
                                                                        title="Tolak">
                                                                    <i class="bi bi-x-lg"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach                                            @if(!$hasWaiting)
                                                <tr>
                                                    <td colspan="8" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                            Tidak ada kurikulum yang sedang menunggu validasi
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="approved" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle kurikulum-table">                                        <thead class="bg-light">
                                            <tr>
                                                <th class="border-0">Pengirim</th>
                                                <th class="border-0">Nama Kurikulum</th>
                                                <th class="border-0">Perusahaan Tujuan</th>
                                                <th class="border-0">Tahun Ajaran</th>
                                                <th class="border-0">File</th>
                                                <th class="border-0">Tanggal Pengajuan</th>
                                                <th class="border-0">Tanggal Validasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $hasApproved = false; @endphp
                                            @foreach ($kurikulums as $kurikulum)
                                                @if($kurikulum->validasi_perusahaan == 'disetujui')
                                                    @php $hasApproved = true; @endphp
                                                    <tr>
                                                        <td>{{ $kurikulum->pengirim->name }}</td>
                                                        <td>{{ $kurikulum->nama_kurikulum }}</td>
                                                        <td>
                                                            @if($kurikulum->perusahaan_id)
                                                                @php 
                                                                    $perusahaan = App\Models\User::find($kurikulum->perusahaan_id);
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
                                                        <td>{{ \Carbon\Carbon::parse($kurikulum->created_at)->format('Y-m-d') }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($kurikulum->updated_at)->format('Y-m-d') }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach                                            @if(!$hasApproved)
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="bi bi-check-circle fs-1 d-block mb-2"></i>
                                                            Belum ada kurikulum yang disetujui
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="rejected" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle kurikulum-table">                                        <thead class="bg-light">
                                            <tr>
                                                <th class="border-0">Pengirim</th>
                                                <th class="border-0">Nama Kurikulum</th>
                                                <th class="border-0">Perusahaan Tujuan</th>
                                                <th class="border-0">Tahun Ajaran</th>
                                                <th class="border-0">File</th>
                                                <th class="border-0">Tanggal Pengajuan</th>
                                                <th class="border-0">Komentar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $hasRejected = false; @endphp
                                            @foreach ($kurikulums as $kurikulum)
                                                @if($kurikulum->validasi_perusahaan == 'tidak_disetujui')
                                                    @php $hasRejected = true; @endphp
                                                    <tr>
                                                        <td>{{ $kurikulum->pengirim->name }}</td>
                                                        <td>{{ $kurikulum->nama_kurikulum }}</td>
                                                        <td>
                                                            @if($kurikulum->perusahaan_id)
                                                                @php 
                                                                    $perusahaan = App\Models\User::find($kurikulum->perusahaan_id);
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
                                                        <td>{{ \Carbon\Carbon::parse($kurikulum->created_at)->format('Y-m-d') }}</td>
                                                        <td>
                                                            <span class="d-inline-block text-truncate" style="max-width: 150px;" data-bs-toggle="tooltip" title="{{ $kurikulum->komentar }}">
                                                                {{ $kurikulum->komentar }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach                                            @if(!$hasRejected)
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="bi bi-x-circle fs-1 d-block mb-2"></i>
                                                            Belum ada kurikulum yang ditolak
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tolak Kurikulum -->
    <div class="modal fade" id="tolakModal" tabindex="-1" aria-labelledby="tolakModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tolakModalLabel">Tolak Kurikulum</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>                <form action="" method="POST" id="tolakForm">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="komentar" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="komentar" name="komentar" rows="4" required></textarea>
                            <div class="form-text">Berikan alasan mengapa kurikulum ini ditolak. Alasan akan ditampilkan kepada pengirim.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Kurikulum</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Set up the modal form action URL when the modal is shown
        document.addEventListener('DOMContentLoaded', function() {            const tolakModal = document.getElementById('tolakModal');
            if (tolakModal) {
                tolakModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const kurikulumId = button.getAttribute('data-kurikulum-id');
                    const tolakForm = document.getElementById('tolakForm');
                    tolakForm.setAttribute('action', `/admin/kurikulum/${kurikulumId}/tolak-sekolah`);
                });
            }
            
            // Filtering functionality for tables
            const filterDate = document.getElementById('filter-date');
            const searchInput = document.getElementById('search-input');
            const tables = document.querySelectorAll('.kurikulum-table');
            
            if (filterDate) {
                filterDate.addEventListener('change', filterTables);
            }
            
            if (searchInput) {
                searchInput.addEventListener('input', filterTables);
            }
            
            function filterTables() {
                const dateValue = filterDate ? filterDate.value : '';
                const searchValue = searchInput ? searchInput.value.toLowerCase() : '';
                
                tables.forEach(table => {
                    const rows = table.querySelectorAll('tbody tr');
                    
                    rows.forEach(row => {
                        if (row.querySelector('td[colspan]')) {
                            return; // Skip empty state rows
                        }
                        
                        const dateCell = row.querySelector('.created-date');
                        const nameCell = row.querySelector('td:nth-child(2)');
                        
                        const dateMatch = !dateValue || (dateCell && dateCell.textContent.includes(dateValue));
                        const searchMatch = !searchValue || (nameCell && nameCell.textContent.toLowerCase().includes(searchValue));
                        
                        if (dateMatch && searchMatch) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                    
                    // Check if all rows are hidden
                    const allHidden = Array.from(rows).every(row => {
                        return row.style.display === 'none' || row.querySelector('td[colspan]');
                    });
                    
                    // Get or create an empty state row
                    let emptyRow = table.querySelector('.empty-filter-result');
                    
                    if (allHidden && !emptyRow) {
                        const tbody = table.querySelector('tbody');
                        emptyRow = document.createElement('tr');
                        emptyRow.className = 'empty-filter-result';
                        emptyRow.innerHTML = `
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-search fs-1 d-block mb-2"></i>
                                    Tidak ada hasil yang sesuai dengan filter
                                </div>
                            </td>
                        `;
                        tbody.appendChild(emptyRow);
                    } else if (!allHidden && emptyRow) {
                        emptyRow.remove();
                    }
                });
            }
        });
    </script>
@endsection
