@extends('layouts.layout')

@section('content')
    <div class="container mt-4">    
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0">Manajemen Project Mitra</h1>
            <div>
                <a href="{{ route('admin-project-create') }}" class="btn btn-primary btn-sm me-2">
                    <i class="bi bi-plus-circle"></i> Tambah Project
                </a>
                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#adminInfoModal">
                    <i class="bi bi-info-circle"></i> Info Admin
                </button>
            </div>
        </div>
        
        <div class="modal fade" id="adminInfoModal" tabindex="-1" aria-labelledby="adminInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="adminInfoModalLabel">Informasi Penggunaan Halaman Admin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle-fill"></i> Hak Istimewa Admin:</h6>
                            <ul class="mb-0">
                                <li>Admin dapat membuat, mengedit, dan menghapus project mitra.</li>
                                <li>Admin dapat memilih perusahaan yang mengajukan project.</li>
                                <li>Admin dapat mengupload, memperbarui, dan menghapus laporan project kapan saja, termasuk setelah timeline project berakhir.</li>
                                <li>Jika project timeline telah berakhir, sistem akan meminta admin untuk memberikan catatan saat mengupload/memperbarui laporan.</li>
                                <li>Catatan admin akan ditampilkan di halaman Guru dan Perusahaan untuk transparansi.</li>
                                <li>Jika admin mengupload laporan setelah timeline project berakhir, Guru tidak akan dapat mengedit/menghapus laporan tersebut.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
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
                    <div class="card h-100 {{ $cardBorder }} shadow-sm {{ $today > $endDate ? 'border-2' : '' }}">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light">
                            <h6 class="card-title mb-0 text-truncate" style="max-width: 70%;" title="{{ $project->judul }}">
                                {{ $project->judul }}
                            </h6>
                            <span class="badge {{ $badgeClass }}" data-status="{{ $status }}">{{ $statusText }}</span>
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
                            
                            <!-- Add Perusahaan information -->
                            <div class="mb-3">
                                <small class="text-muted">Perusahaan:</small>
                                <div class="d-flex align-items-center mt-1">
                                    <i class="bi bi-building me-2 text-secondary"></i>
                                    <strong>{{ $project->perusahaan ? $project->perusahaan->name : 'Tidak ada perusahaan' }}</strong>
                                </div>
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
                                    @if($today > $endDate)
                                        <div class="mt-1 text-danger small">
                                            <i class="bi bi-exclamation-triangle-fill"></i> Upload setelah timeline berakhir
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-footer bg-white">
                            <div class="d-flex flex-column gap-2">
                                <!-- Project Actions -->
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('admin-project-edit', $project) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-pencil-square"></i> Edit Project
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDeleteProject({{ $project->id }})">
                                        <i class="bi bi-trash"></i> Hapus Project
                                    </button>
                                </div>
                                
                                <!-- Laporan Actions -->
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $project->id }}">
                                        <i class="bi bi-eye"></i> Detail
                                    </button>
                                    @if($project->file_laporan)
                                        <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateLaporanModal{{ $project->id }}">
                                            <i class="bi bi-pencil"></i> Update Laporan
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDeleteLaporan({{ $project->id }})">
                                            <i class="bi bi-trash"></i> Hapus Laporan
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#uploadLaporanModal{{ $project->id }}">
                                            <i class="bi bi-upload"></i> Upload Laporan
                                        </button>
                                    @endif
                                </div>
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
                                
                                <!-- Display perusahaan information -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="card bg-light">
                                            <div class="card-body py-2">
                                                <h6 class="text-primary mb-2"><i class="bi bi-building"></i> Perusahaan</h6>
                                                <p class="mb-0">
                                                    @if($project->perusahaan)
                                                        <strong>{{ $project->perusahaan->name }}</strong><br>
                                                        <small class="text-muted">{{ $project->perusahaan->email }}</small>
                                                    @else
                                                        <span class="text-muted">Tidak ada perusahaan terkait</span>
                                                    @endif
                                                </p>
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
                                            @if($project->file_brief)
                                                <a href="{{ Storage::url($project->file_brief) }}" target="_blank" class="btn btn-info btn-sm">
                                                    <i class="bi bi-download"></i> Download Brief
                                                </a>
                                            @else
                                                <span class="text-muted"><i class="bi bi-exclamation-circle"></i> Brief tidak tersedia</span>
                                            @endif
                                        </p>
                                        <p>
                                            @if($project->file_laporan)
                                                <a href="{{ Storage::url($project->file_laporan) }}" target="_blank" class="btn btn-success btn-sm">
                                                    <i class="bi bi-download"></i> Download Laporan
                                                </a>                                            @if($project->is_manual_upload && $project->upload_notes)
                                                    <div class="alert alert-info py-2 px-3 mt-2 mb-0">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-info-circle-fill me-2"></i>
                                                            <div>
                                                                <strong>Catatan Admin:</strong><br>
                                                                {{ $project->upload_notes }}
                                                                @if($today > $endDate)
                                                                    <div class="mt-1 text-danger small">
                                                                        <i class="bi bi-exclamation-triangle-fill"></i> Upload setelah timeline berakhir
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted"><i class="bi bi-exclamation-circle"></i> Laporan belum tersedia</span>
                                                @if($today > $endDate)
                                                    <div class="alert alert-warning py-1 px-2 mt-2 small">
                                                        <i class="bi bi-exclamation-triangle"></i> Timeline project telah berakhir. Guru tidak dapat mengupload laporan, tetapi Admin masih dapat mengunggah laporan.
                                                    </div>
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

                <div class="modal fade" id="uploadLaporanModal{{ $project->id }}" tabindex="-1" aria-labelledby="uploadLaporanModalLabel{{ $project->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadLaporanModalLabel{{ $project->id }}">Upload Laporan Project</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="{{ route('admin-project-laporan-upload', $project->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="file_laporan" class="form-label">File Laporan</label>
                                        <input type="file" class="form-control" id="file_laporan" name="file_laporan" required>
                                        <small class="text-muted">Format yang didukung: PDF, DOC, DOCX</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Catatan Upload</label>
                                        @if($today > $endDate)
                                            <textarea class="form-control" id="notes" name="notes" rows="3">Laporan diupload oleh admin setelah timeline project berakhir. {{ $endDate->format('d M Y') }}</textarea>
                                            <small class="text-danger">Catatan ini akan ditampilkan bersama dengan laporan di halaman Guru dan Perusahaan</small>
                                        @else
                                            <textarea class="form-control" id="notes" name="notes" rows="2">Diupload oleh admin</textarea>
                                            <small class="text-muted">Catatan ini akan ditampilkan bersama dengan laporan</small>
                                        @endif
                                    </div>
                                    
                                    @if($today > $endDate)
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle-fill"></i> <strong>Timeline project telah berakhir!</strong> Anda melakukan upload laporan setelah batas waktu.
                                            <hr>
                                            <small>Setelah laporan diupload oleh admin, Guru <strong>tidak akan dapat</strong> memperbarui atau menghapus laporan tersebut.</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="updateLaporanModal{{ $project->id }}" tabindex="-1" aria-labelledby="updateLaporanModalLabel{{ $project->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateLaporanModalLabel{{ $project->id }}">Update Laporan Project</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="{{ route('admin-project-laporan-update', $project->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="file_laporan" class="form-label">File Laporan Baru</label>
                                        <input type="file" class="form-control" id="file_laporan" name="file_laporan" required>
                                        <small class="text-muted">Format yang didukung: PDF, DOC, DOCX</small>
                                    </div>
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Catatan Update</label>
                                        @if($today > $endDate)
                                            <textarea class="form-control" id="notes" name="notes" rows="3">Laporan diperbarui oleh admin setelah timeline project berakhir. {{ $endDate->format('d M Y') }}</textarea>
                                            <small class="text-danger">Catatan ini akan ditampilkan bersama dengan laporan di halaman Guru dan Perusahaan</small>
                                        @else
                                            <textarea class="form-control" id="notes" name="notes" rows="2">Diperbarui oleh admin</textarea>
                                            <small class="text-muted">Catatan ini akan ditampilkan bersama dengan laporan</small>
                                        @endif
                                    </div>
                                    
                                    @if($today > $endDate)
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle-fill"></i> <strong>Timeline project telah berakhir!</strong> Anda melakukan update laporan setelah batas waktu.
                                            <hr>
                                            <small>Setelah laporan diupdate oleh admin, Guru <strong>tidak akan dapat</strong> memperbarui atau menghapus laporan tersebut.</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-warning">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <form id="deleteLaporanForm{{ $project->id }}" action="{{ route('admin-project-laporan-delete', $project) }}" method="POST" style="display: none;">
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

        function confirmDeleteProject(projectId) {
            if (confirm('Apakah Anda yakin ingin menghapus project ini? Semua data termasuk file brief dan laporan akan dihapus.')) {
                document.getElementById('deleteProjectForm' + projectId).submit();
            }
        }
    </script>

    <!-- Delete Project Forms -->
    @foreach($projects as $project)
        <form id="deleteProjectForm{{ $project->id }}" action="{{ route('admin-project-destroy', $project) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        <form id="deleteLaporanForm{{ $project->id }}" action="{{ route('admin-project-laporan-delete', $project) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endsection
