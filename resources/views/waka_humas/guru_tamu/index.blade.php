@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Guru Tamu</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>                                
                                <tr>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Keahlian</th>
                                    <th>Jadwal</th>
                                    <th>Diajukan Oleh</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($guruTamus as $guruTamu)
                                <tr>                                    
                                    <td>{{ $guruTamu->nama_karyawan }}</td>
                                    <td>{{ $guruTamu->jabatan }}</td>
                                    <td>{{ $guruTamu->keahlian }}</td>
                                    <td>{{ $guruTamu->getFormattedJadwalAttribute() }}</td>
                                    <td>{{ $guruTamu->submitter ? $guruTamu->submitter->name : 'Tidak diketahui' }}</td>
                                    <td>                                        
                                        <span class="badge bg-{{ $guruTamu->status === 'disetujui' ? 'success' : 'warning' }}">
                                            {{ $guruTamu->status === 'disetujui' ? 'Disetujui' : 'Menunggu Konfirmasi' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="{{ route('waka-humas-guru-tamu-show', $guruTamu) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            @if($guruTamu->status === 'proses')
                                                <form action="{{ route('waka-humas-guru-tamu-approve', $guruTamu) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="bi bi-check-circle"></i> Setujui
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data guru tamu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $guruTamus->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
