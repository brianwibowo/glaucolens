@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Manajemen Guru Tamu</h4>
                    <a href="{{ route('admin-guru-tamu-create') }}" class="btn btn-success">
                        <i class="fa fa-plus"></i> Ajukan Guru Tamu
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="dataTable">
                            <thead class="table-light">
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
                        @forelse ($guruTamus as $guru)
                        <tr>
                            <td>{{ $guru->nama_karyawan }}</td>
                            <td>{{ $guru->jabatan }}</td>
                            <td>{{ $guru->keahlian }}</td>
                            <td>{{ $guru->formatted_jadwal }}</td>
                            <td>{{ $guru->submitter ? $guru->submitter->name : 'Tidak diketahui' }}</td>
                            <td>                                @php
                                $statusClasses = [
                                    'proses' => 'badge bg-warning text-dark',
                                    'disetujui' => 'badge bg-success'
                                ];                                $statusLabels = \App\Models\GuruTamu::getStatusOptions();
                                @endphp
                                <span class="{{ $statusClasses[$guru->status] }}">
                                    {{ $statusLabels[$guru->status] }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin-guru-tamu-show', $guru->id) }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin-guru-tamu-edit', $guru->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @if ($guru->status == 'proses')
                                    <form action="{{ route('admin-guru-tamu-approve', $guru->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin-guru-tamu-destroy', $guru->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data guru tamu.</td>
                        </tr>
                        @endforelse                        </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $guruTamus->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
