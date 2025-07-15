<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificationExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_ujian',
        'deskripsi',
        'kompetensi_terkait',
        'pembuat_user_id',
        'durasi_menit',
        'nilai_minimum_lulus',
        'status_ujian',
    ];

    protected $casts = [
        'durasi_menit' => 'integer',
        'nilai_minimum_lulus' => 'integer',
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'pembuat_user_id');
    }

    public function questions()
    {
        return $this->hasMany(CertificationExamQuestion::class, 'certification_exam_id'); // Changed from SoalLsp
    }

    public function registrations()
    {
        return $this->hasMany(Sertifikasi::class, 'certification_exam_id');
    }
}