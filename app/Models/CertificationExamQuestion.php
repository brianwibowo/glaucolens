<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificationExamQuestion extends Model
{
    use HasFactory;

    protected $table = 'certification_exam_questions'; // Nama tabel baru

    protected $fillable = [
        'certification_exam_id',
        'soal',
        'pilihan_jawaban_1',
        'pilihan_jawaban_2',
        'pilihan_jawaban_3',
        'pilihan_jawaban_4',
        'jawaban_benar',
        'nilai_akhir',
    ];

    protected $casts = [
        'nilai_akhir' => 'integer',
        'jawaban_benar' => 'integer',
    ];

    public function exam()
    {
        return $this->belongsTo(CertificationExam::class, 'certification_exam_id');
    }
}