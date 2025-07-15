@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Guru Tamu</h4>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <h4>{{ $guru_tamu->nama_karyawan }}</h4>                        <span class="badge bg-{{ $guru_tamu->status === 'disetujui' ? 'success' : 'warning' }}">
                            {{ $guru_tamu->status === 'disetujui' ? 'Disetujui' : 'Menunggu Konfirmasi' }}
                        </span>
                    </div>                    <dl class="row">
                        <dt class="col-sm-3">Diajukan Oleh</dt>
                        <dd class="col-sm-9">{{ $guru_tamu->submitter ? $guru_tamu->submitter->name : 'Tidak diketahui' }}</dd>

                        <dt class="col-sm-3">Jabatan</dt>
                        <dd class="col-sm-9">{{ $guru_tamu->jabatan }}</dd>

                        <dt class="col-sm-3">Keahlian</dt>
                        <dd class="col-sm-9">{{ $guru_tamu->keahlian }}</dd>

                        <dt class="col-sm-3">Jadwal</dt>
                        <dd class="col-sm-9">{{ $guru_tamu->getFormattedJadwalAttribute() }}</dd>

                        <dt class="col-sm-3">Deskripsi</dt>
                        <dd class="col-sm-9">{{ $guru_tamu->deskripsi }}</dd>

                        @if($guru_tamu->file_cv)
                        <dt class="col-sm-3">File CV</dt>
                        <dd class="col-sm-9">
                            <a href="{{ asset('storage/' . $guru_tamu->file_cv) }}" target="_blank" class="btn btn-sm btn-primary">Download CV</a>
                        </dd>
                        @endif

                        @if($guru_tamu->file_materi)
                        <dt class="col-sm-3">File Materi</dt>
                        <dd class="col-sm-9">
                            <a href="{{ asset('storage/' . $guru_tamu->file_materi) }}" target="_blank" class="btn btn-sm btn-primary">Download Materi</a>
                        </dd>
                        @endif
                    </dl>

                    <div class="mt-4">
                        <a href="{{ route('waka-humas-guru-tamu-index') }}" class="btn btn-secondary">Kembali</a>
                        @if($guru_tamu->status === 'proses')
                        <form action="{{ route('waka-humas-guru-tamu-approve', $guru_tamu) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">Setujui</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
