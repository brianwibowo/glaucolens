@extends('layouts.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">Kelola Semua User</h2>
        <div class="text-muted">
            <i class="fas fa-users me-2"></i>Total Users: {{ $users->count() }}
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 25%">Nama</th>
                            <th style="width: 25%">Email</th>
                            <th style="width: 15%">Role</th>
                            <th style="width: 15%">Jenis Guru</th>
                            <th style="width: 20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm">
                                            <img src="https://api.dicebear.com/9.x/miniavs/svg?seed={{ $user->name ?? 'Admin' }}" alt="..."
                                                class="avatar-img rounded-circle" />
                                        </div>
                                        <span class="ms-2 text-truncate" style="max-width: 150px;">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;">{{ $user->email }}</span>
                                </td>
                                <td class="align-middle">
                                    @if($user->getRoleNames()->isNotEmpty())
                                        @foreach($user->getRoleNames() as $role)
                                            <span class="badge bg-primary rounded-pill px-2 py-1">{{ $role }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-2 py-1">Belum ada role</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($user->hasRole('guru'))
                                        <span class="badge bg-info text-dark rounded-pill px-2 py-1">
                                            {{ $user->jenis_guru ?: 'Belum ditentukan' }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($user->id == auth()->id())
                                        <div class="alert alert-info py-1 px-2 mb-0">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <small>Akun Anda</small>
                                        </div>
                                    @else
                                        <form action="{{ route('admin-users-update-role', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <div class="d-flex flex-wrap gap-1">
                                                <select name="role" id="role-select-{{ $user->id }}" class="form-select form-select-sm role-select" data-user-id="{{ $user->id }}">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                
                                                <div id="jenis-guru-options-{{ $user->id }}" class="jenis-guru-options" style="{{ $user->hasRole('guru') ? 'display: block;' : 'display: none;' }}">
                                                    <select name="jenis_guru" class="form-select form-select-sm">
                                                        <option value="">Pilih Jenis</option>
                                                        <option value="guru pembimbing" {{ $user->jenis_guru == 'guru pembimbing' ? 'selected' : '' }}>Pembimbing</option>
                                                        <option value="guru produktif" {{ $user->jenis_guru == 'guru produktif' ? 'selected' : '' }}>Produktif</option>
                                                    </select>
                                                </div>
                                                
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-save"></i>
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users fa-2x mb-3"></i>
                                        <p class="mb-0">Tidak ada user yang ditemukan</p>
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

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    flex-shrink: 0;
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.table > :not(caption) > * > * {
    padding: 0.75rem;
}

.badge {
    font-weight: 500;
    font-size: 0.75rem;
}

.form-select-sm {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    height: auto;
}

.gap-1 {
    gap: 0.25rem;
}

.role-select {
    min-width: 100px;
    max-width: 120px;
}

.jenis-guru-options select {
    min-width: 100px;
    max-width: 120px;
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .table > :not(caption) > * > * {
        padding: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelects = document.querySelectorAll('.role-select');
    
    roleSelects.forEach(select => {
        const userId = select.getAttribute('data-user-id');
        const jenisGuruOptions = document.getElementById(`jenis-guru-options-${userId}`);
        
        if (select.value === 'guru') {
            jenisGuruOptions.style.display = 'block';
        }
        
        select.addEventListener('change', function() {
            if (this.value === 'guru') {
                jenisGuruOptions.style.display = 'block';
            } else {
                jenisGuruOptions.style.display = 'none';
            }
        });
    });
});
</script>
@endsection