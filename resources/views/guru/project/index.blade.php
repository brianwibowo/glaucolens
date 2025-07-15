@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0">Daftar Project</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif    
        
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                    <input type="date" id="filter-date" class="form-control" placeholder="Filter berdasarkan tanggal mulai">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                    <select id="filter-status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="tertunda">Tertunda</option>
                        <option value="berlangsung">Berlangsung</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="search-title" class="form-control" placeholder="Cari berdasarkan judul project...">
                </div>
            </div>
        </div>

        <div class="row" id="project-cards">
            @foreach($projects as $project)
                @php
                    $today = now();
                    $startDate = \Carbon\Carbon::parse($project->tanggal_mulai);
                    $endDate = \Carbon\Carbon::parse($project->tanggal_selesai);
                    
                    if ($today < $startDate) {
                        $status = 'tertunda';
                        $badgeClass = 'bg-warning text-dark';
                        $statusText = 'Tertunda';
                        $cardBorder = 'border-warning';
                    } elseif ($today > $endDate) {
                        $status = 'selesai';
                        $badgeClass = 'bg-success';
                        $statusText = 'Selesai';
                        $cardBorder = 'border-success';
                    } else {
                        $status = 'berlangsung';
                        $badgeClass = 'bg-primary';
                        $statusText = 'Berlangsung';
                        $cardBorder = 'border-primary';
                    }
                    
                    $progressPercentage = 0;
                    if ($today >= $startDate && $today <= $endDate) {
                        $totalDays = $startDate->diffInDays($endDate);
                        $elapsedDays = $startDate->diffInDays($today);
                        $progressPercentage = $totalDays > 0 ? min(100, ($elapsedDays / $totalDays) * 100) : 0;
                    } elseif ($today > $endDate) {
                        $progressPercentage = 100;
                    }
                @endphp
                
                <div class="col-lg-4 col-md-6 mb-4 project-card" data-status="{{ $status }}" data-start-date="{{ $startDate->format('Y-m-d') }}" data-title="{{ strtolower($project->judul) }}">
                    <div class="card h-100 {{ $cardBorder }} shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light">
                            <h6 class="card-title mb-0 text-truncate" style="max-width: 70%;" title="{{ $project->judul }}">
                                {{ $project->judul }}
                            </h6>
                            <span class="badge {{ $badgeClass }}" data-status="{{ $status }}">{{ $statusText }}</span>
                        </div>
                        <div class="px-3 pt-2 pb-0">
                            <small class="text-muted">Perusahaan: <strong>{{ $project->perusahaan->name ?? '-' }}</strong></small>
                        </div>
                        
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Progress:</small>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $status === 'selesai' ? 'success' : ($status === 'berlangsung' ? 'primary' : 'secondary') }}" 
                                        role="progressbar" 
                                        style="width: {{ $progressPercentage }}%"
                                        aria-valuenow="{{ $progressPercentage }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted">{{ round($progressPercentage) }}% selesai</small>
                            </div>
                            
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Mulai</small>
                                    <strong>{{ $startDate->format('d M Y') }}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Selesai</small>
                                    <strong>{{ $endDate->format('d M Y') }}</strong>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    @if($project->file_brief)
                                        <a href="{{ Storage::url($project->file_brief) }}" target="_blank" class="btn btn-outline-info btn-sm w-100">
                                            <i class="bi bi-file-earmark-text"></i> Brief
                                        </a>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm w-100" disabled>
                                            <i class="bi bi-file-earmark-x"></i> Tidak Ada
                                        </button>
                                    @endif
                                </div>
                                <div class="col-6">
                                    @if($project->file_laporan)
                                        <a href="{{ Storage::url($project->file_laporan) }}" target="_blank" class="btn btn-outline-success btn-sm w-100">
                                            <i class="bi bi-file-earmark-check"></i> Laporan
                                        </a>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm w-100" disabled>
                                            <i class="bi bi-file-earmark-x"></i> Belum Ada
                                        </button>
                                    @endif
                                </div>
                            </div>
                            
                            @if($project->is_manual_upload && $project->upload_notes)
                                <div class="alert alert-info py-1 px-2 mb-0 small">
                                    <i class="bi bi-info-circle"></i> <strong>Catatan Admin:</strong> {{ $project->upload_notes }}
                                </div>
                            @endif
                        </div>

                        <div class="card-footer bg-white">
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $project->id }}">
                                    <i class="bi bi-eye"></i> Detail
                                </button>
                                @if($project->file_laporan)
                                    @if($project->is_manual_upload)
                                        <!-- If admin uploaded the report, show disabled buttons -->
                                        <button type="button" class="btn btn-outline-warning btn-sm" disabled title="Laporan ini diupload oleh admin. Anda tidak dapat memperbarui atau menghapusnya.">
                                            <i class="bi bi-pencil"></i> Update Laporan
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" disabled title="Laporan ini diupload oleh admin. Anda tidak dapat memperbarui atau menghapusnya.">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    @elseif($today <= $endDate)
                                        <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateLaporanModal{{ $project->id }}">
                                            <i class="bi bi-pencil"></i> Update Laporan
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDeleteLaporan({{ $project->id }})">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-outline-warning btn-sm" disabled title="Tidak dapat mengupdate laporan karena timeline telah berakhir">
                                            <i class="bi bi-pencil"></i> Update Laporan
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" disabled title="Tidak dapat menghapus laporan karena timeline telah berakhir">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    @endif
                                @else
                                    @if($today <= $endDate)
                                        <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#uploadLaporanModal{{ $project->id }}">
                                            <i class="bi bi-upload"></i> Upload Laporan
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-outline-success btn-sm" disabled title="Tidak dapat mengupload laporan karena timeline telah berakhir">
                                            <i class="bi bi-upload"></i> Upload Laporan
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="detailModal{{ $project->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $project->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailModalLabel{{ $project->id }}">{{ $project->judul }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="text-primary">Status Project</h6>
                                            <span class="badge {{ $badgeClass }} fs-6">{{ $statusText }}</span>
                                        </div>
                                        <div class="progress mb-2">
                                            <div class="progress-bar bg-{{ $status === 'selesai' ? 'success' : ($status === 'berlangsung' ? 'primary' : 'secondary') }}" 
                                                style="width: {{ $progressPercentage }}%">
                                                {{ round($progressPercentage) }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <small class="text-muted">Perusahaan: <strong>{{ $project->perusahaan->name ?? '-' }}</strong></small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><strong>Timeline:</strong></h6>
                                        <p><i class="bi bi-calendar-event text-success"></i> <strong>Mulai:</strong> {{ $startDate->format('d M Y') }}</p>
                                        <p><i class="bi bi-calendar-check text-danger"></i> <strong>Selesai:</strong> {{ $endDate->format('d M Y') }}</p>
                                        
                                        <h6><strong>Durasi:</strong></h6>
                                        <p><i class="bi bi-clock"></i> {{ $startDate->diffInDays($endDate) }} hari</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><strong>Files:</strong></h6>
                                        <p>
                                            @if($project->file_brief)
                                                <a href="{{ Storage::url($project->file_brief) }}" target="_blank" class="btn btn-info btn-sm">
                                                    <i class="bi bi-download"></i> Download Brief
                                                </a>
                                            @else
                                                <span class="text-muted"><i class="bi bi-exclamation-circle"></i> Brief tidak tersedia</span>
                                            @endif
                                        </p>                                    <p>                                        @if($project->file_laporan)
                                                <a href="{{ Storage::url($project->file_laporan) }}" target="_blank" class="btn btn-success btn-sm">
                                                    <i class="bi bi-download"></i> Download Laporan
                                                </a>
                                                @if($project->is_manual_upload && $project->upload_notes)
                                                    <div class="alert alert-info py-1 px-2 mt-2 small">
                                                        <i class="bi bi-info-circle"></i> <strong>Catatan Admin:</strong> {{ $project->upload_notes }}
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted"><i class="bi bi-exclamation-circle"></i> Laporan belum tersedia</span>
                                                @if($today > $endDate)
                                                    <br><span class="text-danger small mt-1"><i class="bi bi-exclamation-triangle"></i> Timeline project telah berakhir, tidak dapat mengupload laporan</span>
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($today <= $endDate)
                <div class="modal fade" id="uploadLaporanModal{{ $project->id }}" tabindex="-1" aria-labelledby="uploadLaporanModalLabel{{ $project->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadLaporanModalLabel{{ $project->id }}">Upload Laporan Project</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('guru-project-laporan-upload', $project->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="file_laporan" class="form-label">File Laporan</label>
                                        <input type="file" class="form-control" id="file_laporan" name="file_laporan" required>
                                        <small class="text-muted">Format yang didukung: PDF, DOC, DOCX</small>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                @if($today <= $endDate)
                <div class="modal fade" id="updateLaporanModal{{ $project->id }}" tabindex="-1" aria-labelledby="updateLaporanModalLabel{{ $project->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateLaporanModalLabel{{ $project->id }}">Update Laporan Project</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('guru-project-laporan-update', $project->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="file_laporan" class="form-label">File Laporan Baru</label>
                                        <input type="file" class="form-control" id="file_laporan" name="file_laporan" required>
                                        <small class="text-muted">Format yang didukung: PDF, DOC, DOCX</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-warning">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                <form id="deleteLaporanForm{{ $project->id }}" action="{{ route('guru-project-laporan-delete', $project->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        </div>

        <div id="empty-state" class="text-center py-5" style="display: none;">
            <div class="mb-3">
                <i class="bi bi-folder-x" style="font-size: 4rem; color: #6c757d;"></i>
            </div>
            <h5 class="text-muted">Tidak ada project yang ditemukan</h5>
            <p class="text-muted">Coba ubah filter pencarian</p>
        </div>
    </div>

    <script>
        function filterProjects() {
            const selectedDate = document.getElementById('filter-date').value;
            const selectedStatus = document.getElementById('filter-status').value;
            const searchTitle = document.getElementById('search-title').value.toLowerCase();
            const cards = document.querySelectorAll('.project-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const startDate = card.getAttribute('data-start-date');
                const status = card.getAttribute('data-status');
                const title = card.getAttribute('data-title');
                
                let showCard = true;
                if (selectedDate && selectedDate !== '') {
                    const filterDate = new Date(selectedDate);
                    const cardDate = new Date(startDate);
                    
                    if (filterDate.toISOString().split('T')[0] !== cardDate.toISOString().split('T')[0]) {
                        showCard = false;
                    }
                }

                if (selectedStatus && status !== selectedStatus) {
                    showCard = false;
                }
                
                if (searchTitle && !title.includes(searchTitle)) {
                    showCard = false;
                }
                
                if (showCard) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            document.getElementById('empty-state').style.display = visibleCount === 0 ? 'block' : 'none';
        }
        const filterDateInput = document.getElementById('filter-date');
        const filterStatusSelect = document.getElementById('filter-status');
        const searchTitleInput = document.getElementById('search-title');

        filterDateInput.addEventListener('change', filterProjects);
        filterDateInput.addEventListener('input', filterProjects);
        filterStatusSelect.addEventListener('change', filterProjects);
        searchTitleInput.addEventListener('input', filterProjects);

        filterProjects();

        function confirmDeleteLaporan(projectId) {
            if (confirm('Apakah Anda yakin ingin menghapus laporan ini?')) {
                document.getElementById('deleteLaporanForm' + projectId).submit();
            }
        }
    </script>
@endsection
