<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Mooc;
use App\Models\MoocScore;
use App\Models\Mooc_Eval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MoocController extends Controller
{
    public function index()
    {
        return view('guru.mooc.index', [
            'moocs' => Mooc::all()
        ]);
    }

    public function show(Mooc $mooc)
    {
        return view('guru.mooc.show', [
            'mooc' => $mooc,
            'modules' => $mooc->modules()->get(),
            'quizzes' => $mooc->quizzes()->get(),
            'nilai' => $mooc->nilai()->where('user_id', auth()->user()->id)->latest()->first()
        ]);
    }

    public function eval(Mooc $mooc){
        return view('guru.mooc.eval', [
            'mooc' => $mooc,
            'quizzes' => $mooc->quizzes()->get()
        ]);
    }

    public function nilai(Mooc $mooc, Request $request){
        // 1. Ambil jawaban dari user (Contoh: [3 => "4", 5 => "3"])
        $userAnswers = $request->input('answers', []);

        // 2. Ambil ID soal yang ada di kuis
        $questionIds = array_keys($userAnswers);

        // 3. Ambil kunci jawaban dari database
        // Hasilnya adalah koleksi [id_soal => "pilihan_jawaban_..."]
        $correctAnswers = MOOC_Eval::whereIn('id', $questionIds)
                                ->pluck('jawaban_benar', 'id');

        // 4. Hitung skor dengan LOGIKA YANG DIPERBAIKI
        $score = 0;
        foreach ($userAnswers as $questionId => $userAnswerIndex) {
            
            // ===================================================================
            // INI BAGIAN UTAMA YANG DIPERBAIKI
            // ===================================================================
            // Ubah indeks jawaban user (misal: "4") menjadi format nama kolom ("pilihan_jawaban_4")
            $formattedUserAnswer = 'pilihan_jawaban_' . $userAnswerIndex;
            // ===================================================================

            // Periksa apakah jawaban user yang sudah diformat sama dengan kunci jawaban
            if (isset($correctAnswers[$questionId]) && $correctAnswers[$questionId] == $formattedUserAnswer) {
                $score++;
            }
        }

        $totalQuestions = count($questionIds);
        $finalScore = ($totalQuestions > 0) ? ($score / $totalQuestions) * 100 : 0;

        MoocScore::create([
            'user_id' => auth()->user()->id,
            'mooc_id' => $mooc->id,
            'score' => $finalScore
        ]);

        return redirect()->route('guru-mooc-show', $mooc);
    }
}
