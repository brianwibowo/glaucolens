@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3">Tugaskan Pembimbing</h1>
            <p class="text-muted">
                Program: {{ $pkl->nama }} | 
                <a href="{{ route('waka-humas-pkl-assign-index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Informasi Program PKL</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="30%">Nama Program</th>
                            <td>{{ $pkl->nama }}</td>
                        </tr>
                        <tr>
                            <th>Perusahaan</th>
                            <td>{{ $pkl->perusahaan->name ?? 'Tidak ada' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Mulai</th>
                            <td>{{ $pkl->tanggal_mulai->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Selesai</th>
                            <td>{{ $pkl->tanggal_selesai->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Pembimbing Saat Ini</th>
                            <td>
                                @if($pkl->pembimbing)
                                    {{ $pkl->pembimbing->name }}
                                @else
                                    <span class="text-muted">Belum ada pembimbing</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Jumlah Siswa</th>
                            <td>{{ $pkl->siswas->count() }} siswa</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Form Penugasan Pembimbing</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('waka-humas-pkl-assign-store', $pkl->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="pembimbing_id" class="form-label">Pilih Guru Pembimbing</label>
                            @if(count($pembimbings) > 0)
                                <select name="pembimbing_id" id="pembimbing_id" class="form-select">
                                    @foreach($pembimbings as $pembimbing)
                                        <option value="{{ $pembimbing->id }}" {{ $pkl->pembimbing_id == $pembimbing->id ? 'selected' : '' }}>
                                            {{ $pembimbing->name }} ({{ $pembimbing->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Pilih guru yang akan ditugaskan sebagai pembimbing program PKL ini.</small>
                            @else                                <div class="alert alert-warning">
                                    Tidak ada guru pembimbing yang tersedia. Pastikan ada guru dengan jenis "guru pembimbing" yang telah terdaftar.
                                </div>
                            @endif
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" {{ count($pembimbings) == 0 ? 'disabled' : '' }}>
                                {{ $pkl->pembimbing_id ? 'Perbarui Penugasan' : 'Tugaskan Pembimbing' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
