<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'sekolah_id', // Pastikan ini ada di fillable jika kamu punya kolom ini di tabel users
        'jenis_guru', // Pastikan ini ada di fillable
        'pkl_status',
        'laporan_pkl',
        'nilai_pkl',
        'pkl_id',
        'is_archived',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::created(function ($user) {
            if (!$user->hasAnyRole()) {
                $user->assignRole('user');
            }
        });

        parent::boot();

        static::deleting(function ($user) {
            if ($user->laporan_pkl) {
                Storage::disk('public')->delete($user->laporan_pkl);
            }
        });
    }

    public function pklSiswa()
    {
        return $this->belongsTo(PKL::class, 'pkl_id');
    }

    public function pklPerusahaan()
    {
        return $this->hasMany(PKL::class, 'perusahaan_id');
    }

    public function pklPembimbing()
    {
        return $this->hasMany(PKL::class, 'pembimbing_id');
    }

    public function moocs()
    {
        return $this->hasMany(Mooc::class, 'perusahaan_id');
    }

    public function logbook()
    {
        return $this->hasOne(Logbook::class, 'siswa_id');
    }

    public function alumni()
    {
        return $this->hasOne(Talent_Scouting::class, 'user_id');
    }

    public function batch()
    {
        return $this->hasMany(ScoutingBatch::class, 'perusahaan_id');
    }
    // Relasi baru untuk fitur sertifikasi
    public function createdCertificationExams()
    {
        return $this->hasMany(CertificationExam::class, 'pembuat_user_id');
    }

    public function sertifikasiRegistrations()
    {
        return $this->hasMany(Sertifikasi::class, 'user_id');
    }

    public function examAttempts()
    {
        return $this->hasMany(StudentExamAttempt::class, 'user_id');
    }

    public function moocScores()
    {
        return $this->hasMany(MoocScore::class, 'user_id');
    }

    public function moocReflections()
    {
        return $this->hasMany(MoocReflection::class, 'user_id');
    }
}
