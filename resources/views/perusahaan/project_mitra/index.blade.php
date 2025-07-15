@extends('layouts.layout')

@section('content')
    <div class="container mt-4">    
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0">Daftar Project</h1>
            <a href="{{ route('perusahaan-project-create') }}" class="btn btn-success">
                + Ajukan Project
            </a>
        </div>
    
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif    
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-info-circle-fill"></i> Anda dapat menambahkan project yang sudah berjalan atau selesai dari tahun kapanpun. Gunakan tanggal yang sesuai dengan timeline project sebenarnya, termasuk untuk project-project lama atau terdahulu.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                        <option value="tertunda">Tertunda</option>
                        <option value="berlangsung">Berlangsung</option>
                        <option value="selesai">Selesai</option>
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

        <div class="row" id="project-cards">
            @foreach ($projects as $project)
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
                        
                        <div class="card-body">
                            <p class="card-text text-muted mb-3" style="min-height: 20px;">
                                {{ Str::limit($project->deskripsi, 120) }}
                            </p>
                            
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
                                    <strong class="start-date">{{ $startDate->format('d M Y') }}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Selesai</small>
                                    <strong>{{ $endDate->format('d M Y') }}</strong>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <a href="{{ asset('storage/'.$project->file_brief)}}" target="_blank" class="btn btn-outline-info btn-sm w-100">
                                        <i class="bi bi-file-earmark-text"></i> Brief
                                    </a>
                                </div>
                                <div class="col-6">
                                    @if($project->file_laporan)
                                        <a href="{{ asset('storage/'.$project->file_laporan)}}" target="_blank" class="btn btn-outline-success btn-sm w-100">
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
                                <a href="{{ route('perusahaan-project-edit', ['project' => $project->id]) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $project->id }})">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </div>
                            
                            <!-- Hidden form for delete -->
                            <form id="deleteForm{{ $project->id }}" action="{{ route('perusahaan-project-destroy', ['project' => $project->id]) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
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
                                            <a href="{{ asset('storage/'.$project->file_brief)}}" target="_blank" class="btn btn-info btn-sm">
                                                <i class="bi bi-download"></i> Download Brief
                                            </a>
                                        </p>                                    <p>
                                            @if($project->file_laporan)
                                                <a href="/project/laporan/{{ $project->file_laporan }}" target="_blank" class="btn btn-success btn-sm">
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
                                                    <div class="alert alert-warning py-1 px-2 mt-2 small">
                                                        <i class="bi bi-exclamation-triangle"></i> Timeline project telah berakhir. Menunggu upload manual oleh admin.
                                                    </div>
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h6><strong>Deskripsi Project:</strong></h6>
                                        <div class="p-3 bg-light rounded">
                                            {{ $project->deskripsi }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <a href="{{ route('perusahaan-project-edit', ['project' => $project->id]) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Edit Project
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="empty-state" class="text-center py-5" style="display: none;">
            <div class="mb-3">
                <i class="bi bi-folder-x" style="font-size: 4rem; color: #6c757d;"></i>
            </div>
            <h5 class="text-muted">Tidak ada project yang ditemukan</h5>
            <p class="text-muted">Coba ubah filter atau tambahkan project baru</p>
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
                
                // Filter by date - improved logic
                if (selectedDate && selectedDate !== '') {
                    // Convert both dates to Date objects for proper comparison
                    const filterDate = new Date(selectedDate);
                    const cardDate = new Date(startDate);
                    
                    // Compare dates ignoring the time part
                    if (filterDate.toISOString().split('T')[0] !== cardDate.toISOString().split('T')[0]) {
                        showCard = false;
                    }
                }
                
                // Filter by status
                if (selectedStatus && status !== selectedStatus) {
                    showCard = false;
                }
                
                // Filter by title
                if (searchTitle && !title.includes(searchTitle)) {
                    showCard = false;
                }
                
                // Update visibility
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

        function confirmDelete(projectId) {
            if (confirm('Yakin ingin menghapus project ini? Semua file yang terkait (brief dan laporan) juga akan dihapus. Tindakan ini tidak dapat dibatalkan.')) {
                document.getElementById('deleteForm' + projectId).submit();
            }
        }

        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.transition = 'transform 0.3s ease';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>

    <style>
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }
        
        .progress {
            background-color: #e9ecef;
        }
        
        .badge {
            font-size: 0.75rem;
        }
        
        .btn-group .btn {
            flex: 1;
        }
        
        .card-footer {
            border-top: 1px solid #dee2e6;
        }
    </style>
@endsection