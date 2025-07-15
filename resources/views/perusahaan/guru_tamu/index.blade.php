@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Daftar Pengajuan Guru Tamu Saya</h4>
                    <a href="{{ route('perusahaan-guru-tamu-create') }}" class="btn btn-success">
                        <i class="fa fa-plus"></i> Ajukan Guru Tamu
                    </a>
                </div>
                
                <div class="card-body">                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="date" id="filter-date" class="form-control" placeholder="Filter tanggal jadwal">
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="gurutamu-table">
        <thead class="table-light">            <tr>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Keahlian</th>
                <th>Deskripsi</th>
                <th>Jadwal</th>
                <th>Status</th>
                <th>File CV</th>
                <th>File Materi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gurutamus as $gurutamu )
                <tr>                    <td>{{ $gurutamu->nama_karyawan }}</td>
                    <td>{{ $gurutamu->jabatan }}</td>
                    <td>{{ $gurutamu->keahlian }}</td>
                    <td>{{ $gurutamu->deskripsi }}</td>
                    <td class="jadwal-date">{{ \Carbon\Carbon::parse($gurutamu->jadwal)->format('Y-m-d') }}</td>
                    <td>                        @php
                        $statusClasses = [
                            'proses' => 'badge bg-warning text-dark',
                            'disetujui' => 'badge bg-success',
                        ];
                        
                        $statusLabels = App\Models\GuruTamu::getStatusOptions();
                        @endphp
                        <span class="{{ $statusClasses[$gurutamu->status] ?? 'badge bg-secondary' }}">
                            {{ $statusLabels[$gurutamu->status] ?? $gurutamu->status }}
                        </span>
                    </td>
                    <td>
                        @if ($gurutamu->file_cv == null)
                            <span class="text-danger">Tidak ada CV</span>
                        @else
                            <a href="{{ asset('storage/' . $gurutamu->file_cv) }}" target="_blank">Unduh CV</a>
                        @endif
                    </td>
                    <td>
                        <a href="{{ asset('storage/' . $gurutamu->file_materi) }}" target="_blank">Unduh materi</a>
                    </td>                    <td>
                        <div class="btn-group" role="group" aria-label="Actions">                            @if($gurutamu->status !== 'disetujui')
                                <a href="{{ route('perusahaan-guru-tamu-edit', ['guru_tamu' => $gurutamu->id]) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('perusahaan-guru-tamu-destroy', ['guru_tamu' => $gurutamu->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-info">Tidak dapat diubah</span>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach                    </tbody>
                </table>
            </div>
            
            @if(count($gurutamus) == 0)
                <div class="text-center py-4 text-muted">
                    <i class="fa fa-folder-open fa-3x mb-3"></i>
                    <h5>Belum ada pengajuan guru tamu</h5>
                    <p>Silakan klik tombol "Ajukan Guru Tamu" untuk membuat pengajuan baru</p>
                </div>
            @endif
        </div>
    </div>
</div>
        </div>
    </div>
</div>

<script>
    document.getElementById('filter-date').addEventListener('input', function () {
        const selectedDate = this.value;
        const rows = document.querySelectorAll('#gurutamu-table tbody tr');
    
        rows.forEach(row => {
            const jadwalDate = row.querySelector('.jadwal-date').textContent.trim();
            if (!selectedDate || jadwalDate.startsWith(selectedDate)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection
