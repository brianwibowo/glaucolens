{{-- Menggunakan layout utama aplikasi Anda, sesuaikan jika perlu --}}
@extends('layouts.layout') 

@section('content')
{{-- Menambahkan library ikon untuk mempercantik tampilan --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /* ... semua style CSS Anda tetap di sini ... */
    .accordion-button:not(.collapsed) {
        background-color: #e7f1ff; color: #0d6efd;
        box-shadow: inset 0 -1px 0 rgba(0,0,0,.125);
    }
    .video-responsive {
        overflow: hidden; padding-bottom: 56.25%; position: relative;
        height: 0; border-radius: 0.5rem; background-color: #f0f0f0;
    }
    .video-responsive iframe {
        left: 0; top: 0; height: 100%; width: 100%; position: absolute;
    }
    .mini-quiz-container.is-correct .card-body {
        background-color: #d1e7dd; border-left: 5px solid #0f5132;
    }
    .mini-quiz-container.is-incorrect .card-body {
        background-color: #f8d7da; border-left: 5px solid #842029;
    }
    .correct-answer-label {
        font-weight: bold; color: #0f5132;
    }
    .correct-answer-label::after {
        content: ' (Jawaban Benar)'; font-style: italic; font-size: 0.9em;
    }
    .module-status-icon {
        color: #198754; font-size: 1.1rem; transition: all 0.3s ease-in-out;
    }
</style>
<div class="container py-4">
    <div class="mb-4">
        <h1 class="display-6 fw-bold">{{ $mooc->judul_pelatihan }}</h1>
        <p class="text-muted fs-5">{{ $mooc->deskripsi }}</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <h3 class="mb-3">Materi Pembelajaran</h3>
            <div class="accordion" id="moduleAccordion">
                @forelse ($modules as $index => $module)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $module->id }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $module->id }}" aria-expanded="false" aria-controls="collapse{{ $module->id }}">
                                <span class="module-status-icon d-none me-2"><i class="bi bi-check-circle-fill"></i></span>
                                <span class="fw-bold">Modul {{ $index + 1 }}: {{ $module->module_name }}</span>
                            </button>
                        </h2>
                        <div id="collapse{{ $module->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $module->id }}" data-bs-parent="#moduleAccordion">
                            <div class="accordion-body">
                                {{-- ... Isi materi modul dan mini kuis ... --}}
                                @if ($module->link_materi)
                                    <h5 class="mb-2">Video Materi</h5>
                                    <div class="video-responsive mb-3 d-flex flex-column gap-2">
                                        <iframe src="{{ $module->link_materi }}" title="Materi Video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        <a href="{{ $module->link_materi }}" target="_blank">Buka video di tab baru</a>
                                    </div>
                                @endif
                                @if ($module->dokumen_materi)
                                    <h5 class="mt-4 mb-2">Dokumen Pendukung</h5>
                                    <a href="{{ asset('storage/' . $module->dokumen_materi) }}" target="_blank" class="btn btn-outline-primary mb-4">
                                        <i class="bi bi-download me-2"></i>Unduh Dokumen Materi
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">Belum ada modul yang ditambahkan untuk pelatihan ini.</div>
                @endforelse
            </div>

            {{-- ====================================================================== --}}
            {{-- SECTION BARU: SERTIFIKAT DAN REFLEKSI --}}
            {{-- ====================================================================== --}}
            <div class="mt-5">
                <h2 class="mb-4">Tahap Akhir Pelatihan</h2>
            
                {{-- KARTU SERTIFIKAT --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4 text-center">
                        <h4 class="card-title">Sertifikat Pelatihan</h4>
                        
                        {{-- Cek jika variabel $sertifikat ada dan path filenya tidak kosong --}}
                        @if(isset($nilai->file_sertifikat) && $nilai->file_sertifikat !== null)
                            <p class="text-muted">Selamat! Anda telah menyelesaikan pelatihan ini. Silakan unduh sertifikat Anda.</p>
                            <a href="{{ asset('storage/' . $nilai->file_sertifikat) }}" class="btn btn-success btn-lg mt-2" download>
                                <i class="bi bi-award-fill me-2"></i> Unduh Sertifikat
                            </a>
                        @else
                            <p class="text-muted mt-3">Sertifikat akan tersedia setelah Anda menyelesaikan seluruh rangkaian pelatihan dan kuis akhir.</p>
                        @endif
                    </div>
                </div>
            
                {{-- KARTU FORM REFLEKSI --}}
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="card-title">Refleksi Pelatihan</h4>
                        <p class="text-muted">Bagikan pemikiran, masukan, atau pengalaman Anda setelah mengikuti pelatihan ini. Refleksi Anda sangat berharga untuk perbaikan di masa mendatang.</p>
                        @if ($mooc->reflections()->first() !== null)
                            <p>Anda : {{ $mooc->reflections()->first()->reflection }}</p>
                        @else    
                            <form action="{{ route('guru-reflection-store', $mooc->id) }}" method="POST" class="mt-3">
                                @csrf
                                <div class="mb-3">
                                    <label for="reflectionText" class="form-label visually-hidden">Tulis refleksi Anda di sini</label>
                                    
                                    <textarea class="form-control" id="reflectionText" name="reflection_text" rows="5" placeholder="Tuliskan refleksi Anda di sini..." required>{{ old('reflection_text', $reflection->content ?? '') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send-fill me-2"></i> 
                                    Kirim Refleksi
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>

        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-body">
                    @if (isset($nilai) && $nilai !== null)
                        <h4 class="card-title mb-3">Nilai Anda</h4>
                        <p class="lead fw-bold">{{ $nilai->score }} <span class="text-muted">/ 100</span></p>
                    @endif
                    <h4 class="card-title mb-3">Informasi Pelatihan</h4>
                    <p class="mb-1"><strong class="d-block text-muted">Penyelenggara:</strong> {{ $mooc->user->name ?? 'Tidak diketahui' }}</p>
                    <hr>
                    <div class="mt-4 pt-2">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h5 class="card-title">Evaluasi Akhir</h5>
                                <a href="{{ route('guru-mooc-eval', $mooc->id) }}" class="btn btn-primary w-100 fw-bold">
                                    <i class="bi bi-pencil-square me-2"></i>Mulai Kuis Akhir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Script untuk fungsionalitas kuis tidak berubah --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const moduleQuizForms = document.querySelectorAll('.quiz-form');
    moduleQuizForms.forEach(form => {
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.addEventListener('click', function(event) {
            event.preventDefault();
            const container = form.closest('.mini-quiz-container');
            const selectedRadio = form.querySelector('input[type="radio"]:checked');
            const successAlert = form.querySelector('.alert-success');
            const errorAlert = form.querySelector('.alert-danger');
            
            successAlert.classList.add('d-none');
            errorAlert.classList.add('d-none');
            container.classList.remove('is-correct', 'is-incorrect');
            form.querySelectorAll('.form-check-label').forEach(label => label.classList.remove('correct-answer-label'));

            if (!selectedRadio) {
                alert('Silakan pilih salah satu jawaban terlebih dahulu.');
                return;
            }

            const userAnswer = selectedRadio.value;
            const correctAnswer = form.dataset.correctAnswer;

            if (userAnswer === correctAnswer) {
                container.classList.add('is-correct');
                successAlert.classList.remove('d-none');
                form.querySelectorAll('input[type="radio"]').forEach(radio => radio.disabled = true);
                submitButton.disabled = true;
                submitButton.innerHTML = 'Benar';

                const accordionItem = form.closest('.accordion-item');
                if (accordionItem) {
                    const statusIcon = accordionItem.querySelector('.module-status-icon');
                    if (statusIcon) {
                        statusIcon.classList.remove('d-none');
                    }
                }
            } else {
                container.classList.add('is-incorrect');
                errorAlert.classList.remove('d-none');
                const correctRadio = form.querySelector(`input[value="${correctAnswer}"]`);
                if (correctRadio) {
                    correctRadio.nextElementSibling.classList.add('correct-answer-label');
                }
            }
        });
    });
});
</script>
@endsection