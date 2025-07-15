@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Penugasan Pembimbing PKL</h1>
        <a href="{{ route('admin-pkl-assign-pembimbing-list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="row">
        <!-- Informasi PKL -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Program PKL</h5>
                </div>
                <div class="card-body">
                    <h6 class="card-title">{{ $pkl->nama }}</h6>
                    <div class="mt-3">
                        <p class="mb-2"><strong>Perusahaan:</strong> {{ $pkl->perusahaan->name ?? 'N/A' }}</p>
                        <p class="mb-2"><strong>Periode:</strong> {{ $pkl->tanggal_mulai->format('d M Y') }} - {{ $pkl->tanggal_selesai->format('d M Y') }}</p>
                        <p class="mb-2"><strong>Status:</strong> 
                            @if($pkl->status == 'proses')
                                <span class="badge bg-warning text-dark">Proses</span>
                            @elseif($pkl->status == 'berjalan')
                                <span class="badge bg-primary">Berjalan</span>
                            @elseif($pkl->status == 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @endif
                        </p>
                        <p class="mb-2"><strong>Jumlah Siswa:</strong> {{ $pkl->siswas->count() }} siswa</p>
                        <p class="mb-0"><strong>Pembimbing Saat Ini:</strong> 
                            @if($pkl->pembimbing)
                                <span class="badge bg-success">{{ $pkl->pembimbing->name }}</span>
                            @else
                                <span class="badge bg-secondary">Belum Ditugaskan</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Penugasan Pembimbing -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Form Penugasan Pembimbing</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin-pkl-assign-pembimbing-store', $pkl->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="pembimbing_id" class="form-label">Pilih Guru Pembimbing <span class="text-danger">*</span></label>
                            <select class="form-select @error('pembimbing_id') is-invalid @enderror" id="pembimbing_id" name="pembimbing_id" required>
                                <option value="">-- Pilih Pembimbing --</option>
                                @foreach($pembimbings as $pembimbing)
                                    <option value="{{ $pembimbing->id }}" {{ $pkl->pembimbing_id == $pembimbing->id ? 'selected' : '' }}>
                                        {{ $pembimbing->name }} ({{ $pembimbing->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('pembimbing_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div class="form-text text-muted mt-2">
                                <i class="bi bi-info-circle"></i> 
                                Pembimbing akan bertanggung jawab untuk memvalidasi logbook dan laporan akhir siswa PKL.
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-person-check"></i> {{ $pkl->pembimbing ? 'Perbarui Pembimbing' : 'Tugaskan Pembimbing' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- List Siswa -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Daftar Siswa PKL</h5>
                </div>
                <div class="card-body">
                    @if($pkl->siswas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pkl->siswas as $index => $siswa)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $siswa->name }}</td>
                                            <td>
                                                @if($siswa->pkl_status == 'disetujui')
                                                    <span class="badge bg-success">Aktif</span>
                                                @elseif($siswa->pkl_status == 'proses')
                                                    <span class="badge bg-warning text-dark">Proses</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $siswa->pkl_status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            Belum ada siswa yang terdaftar untuk program PKL ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
