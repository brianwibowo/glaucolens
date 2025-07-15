<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExamAttempt extends Model
{
    use HasFactory;

    protected $table = 'student_exam_attempts'; // Nama tabel baru

    protected $fillable = [
        'user_id',
        'certification_exam_id',
        'nilai',
    ];

    protected $casts = [
        'nilai' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function exam()
    {
        return $this->belongsTo(CertificationExam::class, 'certification_exam_id');
    }
}