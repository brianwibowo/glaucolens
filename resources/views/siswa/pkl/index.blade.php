@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 h4">Daftar Praktik Kerja Lapangan</h1>

    {{-- Filter dan Pencarian --}}
    <div class="row mb-4">
        <div class="col-md-5 mb-2">
            <input type="text" id="search-title" class="form-control" placeholder="Cari berdasarkan judul atau perusahaan...">
        </div>            <div class="col-md-4 mb-2">
            <select id="filter-status" class="form-control">
                <option value="">Semua Status</option>
                <option value="proses">Proses</option>
                <option value="berjalan">Berjalan</option>
                <option value="ditolak">Ditolak</option>
                <option value="selesai">Selesai</option>
            </select>
        </div>
        <div class="col-md-3 mb-2">
            <input type="date" id="filter-date" class="form-control" placeholder="Filter berdasarkan batas akhir daftar">
        </div>
    </div>

    {{-- Daftar Card PKL --}}
    <div class="row" id="pkl-cards">
        @forelse ($pkls as $pkl)
            @php
                $today = \Carbon\Carbon::now();
                // Assuming these are the PKL activity dates, not registration dates
                $pklActivityStartDate = \Carbon\Carbon::parse($pkl->tanggal_mulai);
                $pklActivityEndDate = \Carbon\Carbon::parse($pkl->tanggal_selesai);

                // Assuming PKL registration dates are separate columns
                $regStartDate = \Carbon\Carbon::parse($pkl->tanggal_mulai_pendaftaran);
                $regEndDate = \Carbon\Carbon::parse($pkl->tanggal_selesai_pendaftaran);

                $pklDisplayStatus = '';
                $statusBadgeClass = '';
                $cardBorder = '';
                
                $actionButtonText = '';
                $actionButtonClass = '';
                $actionButtonDisabled = false;
                $buttonActionType = null; // 'register', 'cancel', or null for disabled                // --- PKL Activity Status for Display Badge ---
                // Calculate progress percentage for this PKL
                $progress = $pkl->calculateProgress();
                $progressPercentage = $progress['percentage'];
                
                // First, get the general PKL status
                $pklStatus = $pkl->status;
                
                // For current student, override with their application status if they applied to this PKL
                if (isset($currentStudentPklId) && $currentStudentPklId == $pkl->id) {
                    $userPklStatus = auth()->user()->pkl_status;
                      // Check if PKL is complete first (overrides all other statuses)
                    if ($progressPercentage >= 100) {
                        $pklDisplayStatus = 'Selesai';
                        $statusBadgeClass = 'bg-success';
                        $cardBorder = 'border-success';
                        
                        // Update user's PKL status to "selesai" if progress is 100% but status hasn't been updated
                        if ($userPklStatus === 'disetujui') {
                            auth()->user()->pkl_status = 'selesai';
                            auth()->user()->save();
                        }
                    } 
                    // Then check user's application status
                    else if ($userPklStatus === 'disetujui') {
                        $pklDisplayStatus = 'Berjalan';
                        $statusBadgeClass = 'bg-primary';
                        $cardBorder = 'border-primary';
                    } elseif ($userPklStatus === 'tidak_disetujui') {
                        $pklDisplayStatus = 'Ditolak';
                        $statusBadgeClass = 'bg-danger';
                        $cardBorder = 'border-danger';
                    } elseif ($userPklStatus === 'proses') {
                        $pklDisplayStatus = 'Proses';
                        $statusBadgeClass = 'bg-warning text-dark';
                        $cardBorder = 'border-warning';
                    } else {
                        // Fallback for other statuses
                        $pklDisplayStatus = 'Proses';
                        $statusBadgeClass = 'bg-warning text-dark';
                        $cardBorder = 'border-warning';
                    }
                } else {
                    // For PKLs the student hasn't applied to, show PKL's general status
                    if ($progressPercentage >= 100) {
                        $pklDisplayStatus = 'Selesai';
                        $statusBadgeClass = 'bg-success';
                        $cardBorder = 'border-success';
                    } elseif ($pklStatus === 'berjalan') {
                        $pklDisplayStatus = 'Berjalan';
                        $statusBadgeClass = 'bg-primary';
                        $cardBorder = 'border-primary';
                    } elseif ($pklStatus === 'proses') {
                        $pklDisplayStatus = 'Proses';
                        $statusBadgeClass = 'bg-warning text-dark';
                        $cardBorder = 'border-warning';
                    } else {
                        $pklDisplayStatus = 'Tidak Diketahui';
                        $statusBadgeClass = 'bg-secondary';
                        $cardBorder = 'border-secondary';
                    }
                }
                
                // Set progress bar color based on percentage
                $progressBarColor = 'bg-danger';
                if ($progressPercentage >= 100) {
                    $progressBarColor = 'bg-success';
                } elseif ($progressPercentage >= 75) {
                    $progressBarColor = 'bg-info';
                } elseif ($progressPercentage >= 50) {
                    $progressBarColor = 'bg-primary';
                } elseif ($progressPercentage >= 25) {
                    $progressBarColor = 'bg-warning';
                }                // --- Button Logic for Registration/Cancellation ---
                $isRegisteredForThisPkl = (isset($currentStudentPklId) && $currentStudentPklId == $pkl->id);
                $isRegisteredForOtherPkl = (isset($currentStudentPklId) && $currentStudentPklId !== null && $currentStudentPklId != $pkl->id);
                $isRegistrationPeriodOpen = ($today >= $regStartDate && $today <= $regEndDate);
                $isPklFull = ($pkl->kuota <= 0); // Assuming 'kuota' is remaining slots
                $isPklCompleted = ($progressPercentage >= 100);

                // PKL is completed
                if ($isPklCompleted) {
                    if ($isRegisteredForThisPkl) {
                        $actionButtonText = 'PKL Selesai';
                        $actionButtonClass = 'btn-success';
                    } else {
                        $actionButtonText = 'PKL Sudah Berakhir';
                        $actionButtonClass = 'btn-secondary';
                    }
                    $actionButtonDisabled = true;
                    $buttonActionType = 'disabled';
                }
                // User is registered for this PKL and status is 'proses' (pending)
                else if ($isRegisteredForThisPkl && auth()->user()->pkl_status === 'proses') {
                    $actionButtonText = 'Batal Daftar';
                    $actionButtonClass = 'btn-danger';
                    $actionButtonDisabled = false;
                    $buttonActionType = 'cancel';
                } 
                // User is registered for this PKL and approved
                else if ($isRegisteredForThisPkl && auth()->user()->pkl_status === 'disetujui') {
                    $actionButtonText = 'Sedang Berjalan';
                    $actionButtonClass = 'btn-info';
                    $actionButtonDisabled = true;
                    $buttonActionType = 'disabled';
                }
                // User is registered for another PKL
                else if ($isRegisteredForOtherPkl) {
                    $actionButtonText = 'Sudah Terdaftar di PKL Lain';
                    $actionButtonClass = 'btn-info';
                    $actionButtonDisabled = true;
                    $buttonActionType = 'disabled';
                }
                // Allow registration for other PKLs
                else { 
                    $actionButtonText = 'Daftar Sekarang';
                    $actionButtonClass = 'btn-primary';
                    $actionButtonDisabled = false;
                    $buttonActionType = 'register';
                }// For JS filter (match the exact filter options: proses, berjalan, ditolak, selesai)
                $dataStatusForFilter = strtolower($pklDisplayStatus);
                if ($dataStatusForFilter === 'tidak diketahui') {
                    $dataStatusForFilter = 'proses'; // Default to proses for filtering unknown status
                }
            @endphp

            <div class="col-lg-4 col-md-6 mb-4 pkl-card"
                data-title="{{ strtolower($pkl->nama ?? '') }}"
                data-company="{{ strtolower($pkl->perusahaan->name ?? '') }}"
                data-status="{{ $dataStatusForFilter }}"
                data-end-date="{{ $regEndDate->format('Y-m-d') }}"> {{-- Use registration end date for filter --}}
                <div class="card h-100 {{ $cardBorder }} shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light">
                        <h6 class="card-title mb-0 text-truncate" style="max-width: 70%;" title="{{ $pkl->nama ?? $pkl->judul ?? 'N/A' }}">
                            {{ $pkl->nama ?? $pkl->judul ?? 'N/A' }}
                        </h6>
                        <span class="badge {{ $statusBadgeClass }}">{{ $pklDisplayStatus }}</span>
                    </div>
                      <div class="card-body">
                        <p class="card-text text-muted mb-3" style="min-height: 60px;">
                            {{ Str::limit($pkl->deskripsi ?? '', 100) }}
                        </p>

                        <ul class="list-unstyled small mb-3">
                            <li><i class="bi bi-building me-1"></i> Perusahaan: <strong>{{ $pkl->perusahaan->name ?? 'N/A' }}</strong></li>
                            <li><i class="bi bi-person-fill-add me-1"></i> Pendaftar: <strong>{{ $pkl->siswas->count() ?? '0' }} peserta</strong></li>
                            <li><i class="bi bi-calendar-event me-1"></i> Periode PKL: {{ $regStartDate->format('d M Y') }} - {{ $regEndDate->format('d M Y') }}</li>
                        </ul>
                    </div>
                    
                    <div class="card-footer bg-white text-center">
                        @if ($actionButtonDisabled)
                            {{-- This button is disabled and will not submit any form --}}
                            <button type="button" class="btn {{ $actionButtonClass }} w-100 rounded-pill shadow-sm" disabled>
                                {{ $actionButtonText }}
                            </button>
                        @else
                            @if ($buttonActionType === 'register')
                                <form action="{{ route('siswa-pkl-register', $pkl->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ $actionButtonClass }} w-100 rounded-pill shadow-sm">
                                        {{ $actionButtonText }}
                                    </button>
                                </form>
                            @elseif ($buttonActionType === 'cancel')
                                <form action="{{ route('siswa-pkl-batal') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran PKL ini?');">
                                    @csrf
                                    @method('DELETE') {{-- Use DELETE method for cancellation --}}
                                    <button type="submit" class="btn {{ $actionButtonClass }} w-100 rounded-pill shadow-sm">
                                        {{ $actionButtonText }}
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div id="empty-state-initial" class="text-center py-5 w-100">
                <div class="mb-3">
                    <i class="bi bi-folder-x" style="font-size: 4rem; color: #6c757d;"></i>
                </div>
                <h5 class="text-muted">Tidak ada Praktik Kerja Lapangan yang ditemukan</h5>
                <p class="text-muted">Silakan cek kembali nanti untuk informasi PKL terbaru.</p>
            </div>
        @endforelse
    </div>

    {{-- Empty State untuk hasil filter/pencarian --}}
    <div id="empty-state-filtered" class="text-center py-5" style="display: none;">
        <div class="mb-3">
            <i class="bi bi-search" style="font-size: 4rem; color: #6c757d;"></i>
        </div>
        <h5 class="text-muted">Tidak Ada PKL yang Cocok</h5>
        <p class="text-muted">Coba ubah kriteria filter atau pencarian Anda.</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterStatus = document.getElementById('filter-status');
        const filterDate = document.getElementById('filter-date');
        const searchTitle = document.getElementById('search-title');
        const pklCardsContainer = document.getElementById('pkl-cards');
        const emptyStateInitial = document.getElementById('empty-state-initial');
        const emptyStateFiltered = document.getElementById('empty-state-filtered');

        function filterPkls() {
            const selectedStatus = filterStatus.value;
            const selectedDate = filterDate.value;
            const searchTitleValue = searchTitle.value.toLowerCase();
            const cards = document.querySelectorAll('.pkl-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const title = card.getAttribute('data-title');
                const company = card.getAttribute('data-company');
                const status = card.getAttribute('data-status');
                const endDate = card.getAttribute('data-end-date');
                
                let showCard = true;
                
                // Filter by title or company name
                if (searchTitleValue && !(title.includes(searchTitleValue) || company.includes(searchTitleValue))) {
                    showCard = false;
                }
                
                // Filter by status
                if (selectedStatus && status !== selectedStatus) {
                    showCard = false;
                }

                // Filter by end date
                if (selectedDate && endDate !== selectedDate) {
                    showCard = false;
                }
                
                if (showCard) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Logic to show/hide empty state
            if (pklCardsContainer.children.length === 0 && emptyStateInitial) {
                // No PKL from backend at all
                emptyStateInitial.style.display = 'block';
                emptyStateFiltered.style.display = 'none';
            } else if (visibleCount === 0) {
                // PKLs exist from backend, but none match the filter
                if (emptyStateInitial) emptyStateInitial.style.display = 'none'; // Ensure initial is hidden
                emptyStateFiltered.style.display = 'block';
            } else {
                // PKLs match the filter
                if (emptyStateInitial) emptyStateInitial.style.display = 'none';
                emptyStateFiltered.style.display = 'none';
            }
        }

        // Event listeners
        filterStatus.addEventListener('change', filterPkls);
        filterDate.addEventListener('input', filterPkls);
        searchTitle.addEventListener('input', filterPkls);

        // Call filter once when DOM is ready to set initial state
        filterPkls();

        // Card hover effects
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.transition = 'transform 0.3s ease';
                this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '';
            });
        });
    });
</script>

<style>
    .card {
        transition: all 0.3s ease;
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

    .card-footer .btn {
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }
    .card-footer .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }
    .card-footer .btn-secondary,
    .card-footer .btn-info {
        cursor: not-allowed;
        opacity: 0.8;
    }
</style>
@endsection