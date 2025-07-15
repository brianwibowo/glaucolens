<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class GuruTamu extends Model
{
    use HasFactory;

    protected $table = 'guru_tamus';

    protected $fillable = [
        'nama_karyawan',
        'jabatan',
        'keahlian',
        'deskripsi',
        'jadwal',
        'file_cv',
        'file_materi',
        'status',
        'submitted_by',
    ];

    protected $casts = [
        'jadwal' => 'datetime',
    ];    public static function getStatusOptions()
    {
        return [
            'proses' => 'Menunggu Konfirmasi',
            'disetujui' => 'Disetujui',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($kurikulum) {
            if ($kurikulum->file_cv) {
                Storage::disk('public')->delete($kurikulum->file_cv);
            }
            if ($kurikulum->file_materi) {
                Storage::disk('public')->delete($kurikulum->file_materi);
            }
        });
    }

    public function getFormattedJadwalAttribute()
    {
        return $this->jadwal ? $this->jadwal->format('d F Y H:i') : '-';
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
