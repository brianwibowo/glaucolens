@extends('layouts.layout')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .quiz-timer {
        position: sticky;
        top: 10px;
        z-index: 1000;
    }
    .quiz-card .card-header {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .lead {
        font-size: 1.15rem;
    }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            {{-- HEADER KUIS --}}
            <div class="text-center mb-4">
                <h1 class="display-6 fw-bold">Kuis Akhir: {{ $mooc->judul_pelatihan }}</h1>
                <p class="text-muted">Pilihlah jawaban yang paling tepat untuk setiap pertanyaan di bawah ini.</p>
            </div>

            {{-- TIMER HITUNG MUNDUR --}}
            <div class="card shadow-sm mb-4 quiz-timer">
                <div class="card-body d-flex justify-content-center align-items-center p-2">
                    <i class="bi bi-clock-history fs-4 me-2"></i>
                    <span class="fs-4 fw-bold text-danger" id="countdown-timer">30:00</span>
                </div>
            </div>

            {{-- FORM KUIS --}}
            {{-- Pastikan route 'guru.kuis.submit' sudah Anda definisikan di web.php --}}
            <form id="quiz-form" action="{{ route('guru-mooc-nilai',$mooc->id) }}" method="POST">
                @csrf
                @if($quizzes->isNotEmpty())
                    @foreach($quizzes as $index => $quiz)
                        <div class="card shadow-sm mb-4 quiz-card">
                            <div class="card-header">
                                Pertanyaan {{ $index + 1 }}
                            </div>
                            <div class="card-body">
                                <p class="lead">{{ $quiz->soal }}</p>
                                <hr>
                                <div class="px-2">
                                    {{-- 
                                        Nama input "answers[{{ $quiz->id }}]" akan mengirim jawaban 
                                        dalam bentuk array ke controller, yang mudah untuk divalidasi dan dinilai.
                                    --}}
                                    <input type="hidden" name="questions[]" value="{{ $quiz->id }}">

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" value="1" name="answers[{{ $quiz->id }}]" id="q{{ $quiz->id }}_1" required>
                                        <label class="form-check-label" for="q{{ $quiz->id }}_1">{{ $quiz->pilihan_jawaban_1 }}</label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" value="2" name="answers[{{ $quiz->id }}]" id="q{{ $quiz->id }}_2" required>
                                        <label class="form-check-label" for="q{{ $quiz->id }}_2">{{ $quiz->pilihan_jawaban_2 }}</label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" value="3" name="answers[{{ $quiz->id }}]" id="q{{ $quiz->id }}_3" required>
                                        <label class="form-check-label" for="q{{ $quiz->id }}_3">{{ $quiz->pilihan_jawaban_3 }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="4" name="answers[{{ $quiz->id }}]" id="q{{ $quiz->id }}_4" required>
                                        <label class="form-check-label" for="q{{ $quiz->id }}_4">{{ $quiz->pilihan_jawaban_4 }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">
                            <i class="bi bi-check2-circle me-2"></i>Kumpulkan Jawaban
                        </button>
                    </div>

                @else
                    <div class="alert alert-warning text-center">
                        Tidak ada soal evaluasi yang tersedia untuk pelatihan ini.
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quizForm = document.getElementById('quiz-form');
    if (quizForm) {
        const timerDisplay = document.getElementById('countdown-timer');
        
        // Atur durasi kuis di sini (dalam menit)
        const quizDurationInMinutes = 30;
        let timeInSeconds = quizDurationInMinutes * 60;

        const timer = setInterval(function() {
            timeInSeconds--;

            const minutes = Math.floor(timeInSeconds / 60);
            const seconds = timeInSeconds % 60;

            // Format tampilan agar selalu 2 digit (e.g., 09:05)
            timerDisplay.textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (timeInSeconds <= 0) {
                clearInterval(timer);
                alert("Waktu habis! Jawaban Anda akan dikumpulkan secara otomatis.");
                quizForm.submit();
            }
        }, 1000);
    }
});
</script>
@endsection