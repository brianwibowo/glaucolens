@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Daftar Pelatihan MOOC</h1>
        <a href="{{ route('admin-mooc-create') }}" class="btn btn-success">
            + Tambah Pelatihan MOOC
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <input type="text" id="search-title" class="form-control" placeholder="Cari berdasarkan judul pelatihan...">
        </div>
        <div class="col-md-6">
            {{-- Tidak ada filter tanggal atau status untuk MOOC berdasarkan skema tabel --}}
            {{-- Jika ingin menambahkan filter, perlu ada kolom tanggal/status di tabel moocs --}}
        </div>
    </div>

    <div class="row" id="mooc-cards">
        @foreach ($moocs as $mooc)
            <div class="col-lg-4 col-md-6 mb-4 mooc-card" data-title="{{ strtolower($mooc->judul_pelatihan) }}">
                <div class="card h-100 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light">
                        <h6 class="card-title mb-0 text-truncate" style="max-width: 70%;" title="{{ $mooc->judul_pelatihan }}">
                            {{ $mooc->judul_pelatihan }}
                        </h6>
                    </div>
                    
                    <div class="card-body">
                        <p class="card-text text-muted mb-3" style="min-height: 60px;">
                            {{ Str::limit($mooc->deskripsi, 120) }}
                        </p>
                    </div>
                    
                    <div class="card-footer bg-white">
                        <div class="btn-group w-100" role="group">
                            {{-- Tombol Detail sekarang mengarah ke halaman show --}}
                            <a href="{{ route('admin-mooc-show', ['mooc' => $mooc->id]) }}" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            <a href="{{ route('admin-mooc-edit', ['mooc' => $mooc->id]) }}" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $mooc->id }})">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                        
                        <form id="deleteForm{{ $mooc->id }}" action="{{ route('admin-mooc-destroy', ['mooc' => $mooc->id]) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div id="empty-state" class="text-center py-5" style="display: none;">
        <div class="mb-3">
            <i class="bi bi-folder-x" style="font-size: 4rem; color: #6c757d;"></i>
        </div>
        <h5 class="text-muted">Tidak ada pelatihan MOOC yang ditemukan</h5>
        <p class="text-muted">Coba ubah filter atau tambahkan pelatihan baru</p>
    </div>
</div>

<script>
    // Filter functions
    function filterMoocs() {
        const searchTitle = document.getElementById('search-title').value.toLowerCase();
        const cards = document.querySelectorAll('.mooc-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const title = card.getAttribute('data-title');
            
            let showCard = true;
            
            // Filter by title
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
        
        // Show/hide empty state
        const emptyState = document.getElementById('empty-state');
        if (visibleCount === 0) {
            emptyState.style.display = 'block';
        } else {
            emptyState.style.display = 'none';
        }
    }

    // Event listeners
    document.getElementById('search-title').addEventListener('input', filterMoocs);

    // Delete confirmation
    function confirmDelete(moocId) {
        if (confirm('Yakin ingin menghapus pelatihan ini? Tindakan ini tidak dapat dibatalkan.')) {
            document.getElementById('deleteForm' + moocId).submit();
        }
    }

    // Card hover effects
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