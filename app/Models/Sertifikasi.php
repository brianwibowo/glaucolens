<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikasi extends Model
{
    use HasFactory;

    protected $table = 'sertifikasis';

    protected $fillable = [
        'user_id',
        'lsp_user_id',
        'perusahaan_user_id',
        'certification_exam_id',
        'dokumen_persyaratan',
        'nilai',
        'sertifikat_kelulusan',
        'status_pendaftaran_ujian',
        'kompetensi', 
    ];

    protected $casts = [
        'nilai' => 'integer',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lsp()
    {
        return $this->belongsTo(User::class, 'lsp_user_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(User::class, 'perusahaan_user_id');
    }

    public function exam()
    {
        return $this->belongsTo(CertificationExam::class, 'certification_exam_id');
    }
}