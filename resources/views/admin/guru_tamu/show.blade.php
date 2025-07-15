@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Pengajuan Guru Tamu</h4>
                    <div>                        @php
                        $statusClasses = [
                            'proses' => 'badge bg-warning text-dark',
                            'disetujui' => 'badge bg-success',
                        ];
                        $statusLabels = \App\Models\GuruTamu::getStatusOptions();
                        @endphp<span class="{{ $statusClasses[$guruTamu->status] }}">
                            {{ $statusLabels[$guruTamu->status] }}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">Diajukan Oleh</div>
                        <div class="col-md-9">{{ $guruTamu->submitter ? $guruTamu->submitter->name : 'Tidak diketahui' }}</div>
                    </div>                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">Nama Karyawan</div>
                        <div class="col-md-9">{{ $guruTamu->nama_karyawan }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">Jabatan</div>
                        <div class="col-md-9">{{ $guruTamu->jabatan }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">Keahlian</div>
                        <div class="col-md-9">{{ $guruTamu->keahlian }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">Deskripsi</div>
                        <div class="col-md-9">{{ $guruTamu->deskripsi }}</div>
                    </div>                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">Jadwal</div>
                        <div class="col-md-9">{{ $guruTamu->formatted_jadwal }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">File CV</div>
                        <div class="col-md-9">
                            @if($guruTamu->file_cv)
                            <a href="{{ asset('storage/' . $guruTamu->file_cv) }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fa fa-file-pdf"></i> Lihat CV
                            </a>
                            @else
                            <span class="text-muted">Tidak ada file</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">File Materi</div>
                        <div class="col-md-9">
                            @if($guruTamu->file_materi)
                            <a href="{{ asset('storage/' . $guruTamu->file_materi) }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fa fa-file-pdf"></i> Lihat Materi
                            </a>
                            @else
                            <span class="text-muted">Tidak ada file</span>
                            @endif                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin-guru-tamu-index') }}" class="btn btn-secondary">Kembali</a>
                        
                        <div>                            @if($guruTamu->status == 'proses')
                            <form action="{{ route('admin-guru-tamu-approve', $guruTamu->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')">
                                    <i class="fa fa-check"></i> Setujui
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('admin-guru-tamu-edit', $guruTamu->id) }}" class="btn btn-primary">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
