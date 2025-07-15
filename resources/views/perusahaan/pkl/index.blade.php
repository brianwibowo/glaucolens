@extends('layouts.layout')

@section('content')
<div class="container mt-4">

    {{-- Judul Halaman dan Tombol Aksi --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Daftar Kelompok PKL</h1>
        <a href="{{ route('perusahaan-pkl-create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Buat Kelompok
        </a>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Opsi Filter --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <select id="filter-status" class="form-select">
                <option value="">Semua Status PKL</option>
                <option value="proses">Proses</option>
                <option value="berjalan">Berjalan</option>
                <option value="selesai">Selesai</option>
            </select>
        </div>
        <div class="col-md-4">
            <select id="filter-tahun" class="form-select">
                <option value="">Semua Tahun</option>
                @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" id="search-nama" class="form-control" placeholder="Cari nama kelompok...">
        </div>
    </div>

    {{-- Daftar Card Kelompok PKL --}}
    <div class="row" id="kelompok-cards">
        @forelse ($pkls as $kelompok)            @php
                // Logika untuk status, badge, dan border
                $progress = $kelompok->calculateProgress();
                $progressPercentage = $progress['percentage'];
                $progressStatus = $progress['status'];
                
                // Cek apakah ada siswa yang terdaftar
                $hasApplicants = $kelompok->siswas()->count() > 0;
                
                // Default status
                $status = 'proses';
                $badgeClass = 'bg-warning text-dark';
                $cardBorder = 'border-warning';
                $statusText = 'Proses';
                
                // Update status berdasarkan progress dan keberadaan pelamar
                if ($progressPercentage >= 100) {
                    // Jika progress sudah 100%, maka status "selesai"
                    $status = 'selesai';
                    $badgeClass = 'bg-success';
                    $cardBorder = 'border-success';
                    $statusText = 'Selesai';
                } elseif ($hasApplicants) {
                    // Jika sudah ada pelamar dan progress belum 100%, maka status "berjalan"
                    $status = 'berjalan';
                    $badgeClass = 'bg-primary';
                    $cardBorder = 'border-primary';
                    $statusText = 'Berjalan';
                }
                
                // Update status PKL di database jika perlu
                if ($kelompok->status !== $status) {
                    $kelompok->status = $status;
                    $kelompok->save();
                }
                
                $tanggalMulai = \Carbon\Carbon::parse($kelompok->tanggal_mulai);
                $tahunPkl = $tanggalMulai->format('Y');
                
                // Calculate progress
                $progress = $kelompok->calculateProgress();
                $progressPercentage = $progress['percentage'];
                $progressStatus = $progress['status'];
                
                // Set progress bar color
                $progressBarColor = 'bg-info';
                if ($progressPercentage >= 100) {
                    $progressBarColor = 'bg-success';
                } elseif ($progressPercentage >= 75) {
                    $progressBarColor = 'bg-info';
                } elseif ($progressPercentage >= 50) {
                    $progressBarColor = 'bg-primary';
                } elseif ($progressPercentage >= 25) {
                    $progressBarColor = 'bg-warning';
                } else {
                    $progressBarColor = 'bg-danger';
                }
            @endphp
            
            <div class="col-lg-4 col-md-6 mb-4 kelompok-card" 
                 data-status="{{ $status }}" 
                 data-tahun="{{ $tahunPkl }}" 
                 data-nama="{{ strtolower($kelompok->nama) }}">
                <div class="card h-100 {{ $cardBorder }} shadow-sm">
                    {{-- Header Kartu: Nama Kelompok dan Status --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 text-truncate" title="{{ $kelompok->nama }}">
                            {{ $kelompok->nama }}
                        </h6>
                        <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                    </div>
                    
                    {{-- Body Kartu: Informasi Esensial --}}
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-building text-muted me-2" style="font-size: 1.2rem;"></i>
                            <span class="text-truncate">{{ $kelompok->perusahaan->name ?? 'Perusahaan belum diatur' }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-person-check-fill text-muted me-2" style="font-size: 1.2rem;"></i>
                            <span class="text-truncate">{{ $kelompok->pembimbing ? $kelompok->pembimbing->name : 'Pembimbing belum diatur' }}</span>
                        </div>                        <div class="d-flex align-items-center mb-2 text-muted">
                            <i class="bi bi-calendar-range text-muted me-2" style="font-size: 1.2rem;"></i>
                            <small>{{ $tanggalMulai->format('d M Y') }} - {{ \Carbon\Carbon::parse($kelompok->tanggal_selesai)->format('d M Y') }}</small>
                        </div>
                    </div>
                    
                    {{-- Footer Kartu: Tombol Aksi --}}
                    <div class="card-footer bg-light">
                        <div class="btn-group w-100" role="group">
                            {{-- Tombol ini mengarah ke halaman detail --}}
                            <a href="{{ route('perusahaan-pkl-show', $kelompok->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            <a href="{{ route('perusahaan-pkl-edit', $kelompok->id) }}" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $kelompok->id }})">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                        <form id="deleteForm{{ $kelompok->id }}" action="{{ route('perusahaan-pkl-destroy', $kelompok->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>

        @empty
            {{-- Tampilan jika tidak ada data kelompok sama sekali --}}
            <div class="col-12">
                 <div class="text-center py-5 bg-light rounded">
                    <div class="mb-3">
                        <i class="bi bi-inbox" style="font-size: 4rem; color: #6c757d;"></i>
                    </div>
                    <h5 class="text-muted">Belum Ada Kelompok PKL</h5>
                    <p class="text-muted">Klik tombol "Buat Kelompok" untuk menambahkan data baru.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div id="empty-state" class="text-center py-5" style="display: none;">
        <div class="mb-3">
            <i class="bi bi-search" style="font-size: 4rem; color: #6c757d;"></i>
        </div>
        <h5 class="text-muted">Tidak Ada Kelompok yang Cocok</h5>
        <p class="text-muted">Coba ubah kriteria filter atau pencarian Anda.</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterStatus = document.getElementById('filter-status');
    const filterTahun = document.getElementById('filter-tahun');
    const searchNama = document.getElementById('search-nama');

    function filterKelompok() {
        const selectedStatus = filterStatus.value;
        const selectedTahun = filterTahun.value;
        const searchNamaValue = searchNama.value.toLowerCase();
        const cards = document.querySelectorAll('.kelompok-card');
        const emptyState = document.getElementById('empty-state');
        let visibleCount = 0;

        cards.forEach(card => {
            const status = card.getAttribute('data-status');
            const tahun = card.getAttribute('data-tahun');
            const nama = card.getAttribute('data-nama');
            
            let showCard = true;
            
            if (selectedStatus && status !== selectedStatus) showCard = false;
            if (selectedTahun && tahun !== selectedTahun) showCard = false;
            if (searchNamaValue && !nama.includes(searchNamaValue)) showCard = false;
            
            if (showCard) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        emptyState.style.display = visibleCount === 0 && cards.length > 0 ? 'block' : 'none';
    }

    // Event listeners
    filterStatus.addEventListener('change', filterKelompok);
    filterTahun.addEventListener('change', filterKelompok);
    searchNama.addEventListener('input', filterKelompok);

    // Card hover effects
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('mouseenter', () => card.classList.add('shadow-lg'));
        card.addEventListener('mouseleave', () => card.classList.remove('shadow-lg'));
    });
});

// Fungsi konfirmasi hapus
function confirmDelete(kelompokId) {
    if (confirm('Anda yakin ingin menghapus kelompok ini?')) {
        document.getElementById('deleteForm' + kelompokId).submit();
    }
}
</script>

<style>
    .card {
        transition: box-shadow 0.3s ease-in-out;
    }
    .btn-group .btn {
        flex: 1;
    }
</style>
@endsection