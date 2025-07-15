@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Daftar Guru Tamu yang Disetujui</h4>
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
        <thead class="table-light">
            <tr>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Keahlian</th>
                <th>Deskripsi</th>
                <th>Jadwal</th>
                <th>File CV</th>
                <th>File Materi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gurutamus as $gurutamu )
                <tr>
                    <td>{{ $gurutamu->nama_karyawan }}</td>
                    <td>{{ $gurutamu->jabatan }}</td>
                    <td>{{ $gurutamu->keahlian }}</td>
                    <td>{{ $gurutamu->deskripsi }}</td>
                    <td class="jadwal-date">{{ \Carbon\Carbon::parse($gurutamu->jadwal)->format('Y-m-d') }}</td>
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
                        <span class="badge bg-success">
                            Disetujui
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
                    </table>
                </div>
                
                @if(count($gurutamus) == 0)
                    <div class="text-center py-4 text-muted">
                        <i class="fa fa-folder-open fa-3x mb-3"></i>
                        <h5>Belum ada guru tamu yang disetujui</h5>
                        <p>Guru tamu yang disetujui akan muncul di sini</p>
                    </div>
                @endif
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
