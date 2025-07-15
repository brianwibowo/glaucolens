@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4">Status Pendaftaran Beasiswa</h3>

        @if ($riwayat->isEmpty())
            <div class="alert alert-warning text-center">
                Anda belum mengajukan pendaftaran beasiswa.
            </div>
        @else
            @foreach ($riwayat as $beasiswa)
                <div
                    class="card shadow-sm mb-4 border-start border-4 
                @if ($beasiswa->status == 'diterima') border-success 
                @elseif ($beasiswa->status == 'ditolak') border-danger 
                @else border-warning @endif">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="mb-1">{{ $beasiswa->batch->batch ?? '-' }}</h5>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-building me-1"></i>
                                    {{ $beasiswa->batch->perusahaan->nama_perusahaan ?? 'Perusahaan tidak terdaftar' }}
                                </p>
                                <p class="text-muted mb-0">
                                    Periode:
                                    {{ \Carbon\Carbon::parse($beasiswa->batch->tanggal_mulai)->translatedFormat('d M Y') }}
                                    –
                                    {{ \Carbon\Carbon::parse($beasiswa->batch->tanggal_selesai)->translatedFormat('d M Y') }}
                                </p>
                            </div>
                            <div>
                                @if ($beasiswa->status == 'lolos')
                                    <span class="badge bg-success px-3 py-2"><i
                                            class="fas fa-check-circle me-1"></i>Lolos Beasiswa</span>
                                @elseif ($beasiswa->status == 'tidak lolos')
                                    <span class="badge bg-danger px-3 py-2"><i
                                            class="fas fa-times-circle me-1"></i>Tidak Lolos Beasiswa</span>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-2"><i
                                            class="fas fa-hourglass-half me-1"></i>Menunggu Review</span>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <p class="mb-2"><i class="fas fa-file-alt me-2 text-primary"></i><strong>Rapor:</strong>
                                </p>
                                <a href="{{ Storage::url($beasiswa->raport) }}" target="_blank"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> Lihat Rapor
                                </a>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><i class="fas fa-user-check me-2 text-success"></i><strong>Surat
                                        Rekomendasi:</strong></p>
                                <a href="{{ Storage::url($beasiswa->surat_rekomendasi) }}" target="_blank"
                                    class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-eye"></i> Lihat Rekomendasi
                                </a>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <p class="mb-2"><i class="fas fa-lightbulb me-2 text-warning"></i><strong>Surat
                                        Motivasi:</strong></p>
                                <a href="{{ Storage::url($beasiswa->surat_motivasi) }}" target="_blank"
                                    class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-eye"></i> Lihat Motivasi
                                </a>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><i
                                        class="fas fa-briefcase me-2 text-info"></i><strong>Portofolio:</strong></p>
                                <a href="{{ Storage::url($beasiswa->portofolio) }}" target="_blank"
                                    class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-eye"></i> Lihat Portofolio
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
