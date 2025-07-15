@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h1 class="h4 mb-4">Manajemen PKL Admin</h1>
    
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-building fs-1 text-primary mb-2"></i>
                    <h5>Mode Perusahaan</h5>
                    <p class="text-muted small">Kelola PKL sebagai perusahaan</p>
                    <a href="{{ route('admin-pkl-select-company') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-arrow-right-circle"></i> Pilih Perusahaan
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-person-check fs-1 text-info mb-2"></i>
                    <h5>Mode Guru</h5>
                    <p class="text-muted small">Validasi logbook & laporan sebagai guru</p>
                    <a href="{{ route('admin-pkl-select-guru') }}" class="btn btn-info mt-2">
                        <i class="bi bi-arrow-right-circle"></i> Pilih Guru
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-1 text-success mb-2"></i>
                    <h5>Mode Waka Humas</h5>
                    <p class="text-muted small">Tugaskan pembimbing & validasi laporan PKL</p>
                    <a href="{{ route('admin-pkl-assign-pembimbing-list') }}" class="btn btn-success mt-2">
                        <i class="bi bi-arrow-right-circle"></i> Kelola Pembimbing
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Daftar semua PKL -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Semua Program PKL</h5>
            <span class="badge bg-primary">{{ $pkls->total() }} Program</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Program</th>
                            <th>Perusahaan</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Jumlah Siswa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pkls as $index => $pkl)
                            @php
                                $statusClass = '';
                                if ($pkl->status == 'proses') {
                                    $statusClass = 'bg-warning text-dark';
                                } elseif ($pkl->status == 'berjalan') {
                                    $statusClass = 'bg-primary';
                                } elseif ($pkl->status == 'selesai') {
                                    $statusClass = 'bg-success';
                                }
                            @endphp
                            <tr>
                                <td>{{ $index + 1 + ($pkls->currentPage() - 1) * $pkls->perPage() }}</td>
                                <td>{{ $pkl->nama }}</td>
                                <td>
                                    {{ $pkl->perusahaan->name ?? 'N/A' }}
                                    @if($pkl->admin_representing && $pkl->admin_representing_role == 'perusahaan')
                                        <span class="badge bg-secondary" title="Dibuat admin atas nama perusahaan">
                                            <i class="bi bi-person-fill-gear"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $pkl->tanggal_mulai->format('d/m/Y') }} - {{ $pkl->tanggal_selesai->format('d/m/Y') }}</td>
                                <td><span class="badge {{ $statusClass }}">{{ ucfirst($pkl->status) }}</span></td>
                                <td>{{ $pkl->siswas->count() }}</td>
                                <td>
                                    <div class="btn-group">
                                        <!-- Tambahkan tombol untuk aksi waka humas -->
                                        <a href="{{ route('admin-pkl-assign-pembimbing-form', $pkl->id) }}" class="btn btn-sm btn-outline-success" title="Tugaskan Pembimbing">
                                            <i class="bi bi-person-plus"></i>
                                        </a>
                                        
                                        <!-- Jika ada laporan yang perlu divalidasi -->
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#validateReportModal{{ $pkl->id }}" title="Validasi Laporan">
                                            <i class="bi bi-file-check"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Modal Validasi Laporan -->
                                    <div class="modal fade" id="validateReportModal{{ $pkl->id }}" tabindex="-1" aria-labelledby="validateReportModalLabel{{ $pkl->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="validateReportModalLabel{{ $pkl->id }}">Validasi Laporan PKL</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin-pkl-validate-report', $pkl->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="status" class="form-label">Status Validasi</label>
                                                            <select class="form-select" id="status" name="status" required>
                                                                <option value="disetujui">Disetujui</option>
                                                                <option value="ditolak">Ditolak</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="catatan" class="form-label">Catatan (opsional)</label>
                                                            <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Validasi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada program PKL yang tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $pkls->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
