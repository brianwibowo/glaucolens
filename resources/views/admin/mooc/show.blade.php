@extends('layouts.layout')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <div class="container mt-4">
        {{-- Header Utama Halaman --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0">Detail Pelatihan MOOC: {{ $mooc->judul_pelatihan }}</h1>
            <div>
                <a href="{{ route('admin-mooc-edit', $mooc->id) }}" class="btn btn-warning me-2">
                    <i class="bi bi-pencil"></i> Edit Pelatihan
                </a>
                <a href="{{ route('admin-mooc-index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>
        </div>

        {{-- Kartu Detail Pelatihan --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h5>Judul Pelatihan</h5>
                        <p class="text-muted">{{ $mooc->judul_pelatihan }}</p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <h5>Deskripsi Pelatihan</h5>
                        <div class="p-3 bg-light rounded border">
                            <p class="mb-0">{{ $mooc->deskripsi }}</p>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <div class="row text-muted small">
                    <div class="col-md-6">
                        <p class="mb-1">Dibuat pada: {{ $mooc->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-1">Terakhir diperbarui: {{ $mooc->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kartu Daftar Modul --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-collection-play-fill me-2"></i>Daftar Modul</h5>
                    <a href="{{ route('admin-module-create', $mooc->id) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Modul Baru
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if ($modules && $modules->count() > 0)
                    <div class="list-group">
                        @foreach ($modules as $module)
                            <div
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $module->module_name }}</h6>
                                    {{-- Tampilkan deskripsi modul baru --}}
                                    @if ($module->deskripsi_modul)
                                        <p class="text-muted small mb-0">{{ Str::limit($module->deskripsi_modul, 80) }}</p>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('admin-module-edit', ['mooc' => $mooc->id, 'module' => $module->id]) }}"
                                        class="btn btn-sm btn-outline-warning me-2">Edit</a>
                                    <a href="{{ route('admin-module-show', ['mooc' => $mooc->id, 'module' => $module->id]) }}"
                                        class="btn btn-sm btn-outline-primary me-2">Detail</a>
                                
                                    {{-- Form untuk tombol Hapus --}}
                                    <form action="{{ route('admin-module-destroy', ['module' => $module->id]) }}"
                                        method="post"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus modul ini? Tindakan ini tidak dapat dibatalkan.')"
                                        class="mb-0"> {{-- Hapus 'd-inline' atau 'd-inline-block' dari sini --}}
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-4">
                        <p class="text-muted">Belum ada modul untuk pelatihan ini.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Kartu Daftar Peserta --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Daftar Peserta Pelatihan</h5>
            </div>
            <div class="card-body">
                @if ($participants && $participants->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama Peserta</th>
                                    <th scope="col">Status Sertifikat</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($participants as $index => $participant)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>{{ $participant->guru->name }}</td>
                                        <td>
                                            @if ($participant->file_sertifikat)
                                                <a href="{{ Storage::url($participant->file_sertifikat) }}"
                                                    target="_blank">
                                                    <i class="bi bi-file-earmark-check-fill text-success"></i>
                                                    {{ basename($participant->file_sertifikat) }} {{-- Menggunakan basename untuk nama file --}}
                                                </a>
                                            @else
                                                <span class="text-muted fst-italic">Belum diunggah</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-sm {{ $participant->file_sertifikat ? 'btn-outline-secondary' : 'btn-success' }} upload-sertifikat-btn"
                                                data-bs-toggle="modal" data-bs-target="#uploadSertifikatModal"
                                                data-participant-id="{{ $participant->user_id }}" {{-- Menggunakan user_id, bukan id MoocScore --}}
                                                data-participant-name="{{ $participant->guru->name }}">
                                                @if ($participant->file_sertifikat)
                                                    <i class="bi bi-arrow-repeat"></i> Update
                                                @else
                                                    <i class="bi bi-upload"></i> Upload
                                                @endif
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center p-4">
                        <p class="text-muted">Belum ada peserta yang mengikuti pelatihan ini.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Kartu Refleksi dari Peserta --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="bi bi-chat-quote-fill me-2"></i>Refleksi dari Peserta</h5>
            </div>
            <div class="card-body">
                @if ($reflections && $reflections->count() > 0)
                    @foreach ($reflections as $reflection)
                        <figure class="mb-4">
                            <blockquote class="blockquote bg-light p-3 rounded">
                                <p class="mb-0 fst-italic">"{{ $reflection->reflection }}"</p>
                            </blockquote>
                            <figcaption class="blockquote-footer mt-1">
                                <cite
                                    title="{{ $reflection->user->name ?? 'Peserta Anonim' }}">{{ $reflection->user->name ?? 'Peserta Anonim' }}</cite>
                                <span class="text-muted small"> - dikirim
                                    {{ $reflection->created_at->diffForHumans() }}</span>
                            </figcaption>
                        </figure>
                        @if (!$loop->last)
                            <hr>
                        @endif
                    @endforeach
                @else
                    <div class="text-center p-4">
                        <p class="text-muted">Belum ada refleksi yang dikirimkan oleh peserta untuk pelatihan ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL UNTUK UPLOAD/UPDATE SERTIFIKAT --}}
    <div class="modal fade" id="uploadSertifikatModal" tabindex="-1" aria-labelledby="uploadSertifikatModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadSertifikatModalLabel">Upload Sertifikat untuk Peserta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="sertifikat-upload-form" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <p>Anda akan mengunggah sertifikat untuk: <strong id="modal-participant-name"></strong></p>
                        <div class="mb-3">
                            <label for="sertifikat_file" class="form-label">Pilih File Sertifikat (PDF)</label>
                            {{-- Ubah dari PDF, JPG, PNG ke PDF --}}
                            <input class="form-control" type="file" id="sertifikat_file" name="sertifikat_file"
                                accept=".pdf" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Sertifikat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadSertifikatModal = document.getElementById('uploadSertifikatModal');
            if (uploadSertifikatModal) {
                uploadSertifikatModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const participantUserId = button.getAttribute('data-participant-id'); // Ambil user_id
                    const participantName = button.getAttribute('data-participant-name');

                    const formAction =
                        "{{ route('admin-mooc-sertifikat-upload', ['mooc' => $mooc->id, 'user' => 'USER_ID_PLACEHOLDER']) }}"
                        .replace('USER_ID_PLACEHOLDER', participantUserId);

                    const modalTitle = uploadSertifikatModal.querySelector('.modal-title');
                    const modalParticipantName = uploadSertifikatModal.querySelector(
                        '#modal-participant-name');
                    const modalForm = uploadSertifikatModal.querySelector('#sertifikat-upload-form');

                    modalTitle.textContent = 'Upload/Update Sertifikat untuk ' + participantName;
                    modalParticipantName.textContent = participantName;
                    modalForm.setAttribute('action', formAction);
                });
            }
        });
    </script>
@endsection
```
