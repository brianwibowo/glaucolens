@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4">Tinjau Pengajuan Beasiswa: {{ $beasiswa->nama_siswa }}</h3>

        {{-- Alert sukses saat simpan --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Info jika sudah direkomendasikan --}}
        @if ($beasiswa->direkomendasikan)
            <div class="alert alert-success">
                Anda telah merekomendasikan siswa ini pada
                {{ $beasiswa->tanggal_rekomendasi ? \Carbon\Carbon::parse($beasiswa->tanggal_rekomendasi)->translatedFormat('d F Y H:i') : '-' }}
            </div>
        @endif

        {{-- Dokumen --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header"><strong>Dokumen Pengajuan</strong></div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Rapor</span>
                        <a href="{{ Storage::url($beasiswa->raport) }}" class="btn btn-outline-primary btn-sm"
                            target="_blank">Lihat</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Surat Rekomendasi</span>
                        <a href="{{ Storage::url($beasiswa->surat_rekomendasi) }}" class="btn btn-outline-primary btn-sm"
                            target="_blank">Lihat</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Surat Motivasi</span>
                        <a href="{{ Storage::url($beasiswa->surat_motivasi) }}" class="btn btn-outline-primary btn-sm"
                            target="_blank">Lihat</a>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Portofolio</span>
                        <a href="{{ Storage::url($beasiswa->portofolio) }}" class="btn btn-outline-primary btn-sm"
                            target="_blank">Lihat</a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Form rekomendasi --}}
        <div class="card shadow-sm">
            <div class="card-header"><strong>Rekomendasi Waka Kurikulum</strong></div>
            <div class="card-body">
                <form action="{{ route('waka_kurikulum.beasiswas.rekomendasi', $beasiswa->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Catatan --}}
                    <div class="mb-3">
                        <label for="catatan" class="form-label fw-bold">Catatan</label>
                        <textarea name="catatan" id="catatan" rows="4" class="form-control"
                            placeholder="Tulis catatan atau alasan rekomendasi...">{{ old('catatan', $beasiswa->catatan) }}</textarea>
                    </div>

                    {{-- Radio rekomendasi / tidak --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold d-block mb-2">Status Rekomendasi</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="direkomendasikan" id="rekomYes"
                                value="1"
                                {{ old('direkomendasikan', (int) $beasiswa->direkomendasikan) === 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="rekomYes">
                                Rekomendasikan siswa ini kepada perusahaan
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="direkomendasikan" id="rekomNo"
                                value="0"
                                {{ old('direkomendasikan', (int) $beasiswa->direkomendasikan) === 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="rekomNo">
                                Tidak direkomendasikan
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('waka_kurikulum.beasiswas.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>Simpan Rekomendasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
